<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="_token" content="{{csrf_token()}}"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <title>@yield('title', 'Page')</title>
</head>
<body>
<div class="container">
    <header class="d-flex flex-wrap justify-content-center py-3 mb-4 border-bottom">
        <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-dark text-decoration-none">
            <svg class="bi me-2" width="40" height="32">
                <use xlink:href="#bootstrap"></use>
            </svg>
            <span class="fs-4">Ingvar</span>
        </a>

        <ul class="nav nav-pills">
            <li class="nav-item"><a href="/" class="nav-link active" aria-current="page">Home</a></li>
            <li class="nav-item"><a href="{{route('users.index')}}" class="nav-link active"
                                    aria-current="page">Users</a></li>
            <li class="nav-item"><a href="{{route('users.create')}}" class="nav-link active" aria-current="page">Registration</a>
            </li>
        </ul>
    </header>
</div>

<div class="container">
    @yield('main-content', 'Default content')
</div>

{{--jQuery--}}
<script src="https://code.jquery.com/jquery-3.5.0.js"></script>
@stack('end_scripts')
</body>
</html>
