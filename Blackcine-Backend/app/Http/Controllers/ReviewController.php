<?php

namespace App\Http\Controllers;

use App\Models\Title;
use App\Models\Review;
use App\Models\EditorialReview;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    // ========== GLOBAL REVIEWS (No Title ID required) ==========

    public function globalIndex()
    {
        $reviews = Review::with(['user', 'reviewable'])
            ->latest()
            ->paginate(20);
            
        return view('admin.reviews.index', compact('reviews'));
    }

    public function globalEditorialIndex()
    {
        $editorialReviews = EditorialReview::with(['author', 'title'])
            ->ordered()
            ->paginate(20);
            
        return view('admin.reviews.editorial', compact('editorialReviews'));
    }

    // ========== USER REVIEWS ==========
    
    public function index($titleId)
    {
        $title = Title::findOrFail($titleId);
        $reviews = $title->reviews()
            ->with('user')
            ->latest()
            ->paginate(20);
            
        return view('admin.titles.reviews.index', compact('title', 'reviews'));
    }

    public function approve($reviewId)
    {
        $review = Review::findOrFail($reviewId);
        $review->approve(auth()->id());
        
        return redirect()->back()->with('success', 'Avis approuvé avec succès.');
    }

    public function reject($reviewId)
    {
        $review = Review::findOrFail($reviewId);
        $review->reject();
        
        return redirect()->back()->with('success', 'Avis rejeté.');
    }

    public function destroy($reviewId)
    {
        $review = Review::findOrFail($reviewId);
        $review->delete();
        
        return redirect()->back()->with('success', 'Avis supprimé.');
    }

    // ========== EDITORIAL REVIEWS ==========
    
    public function editorialIndex($titleId)
    {
        $title = Title::findOrFail($titleId);
        $editorialReviews = $title->editorialReviews()
            ->with('author')
            ->ordered()
            ->get();
            
        return view('admin.titles.reviews.editorial', compact('title', 'editorialReviews'));
    }

    public function editorialCreate($titleId)
    {
        $title = Title::findOrFail($titleId);
        return view('admin.titles.reviews.editorial-create', compact('title'));
    }

    public function editorialStore(Request $request, $titleId)
    {
        $title = Title::findOrFail($titleId);
        
        $validated = $request->validate([
            'headline' => 'required|string|max:255',
            'content' => 'required|string',
            'rating' => 'nullable|integer|min:1|max:10',
            'critic_name' => 'nullable|string|max:255',
            'publication' => 'nullable|string|max:255',
            'publication_date' => 'nullable|date',
            'external_url' => 'nullable|url',
            'status' => 'required|in:draft,published,archived',
            'is_featured' => 'boolean',
            'display_order' => 'nullable|integer',
        ]);

        $validated['author_id'] = auth()->id();
        $validated['title_id'] = $titleId;

        if ($validated['status'] === 'published') {
            $validated['published_at'] = now();
        }

        EditorialReview::create($validated);

        return redirect()->route('admin.titles.show', $titleId)
            ->with('success', 'Critique rédactionnelle ajoutée avec succès.');
    }

    public function editorialEdit($titleId, $reviewId)
    {
        $title = Title::findOrFail($titleId);
        $review = EditorialReview::findOrFail($reviewId);
        
        return view('admin.titles.reviews.editorial-edit', compact('title', 'review'));
    }

    public function editorialUpdate(Request $request, $titleId, $reviewId)
    {
        $review = EditorialReview::findOrFail($reviewId);
        
        $validated = $request->validate([
            'headline' => 'required|string|max:255',
            'content' => 'required|string',
            'rating' => 'nullable|integer|min:1|max:10',
            'critic_name' => 'nullable|string|max:255',
            'publication' => 'nullable|string|max:255',
            'publication_date' => 'nullable|date',
            'external_url' => 'nullable|url',
            'status' => 'required|in:draft,published,archived',
            'is_featured' => 'boolean',
            'display_order' => 'nullable|integer',
        ]);

        if ($validated['status'] === 'published' && $review->status !== 'published') {
            $validated['published_at'] = now();
        }

        $review->update($validated);

        return redirect()->route('admin.titles.show', $titleId)
            ->with('success', 'Critique mise à jour avec succès.');
    }

    public function editorialDestroy($titleId, $reviewId)
    {
        $review = EditorialReview::findOrFail($reviewId);
        $review->delete();
        
        return redirect()->route('admin.titles.show', $titleId)
            ->with('success', 'Critique supprimée.');
    }

    public function editorialToggleFeatured($reviewId)
    {
        $review = EditorialReview::findOrFail($reviewId);
        
        if ($review->is_featured) {
            $review->unfeature();
        } else {
            $review->feature();
        }
        
        return redirect()->back()->with('success', 'Statut mis à jour.');
    }
}
