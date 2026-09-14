<?php

namespace App\Http\Controllers;

use App\Models\Title;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ImportExportController extends Controller
{
    public function index()
    {
        return view('admin.catalog.import-export.index');
    }

    public function export(Request $request)
    {
        $format = $request->get('format', 'csv');
        $type = $request->get('type', 'all');

        $query = Title::with(['genres', 'credits.person']);

        if ($type !== 'all') {
            $query->where('type', $type);
        }

        $titles = $query->get();

        if ($format === 'csv') {
            return $this->exportCsv($titles);
        } elseif ($format === 'excel') {
            return $this->exportExcel($titles);
        }

        return redirect()->back()->with('error', 'Format non supporté.');
    }

    private function exportCsv($titles)
    {
        $filename = 'catalog_export_' . date('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($titles) {
            $file = fopen('php://output', 'w');
            
            // En-têtes
            fputcsv($file, [
                'ID', 'Nom', 'Type', 'Synopsis', 'Date de sortie', 'Pays', 
                'Langue', 'Durée (min)', 'Genres', 'Statut'
            ]);

            // Données
            foreach ($titles as $title) {
                fputcsv($file, [
                    $title->id,
                    $title->name,
                    $title->type,
                    $title->synopsis,
                    $title->release_date?->format('Y-m-d'),
                    $title->origin_country,
                    $title->original_language,
                    $title->runtime_minutes,
                    $title->genres->pluck('name')->implode(', '),
                    $title->status,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportExcel($titles)
    {
        // Pour Excel, on utilise CSV pour l'instant (nécessiterait une librairie comme PhpSpreadsheet)
        return $this->exportCsv($titles);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt',
        ]);

        $file = $request->file('file');
        $path = $file->getRealPath();
        $data = array_map('str_getcsv', file($path));
        
        // Enlever l'en-tête
        $header = array_shift($data);

        $imported = 0;
        $errors = [];

        foreach ($data as $index => $row) {
            try {
                if (count($row) < 3) continue;

                $title = Title::create([
                    'name' => $row[1] ?? 'Sans titre',
                    'type' => $row[2] ?? 'movie',
                    'synopsis' => $row[3] ?? null,
                    'release_date' => !empty($row[4]) ? $row[4] : null,
                    'origin_country' => $row[5] ?? null,
                    'original_language' => $row[6] ?? null,
                    'runtime_minutes' => !empty($row[7]) ? (int)$row[7] : null,
                    'status' => $row[9] ?? 'draft',
                    'slug' => \Illuminate\Support\Str::slug($row[1] ?? 'sans-titre'),
                ]);

                // Gérer les genres si présents
                if (!empty($row[8])) {
                    $genreNames = explode(',', $row[8]);
                    foreach ($genreNames as $genreName) {
                        $genre = \App\Models\Genre::firstOrCreate(['name' => trim($genreName)]);
                        $title->genres()->attach($genre->id);
                    }
                }

                $imported++;
            } catch (\Exception $e) {
                $errors[] = "Ligne " . ($index + 2) . ": " . $e->getMessage();
                Log::error('Import error', ['row' => $row, 'error' => $e->getMessage()]);
            }
        }

        $message = "{$imported} titre(s) importé(s) avec succès.";
        if (!empty($errors)) {
            $message .= " " . count($errors) . " erreur(s).";
        }

        return redirect()->route('admin.catalog.import-export.index')
            ->with('success', $message)
            ->with('errors', $errors);
    }
}

