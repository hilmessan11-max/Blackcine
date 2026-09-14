<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use App\Models\Title;
use App\Models\Article;
use App\Models\Emission;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class FooterController extends Controller
{
    public function index()
    {
        $partners = Cache::remember('footer_partners', 3600, function () {
            return Partner::where('is_active', true)
                ->limit(6)
                ->get()
                ->map(fn($p) => [
                    'id' => $p->id,
                    'name' => $p->name,
                    'logo' => $p->logo_path ? asset('storage/' . $p->logo_path) : null,
                    'website_url' => $p->website_url ?? '#',
                ]);
        });

        $stats = Cache::remember('footer_stats', 3600, function () {
            return [
                'films' => Title::where('type', 'movie')->count(),
                'series' => Title::where('type', 'series')->count(),
                'articles' => Article::where('status', 'published')->count(),
                'emissions' => Emission::count(),
                'members' => User::count(),
            ];
        });

        return response()->json([
            'data' => [
                'partners' => $partners,
                'stats' => $stats,
            ]
        ]);
    }
}
