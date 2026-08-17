<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebConfiguration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class WebConfigurationController extends Controller
{
    /**
     * Display the web configuration edit form (direct form, no index).
     */
    public function edit(): View
    {
        $config = WebConfiguration::current();

        return view('admin.settings.web-configuration', compact('config'));
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
            'maintenance_mode' => ['sometimes', 'boolean'],
            'logo' => ['nullable', 'image', 'mimes:png,jpg,jpeg,svg,webp', 'max:2048'],
            'favicon' => ['nullable', 'image', 'mimes:png,ico,svg,jpg,jpeg', 'max:1024'],
        ]);

        $validated['maintenance_mode'] = $request->boolean('maintenance_mode');

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
