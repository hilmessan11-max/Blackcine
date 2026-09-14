<?php

namespace App\Http\Controllers;

use App\Helpers\SanitizeHelper;
use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AssetController extends Controller
{
    public function index(Request $request)
    {
        $query = Asset::with('user');

        if ($request->has('type')) {
            if ($request->type === 'image') {
                $query->where('mime_type', 'like', 'image/%');
            } elseif ($request->type === 'video') {
                $query->where('mime_type', 'like', 'video/%');
            } elseif ($request->type === 'document') {
                $query->whereNotIn('mime_type', ['image/%', 'video/%']);
            }
        }

        if ($request->has('search')) {
            SanitizeHelper::whereLike($query, 'path', $request->search);
        }

        $assets = $query->latest()->paginate(24);

        return view('admin.media.index', compact('assets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:102400', // 100MB max
        ]);

        $file = $request->file('file');
        $path = $file->store('assets', 'public');

        $asset = Asset::create([
            'disk' => 'public',
            'path' => $path,
            'mime_type' => $file->getMimeType(),
            'size_bytes' => $file->getSize(),
            'width' => null,
            'height' => null,
            'user_id' => auth()->id(),
            'status' => 'active',
        ]);

        // Pour les images, extraire dimensions si possible
        if (str_starts_with($asset->mime_type, 'image/')) {
            try {
                $imageInfo = getimagesize(storage_path('app/public/' . $path));
                if ($imageInfo) {
                    $asset->update([
                        'width' => $imageInfo[0],
                        'height' => $imageInfo[1],
                    ]);
                }
            } catch (\Exception $e) {
                // Ignorer si impossible d'extraire les dimensions
            }
        }

        return redirect()->route('admin.media.index')
            ->with('success', 'Fichier uploadé avec succès.');
    }

    public function destroy($id)
    {
        $asset = Asset::findOrFail($id);

        // Supprimer le fichier
        Storage::disk($asset->disk)->delete($asset->path);

        // Supprimer l'enregistrement
        $asset->delete();

        return redirect()->route('admin.media.index')
            ->with('success', 'Fichier supprimé avec succès.');
    }
}

