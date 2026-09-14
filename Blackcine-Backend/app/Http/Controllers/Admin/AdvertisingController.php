<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdvertisingCampaign;
use Illuminate\Http\Request;

class AdvertisingController extends Controller
{
    public function index()
    {
        $campaigns = AdvertisingCampaign::latest()->paginate(20);
        
        $stats = [
            'active' => AdvertisingCampaign::where('status', 'active')->count(),
            'total_impressions' => AdvertisingCampaign::sum('impressions_count'),
            'total_clicks' => AdvertisingCampaign::sum('clicks_count'),
            'revenue' => AdvertisingCampaign::sum('price_cents'),
        ];

        return view('admin.advertising.campaigns.index', compact('campaigns', 'stats'));
    }

    public function create()
    {
        return view('admin.advertising.campaigns.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'client_name' => 'nullable|string|max:255',
            'type' => 'required|in:banner,video,sponsored_post',
            'placement' => 'required|string',
            'target_url' => 'nullable|url',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:active,paused,ended,draft',
            'price_cents' => 'nullable|integer|min:0',
            'image_url' => 'nullable|string',
            'video_url' => 'nullable|string',
        ]);

        AdvertisingCampaign::create($validated);

        return redirect()->route('admin.advertising.campaigns.index')
            ->with('success', 'Campagne publicitaire créée avec succès !');
    }

    public function edit(AdvertisingCampaign $campaign)
    {
        return view('admin.advertising.campaigns.edit', compact('campaign'));
    }

    public function update(Request $request, AdvertisingCampaign $campaign)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'client_name' => 'nullable|string|max:255',
            'type' => 'required|in:banner,video,sponsored_post',
            'placement' => 'required|string',
            'target_url' => 'nullable|url',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:active,paused,ended,draft',
            'price_cents' => 'nullable|integer|min:0',
            'image_url' => 'nullable|string',
            'video_url' => 'nullable|string',
        ]);

        $campaign->update($validated);

        return redirect()->route('admin.advertising.campaigns.index')
            ->with('success', 'Campagne publicitaire mise à jour avec succès !');
    }

    public function destroy(AdvertisingCampaign $campaign)
    {
        $campaign->delete();
        
        return redirect()->route('admin.advertising.campaigns.index')
            ->with('success', 'Campagne publicitaire supprimée avec succès !');
    }
}
