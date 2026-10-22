<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Support\PublicImage;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        return redirect()->route('admin.promotions.index');
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateEvent($request);
        $validated['is_active'] = $request->boolean('is_active');
        if ($request->hasFile('image_file')) {
            $validated['image_url'] = PublicImage::store($request->file('image_file'), 'events');
        }
        unset($validated['image_file']);
        Event::create($validated);

        return redirect()->route('admin.promotions.index')->with('success', 'Événement créé.');
    }

    public function edit(Event $event)
    {
        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $validated = $this->validateEvent($request);
        $validated['is_active'] = $request->boolean('is_active');
        if ($request->hasFile('image_file')) {
            $newImageUrl = PublicImage::store($request->file('image_file'), 'events');
            PublicImage::delete($event->image_url, 'events');
            $validated['image_url'] = $newImageUrl;
        }
        unset($validated['image_file']);
        $event->update($validated);

        return redirect()->route('admin.promotions.index')->with('success', 'Événement mis à jour.');
    }

    public function destroy(Event $event)
    {
        PublicImage::delete($event->image_url, 'events');
        $event->delete();

        return redirect()->route('admin.promotions.index')->with('success', 'Événement supprimé.');
    }

    private function validateEvent(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
            'type' => ['nullable', 'string', 'max:80'],
            'image_url' => ['nullable', 'url', 'max:500'],
            'image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'is_active' => ['sometimes', 'boolean'],
        ]);
    }
}
