<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add images JSON column
        Schema::table('projects', function (Blueprint $table) {
            $table->json('images')->nullable()->after('live_link');
        });

        // 2. Migrate existing image_url to images JSON
        $projects = DB::table('projects')->get();
        foreach ($projects as $project) {
            $images = [];
            if (!empty($project->image_url)) {
                $images[] = [
                    'url' => $project->image_url,
                    'alt' => $project->image_alt ?? ''
                ];
            }
            DB::table('projects')
                ->where('id', $project->id)
                ->update(['images' => json_encode($images)]);
        }

        // 3. Drop old columns
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['image_url', 'image_alt']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse process
        Schema::table('projects', function (Blueprint $table) {
            $table->text('image_url')->nullable()->after('live_link');
            $table->string('image_alt')->nullable()->after('image_url');
        });

        $projects = DB::table('projects')->get();
        foreach ($projects as $project) {
            $images = json_decode($project->images, true);
            $firstImage = is_array($images) && count($images) > 0 ? $images[0] : null;
            
            DB::table('projects')
                ->where('id', $project->id)
                ->update([
                    'image_url' => $firstImage ? $firstImage['url'] : null,
                    'image_alt' => $firstImage ? ($firstImage['alt'] ?? null) : null,
                ]);
        }

        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('images');
        });
    }
};
