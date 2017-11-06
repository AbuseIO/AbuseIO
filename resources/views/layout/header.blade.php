<!DOCTYPE html>
<html lang="{{ isset($auth_user->locale) ? $auth_user->locale : 'en' }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('/favicon.ico') }}">
    <title>{{ Config::get('app.name') }} {{ Config::get('app.version') }}</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/font-roboto.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/material-icons.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/bootstrap-material-design.min.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/dropdown.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/flag-icon-min.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/font-awesome.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/style.css') }}" />
</head>
<body>