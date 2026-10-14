<!doctype html>
<html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Admin sign in · Project SPARK</title><link rel="stylesheet" href="{{ asset('admin.css') }}"></head>
<body class="auth-page"><div class="auth-orb orb-a"></div><div class="auth-orb orb-b"></div><main class="auth-card">
<a class="auth-brand" href="{{ route('home') }}"><span class="brand-mark"><span></span><span></span><span></span></span><span><b>PROJECT SPARK</b><small>Admin portal</small></span></a>
<div class="auth-copy"><span class="eyebrow">Welcome back</span><h1>Keep the spark moving.</h1><p>Manage the public story, programmes and collaboration details from one calm place.</p></div>
@if(session('status'))<div class="notice">{{ session('status') }}</div>@endif
@if($errors->any())<div class="error-box">{{ $errors->first() }}</div>@endif
<form method="post" action="{{ route('admin.login.submit') }}" class="auth-form">@csrf
<label>Email address<input type="email" name="email" value="{{ old('email') }}" required autofocus></label><label>Password<input type="password" name="password" required></label>
<div class="form-meta"><label class="check"><input type="checkbox" name="remember"> Remember me</label><a href="{{ route('admin.password.request') }}">Forgot password?</a></div><button class="admin-button" type="submit">Sign in <span>↗</span></button></form>
<a class="back-link" href="{{ route('home') }}">← Return to public site</a></main></body></html>
