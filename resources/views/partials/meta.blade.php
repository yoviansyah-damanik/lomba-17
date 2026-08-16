@php
    $metaTitle = $title ?? config('app.name', 'Laravel');
    $metaDescription = $description ?? config('app.description');
@endphp

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">

<meta name="description" content="{{ $metaDescription }}">
<meta name="author" content="{{ config('app.owner') }}">
<meta name="robots" content="noindex, nofollow">
<meta name="theme-color" content="#dc2626">

<title>{{ $metaTitle }}</title>

<link rel="icon" href="{{ Vite::asset('resources/images/logo.png') }}">
<link rel="apple-touch-icon" href="{{ Vite::asset('resources/images/logo.png') }}">

<meta property="og:type" content="website">
<meta property="og:site_name" content="{{ config('app.name') }}">
<meta property="og:title" content="{{ $metaTitle }}">
<meta property="og:description" content="{{ $metaDescription }}">
<meta property="og:image" content="{{ Vite::asset('resources/images/logo.png') }}">
<meta property="og:locale" content="{{ str_replace('-', '_', app()->getLocale()) }}">

<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="{{ $metaTitle }}">
<meta name="twitter:description" content="{{ $metaDescription }}">
<meta name="twitter:image" content="{{ Vite::asset('resources/images/logo.png') }}">
