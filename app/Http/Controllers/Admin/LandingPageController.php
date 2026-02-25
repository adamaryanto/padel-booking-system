<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LandingPageContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LandingPageController extends Controller
{
    public function index()
    {
        $content = LandingPageContent::first() ?? new LandingPageContent();
        return view('admin.landing.index', compact('content'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'hero_title' => 'nullable|string|max:255',
            'hero_subtitle' => 'nullable|string|max:255',
            'hero_cta_text' => 'nullable|string|max:255',
            'hero_cta_link' => 'nullable|string|max:255',
            'hero_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'about_title' => 'nullable|string|max:255',
            'about_subtitle' => 'nullable|string|max:255',
            'about_description' => 'nullable|string',
            'contact_address' => 'nullable|string',
            'contact_phone' => 'nullable|string',
        ]);

        $content = LandingPageContent::firstOrCreate([]);
        $data = $request->except(['hero_image']);

        if ($request->hasFile('hero_image')) {
            if ($content->hero_image) {
                Storage::disk('public')->delete($content->hero_image);
            }
            $data['hero_image'] = $request->file('hero_image')->store('landing', 'public');
        }

        $content->update($data);

        return back()->with('success', 'Konten landing page berhasil diperbarui.');
    }
}
