@props([
    'split' => true,
])

<div {{ $attributes->merge(['class' => 'w-full max-w-5xl mx-auto']) }}>
    @if($split)
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
            <!-- Left Branding Showcase Panel -->
            <div class="hidden lg:block lg:col-span-5">
                <x-auth.brand-panel />
            </div>

            <!-- Right Main Form Card -->
            <div class="lg:col-span-7 w-full max-w-xl mx-auto">
                {{ $slot }}
            </div>
        </div>
    @else
        <div class="w-full max-w-md mx-auto">
            {{ $slot }}
        </div>
    @endif
</div>
