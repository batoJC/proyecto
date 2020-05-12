@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="nombre_completo" class="col-md-4 col-form-label text-md-right">{{ __('nombre_completo') }}</label>

                            <div class="col-md-6">
                                <input id="nombre_completo" type="text" class="form-control{{ $errors->has('nombre_completo') ? ' is-invalid' : '' }}" name="nombre_completo" value="{{ old('nombre_completo') }}" required autofocus>

                                @if ($errors->has('nombre_completo'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('nombre_completo') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="edad" class="col-md-4 col-form-label text-md-right">{{ __('edad') }}</label>

                            <div class="col-md-6">
                                <input id="edad" type="number" class="form-control{{ $errors->has('edad') ? ' is-invalid' : '' }}" name="edad" value="{{ old('edad') }}" required autofocus>

                                @if ($errors->has('edad'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('edad') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="genero" class="col-md-4 col-form-label text-md-right">{{ __('genero') }}</label>

                            <div class="col-md-6">
                                <input id="genero" type="text" class="form-control{{ $errors->has('genero') ? ' is-invalid' : '' }}" name="genero" value="{{ old('genero') }}" required autofocus>

                                @if ($errors->has('genero'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('genero') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="tipo_cuenta" class="col-md-4 col-form-label text-md-right">{{ __('tipo_cuenta') }}</label>

                            <div class="col-md-6">
                                <input id="tipo_cuenta" type="text" class="form-control{{ $errors->has('tipo_cuenta') ? ' is-invalid' : '' }}" name="tipo_cuenta" value="{{ old('tipo_cuenta') }}" autofocus>

                                @if ($errors->has('tipo_cuenta'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('tipo_cuenta') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="numero_cuenta" class="col-md-4 col-form-label text-md-right">{{ __('numero_cuenta') }}</label>

                            <div class="col-md-6">
                                <input id="numero_cuenta" type="password" class="form-control{{ $errors->has('numero_cuenta') ? ' is-invalid' : '' }}" name="numero_cuenta" value="{{ old('numero_cuenta') }}" autofocus>

                                @if ($errors->has('numero_cuenta'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('numero_cuenta') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="telefono" class="col-md-4 col-form-label text-md-right">{{ __('telefono') }}</label>

                            <div class="col-md-6">
                                <input id="telefono" type="number" class="form-control{{ $errors->has('telefono') ? ' is-invalid' : '' }}" name="telefono" value="{{ old('telefono') }}" autofocus>

                                @if ($errors->has('telefono'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('telefono') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="celular" class="col-md-4 col-form-label text-md-right">{{ __('celular') }}</label>

                            <div class="col-md-6">
                                <input id="celular" type="number" class="form-control{{ $errors->has('celular') ? ' is-invalid' : '' }}" name="celular" value="{{ old('celular') }}" required autofocus>

                                @if ($errors->has('celular'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('celular') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="id_rol" class="col-md-4 col-form-label text-md-right">{{ __('id_rol') }}</label>

                            <div class="col-md-6">
                                <input id="id_rol" type="number" class="form-control{{ $errors->has('id_rol') ? ' is-invalid' : '' }}" name="id_rol" value="{{ old('id_rol') }}" required autofocus>

                                @if ($errors->has('id_rol'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('id_rol') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
