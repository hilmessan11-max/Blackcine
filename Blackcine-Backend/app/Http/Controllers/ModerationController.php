<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ModerationController extends Controller
{
    public function index()
    {
        $pendingComments = \App\Models\Comment::where('status', 'pending')->count();
        $pendingReports = \App\Models\Report::where('status', 'pending')->count();
        $recentLogs = \App\Models\ActivityLog::with('user')->latest()->take(10)->get();
        
        return view('admin.moderation.index', compact('pendingComments', 'pendingReports', 'recentLogs'));
    }

    public function comments(Request $request)
    {
        $query = \App\Models\Comment::with(['user', 'commentable'])->latest();

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $comments = $query->paginate(20);
        return view('admin.moderation.comments', compact('comments'));
    }

    public function updateCommentStatus(Request $request, $id)
    {
        $comment = \App\Models\Comment::findOrFail($id);
        $comment->update([
            'status' => $request->status
        ]);

        return back()->with('success', 'Statut du commentaire mis à jour.');
    }

    public function reports(Request $request)
    {
        $query = \App\Models\Report::with(['user', 'reportable'])->latest();

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $reports = $query->paginate(20);
        return view('admin.moderation.reports', compact('reports'));
    }

    public function updateReportStatus(Request $request, $id)
    {
        $report = \App\Models\Report::findOrFail($id);
        
        $data = [
            'status' => $request->status
        ];

        if (in_array($request->status, ['resolved', 'dismissed'])) {
            $data['resolved_by'] = auth()->id();
            $data['resolved_at'] = now();
        }

        $report->update($data);

        return back()->with('success', 'Signalement mis à jour.');
    }

    public function logs()
    {
        $logs = \App\Models\ActivityLog::with('user')->latest()->paginate(50);
        return view('admin.moderation.logs', compact('logs'));
    }
}
