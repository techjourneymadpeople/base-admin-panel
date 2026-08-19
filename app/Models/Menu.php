<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;

class Menu extends Model
{
    use HasFactory, HasUlids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'parent_id',
        'title',
        'type',
        'route',
        'url',
        'icon',
        'permission',
        'badge',
        'badge_color',
        'order',
        'is_active',
        'target',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Many-to-Many relationship with Spatie Permissions.
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'menu_has_permissions', 'menu_id', 'permission_id');
    }

    /**
     * Parent menu relationship.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    /**
     * Sub-menu / children relationship.
     */
    public function children(): HasMany
    {
        return $this->hasMany(Menu::class, 'parent_id')
            ->where('is_active', true)
            ->orderBy('order');
    }

    /**
     * Scope for top-level menu items.
     */
    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id')->orderBy('order');
    }

    /**
     * Scope for active menu items.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Assign permissions to this menu item.
     */
    public function assignPermissions(...$permissions): self
    {
        $permissionIds = collect($permissions)->flatten()->map(function ($permission) {
            if ($permission instanceof Permission) {
                return $permission->id;
            }
            if (is_numeric($permission)) {
                return (int) $permission;
            }
            $perm = Permission::where('name', $permission)->first();
            return $perm ? $perm->id : null;
        })->filter()->all();

        $this->permissions()->syncWithoutDetaching($permissionIds);

        return $this;
    }

    /**
     * Sync permissions for this menu item.
     */
    public function syncPermissions($permissions): self
    {
        $permissionIds = collect($permissions)->flatten()->map(function ($permission) {
            if ($permission instanceof Permission) {
                return $permission->id;
            }
            if (is_numeric($permission)) {
                return (int) $permission;
            }
            $perm = Permission::where('name', $permission)->first();
            return $perm ? $perm->id : null;
        })->filter()->all();

        $this->permissions()->sync($permissionIds);

        return $this;
    }

    /**
     * Resolve the target URL for this menu item.
     */
    public function getUrl(): string
    {
        if ($this->route && Route::has($this->route)) {
            return route($this->route);
        }

        if ($this->url) {
            return url($this->url);
        }

        return '#';
    }

    /**
     * Check if the current menu item or any of its children is currently active.
     */
    public function isActive(): bool
    {
        if ($this->route) {
            if (request()->routeIs($this->route, $this->route . '.*')) {
                return true;
            }

            // If route ends in .index (e.g. admin.users.index), match admin.users.* (create, edit, show, roles, etc.)
            if (str_ends_with($this->route, '.index')) {
                $baseRoute = substr($this->route, 0, -6);
                if (request()->routeIs($baseRoute . '.*')) {
                    return true;
                }
            }
        }

        if ($this->url && $this->url !== '#') {
            $path = trim(parse_url($this->getUrl(), PHP_URL_PATH) ?? $this->url, '/');
            if (!empty($path) && (request()->is($path) || request()->is($path . '/*'))) {
                return true;
            }
        }

        // Check if any child is active
        if ($this->relationLoaded('children') ? $this->children->isNotEmpty() : $this->children()->exists()) {
            $children = $this->relationLoaded('children') ? $this->children : $this->children;
            foreach ($children as $child) {
                if ($child->isActive()) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Determine if this menu is visible for the given user based on permissions and roles.
     */
    public function isVisibleForUser(?User $user = null): bool
    {
        $user = $user ?? auth()->user();

        if (!$user) {
            return false;
        }

        // 0. Check system-wide module toggles (Web Configuration)
        $isArticleMenu = (
            ($this->route && in_array($this->route, ['admin.articles.index', 'admin.article-categories.index', 'admin.article-tags.index'])) ||
            in_array($this->title, ['Article SEO', 'Article Category', 'Article Tag', 'Article']) ||
            ($this->parent && in_array($this->parent->title, ['Article SEO']))
        );

        if ($isArticleMenu) {
            $config = WebConfiguration::current();
            if (!$config->article_module_enabled) {
                return false;
            }
        }

        $isTestimonialMenu = (
            ($this->route && in_array($this->route, ['admin.testimonials.index'])) ||
            in_array($this->title, ['Testimonial', 'Testimoni']) ||
            ($this->parent && in_array($this->parent->title, ['Testimonial', 'Testimoni']))
        );

        if ($isTestimonialMenu) {
            $config = WebConfiguration::current();
            if (!$config->testimonial_module_enabled) {
                return false;
            }
        }

        $isPartnerMenu = (
            ($this->route && in_array($this->route, ['admin.partners.index'])) ||
            in_array($this->title, ['Brand / Partner', 'Partner', 'Mitra', 'Brand']) ||
            ($this->parent && in_array($this->parent->title, ['Brand / Partner', 'Partner', 'Mitra', 'Brand']))
        );

        if ($isPartnerMenu) {
            $config = WebConfiguration::current();
            if (!$config->partner_module_enabled) {
                return false;
            }
        }

        $isFaqMenu = (
            ($this->route && in_array($this->route, ['admin.faqs.index'])) ||
            in_array($this->title, ['FAQ', 'Tanya Jawab', 'Faq']) ||
            ($this->parent && in_array($this->parent->title, ['FAQ', 'Tanya Jawab', 'Faq']))
        );

        if ($isFaqMenu) {
            $config = WebConfiguration::current();
            if (!$config->faq_module_enabled) {
                return false;
            }
        }

        $isGalleryMenu = (
            ($this->route && in_array($this->route, ['admin.gallery-activities.index'])) ||
            in_array($this->title, ['Galeri Kegiatan', 'Gallery Activity', 'Galeri', 'Gallery']) ||
            ($this->parent && in_array($this->parent->title, ['Galeri Kegiatan', 'Gallery Activity', 'Galeri', 'Gallery']))
        );

        if ($isGalleryMenu) {
            $config = WebConfiguration::current();
            if (!$config->gallery_module_enabled) {
                return false;
            }
        }

        // Super Admin can view all remaining active menus
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        // 1. Check Many-to-Many permissions relation
        if ($this->relationLoaded('permissions') ? $this->permissions->isNotEmpty() : $this->permissions()->exists()) {
            $permissionNames = $this->relationLoaded('permissions') 
                ? $this->permissions->pluck('name') 
                : $this->permissions()->pluck('name');

            if ($user->hasAnyPermission($permissionNames)) {
                return true;
            }
            return false;
        }

        // 2. Check direct string permission column
        if (!empty($this->permission)) {
            return $user->can($this->permission);
        }

        // 3. Dropdown Menu visibility: visible if at least one child is visible
        if ($this->type === 'dropdown') {
            if ($this->children && $this->children->isNotEmpty()) {
                return $this->children->some(fn($child) => $child->isVisibleForUser($user));
            }
            return false;
        }

        // 4. Default: open to all authenticated users if no permissions assigned
        return true;
    }
}
