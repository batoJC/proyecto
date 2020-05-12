
{{-- <h1>Landing page con el boton que dice dashboard y redicreciona a donde debe ser!!</h1> --}}

{{-- @if (Route::has('login'))
    <div class="top-right links">
        @auth
            @if(Auth::user()->id_rol == 1)
                <a href="{{ url('/owner') }}">owner</a>
            @elseif(Auth::user()->id_rol == 2)
                <a href="{{ url('/admin') }}">admin</a>
            @else
                <a href="{{ url('/home') }}">Inicio</a>
            @endif                        
        @else
            <a href="{{ route('login') }}">Login</a>
            <a href="{{ route('register') }}">Register</a>
        @endauth
    </div>
@endif --}}

{{-- @if (Route::has('login'))
    <div class="top-right links">
        @auth
            <a href="{{ url('/home') }}">Inicio</a>
        @else
            <a href="{{ route('login') }}">Login</a>
            <a href="{{ route('register') }}">Register</a>
        @endauth
    </div>
@endif --}}

