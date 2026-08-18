<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BusinessIdentity;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BusinessIdentityController extends Controller
{
    /**
     * Display the business identity edit form (direct form, no index).
     */
    public function edit(): View
    {
        $identity = BusinessIdentity::current();
        $identity->load(['logoLightMedia', 'logoDarkMedia', 'faviconMedia', 'heroBannerMedia']);

        return view('admin.business-identity.edit', compact('identity'));
    }

    /**
     * Update the business identity in storage.
     */
    public function update(Request $request): RedirectResponse
    {
        $identity = BusinessIdentity::current();

        $validated = $request->validate([
            // Identitas & Legalitas
            'company_name' => ['required', 'string', 'max:255'],
            'brand_name' => ['nullable', 'string', 'max:255'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'legal_type' => ['nullable', 'string', 'max:100'],
            'business_category' => ['nullable', 'string', 'max:100'],
            'npwp' => ['nullable', 'string', 'max:100'],
            'nib' => ['nullable', 'string', 'max:100'],
            'founded_year' => ['nullable', 'string', 'max:10'],
            'director_name' => ['nullable', 'string', 'max:255'],

            // Informasi Perbankan & Rekening Resmi
            'bank_name' => ['nullable', 'string', 'max:100'],
            'bank_account_number' => ['nullable', 'string', 'max:100'],
            'bank_account_holder' => ['nullable', 'string', 'max:255'],
            'bank_branch' => ['nullable', 'string', 'max:255'],

            // Tentang Perusahaan, Visi & Misi
            'about_summary' => ['nullable', 'string', 'max:1000'],
            'about_story' => ['nullable', 'string'],
            'vision' => ['nullable', 'string', 'max:1000'],
            'mission' => ['nullable', 'string', 'max:2000'],
            'core_values' => ['nullable', 'string', 'max:2000'],

            // Aset Visual (Media Library)
            'logo_light_media_id' => ['nullable', 'string', 'exists:media,id'],
            'logo_light_url' => ['nullable', 'string', 'max:2048'],
            'logo_dark_media_id' => ['nullable', 'string', 'exists:media,id'],
            'logo_dark_url' => ['nullable', 'string', 'max:2048'],
            'favicon_media_id' => ['nullable', 'string', 'exists:media,id'],
            'favicon_url' => ['nullable', 'string', 'max:2048'],
            'hero_banner_media_id' => ['nullable', 'string', 'exists:media,id'],
            'hero_banner_url' => ['nullable', 'string', 'max:2048'],

            // Kontak & Lokasi Kantor
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'whatsapp' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:1000'],
            'city' => ['nullable', 'string', 'max:100'],
            'province' => ['nullable', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'google_maps_embed' => ['nullable', 'string'],
            'google_maps_url' => ['nullable', 'url', 'max:1000'],
            'operational_hours' => ['nullable', 'string', 'max:255'],

            // Sosial Media Resmi
            'social_instagram' => ['nullable', 'url', 'max:255'],
            'social_tiktok' => ['nullable', 'url', 'max:255'],
            'social_youtube' => ['nullable', 'url', 'max:255'],
            'social_linkedin' => ['nullable', 'url', 'max:255'],
            'social_facebook' => ['nullable', 'url', 'max:255'],
            'social_twitter' => ['nullable', 'url', 'max:255'],
            'social_threads' => ['nullable', 'url', 'max:255'],
        ]);

        $identity->update($validated);

        return redirect()->route('admin.business-identity.edit')
            ->with('success', 'Profile Business Identity berhasil diperbarui!');
    }
}
