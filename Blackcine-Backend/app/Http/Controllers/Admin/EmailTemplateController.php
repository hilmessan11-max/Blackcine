<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;

class EmailTemplateController extends Controller
{
    public function index()
    {
        $templates = EmailTemplate::orderBy('name')->paginate(20);
        return view('admin.notifications.templates.index', compact('templates'));
    }

    public function create()
    {
        return view('admin.notifications.templates.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'key' => 'required|string|unique:email_templates,key|max:255',
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'description' => 'nullable|string',
            'variables' => 'nullable|string', // On le recevra comme une chaine séparée par des virgules
        ]);

        // Traitement des variables
        if (!empty($validated['variables'])) {
            $validated['variables'] = array_map('trim', explode(',', $validated['variables']));
        } else {
            $validated['variables'] = [];
        }

        $validated['is_active'] = $request->has('is_active');

        EmailTemplate::create($validated);

        return redirect()->route('admin.notifications.templates.index')
            ->with('success', 'Modèle d\'email créé avec succès.');
    }

    public function edit(EmailTemplate $template)
    {
        return view('admin.notifications.templates.edit', compact('template'));
    }

    public function update(Request $request, EmailTemplate $template)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'description' => 'nullable|string',
            'variables' => 'nullable|string',
        ]);

        // On ne permet pas de modifier la clé 'key' car elle est utilisée par le système

        if (!empty($validated['variables'])) {
            $validated['variables'] = array_map('trim', explode(',', $validated['variables']));
        } else {
            $validated['variables'] = [];
        }

        $validated['is_active'] = $request->has('is_active');

        $template->update($validated);

        return redirect()->route('admin.notifications.templates.index')
            ->with('success', 'Modèle d\'email mis à jour.');
    }

    public function destroy(EmailTemplate $template)
    {
        // Attention: supprimer un template système peut causer des erreurs. 
        // Idéalement on devrait empêcher la suppression des templates système.
        $template->delete();
        return redirect()->route('admin.notifications.templates.index')
            ->with('success', 'Modèle supprimé.');
    }
}
