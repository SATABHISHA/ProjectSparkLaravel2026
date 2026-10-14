<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('text');
            $table->timestamps();
        });

        Schema::create('content_sections', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('eyebrow')->nullable();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->longText('body')->nullable();
            $table->json('items')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_visible')->default(true);
            $table->timestamps();
        });

        Schema::create('focus_areas', function (Blueprint $table) {
            $table->id();
            $table->string('number', 8);
            $table->string('title');
            $table->text('description');
            $table->string('icon')->default('spark');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_visible')->default(true);
            $table->timestamps();
        });

        Schema::create('partners', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('tagline')->nullable();
            $table->text('description')->nullable();
            $table->string('logo_path')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_visible')->default(true);
            $table->timestamps();
        });

        Schema::create('contact_submissions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('organization')->nullable();
            $table->text('message');
            $table->string('status')->default('new');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_submissions');
        Schema::dropIfExists('partners');
        Schema::dropIfExists('focus_areas');
        Schema::dropIfExists('content_sections');
        Schema::dropIfExists('site_settings');
    }
};
