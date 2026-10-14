<?php

namespace App\Services;

use App\Models\ContentSection;
use App\Models\FocusArea;
use App\Models\Partner;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Schema;

class SiteContentService
{
    public function payload(): array
    {
        $settings = Schema::hasTable('site_settings') ? SiteSetting::query()->pluck('value', 'key')->all() : [];

        return [
            'settings' => array_merge($this->defaultSettings(), $settings),
            'sections' => Schema::hasTable('content_sections') ? ContentSection::query()->where('is_visible', true)->orderBy('sort_order')->get()->values() : collect(),
            'focus_areas' => Schema::hasTable('focus_areas') ? FocusArea::query()->where('is_visible', true)->orderBy('sort_order')->get()->values() : collect(),
            'partners' => Schema::hasTable('partners') ? Partner::query()->where('is_visible', true)->orderBy('sort_order')->get()->values() : collect(),
        ];
    }

    public function defaultSettings(): array
    {
        return [
            'site_name' => 'Project SPARK',
            'site_kicker' => 'School and Public Awareness for Responsible Knowledge of Health',
            'site_tagline' => 'Igniting Awareness, Inspiring Healthy Choices.',
            'site_intro' => 'A Healthier Tomorrow Begins with Awareness.',
            'hero_supporting' => 'Knowledge is the first step towards a healthier life. Awareness is the catalyst for change.',
            'collaboration_intro' => 'Two institutions, complementary strengths and one shared commitment to social well-being.',
            'focus_intro' => 'Programmes will be designed according to community needs, appropriate age groups, relevant health priorities and professional guidance.',
            'programmes_intro' => 'Health awareness travels further when learning is engaging, practical and shared.',
            'site_logo' => '/images/logo.jpeg',
            'address' => 'Narendrapur Nilachal complex plot no 9 Ram Krishna Misson Mandir Gate opposite Suravi sweet kolkata 700103',
            'contact_email' => '',
            'contact_phone' => '',
            'footer_commitment' => 'Promoting responsible health awareness through education, collaboration and community engagement.',
            'disclaimer' => 'Project SPARK is an educational and public awareness initiative. Its content is intended for general informational purposes and does not replace professional medical advice, diagnosis or treatment. Individuals should consult qualified health professionals regarding personal medical concerns.',
            'smtp_host' => '', 'smtp_port' => '587', 'smtp_username' => '', 'smtp_password' => '',
            'smtp_encryption' => 'tls', 'smtp_from_address' => '', 'smtp_from_name' => 'Project SPARK',
        ];
    }

    public function setting(string $key, ?string $fallback = null): ?string
    {
        return (Schema::hasTable('site_settings') ? SiteSetting::query()->where('key', $key)->value('value') : null)
            ?? ($this->defaultSettings()[$key] ?? $fallback);
    }
}
