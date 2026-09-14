<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SystemLogController extends Controller
{
    public function index()
    {
        $logPath = storage_path('logs/laravel.log');
        
        $logs = [];
        $logExists = File::exists($logPath);
        
        if ($logExists) {
            $logContent = File::get($logPath);
            $logSize = File::size($logPath);
            
            // Parse les dernières lignes (simplifié)
            $lines = array_reverse(array_filter(explode("\n", $logContent)));
            $logs = array_slice($lines, 0, 100); // 100 dernières lignes
        } else {
            $logSize = 0;
        }

        return view('admin.support.logs.index', compact('logs', 'logExists', 'logSize'));
    }

    public function clear()
    {
        $logPath = storage_path('logs/laravel.log');
        
        if (File::exists($logPath)) {
            File::put($logPath, '');
        }

        return redirect()->route('admin.support.logs.index')
            ->with('success', 'Logs effacés avec succès.');
    }

    public function download()
    {
        $logPath = storage_path('logs/laravel.log');
        
        if (File::exists($logPath)) {
            return response()->download($logPath, 'laravel-' . date('Y-m-d') . '.log');
        }

        return redirect()->route('admin.support.logs.index')
            ->with('error', 'Fichier de log introuvable.');
    }
}
