<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminLoginRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\UpsertBlogRequest;
use App\Http\Requests\UpsertExperienceRequest;
use App\Http\Requests\UpsertFaqRequest;
use App\Http\Requests\UpsertProjectRequest;
use App\Http\Requests\UpsertSkillRequest;
use App\Models\Admin;
use App\Models\Blog;
use App\Models\Experience;
use App\Models\Faq;
use App\Models\Message;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Review;
use App\Models\SiteSetting;
use App\Models\Skill;
use Cloudinary\Api\Upload\UploadApi;
use Cloudinary\Configuration\Configuration;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class AdminController extends Controller
{
    public function login(AdminLoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();
        $admin = Admin::where('email', $credentials['email'])->first();

        if (! $admin || ! Hash::check($credentials['password'], $admin->password)) {
            return response()->json(['message' => 'Invalid email or password.'], 401);
        }

        // Keep only the newest admin session token.
        $admin->tokens()->delete();
        $token = $admin->createToken('admin-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'email' => $admin->email,
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $token = $request->user()?->currentAccessToken();

        if ($token && method_exists($token, 'delete')) {
            $token->delete();
        }

        return response()->json(['message' => 'Logged out successfully.']);
    }

    public function settings(): JsonResponse
    {
        $settings = SiteSetting::query()
            ->get()
            ->mapWithKeys(fn (SiteSetting $setting) => [$setting->key => $setting->value])
            ->all();

        return response()->json($settings);
    }

    public function updateSettings(Request $request): JsonResponse
    {
        $allowedKeys = implode(',', [
            'site',
            'navItems',
            'hero',
            'about',
            'techStack',
            'projectsSection',
            'blogsSection',
            'journeySection',
            'faqSection',
            'reviewsSection',
            'contact',
            'seo',
            'theme',
        ]);

        $validated = $request->validate([
            'settings' => ['required', 'array:'.$allowedKeys],
            'settings.*' => ['nullable', 'array'],
        ]);

        foreach ($validated['settings'] as $key => $value) {
            SiteSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return $this->settings();
    }

    public function messages(): JsonResponse
    {
        return response()->json(Message::latest()->get());
    }

    public function readMessage(int $id): JsonResponse
    {
        $message = Message::findOrFail($id);
        $message->update(['is_read' => true]);

        return response()->json($message);
    }

    public function updateProfile(UpdateProfileRequest $request): JsonResponse
    {
        $profile = Profile::first();

        if (! $profile) {
            $profile = Profile::create($request->validated());
        } else {
            $profile->update($request->validated());
        }

        return response()->json($profile);
    }

    public function upload(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'file' => [
                'required',
                'file',
                'max:5120',
                'mimetypes:image/jpeg,image/png,image/gif,image/webp,image/svg+xml,application/pdf',
            ],
        ]);

        $cloudinaryConfig = config('filesystems.disks.cloudinary', []);
        $hasUrl = filled($cloudinaryConfig['url'] ?? null);
        $hasSeparateCredentials = filled($cloudinaryConfig['cloud'] ?? null)
            && filled($cloudinaryConfig['key'] ?? null)
            && filled($cloudinaryConfig['secret'] ?? null);

        if (! $hasUrl && ! $hasSeparateCredentials) {
            return response()->json([
                'message' => 'Cloudinary is not configured on the backend.',
            ], 503);
        }

        $file = $validated['file'];
        $isPdf = $file->getMimeType() === 'application/pdf';
        $folder = $isPdf ? 'portfolio/documents' : 'portfolio/images';

        try {
            $cloudinaryUrl = env('CLOUDINARY_URL');

            if (blank($cloudinaryUrl)) {
                throw new \RuntimeException('CLOUDINARY_URL is not set in the environment.');
            }

            $configuration = Configuration::instance($cloudinaryUrl);
            $uploadApi = new UploadApi($configuration);

            $result = $uploadApi->upload($file->getRealPath(), [
                'folder' => $folder,
                'public_id' => Str::uuid()->toString(),
                'resource_type' => 'auto',
                'overwrite' => false,
            ]);

            $url = $result['secure_url'] ?? $result['url'] ?? null;

            if (! $url) {
                throw new \RuntimeException('Cloudinary did not return a delivery URL.');
            }

            return response()->json([
                'url' => $url,
                'public_id' => $result['public_id'] ?? null,
                'resource_type' => $result['resource_type'] ?? null,
                'format' => $result['format'] ?? null,
                'width' => $result['width'] ?? null,
                'height' => $result['height'] ?? null,
            ], 201);
        } catch (Throwable $exception) {
            Log::error('Cloudinary upload failed.', [
                'exception_class' => get_class($exception),
                'exception' => $exception->getMessage(),
            ]);

            return response()->json([
                'message' => 'The file could not be uploaded. Please try again.',
            ], 502);
        }
    }

    public function indexProjects(): JsonResponse
    {
        return response()->json(Project::orderBy('sort_order')->get());
    }

    public function storeProject(UpsertProjectRequest $request): JsonResponse
    {
        return response()->json(Project::create($request->validated()), 201);
    }

    public function updateProject(UpsertProjectRequest $request, Project $project): JsonResponse
    {
        $project->update($request->validated());

        return response()->json($project);
    }

    public function destroyProject(Project $project): JsonResponse
    {
        $project->delete();

        return response()->json(['message' => 'Project deleted successfully.']);
    }

    public function indexBlogs(): JsonResponse
    {
        return response()->json(Blog::orderBy('sort_order')->get());
    }

    public function storeBlog(UpsertBlogRequest $request): JsonResponse
    {
        return response()->json(Blog::create($request->validated()), 201);
    }

    public function updateBlog(UpsertBlogRequest $request, Blog $blog): JsonResponse
    {
        $blog->update($request->validated());

        return response()->json($blog);
    }

    public function destroyBlog(Blog $blog): JsonResponse
    {
        $blog->delete();

        return response()->json(['message' => 'Blog deleted successfully.']);
    }

    public function indexExperiences(): JsonResponse
    {
        return response()->json(Experience::orderBy('sort_order')->get());
    }

    public function storeExperience(UpsertExperienceRequest $request): JsonResponse
    {
        return response()->json(Experience::create($request->validated()), 201);
    }

    public function updateExperience(UpsertExperienceRequest $request, Experience $experience): JsonResponse
    {
        $experience->update($request->validated());

        return response()->json($experience);
    }

    public function destroyExperience(Experience $experience): JsonResponse
    {
        $experience->delete();

        return response()->json(['message' => 'Experience deleted successfully.']);
    }

    public function indexSkills(): JsonResponse
    {
        return response()->json(Skill::orderBy('sort_order')->get());
    }

    public function storeSkill(UpsertSkillRequest $request): JsonResponse
    {
        return response()->json(Skill::create($request->validated()), 201);
    }

    public function updateSkill(UpsertSkillRequest $request, Skill $skill): JsonResponse
    {
        $skill->update($request->validated());

        return response()->json($skill);
    }

    public function destroySkill(Skill $skill): JsonResponse
    {
        $skill->delete();

        return response()->json(['message' => 'Skill deleted successfully.']);
    }

    public function indexFaqs(): JsonResponse
    {
        return response()->json(Faq::orderBy('sort_order')->get());
    }

    public function storeFaq(UpsertFaqRequest $request): JsonResponse
    {
        return response()->json(Faq::create($request->validated()), 201);
    }

    public function updateFaq(UpsertFaqRequest $request, Faq $faq): JsonResponse
    {
        $faq->update($request->validated());

        return response()->json($faq);
    }

    public function destroyFaq(Faq $faq): JsonResponse
    {
        $faq->delete();

        return response()->json(['message' => 'FAQ deleted successfully.']);
    }

    public function indexReviews(): JsonResponse
    {
        return response()->json(Review::latest()->get());
    }

    public function updateReview(Request $request, Review $review): JsonResponse
    {
        $validated = $request->validate([
            'is_approved' => ['required', 'boolean'],
        ]);

        $review->update($validated);

        return response()->json($review);
    }

    public function destroyReview(Review $review): JsonResponse
    {
        $review->delete();

        return response()->json(['message' => 'Review deleted successfully.']);
    }
}