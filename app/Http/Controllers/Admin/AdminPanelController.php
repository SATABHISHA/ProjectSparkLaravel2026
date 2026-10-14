<?php

namespace App\Http\Controllers\Admin;

class AdminPanelController
{
    public function __invoke()
    {
        return view('admin.dashboard');
    }
}
