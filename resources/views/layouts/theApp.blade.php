<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="https://js.stripe.com/v3/"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="https://unpkg.com/vue-material/dist/vue-material.min.css">
    <link rel="stylesheet" href="https://unpkg.com/vue-material/dist/theme/default.css">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Roboto:400,500,700,400italic|Material+Icons">

    <link rel="stylesheet" href="https://unpkg.com/vue-multiselect@2.1.0/dist/vue-multiselect.min.css">
</head>
<body>
    <div id="app" class="page-container md-layout-column">
        <dashboard>
            <template v-slot:title>
                @yield('title')
            </template>

            <template v-slot:toolbar_right>
                @yield('toolbar_right')
            </template>

            <template v-slot:navigation-items>
                @if(\Illuminate\Support\Facades\Auth::user()->isAdmin())
                    @include('layouts.navigation.admin')
                @elseif(\Illuminate\Support\Facades\Auth::user()->isModerator())
                    @include('layouts.navigation.moderator')
                @elseif(\Illuminate\Support\Facades\Auth::user()->isStudent())
                    @include('layouts.navigation.student')
                @elseif(\Illuminate\Support\Facades\Auth::user()->isTeacher())
                    @include('layouts.navigation.teacher')
                @endif
            </template>
            <!-- Page Content -->
            <template v-slot:content>
                @yield('content')
            </template>
        </dashboard>

        <chat
            conversation-endpoint="{{route('api.chat.get_conversations')}}"
            send-message-endpoint="{{route('api.chat.send')}}"
            current-user-id="{{Auth::user()->id}}"
            :friends="{{Auth::user()->friends}}"
        ></chat>

        <!-- Snack Bar flash messages -->
        @if(Session::has('message'))
            <flash message="{{Session::get('message')}}"></flash>
        @endif
    </div>

</body>
</html>
