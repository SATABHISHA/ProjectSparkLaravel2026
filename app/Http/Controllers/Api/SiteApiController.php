<?php

namespace App\Http\Controllers\Api;

use App\Models\ContactSubmission;
use App\Services\SiteContentService;
use Illuminate\Http\Request;

class SiteApiController
{
    public function __construct(private readonly SiteContentService $content) {}

    public function show()
    {
        return response()->json(['data' => $this->content->payload()]);
    }

    public function contact(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:160'],
            'organization' => ['nullable', 'string', 'max:160'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $submission = ContactSubmission::create($data);

        return response()->json(['message' => 'Thank you. Your note has been received.', 'data' => $submission], 201);
    }
}
