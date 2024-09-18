@include('layouts.head')
@include('layouts.nav')

<div class="container mt-4">
    @yield('content')
</div>

@include('layouts.logout-form')
@include('layouts.footer')
