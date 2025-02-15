@if(Auth::check())
    @if(Auth::user()->role === 'admin')
        <script>window.location = "{{ route('admin.dashboard') }}";</script>
    @else
        <script>window.location = "{{ route('user.dashboard') }}";</script>
    @endif
@else
    <h1>Welcome to Our Website</h1>
    <a href="{{ route('login') }}">Login</a>
@endif
