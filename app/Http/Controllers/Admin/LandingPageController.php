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
        // First load live content as a base
        $liveContent = LandingPageContent::first() ?? new LandingPageContent();
        $liveExtras = [];
        if (Storage::disk('public')->exists('landing/extras.json')) {
            $liveExtras = json_decode(Storage::disk('public')->get('landing/extras.json'), true);
        }

        // Initialize draft content and extras
        $content = $liveContent;
        $extras = $liveExtras;
        $status = 'published';
        $lastUpdated = $liveContent->updated_at ? $liveContent->updated_at->format('Y-m-d H:i:s') : now()->format('Y-m-d H:i:s');

        // Check if draft exists and load it
        if (Storage::disk('public')->exists('landing/draft.json')) {
            $draftData = json_decode(Storage::disk('public')->get('landing/draft.json'), true);
            
            $content = new LandingPageContent();
            $content->forceFill($draftData['db_fields'] ?? []);
            
            $extras = $draftData['extras_fields'] ?? [];
            $status = $draftData['publish_status'] ?? 'draft';
            $lastUpdated = $draftData['last_updated'] ?? $lastUpdated;
        }

        return view('admin.landing.edit', compact('content', 'extras', 'status', 'lastUpdated', 'liveContent', 'liveExtras'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'hero_title' => 'nullable|string|max:255',
            'hero_subtitle' => 'nullable|string',
            'hero_cta_text' => 'nullable|string|max:255',
            'hero_cta_link' => 'nullable|string|max:255',
            'hero_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'hero_bg_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'about_title' => 'nullable|string|max:255',
            'about_subtitle' => 'nullable|string|max:255',
            'about_description' => 'nullable|string',
            'about_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'cta_bg_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'setting_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'setting_favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:1024',
        ]);

        // Load existing draft or live data to merge images
        $existingDbFields = [];
        $existingExtrasFields = [];
        if (Storage::disk('public')->exists('landing/draft.json')) {
            $draftData = json_decode(Storage::disk('public')->get('landing/draft.json'), true);
            $existingDbFields = $draftData['db_fields'] ?? [];
            $existingExtrasFields = $draftData['extras_fields'] ?? [];
        } else {
            $liveContent = LandingPageContent::first();
            if ($liveContent) {
                $existingDbFields = $liveContent->toArray();
            }
            if (Storage::disk('public')->exists('landing/extras.json')) {
                $existingExtrasFields = json_decode(Storage::disk('public')->get('landing/extras.json'), true);
            }
        }

        // 1. Prepare Database fields
        $dbFields = [
            'hero_title' => $request->input('hero_title'),
            'hero_subtitle' => $request->input('hero_subtitle'),
            'hero_cta_text' => $request->input('hero_cta_text'),
            'hero_cta_link' => $request->input('hero_cta_link'),
            'about_title' => $request->input('about_title'),
            'about_subtitle' => $request->input('about_subtitle'),
            'about_description' => $request->input('about_description'),
            'contact_address' => $request->input('contact_address'),
            'contact_phone' => $request->input('contact_phone'),
            'contact_email' => $request->input('contact_email'),
            'whatsapp_number' => $request->input('whatsapp_number'),
            'hero_image' => $existingDbFields['hero_image'] ?? null,
            'about_image' => $existingDbFields['about_image'] ?? null,
        ];

        if ($request->hasFile('hero_image')) {
            $dbFields['hero_image'] = $request->file('hero_image')->store('landing', 'public');
        }
        if ($request->hasFile('about_image')) {
            $dbFields['about_image'] = $request->file('about_image')->store('landing', 'public');
        }

        // 2. Prepare Extras fields
        $extrasFields = [
            'hero_secondary_text' => $request->input('hero_secondary_text'),
            'hero_secondary_link' => $request->input('hero_secondary_link'),
            'hero_status' => $request->input('hero_status', 'active'),
            
            'stat_courts' => $request->input('stat_courts', '500+'),
            'stat_cities' => $request->input('stat_cities', '20+'),
            'stat_members' => $request->input('stat_members', '10.000+'),
            'stat_bookings' => $request->input('stat_bookings', '50.000+'),
            
            'features' => $request->input('features', []),
            'testimonials' => $request->input('testimonials', []),
            'popular_courts' => $request->input('popular_courts', []),
            'membership' => $request->input('membership', []),
            
            'cta_title' => $request->input('cta_title'),
            'cta_subtitle' => $request->input('cta_subtitle'),
            'cta_btn_text' => $request->input('cta_btn_text'),
            'cta_btn_link' => $request->input('cta_btn_link'),
            'cta_status' => $request->input('cta_status', 'active'),
            
            'company_name' => $request->input('company_name'),
            'social_instagram' => $request->input('social_instagram'),
            'social_facebook' => $request->input('social_facebook'),
            'social_tiktok' => $request->input('social_tiktok'),
            'social_whatsapp' => $request->input('social_whatsapp'),
            'social_youtube' => $request->input('social_youtube'),
            'footer_copyright' => $request->input('footer_copyright'),
            
            'hero_bg_image' => $existingExtrasFields['hero_bg_image'] ?? null,
            'cta_bg_image' => $existingExtrasFields['cta_bg_image'] ?? null,
            'setting_logo' => $existingExtrasFields['setting_logo'] ?? null,
            'setting_favicon' => $existingExtrasFields['setting_favicon'] ?? null,
        ];

        if ($request->hasFile('hero_bg_image')) {
            $extrasFields['hero_bg_image'] = $request->file('hero_bg_image')->store('landing', 'public');
        }
        if ($request->hasFile('cta_bg_image')) {
            $extrasFields['cta_bg_image'] = $request->file('cta_bg_image')->store('landing', 'public');
        }
        if ($request->hasFile('setting_logo')) {
            $extrasFields['setting_logo'] = $request->file('setting_logo')->store('landing', 'public');
        }
        if ($request->hasFile('setting_favicon')) {
            $extrasFields['setting_favicon'] = $request->file('setting_favicon')->store('landing', 'public');
        }

        // Handle dynamic list image uploads: Testimonials
        if ($request->has('testimonials')) {
            $testimonialsInput = $request->input('testimonials', []);
            if ($request->hasFile('testimonials')) {
                foreach ($request->file('testimonials') as $idx => $fileData) {
                    if (isset($fileData['photo_file'])) {
                        $testimonialsInput[$idx]['photo'] = $fileData['photo_file']->store('landing', 'public');
                    }
                }
            }
            foreach ($testimonialsInput as $idx => $val) {
                if (!isset($val['photo'])) {
                    $testimonialsInput[$idx]['photo'] = $existingExtrasFields['testimonials'][$idx]['photo'] ?? '';
                }
            }
            $extrasFields['testimonials'] = $testimonialsInput;
        }

        // Handle dynamic list image uploads: Popular Courts
        if ($request->has('popular_courts')) {
            $popularCourtsInput = $request->input('popular_courts', []);
            if ($request->hasFile('popular_courts')) {
                foreach ($request->file('popular_courts') as $idx => $fileData) {
                    if (isset($fileData['image_file'])) {
                        $popularCourtsInput[$idx]['image'] = $fileData['image_file']->store('landing', 'public');
                    }
                }
            }
            foreach ($popularCourtsInput as $idx => $val) {
                if (!isset($val['image'])) {
                    $popularCourtsInput[$idx]['image'] = $existingExtrasFields['popular_courts'][$idx]['image'] ?? '';
                }
            }
            $extrasFields['popular_courts'] = $popularCourtsInput;
        }

        // Save Draft data
        $draftData = [
            'db_fields' => $dbFields,
            'extras_fields' => $extrasFields,
            'publish_status' => 'draft',
            'last_updated' => now()->format('Y-m-d H:i:s'),
        ];

        Storage::disk('public')->put('landing/draft.json', json_encode($draftData));

        return back()->with('success', 'Draft landing page berhasil disimpan.');
    }

    public function publish(Request $request)
    {
        if (!Storage::disk('public')->exists('landing/draft.json')) {
            return back()->with('error', 'Tidak ada draft yang tersimpan untuk dipublikasikan.');
        }

        $draftData = json_decode(Storage::disk('public')->get('landing/draft.json'), true);

        // Backup current live version for Rollback
        $liveContent = LandingPageContent::first();
        $liveExtras = [];
        if (Storage::disk('public')->exists('landing/extras.json')) {
            $liveExtras = json_decode(Storage::disk('public')->get('landing/extras.json'), true);
        }

        $backupData = [
            'db_fields' => $liveContent ? $liveContent->toArray() : [],
            'extras_fields' => $liveExtras,
            'last_published' => now()->format('Y-m-d H:i:s'),
        ];
        Storage::disk('public')->put('landing/rollback.json', json_encode($backupData));

        // Commit database fields
        $content = LandingPageContent::firstOrCreate([]);
        $content->update($draftData['db_fields'] ?? []);

        // Commit extras JSON
        Storage::disk('public')->put('landing/extras.json', json_encode($draftData['extras_fields'] ?? []));

        // Set status to published
        $draftData['publish_status'] = 'published';
        Storage::disk('public')->put('landing/draft.json', json_encode($draftData));

        return back()->with('success', 'Konten landing page berhasil dipublikasikan secara live.');
    }

    public function rollback(Request $request)
    {
        if (!Storage::disk('public')->exists('landing/rollback.json')) {
            return back()->with('error', 'Tidak ada versi sebelumnya yang tersedia untuk di-rollback.');
        }

        $backupData = json_decode(Storage::disk('public')->get('landing/rollback.json'), true);

        // Restore live database record
        $content = LandingPageContent::firstOrCreate([]);
        $content->update($backupData['db_fields'] ?? []);

        // Restore live extras JSON file
        Storage::disk('public')->put('landing/extras.json', json_encode($backupData['extras_fields'] ?? []));

        // Sync draft JSON with restored live version
        $draftData = [
            'db_fields' => $backupData['db_fields'] ?? [],
            'extras_fields' => $backupData['extras_fields'] ?? [],
            'publish_status' => 'published',
            'last_updated' => now()->format('Y-m-d H:i:s'),
        ];
        Storage::disk('public')->put('landing/draft.json', json_encode($draftData));

        return back()->with('success', 'Konten landing page berhasil dikembalikan (rollback) ke versi sebelumnya.');
    }
}
