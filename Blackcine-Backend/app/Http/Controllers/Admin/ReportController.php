<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function activity()
    {
        // Ici, nous pourrions récupérer de vraies données statistiques
        // Pour l'instant, la vue contient des données statiques pour la démo
        return view('admin.reports.activity');
    }

    public function exports()
    {
        return view('admin.reports.exports');
    }
}
