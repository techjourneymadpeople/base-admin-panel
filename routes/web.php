<?php

use App\Http\Controllers\Admin\ArticleCategoryController;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\ArticleTagController;
use App\Http\Controllers\Admin\MediaLibraryController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\UserRoleController;
use App\Http\Controllers\Admin\WebConfigurationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // 1. User Profile Management (Profil Saya)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // 2. Web Configuration (Direct Edit Form)
    Route::get('/settings', [WebConfigurationController::class, 'edit'])->name('settings.edit')->middleware('can:view-settings');
    Route::put('/settings', [WebConfigurationController::class, 'update'])->name('settings.update')->middleware('can:edit-settings');

    // 3. Media Library (Gudang Gambar, Cropper & WebP Compressor)
    Route::get('/media', [MediaLibraryController::class, 'index'])->name('media.index')->middleware('can:view-content');
    Route::get('/media/api/list', [MediaLibraryController::class, 'apiList'])->name('media.api.list')->middleware('can:view-content');
    Route::post('/media', [MediaLibraryController::class, 'store'])->name('media.store')->middleware('can:upload-media');
    Route::post('/media/{media}/crop', [MediaLibraryController::class, 'crop'])->name('media.crop')->middleware('can:upload-media');
    Route::delete('/media/{media}', [MediaLibraryController::class, 'destroy'])->name('media.destroy')->middleware('can:delete-media');

    // 4. User Export & Import (Excel)
    Route::get('/users/export/excel', [UserController::class, 'export'])->name('users.export')->middleware('can:view-users');
    Route::post('/users/import/excel', [UserController::class, 'import'])->name('users.import')->middleware('can:create-users');
    Route::get('/users/import/template', [UserController::class, 'downloadTemplate'])->name('users.import.template')->middleware('can:create-users');

    // 5. Dedicated Assign Role Routes
    Route::get('/users/{user}/roles', [UserRoleController::class, 'edit'])->name('users.roles.edit')->middleware('can:assign-roles');
    Route::put('/users/{user}/roles', [UserRoleController::class, 'update'])->name('users.roles.update')->middleware('can:assign-roles');

    // 6. User CRUD Resource (No Delete)
    Route::resource('users', UserController::class, ['except' => ['destroy']]);

    // 7. Role Routes (with Dedicated Assign Permissions)
    Route::get('/roles/{role}/permissions', [RoleController::class, 'permissions'])->name('roles.permissions')->middleware('can:assign-permissions');
    Route::put('/roles/{role}/permissions', [RoleController::class, 'updatePermissions'])->name('roles.permissions.update')->middleware('can:assign-permissions');
    Route::resource('roles', RoleController::class);

    // 8. Permission Routes
    Route::resource('permissions', PermissionController::class);

    // 9. Dynamic Menu Routes (with View-only Assign Permissions)
    Route::get('/menus/{menu}/permissions', [MenuController::class, 'permissions'])->name('menus.permissions')->middleware('can:assign-menu-permissions');
    Route::put('/menus/{menu}/permissions', [MenuController::class, 'updatePermissions'])->name('menus.permissions.update')->middleware('can:assign-menu-permissions');
    Route::resource('menus', MenuController::class);

    // 10. Article SEO Routes
    // Sitemap manual regenerate
    Route::post('/articles/regenerate-sitemap', [ArticleController::class, 'regenerateSitemap'])->name('articles.regenerate-sitemap')->middleware('can:edit-articles');
    
    // Article Categories CRUD
    Route::resource('article-categories', ArticleCategoryController::class, [
        'names' => 'article-categories',
    ]);

    // Article Tags CRUD
    Route::resource('article-tags', ArticleTagController::class, [
        'names' => 'article-tags',
    ]);

    // Articles CRUD
    Route::resource('articles', ArticleController::class);

    // 11. Gallery Activity Routes
    Route::resource('gallery-activities', \App\Http\Controllers\Admin\GalleryActivityController::class, [
        'names' => 'gallery-activities',
    ]);

    // 12. FAQ Routes
    Route::post('faqs/{faq}/toggle-status', [\App\Http\Controllers\Admin\FaqController::class, 'toggleStatus'])->name('faqs.toggle-status')->middleware('can:edit-faqs');
    Route::resource('faqs', \App\Http\Controllers\Admin\FaqController::class);

    // 13. Brand / Partner Routes
    Route::post('partners/{partner}/toggle-status', [\App\Http\Controllers\Admin\PartnerController::class, 'toggleStatus'])->name('partners.toggle-status')->middleware('can:edit-partners');
    Route::resource('partners', \App\Http\Controllers\Admin\PartnerController::class);

    // 14. Testimonial Routes
    Route::post('testimonials/{testimonial}/toggle-status', [\App\Http\Controllers\Admin\TestimonialController::class, 'toggleStatus'])->name('testimonials.toggle-status')->middleware('can:edit-testimonials');
    Route::resource('testimonials', \App\Http\Controllers\Admin\TestimonialController::class);

    // 15. Feedback / Saran & Masukan Routes
    Route::post('feedbacks/{feedback}/toggle-star', [\App\Http\Controllers\Admin\FeedbackController::class, 'toggleStar'])->name('feedbacks.toggle-star')->middleware('can:edit-feedbacks');
    Route::post('feedbacks/{feedback}/update-status', [\App\Http\Controllers\Admin\FeedbackController::class, 'updateStatus'])->name('feedbacks.update-status')->middleware('can:edit-feedbacks');
    Route::resource('feedbacks', \App\Http\Controllers\Admin\FeedbackController::class);

    // 16. Profile Business Identity Routes
    Route::get('/business-identity', [\App\Http\Controllers\Admin\BusinessIdentityController::class, 'edit'])->name('business-identity.edit')->middleware('can:view-business-identity');
    Route::put('/business-identity', [\App\Http\Controllers\Admin\BusinessIdentityController::class, 'update'])->name('business-identity.update')->middleware('can:edit-business-identity');
});
