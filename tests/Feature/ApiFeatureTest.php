<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Message;
use App\Models\Review;
use Cloudinary\Api\ApiResponse;
use Cloudinary\Api\Upload\UploadApi;
use Cloudinary\Cloudinary;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Laravel\Sanctum\Sanctum;
use Mockery;
use Tests\TestCase;

class ApiFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_health_endpoint_is_available(): void
    {
        $this->get('/up')->assertOk();
    }

    public function test_portfolio_endpoint_works_with_an_empty_database(): void
    {
        $this->getJson('/api/portfolio')
            ->assertOk()
            ->assertJson([
                'settings' => [],
                'profile' => null,
                'projects' => [],
                'blogs' => [],
                'experience' => [],
                'skills' => [],
                'faqs' => [],
                'reviews' => [],
            ]);
    }

    public function test_message_validation_and_creation(): void
    {
        $this->postJson('/api/messages', [
            'name' => '',
            'email' => 'not-an-email',
            'message' => 'short',
        ])->assertUnprocessable()->assertJsonValidationErrors(['name', 'email', 'message']);

        $this->postJson('/api/messages', [
            'name' => 'Portfolio Visitor',
            'email' => 'visitor@example.com',
            'message' => 'I would like to discuss a project with you.',
        ])->assertCreated()->assertJsonPath('data.email', 'visitor@example.com');

        $this->assertDatabaseHas(Message::class, ['email' => 'visitor@example.com']);
    }

    public function test_review_validation_and_creation(): void
    {
        $this->postJson('/api/reviews', [
            'name' => '',
            'rating' => 7,
            'text' => 'No',
            'social_link' => 'invalid',
        ])->assertUnprocessable()->assertJsonValidationErrors(['name', 'rating', 'text', 'social_link']);

        $this->postJson('/api/reviews', [
            'name' => 'Client Name',
            'role' => 'Product Manager',
            'rating' => 5,
            'text' => 'Excellent communication and implementation quality.',
            'social_link' => 'https://example.com/client',
        ])->assertCreated()->assertJsonPath('data.is_approved', false);

        $this->assertDatabaseHas(Review::class, [
            'name' => 'Client Name',
            'is_approved' => false,
        ]);
    }

    public function test_invalid_and_successful_admin_login(): void
    {
        $admin = Admin::create([
            'email' => 'owner@example.com',
            'password' => 'StrongPass!123',
        ]);

        $this->postJson('/api/admin/login', [
            'email' => $admin->email,
            'password' => 'wrong-password',
        ])->assertUnauthorized();

        $this->postJson('/api/admin/login', [
            'email' => $admin->email,
            'password' => 'StrongPass!123',
        ])->assertOk()->assertJsonStructure(['token', 'email']);
    }

    public function test_protected_route_requires_a_token_and_accepts_a_valid_token(): void
    {
        $this->getJson('/api/admin/settings')->assertUnauthorized();

        $admin = Admin::create([
            'email' => 'owner@example.com',
            'password' => 'StrongPass!123',
        ]);

        Sanctum::actingAs($admin, ['*']);

        $this->getJson('/api/admin/settings')->assertOk()->assertExactJson([]);
    }

    public function test_upload_rejects_unsupported_files_without_calling_cloudinary(): void
    {
        $admin = Admin::create([
            'email' => 'owner@example.com',
            'password' => 'StrongPass!123',
        ]);
        Sanctum::actingAs($admin, ['*']);

        $this->postJson('/api/admin/upload', [
            'file' => UploadedFile::fake()->create('script.exe', 20, 'application/octet-stream'),
        ])->assertUnprocessable()->assertJsonValidationErrors(['file']);
    }

    public function test_upload_uses_cloudinary_and_returns_a_secure_url(): void
    {
        $admin = Admin::create([
            'email' => 'owner@example.com',
            'password' => 'StrongPass!123',
        ]);
        Sanctum::actingAs($admin, ['*']);

        config()->set('filesystems.disks.cloudinary.url', 'cloudinary://key:secret@example');

        $uploadApi = Mockery::mock(UploadApi::class);
        $uploadApi->shouldReceive('upload')
            ->once()
            ->andReturn(new ApiResponse([
                'secure_url' => 'https://res.cloudinary.com/example/image/upload/file.jpg',
                'public_id' => 'portfolio/images/file',
                'resource_type' => 'image',
                'format' => 'jpg',
                'width' => 100,
                'height' => 100,
            ], []));

        $cloudinary = Mockery::mock(Cloudinary::class);
        $cloudinary->shouldReceive('uploadApi')->once()->andReturn($uploadApi);
        $this->app->instance(Cloudinary::class, $cloudinary);

        $this->post('/api/admin/upload', [
            'file' => UploadedFile::fake()->create('photo.jpg', 100, 'image/jpeg'),
        ], ['Accept' => 'application/json'])
            ->assertCreated()
            ->assertJsonPath('url', 'https://res.cloudinary.com/example/image/upload/file.jpg');
    }
}
