<!doctype html>
<html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Reset password · Project SPARK</title><link rel="stylesheet" href="{{ asset('admin.css') }}"></head>
<body class="auth-page"><div class="auth-orb orb-a"></div><main class="auth-card"><a class="auth-brand" href="{{ route('home') }}"><span class="brand-mark"><span></span><span></span><span></span></span><span><b>PROJECT SPARK</b><small>Admin portal</small></span></a>
<div class="auth-copy"><span class="eyebrow">Account recovery</span><h1>Find your way back in.</h1><p>Enter the administrator email. If SMTP is configured, a reset link will land in your inbox.</p></div>
@if(session('status'))<div class="notice">{{ session('status') }}@if(session('reset_link'))<br><a href="{{ session('reset_link') }}">Open local reset link →</a>@endif</div>@endif
@if($errors->any())<div class="error-box">{{ $errors->first() }}</div>@endif
<form method="post" action="{{ route('admin.password.email') }}" class="auth-form">@csrf<label>Email address<input type="email" name="email" value="{{ old('email') }}" required autofocus></label><button class="admin-button" type="submit">Send reset link <span>↗</span></button></form><a class="back-link" href="{{ route('admin.login') }}">← Back to sign in</a></main></body></html>
