<?php

namespace App\Http\Controllers;

use App\Services\SiteContentService;

class SiteController
{
    public function __invoke(SiteContentService $content)
    {
        return view('welcome', ['site' => $content->payload()]);
    }
}
