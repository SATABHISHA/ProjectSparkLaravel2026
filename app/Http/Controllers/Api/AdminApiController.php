<?php

namespace App\Http\Controllers\Api;

use App\Models\ContentSection;
use App\Models\ContactSubmission;
use App\Models\FocusArea;
use App\Models\Partner;
use App\Models\SiteSetting;
use App\Models\User;
use App\Services\SiteContentService;
use App\Services\SmtpMailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class AdminApiController
{
    public function __construct(private readonly SiteContentService $content, private readonly SmtpMailService $mailer) {}

    public function dashboard()
    {
        return response()->json(['data' => [
            'content' => $this->content->payload(),
            'submissions_count' => ContactSubmission::where('status', 'new')->count(),
            'admins_count' => User::where('is_admin', true)->count(),
        ]]);
    }

    public function settings()
    {
        $settings = array_merge($this->content->defaultSettings(), SiteSetting::pluck('value', 'key')->all());
        $settings['smtp_password'] = $settings['smtp_password'] ? '••••••••' : '';
        return response()->json(['data' => $settings]);
    }

    public function updateSettings(Request $request)
    {
        $allowed = array_keys($this->content->defaultSettings());
        $data = $request->validate(['settings' => ['required', 'array']]);
        foreach ($data['settings'] as $key => $value) {
            if (! in_array($key, $allowed, true) || ($key === 'smtp_password' && $value === '••••••••')) continue;
            SiteSetting::updateOrCreate(['key' => $key], ['value' => is_scalar($value) ? (string) $value : json_encode($value), 'type' => 'text']);
        }
        return $this->settings();
    }

    public function sections()
    {
        return response()->json(['data' => ContentSection::orderBy('sort_order')->get()]);
    }

    public function updateSection(Request $request, ContentSection $section)
    {
        $data = $request->validate([
            'eyebrow' => ['nullable', 'string', 'max:160'], 'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'], 'body' => ['nullable', 'string'], 'items' => ['nullable', 'array'],
            'sort_order' => ['nullable', 'integer', 'min:0'], 'is_visible' => ['boolean'],
        ]);
        $section->update($data);
        return response()->json(['data' => $section->fresh()]);
    }

    public function focusAreas()
    {
        return response()->json(['data' => FocusArea::orderBy('sort_order')->get()]);
    }

    public function storeFocusArea(Request $request)
    {
        $area = FocusArea::create($request->validate(['number' => ['required', 'string', 'max:8'], 'title' => ['required', 'string', 'max:255'], 'description' => ['required', 'string'], 'icon' => ['nullable', 'string', 'max:40'], 'sort_order' => ['nullable', 'integer'], 'is_visible' => ['boolean']]));
        return response()->json(['data' => $area], 201);
    }

    public function updateFocusArea(Request $request, FocusArea $focusArea)
    {
        $focusArea->update($request->validate(['number' => ['required', 'string', 'max:8'], 'title' => ['required', 'string', 'max:255'], 'description' => ['required', 'string'], 'icon' => ['nullable', 'string', 'max:40'], 'sort_order' => ['nullable', 'integer'], 'is_visible' => ['boolean']]));
        return response()->json(['data' => $focusArea->fresh()]);
    }

    public function deleteFocusArea(FocusArea $focusArea)
    {
        $focusArea->delete();
        return response()->json(['message' => 'Focus area deleted.']);
    }

    public function partners()
    {
        return response()->json(['data' => Partner::orderBy('sort_order')->get()]);
    }

    public function updatePartner(Request $request, Partner $partner)
    {
        $partner->update($request->validate(['name' => ['required', 'string', 'max:255'], 'tagline' => ['nullable', 'string', 'max:255'], 'description' => ['nullable', 'string'], 'logo_path' => ['nullable', 'string', 'max:255'], 'sort_order' => ['nullable', 'integer'], 'is_visible' => ['boolean']]));
        return response()->json(['data' => $partner->fresh()]);
    }

    public function submissions(Request $request)
    {
        $query = ContactSubmission::latest();
        if ($request->filled('status')) $query->where('status', $request->string('status'));
        return response()->json(['data' => $query->paginate(20)]);
    }

    public function updateSubmission(Request $request, ContactSubmission $submission)
    {
        $submission->update($request->validate(['status' => ['required', 'in:new,reviewed,closed']]));
        return response()->json(['data' => $submission->fresh()]);
    }

    public function admins()
    {
        return response()->json(['data' => User::where('is_admin', true)->latest()->get(['id', 'name', 'email', 'is_active', 'created_at'])]);
    }

    public function storeAdmin(Request $request)
    {
        $data = $request->validate(['name' => ['required', 'string', 'max:120'], 'email' => ['required', 'email', 'max:160', 'unique:users,email'], 'password' => ['nullable', 'string', 'min:8']]);
        $password = $data['password'] ?: Str::password(14);
        $admin = User::create(['name' => $data['name'], 'email' => $data['email'], 'password' => Hash::make($password), 'is_admin' => true, 'is_active' => true]);
        $sent = $this->mailer->sendCredentials($admin->email, $admin->name, $password);
        return response()->json(['data' => $admin->only(['id', 'name', 'email']), 'message' => $sent ? 'Admin created and credentials sent.' : 'Admin created. SMTP is not configured, so use the email app option below.', 'temporary_password' => $sent ? null : $password, 'mailto' => $sent ? null : $this->mailto($admin->email, 'Project SPARK admin credentials', "Email: {$admin->email}\nTemporary password: {$password}")], 201);
    }

    public function updateAdminPassword(Request $request, User $user)
    {
        abort_unless($user->is_admin, 404);
        $data = $request->validate(['password' => ['required', 'string', 'min:8', 'confirmed'], 'send_email' => ['boolean']]);
        $user->update(['password' => Hash::make($data['password'])]);
        $sent = ! empty($data['send_email']) && $this->mailer->sendCredentials($user->email, $user->name, $data['password']);
        return response()->json(['message' => $sent ? 'Password reset and emailed.' : 'Password reset successfully.', 'mailto' => $sent ? null : $this->mailto($user->email, 'Updated Project SPARK admin credentials', "Email: {$user->email}\nNew password: {$data['password']}")]);
    }

    public function toggleAdmin(User $user)
    {
        abort_unless($user->is_admin, 404);
        abort_if($user->id === auth()->id(), 422, 'You cannot deactivate your own account.');
        $user->update(['is_active' => ! $user->is_active]);
        return response()->json(['data' => $user->only(['id', 'name', 'email', 'is_active'])]);
    }

    private function mailto(string $email, string $subject, string $body): string
    {
        return 'mailto:' . $email . '?subject=' . rawurlencode($subject) . '&body=' . rawurlencode($body);
    }
}
