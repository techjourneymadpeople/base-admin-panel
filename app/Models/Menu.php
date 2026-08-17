<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Route;

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
        if ($this->route && request()->routeIs($this->route . '*')) {
            return true;
        }

        if ($this->url && request()->is(trim($this->url, '/') . '*')) {
            return true;
        }

        // Check if any child is active
        if ($this->children && $this->children->isNotEmpty()) {
            foreach ($this->children as $child) {
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

        // Super Admin can view all menus
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        // If no permission specified, available to all authenticated users
        if (empty($this->permission)) {
            return true;
        }

        return $user->can($this->permission);
    }
}
