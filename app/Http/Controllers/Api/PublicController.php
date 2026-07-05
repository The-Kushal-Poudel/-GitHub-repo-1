<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMessageRequest;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Blog;
use App\Models\Experience;
use App\Models\Skill;
use App\Models\Message;
use App\Models\SiteSetting;
use App\Models\Review;
use App\Models\Faq;
use App\Http\Requests\StoreReviewRequest;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class PublicController extends Controller
{
    public function portfolio()
    {
        $settings = SiteSetting::query()
            ->get()
            ->mapWithKeys(fn (SiteSetting $setting) => [$setting->key => $setting->value])
            ->all();

        return response()->json([
            'settings' => $settings,
            'profile' => Profile::first(),
            'projects' => Project::where('is_visible', true)->orderBy('sort_order', 'asc')->get(),
            'blogs' => Blog::where('is_published', true)->orderBy('sort_order', 'asc')->get(),
            'experience' => Experience::orderBy('sort_order', 'asc')->get(),
            'skills' => Skill::orderBy('sort_order', 'asc')->get(),
            'faqs' => Faq::where('is_active', true)->orderBy('sort_order', 'asc')->get(),
            'reviews' => Review::where('is_approved', true)->latest()->get(),
        ]);
    }

    public function profile()
    {
        $profile = Profile::first();
        if (!$profile) {
            return response()->json(['message' => 'Profile not found'], 404);
        }
        return response()->json($profile);
    }

    public function projects()
    {
        $projects = Project::where('is_visible', true)
            ->orderBy('sort_order', 'asc')
            ->get();
        return response()->json($projects);
    }

    public function blogs()
    {
        $blogs = Blog::where('is_published', true)
            ->orderBy('sort_order', 'asc')
            ->get();
        return response()->json($blogs);
    }

    public function experience()
    {
        $experience = Experience::orderBy('sort_order', 'asc')->get();
        return response()->json($experience);
    }

    public function skills()
    {
        $skills = Skill::orderBy('sort_order', 'asc')->get();
        return response()->json($skills);
    }

    public function storeMessage(StoreMessageRequest $request)
    {
        $message = Message::create($request->validated());

        try {
            $profile = Profile::first();
            $recipient = $profile?->email ?: config('mail.from.address');

            if ($recipient) {
                Mail::raw(
                "You have a new portfolio message from: {$message->name} ({$message->email})\n\nMessage:\n{$message->message}",
                function ($mail) use ($message, $recipient) {
                    $mail->to($recipient)
                        ->subject('Portfolio inquiry from ' . $message->name);
                }
                );
            }
        } catch (\Exception $e) {
            Log::error('Failed to send contact email: ' . $e->getMessage());
        }

        return response()->json([
            'message' => 'Message sent successfully!',
            'data' => $message,
        ], 201);
    }

    public function storeReview(StoreReviewRequest $request)
    {
        $googleResponse = \Illuminate\Support\Facades\Http::withToken($request->google_token)
            ->get('https://www.googleapis.com/oauth2/v3/userinfo');

        if (!$googleResponse->successful()) {
            return response()->json(['message' => 'Invalid Google authentication token.'], 401);
        }

        $googleUser = $googleResponse->json();

        $review = Review::create([
            'name' => $request->name ?: ($googleUser['name'] ?? 'Anonymous'),
            'role' => $request->role,
            'rating' => $request->rating,
            'text' => $request->text,
            'google_id' => $googleUser['sub'] ?? null,
            'email' => $googleUser['email'] ?? null,
            'avatar' => $googleUser['picture'] ?? null,
            'is_approved' => false,
        ]);

        return response()->json([
            'message' => 'Review submitted successfully! It will appear once approved.',
            'data' => $review,
        ], 201);
    }
}
