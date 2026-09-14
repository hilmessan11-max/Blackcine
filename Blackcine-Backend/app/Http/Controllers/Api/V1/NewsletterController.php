<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
        ]);

        $subscriber = NewsletterSubscriber::updateOrCreate(
            ['email' => $request->email],
            [
                'is_active' => true,
                'source' => 'website',
                'subscribed_at' => now(),
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Inscription réussie à la newsletter.'
        ]);
    }

    public function unsubscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
        ]);

        $subscriber = NewsletterSubscriber::where('email', $request->email)->first();

        if (!$subscriber) {
            return response()->json([
                'success' => false,
                'message' => 'Email non trouvé dans la newsletter.'
            ], 404);
        }

        $subscriber->update(['is_active' => false]);

        return response()->json([
            'success' => true,
            'message' => 'Désinscription réussie.'
        ]);
    }
}
