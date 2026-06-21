<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\PublicController;
use Illuminate\Support\Facades\Route;

// TEMPORARY DEBUG ROUTE - remove after diagnosing Cloudinary config issue
Route::get('/debug-cloudinary', function () {
    $url = env('CLOUDINARY_URL');
    $configUrl = config('filesystems.disks.cloudinary.url');
    $parsed = $url ? parse_url($url) : null;

    return response()->json([
        'env_CLOUDINARY_URL_present' => $url !== null && $url !== '',
        'env_CLOUDINARY_URL_length' => $url ? strlen($url) : 0,
        'env_CLOUDINARY_URL_starts_with' => $url ? substr($url, 0, 14) : null,
        'env_CLOUDINARY_URL_has_at_symbol' => $url ? str_contains($url, '@') : false,
        'config_filesystems_url_present' => $configUrl !== null && $configUrl !== '',
        'config_matches_env' => $url === $configUrl,
        'cloudinary_cloud_name_env' => env('CLOUDINARY_CLOUD_NAME') ? 'set' : 'not set',
        'parse_url_result_keys' => $parsed ? array_keys($parsed) : null,
        'parse_url_has_user' => isset($parsed['user']),
        'parse_url_has_pass' => isset($parsed['pass']),
        'parse_url_has_host' => isset($parsed['host']),
        'parse_url_user_length' => isset($parsed['user']) ? strlen($parsed['user']) : 0,
        'parse_url_pass_length' => isset($parsed['pass']) ? strlen($parsed['pass']) : 0,
        'parse_url_host_value' => $parsed['host'] ?? null,
        'secret_contains_special_chars' => $url ? (bool) preg_match('#[/+=]#', explode('@', str_replace('cloudinary://', '', $url))[0] ?? '') : null,
    ]);
});

Route::get('/portfolio', [PublicController::class, 'portfolio']);
Route::get('/profile', [PublicController::class, 'profile']);
Route::get('/projects', [PublicController::class, 'projects']);
Route::get('/blogs', [PublicController::class, 'blogs']);
Route::get('/experience', [PublicController::class, 'experience']);
Route::get('/skills', [PublicController::class, 'skills']);
Route::post('/messages', [PublicController::class, 'storeMessage'])->middleware('throttle:public-submissions');
Route::post('/reviews', [PublicController::class, 'storeReview'])->middleware('throttle:public-submissions');

Route::post('/admin/login', [AdminController::class, 'login'])->middleware('throttle:admin-login');

Route::middleware('auth:sanctum')->prefix('admin')->group(function () {
    Route::post('/logout', [AdminController::class, 'logout']);

    Route::get('/settings', [AdminController::class, 'settings']);
    Route::put('/settings', [AdminController::class, 'updateSettings']);

    Route::get('/messages', [AdminController::class, 'messages']);
    Route::put('/messages/{id}/read', [AdminController::class, 'readMessage'])->whereNumber('id');

    Route::put('/profile', [AdminController::class, 'updateProfile']);
    Route::post('/upload', [AdminController::class, 'upload']);

    Route::get('/projects', [AdminController::class, 'indexProjects']);
    Route::post('/projects', [AdminController::class, 'storeProject']);
    Route::put('/projects/{project}', [AdminController::class, 'updateProject']);
    Route::delete('/projects/{project}', [AdminController::class, 'destroyProject']);

    Route::get('/blogs', [AdminController::class, 'indexBlogs']);
    Route::post('/blogs', [AdminController::class, 'storeBlog']);
    Route::put('/blogs/{blog}', [AdminController::class, 'updateBlog']);
    Route::delete('/blogs/{blog}', [AdminController::class, 'destroyBlog']);

    Route::get('/experience', [AdminController::class, 'indexExperiences']);
    Route::post('/experience', [AdminController::class, 'storeExperience']);
    Route::put('/experience/{experience}', [AdminController::class, 'updateExperience']);
    Route::delete('/experience/{experience}', [AdminController::class, 'destroyExperience']);

    Route::get('/skills', [AdminController::class, 'indexSkills']);
    Route::post('/skills', [AdminController::class, 'storeSkill']);
    Route::put('/skills/{skill}', [AdminController::class, 'updateSkill']);
    Route::delete('/skills/{skill}', [AdminController::class, 'destroySkill']);

    Route::get('/faqs', [AdminController::class, 'indexFaqs']);
    Route::post('/faqs', [AdminController::class, 'storeFaq']);
    Route::put('/faqs/{faq}', [AdminController::class, 'updateFaq']);
    Route::delete('/faqs/{faq}', [AdminController::class, 'destroyFaq']);

    Route::get('/reviews', [AdminController::class, 'indexReviews']);
    Route::put('/reviews/{review}', [AdminController::class, 'updateReview']);
    Route::delete('/reviews/{review}', [AdminController::class, 'destroyReview']);
});