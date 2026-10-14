<?php

namespace App\Services;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

class SmtpMailService
{
    public function configured(): bool
    {
        return filled(SiteSetting::query()->where('key', 'smtp_host')->value('value'))
            && filled(SiteSetting::query()->where('key', 'smtp_from_address')->value('value'));
    }

    public function configure(): void
    {
        $settings = SiteSetting::query()->whereIn('key', [
            'smtp_host', 'smtp_port', 'smtp_username', 'smtp_password',
            'smtp_encryption', 'smtp_from_address', 'smtp_from_name',
        ])->pluck('value', 'key');

        Config::set('mail.default', 'smtp');
        Config::set('mail.mailers.smtp.host', $settings->get('smtp_host'));
        Config::set('mail.mailers.smtp.port', (int) ($settings->get('smtp_port') ?: 587));
        Config::set('mail.mailers.smtp.username', $settings->get('smtp_username'));
        Config::set('mail.mailers.smtp.password', $settings->get('smtp_password'));
        Config::set('mail.mailers.smtp.encryption', $settings->get('smtp_encryption') ?: 'tls');
        Config::set('mail.from.address', $settings->get('smtp_from_address'));
        Config::set('mail.from.name', $settings->get('smtp_from_name') ?: 'Project SPARK');
    }

    public function sendResetLink(string $email, string $url): bool
    {
        if (! $this->configured()) return false;
        $this->configure();
        Mail::raw("Use this link to reset your Project SPARK admin password:\n\n{$url}\n\nThis link expires soon.", function ($message) use ($email) {
            $message->to($email)->subject('Reset your Project SPARK admin password');
        });
        return true;
    }

    public function sendCredentials(string $email, string $name, string $password): bool
    {
        if (! $this->configured()) return false;
        $this->configure();
        Mail::raw("Your Project SPARK admin account is ready.\n\nEmail: {$email}\nTemporary password: {$password}\n\nPlease sign in and change your password immediately.", function ($message) use ($email) {
            $message->to($email)->subject('Your Project SPARK admin credentials');
        });
        return true;
    }
}
