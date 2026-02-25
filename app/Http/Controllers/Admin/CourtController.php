<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Court;
use App\Models\CourtImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CourtController extends Controller
{
    public function index()
    {
        $courts = Court::with('images')->get();
        return view('admin.courts.index', compact('courts'));
    }

    public function create()
    {
        return view('admin.courts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price_per_hour' => 'required|numeric|min:0',
            'price_weekday' => 'nullable|numeric|min:0',
            'price_weekend' => 'nullable|numeric|min:0',
            'open_time' => 'nullable',
            'close_time' => 'nullable',
            'member_promo' => 'nullable|string',
            'description' => 'nullable|string',
            'facilities' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'additional_photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_active' => 'nullable|in:0,1',
        ]);

        $data = $request->except(['photo', 'additional_photos']);
        
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('courts', 'public');
        }

        $court = Court::create($data);

        if ($request->hasFile('additional_photos')) {
            foreach ($request->file('additional_photos') as $photo) {
                $path = $photo->store('courts/additional', 'public');
                $court->images()->create(['path' => $path]);
            }
        }

        return redirect()->route('admin.courts.index')->with('success', 'Lapangan berhasil ditambahkan.');
    }

    public function edit(Court $court)
    {
        $court->load('images');
        return view('admin.courts.edit', compact('court'));
    }

    public function update(Request $request, Court $court)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price_per_hour' => 'required|numeric|min:0',
            'price_weekday' => 'nullable|numeric|min:0',
            'price_weekend' => 'nullable|numeric|min:0',
            'open_time' => 'nullable',
            'close_time' => 'nullable',
            'member_promo' => 'nullable|string',
            'description' => 'nullable|string',
            'facilities' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'additional_photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'remove_images.*' => 'nullable|exists:court_images,id',
            'is_active' => 'nullable|in:0,1',
        ]);

        $data = $request->except(['photo', 'additional_photos', 'remove_images']);

        if ($request->hasFile('photo')) {
            if ($court->photo) {
                Storage::disk('public')->delete($court->photo);
            }
            $data['photo'] = $request->file('photo')->store('courts', 'public');
        }

        $court->update($data);

        if ($request->has('remove_images')) {
            $imagesToRemove = CourtImage::whereIn('id', $request->remove_images)->where('court_id', $court->id)->get();
            foreach ($imagesToRemove as $image) {
                Storage::disk('public')->delete($image->path);
                $image->delete();
            }
        }

        if ($request->hasFile('additional_photos')) {
            foreach ($request->file('additional_photos') as $photo) {
                $path = $photo->store('courts/additional', 'public');
                $court->images()->create(['path' => $path]);
            }
        }

        return redirect()->route('admin.courts.index')->with('success', 'Lapangan berhasil diperbarui.');
    }

    public function destroy(Court $court)
    {
        if ($court->photo) {
            Storage::disk('public')->delete($court->photo);
        }
        $court->delete();

        return redirect()->route('admin.courts.index')->with('success', 'Lapangan berhasil dihapus.');
    }
}
