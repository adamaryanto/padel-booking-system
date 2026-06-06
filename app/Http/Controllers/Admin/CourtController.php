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
            'price_per_hour' => 'nullable|numeric|min:0',
            'price_weekday' => 'required|numeric|min:0',
            'price_weekend' => 'required|numeric|min:0',
            'open_time' => 'nullable',
            'close_time' => 'nullable',
            'member_promo' => 'nullable|string',
            'description' => 'nullable|string',
            'facilities' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'court_type' => 'nullable|in:Indoor,Outdoor',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'additional_photo_1' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'additional_photo_2' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'additional_photo_3' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_active' => 'nullable|in:0,1',
        ]);

        $data = $request->except([
            'photo', 
            'additional_photo_1', 'additional_photo_2', 'additional_photo_3',
            'location', 'court_type'
        ]);

        // Integrate Court Type into facilities
        if ($request->filled('court_type')) {
            $data['facilities'] = $request->court_type . ' Court, ' . ($request->facilities ?? '');
        }

        // Integrate Location into description
        if ($request->filled('location')) {
            $data['description'] = 'Lokasi: ' . $request->location . '. ' . ($request->description ?? '');
        }
        
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('courts', 'public');
        }

        $court = Court::create($data);

        for ($i = 1; $i <= 3; $i++) {
            if ($request->hasFile("additional_photo_{$i}")) {
                $path = $request->file("additional_photo_{$i}")->store('courts/additional', 'public');
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
            'price_per_hour' => 'nullable|numeric|min:0',
            'price_weekday' => 'required|numeric|min:0',
            'price_weekend' => 'required|numeric|min:0',
            'open_time' => 'nullable',
            'close_time' => 'nullable',
            'member_promo' => 'nullable|string',
            'description' => 'nullable|string',
            'facilities' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'court_type' => 'nullable|in:Indoor,Outdoor',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'additional_photo_1' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'additional_photo_2' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'additional_photo_3' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_active' => 'nullable|in:0,1',
        ]);

        $data = $request->except([
            'photo', 
            'additional_photo_1', 'additional_photo_2', 'additional_photo_3',
            'remove_additional_photo_1', 'remove_additional_photo_2', 'remove_additional_photo_3',
            'location', 'court_type'
        ]);

        // Integrate Court Type into facilities
        if ($request->filled('court_type')) {
            // Remove previous Indoor/Outdoor Court mentions in facilities
            $cleanedFacilities = preg_replace('/(Indoor|Outdoor)\s+Court,\s*/i', '', $court->facilities ?? '');
            $data['facilities'] = $request->court_type . ' Court, ' . ($request->facilities ?? $cleanedFacilities);
        }

        // Integrate Location into description
        if ($request->filled('location')) {
            // Remove previous Lokasi mentions in description
            $cleanedDesc = preg_replace('/Lokasi:\s*[^.]+\.\s*/i', '', $court->description ?? '');
            $data['description'] = 'Lokasi: ' . $request->location . '. ' . ($request->description ?? $cleanedDesc);
        }

        if ($request->hasFile('photo')) {
            if ($court->photo) {
                Storage::disk('public')->delete($court->photo);
            }
            $data['photo'] = $request->file('photo')->store('courts', 'public');
        }

        $court->update($data);

        $existingImages = $court->images()->get();

        for ($i = 1; $i <= 3; $i++) {
            $removeVal = $request->input("remove_additional_photo_{$i}");
            $imgModel = $existingImages->get($i - 1);

            if ($removeVal && $removeVal != '0' && $removeVal != '1') {
                $imageToRemove = CourtImage::where('id', $removeVal)->where('court_id', $court->id)->first();
                if ($imageToRemove) {
                    Storage::disk('public')->delete($imageToRemove->path);
                    $imageToRemove->delete();
                }
                $imgModel = null;
            }

            if ($request->hasFile("additional_photo_{$i}")) {
                $path = $request->file("additional_photo_{$i}")->store('courts/additional', 'public');

                if ($imgModel) {
                    Storage::disk('public')->delete($imgModel->path);
                    $imgModel->update(['path' => $path]);
                } else {
                    $court->images()->create(['path' => $path]);
                }
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
