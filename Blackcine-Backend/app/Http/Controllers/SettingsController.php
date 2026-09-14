<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Currency;
use App\Models\VatRate;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $groups = [
            'identity' => Setting::getGroup('identity'),
            'general' => Setting::getGroup('general'),
            'payment' => Setting::getGroup('payment'),
            'billing' => Setting::getGroup('billing'),
            'ticket' => Setting::getGroup('ticket'),
        ];

        $currencies = Currency::all();
        $vatRates = VatRate::all();

        return view('admin.settings.index', compact('groups', 'currencies', 'vatRates'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'settings' => 'required|array',
        ]);

        foreach ($validated['settings'] as $key => $value) {
            $setting = Setting::where('key', $key)->first();
            if ($setting) {
                $type = $setting->type;
                $value = match ($type) {
                    'json' => is_array($value) ? json_encode($value) : $value,
                    'boolean' => $request->has("settings.{$key}") ? '1' : '0',
                    default => $value,
                };
                $setting->update(['value' => $value]);
            } else {
                Setting::set($key, $value);
            }
        }

        // Clear cache
        foreach (array_keys($validated['settings']) as $key) {
            \Illuminate\Support\Facades\Cache::forget("setting.{$key}");
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Paramètres mis à jour avec succès.');
    }

    public function updateIdentity(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'site_description' => 'nullable|string',
            'logo' => 'nullable|exists:assets,id',
            'favicon' => 'nullable|exists:assets,id',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string',
            'address' => 'nullable|string',
            'social_facebook' => 'nullable|url',
            'social_twitter' => 'nullable|url',
            'social_instagram' => 'nullable|url',
            'social_youtube' => 'nullable|url',
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, $value, 'identity');
        }

        return redirect()->route('admin.settings.index', ['tab' => 'identity'])
            ->with('success', 'Identité mise à jour avec succès.');
    }

    public function updateLanguages(Request $request)
    {
        $validated = $request->validate([
            'default_language' => 'required|string|max:5',
            'available_languages' => 'required|array',
            'available_languages.*' => 'string|max:5',
        ]);

        Setting::set('default_language', $validated['default_language'], 'general', 'string');
        Setting::set('available_languages', $validated['available_languages'], 'general', 'json');

        return redirect()->route('admin.settings.index', ['tab' => 'languages'])
            ->with('success', 'Langues mises à jour avec succès.');
    }

    public function updatePayment(Request $request)
    {
        $validated = $request->validate([
            'payment_provider' => 'required|in:stripe,paypal,orange_money,mtn_mobile_money,other',
            'stripe_public_key' => 'nullable|string',
            'stripe_secret_key' => 'nullable|string',
            'stripe_webhook_secret' => 'nullable|string',
            'paypal_client_id' => 'nullable|string',
            'paypal_secret' => 'nullable|string',
            'paypal_mode' => 'nullable|in:sandbox,live',
            'orange_money_merchant_id' => 'nullable|string',
            'mtn_mobile_money_api_key' => 'nullable|string',
            'default_currency' => 'required|exists:currencies,id',
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, $value, 'payment');
        }

        return redirect()->route('admin.settings.index', ['tab' => 'payment'])
            ->with('success', 'Paramètres de paiement mis à jour avec succès.');
    }

    public function updateBilling(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'nullable|string|max:255',
            'company_address' => 'nullable|string',
            'company_tax_id' => 'nullable|string',
            'invoice_prefix' => 'nullable|string|max:10',
            'default_vat_rate_id' => 'nullable|exists:vat_rates,id',
            'tax_included' => 'boolean',
            'invoice_footer' => 'nullable|string',
        ]);

        foreach ($validated as $key => $value) {
            $type = $key === 'tax_included' ? 'boolean' : 'string';
            Setting::set($key, $value, 'billing', $type);
        }

        return redirect()->route('admin.settings.index', ['tab' => 'billing'])
            ->with('success', 'Paramètres de facturation mis à jour avec succès.');
    }

    public function updateTicket(Request $request)
    {
        $validated = $request->validate([
            'ticket_booking_fee_cents' => 'nullable|integer|min:0',
            'ticket_cancellation_allowed' => 'boolean',
            'ticket_cancellation_hours' => 'nullable|integer|min:0',
            'ticket_refund_policy' => 'nullable|string',
            'ticket_max_per_user' => 'nullable|integer|min:1',
            'ticket_reservation_minutes' => 'nullable|integer|min:1',
        ]);

        foreach ($validated as $key => $value) {
            $type = in_array($key, ['ticket_booking_fee_cents', 'ticket_cancellation_hours', 'ticket_max_per_user', 'ticket_reservation_minutes']) 
                ? 'integer' 
                : ($key === 'ticket_cancellation_allowed' ? 'boolean' : 'string');
            Setting::set($key, $value, 'ticket', $type);
        }

        return redirect()->route('admin.settings.index', ['tab' => 'ticket'])
            ->with('success', 'Paramètres de billetterie mis à jour avec succès.');
    }
}
