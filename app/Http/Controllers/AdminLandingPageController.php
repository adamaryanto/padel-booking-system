<?php

namespace App\Http\Controllers;

use App\Models\LandingPageContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminLandingPageController extends Controller
{
    public function edit()
    {
        $content = LandingPageContent::first();
        if (!$content) {
            // Should be seeded, but fallback just in case
            $content = LandingPageContent::create([
                'hero_title' => 'LOCKED PEEK PERFORMANCE',
                'hero_subtitle' => 'ULTIMATE PADEL EXPERIENCE',
            ]);
        }
        return view('admin.landing.edit', compact('content'));
    }

    public function update(Request $request)
    {
        $content = LandingPageContent::first();
        
        $request->validate([
            'hero_title' => 'required|string|max:255',
            'hero_subtitle' => 'nullable|string|max:255',
            'hero_cta_text' => 'nullable|string|max:255',
            'hero_cta_link' => 'nullable|string|max:255',
            'hero_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'about_title' => 'required|string|max:255',
            'about_subtitle' => 'nullable|string|max:255',
            'about_description' => 'required|string',
            'about_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'contact_address' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'whatsapp_number' => 'nullable|string|max:20',
        ]);

        $data = $request->except(['hero_image', 'about_image']);

        if ($request->hasFile('hero_image')) {
            if ($content->hero_image && !str_starts_with($content->hero_image, 'http')) {
                Storage::disk('public')->delete($content->hero_image);
            }
            $data['hero_image'] = $request->file('hero_image')->store('landing', 'public');
        }

        if ($request->hasFile('about_image')) {
            if ($content->about_image && !str_starts_with($content->about_image, 'http')) {
                Storage::disk('public')->delete($content->about_image);
            }
            $data['about_image'] = $request->file('about_image')->store('landing', 'public');
        }

        $content->update($data);

        return redirect()->route('admin.landing.edit')->with('success', 'Konten landing page berhasil diperbarui!');
    }
}
