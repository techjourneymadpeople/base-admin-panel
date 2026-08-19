<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Faq;
use App\Models\GalleryActivity;
use App\Models\MediaWarehouse;
use App\Models\Partner;
use App\Models\Testimonial;
use App\Models\User;
use App\Models\WebConfiguration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class WebConfigurationController extends Controller
{
    /**
     * Display the web configuration edit form (direct form, no index).
     */
    public function edit(): View
    {
        $config = WebConfiguration::current();

        $warehouse = MediaWarehouse::getInstance();
        $totalMediaBytes = Media::where('model_type', MediaWarehouse::class)
            ->where('model_id', $warehouse->id)
            ->sum('size');
        $mediaStorageUsedMb = round($totalMediaBytes / (1024 * 1024), 2);

        $currentUsage = [
            'media_storage_bytes' => $totalMediaBytes,
            'media_storage_mb' => $mediaStorageUsedMb,
            'users_count' => User::count(),
            'articles_count' => Article::count(),
            'gallery_activities_count' => GalleryActivity::count(),
            'faqs_count' => Faq::count(),
            'partners_count' => Partner::count(),
            'testimonials_count' => Testimonial::count(),
        ];

        return view('admin.settings.web-configuration', compact('config', 'currentUsage'));
    }

    /**
     * Update the web configuration in storage.
     */
    public function update(Request $request): RedirectResponse
    {
        $config = WebConfiguration::current();

        $validated = $request->validate([
            'site_name' => ['required', 'string', 'max:255'],
            'site_tagline' => ['nullable', 'string', 'max:255'],
            'site_description' => ['nullable', 'string', 'max:1000'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:50'],
            'contact_whatsapp' => ['nullable', 'string', 'max:50'],
            'contact_address' => ['nullable', 'string', 'max:500'],
            'footer_text' => ['nullable', 'string', 'max:255'],
            'meta_keywords' => ['nullable', 'string', 'max:500'],
            'meta_author' => ['nullable', 'string', 'max:255'],
            'social_facebook' => ['nullable', 'string', 'max:255'],
            'social_instagram' => ['nullable', 'string', 'max:255'],
            'social_twitter' => ['nullable', 'string', 'max:255'],
            'social_youtube' => ['nullable', 'string', 'max:255'],
            'social_linkedin' => ['nullable', 'string', 'max:255'],
            'social_tiktok' => ['nullable', 'string', 'max:255'],
            'social_threads' => ['nullable', 'string', 'max:255'],
            'google_analytics_id' => ['nullable', 'string', 'max:100'],
            'custom_head_scripts' => ['nullable', 'string'],
            'custom_body_scripts' => ['nullable', 'string'],
            'robots_indexing' => ['sometimes', 'boolean'],
            'cookie_consent_enabled' => ['sometimes', 'boolean'],
            'cookie_consent_text' => ['nullable', 'string', 'max:500'],
            'maintenance_mode' => ['sometimes', 'boolean'],
            'registration_enabled' => ['sometimes', 'boolean'],
            'article_module_enabled' => ['sometimes', 'boolean'],
            'testimonial_module_enabled' => ['sometimes', 'boolean'],
            'limit_media_storage_mb' => ['nullable', 'integer', 'min:0'],
            'limit_users_count' => ['nullable', 'integer', 'min:0'],
            'limit_articles_count' => ['nullable', 'integer', 'min:0'],
            'limit_gallery_activities_count' => ['nullable', 'integer', 'min:0'],
            'limit_faqs_count' => ['nullable', 'integer', 'min:0'],
            'limit_partners_count' => ['nullable', 'integer', 'min:0'],
            'limit_testimonials_count' => ['nullable', 'integer', 'min:0'],
            'logo' => ['nullable', 'image', 'mimes:png,jpg,jpeg,svg,webp', 'max:2048'],
            'favicon' => ['nullable', 'image', 'mimes:png,ico,svg,jpg,jpeg', 'max:1024'],
        ]);

        $validated['maintenance_mode'] = $request->boolean('maintenance_mode');
        $validated['registration_enabled'] = $request->boolean('registration_enabled');
        $validated['article_module_enabled'] = $request->boolean('article_module_enabled');
        $validated['testimonial_module_enabled'] = $request->boolean('testimonial_module_enabled');
        $validated['robots_indexing'] = $request->boolean('robots_indexing', true);
        $validated['cookie_consent_enabled'] = $request->boolean('cookie_consent_enabled');

        // Handle Logo Upload
        if ($request->hasFile('logo')) {
            if ($config->logo_path && Storage::disk('public')->exists($config->logo_path)) {
                Storage::disk('public')->delete($config->logo_path);
            }
            $validated['logo_path'] = $request->file('logo')->store('settings', 'public');
        }

        // Handle Favicon Upload
        if ($request->hasFile('favicon')) {
            if ($config->favicon_path && Storage::disk('public')->exists($config->favicon_path)) {
                Storage::disk('public')->delete($config->favicon_path);
            }
            $validated['favicon_path'] = $request->file('favicon')->store('settings', 'public');
        }

        $config->update($validated);

        return redirect()->route('admin.settings.edit')
            ->with('success', 'Konfigurasi website berhasil disimpan dan diperbarui!');
    }
}
