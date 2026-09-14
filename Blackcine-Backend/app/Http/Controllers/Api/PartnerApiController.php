<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Partner;

class PartnerApiController extends Controller
{
    public function index()
    {
        return response()->json([
            'data' => Partner::where('is_active', true)
                ->latest()
                ->get()
                ->map(function ($partner) {
                    return [
                        'id' => $partner->id,
                        'name' => $partner->name,
                        'slug' => $partner->slug,
                        'description' => $partner->description,
                        'website_url' => $partner->website_url,
                        'contact_email' => $partner->contact_email,
                        'contact_phone' => $partner->contact_phone,
                    ];
                }),
        ]);
    }
}
