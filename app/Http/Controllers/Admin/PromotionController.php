<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use App\Models\Event;
use App\Support\PublicImage;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function index()
    {
        $promotions = Promotion::latest('created_at')->paginate(15);
        $activePromotionCount = Promotion::where('is_active', true)->count();
        $currentlyRunningPromotionCount = Promotion::where('is_active', true)
            ->where(fn ($query) => $query->whereNull('starts_at')->orWhere('starts_at', '<=', now()))
            ->where(fn ($query) => $query->whereNull('ends_at')->orWhere('ends_at', '>=', now()))
            ->count();
        $events = Event::latest()->paginate(10, ['*'], 'events_page');

        return view('admin.promotions.index', compact('promotions', 'activePromotionCount', 'currentlyRunningPromotionCount', 'events'));
    }

    public function create()
    {
        return view('admin.promotions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => ['sometimes', 'in:promotion,event'],
            'title' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
            'image_url' => ['nullable', 'url', 'max:500'],
            'image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'is_active' => ['sometimes', 'boolean'],
        ]);
        $validated['is_active'] = $request->boolean('is_active');
        if ($request->hasFile('image_file')) {
            $validated['image_url'] = PublicImage::store($request->file('image_file'), 'promotions');
        }
        unset($validated['image_file']);

        Promotion::create($validated);

        return redirect()->route('admin.promotions.index')->with('success', 'La promotion a été créée.');
    }

    public function edit(Promotion $promotion)
    {
        return view('admin.promotions.edit', compact('promotion'));
    }

    public function update(Request $request, Promotion $promotion)
    {
        $validated = $request->validate([
            'type' => ['sometimes', 'in:promotion,event'],
            'title' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
            'image_url' => ['nullable', 'url', 'max:500'],
            'image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'is_active' => ['sometimes', 'boolean'],
        ]);
        $validated['is_active'] = $request->boolean('is_active');
        if ($request->hasFile('image_file')) {
            $newImageUrl = PublicImage::store($request->file('image_file'), 'promotions');
            PublicImage::delete($promotion->image_url, 'promotions');
            $validated['image_url'] = $newImageUrl;
        }
        unset($validated['image_file']);

        $promotion->update($validated);

        return redirect()->route('admin.promotions.index')->with('success', 'La promotion a été mise à jour.');
    }

    public function destroy(Promotion $promotion)
    {
        PublicImage::delete($promotion->image_url, 'promotions');
        $promotion->delete();

        return redirect()->route('admin.promotions.index')->with('success', 'La promotion a été supprimée.');
    }
}