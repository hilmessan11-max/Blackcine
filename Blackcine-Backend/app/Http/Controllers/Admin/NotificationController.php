<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use App\Models\NewsletterCampaign;
use App\Models\NewsletterSubscriber;
use App\Models\PushNotification;

class NotificationController extends Controller
{
    public function index()
    {
        $stats = [
            'email_templates' => [
                'total' => EmailTemplate::count(),
                'active' => EmailTemplate::where('is_active', true)->count(),
            ],
            'push_notifications' => [
                'total' => PushNotification::count(),
                'sent' => PushNotification::where('status', 'sent')->count(),
                'scheduled' => PushNotification::where('status', 'scheduled')->count(),
            ],
            'newsletter' => [
                'subscribers' => NewsletterSubscriber::count(),
                'active_subscribers' => NewsletterSubscriber::where('is_active', true)->count(),
                'campaigns' => NewsletterCampaign::count(),
                'sent_campaigns' => NewsletterCampaign::where('status', 'sent')->count(),
            ],
        ];

        $recentTemplates = EmailTemplate::latest()->take(5)->get();
        $recentPushNotifications = PushNotification::latest()->take(5)->get();
        $recentCampaigns = NewsletterCampaign::latest()->take(5)->get();

        return view('admin.notifications.index', compact(
            'stats',
            'recentTemplates',
            'recentPushNotifications',
            'recentCampaigns'
        ));
    }
}
