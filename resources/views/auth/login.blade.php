@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 100%; height: 100%; margin: 0;">
    <div class="row" style="height: 100%;">
        <div class="col-md-5">
        <form method="POST" action="{{ route('login') }}" style="margin-top: 30%;">
                @csrf
                <div class="form-group">
                    <label for="email" class="col-md-4 offset-md-2 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                    <div class="col-md-6 offset-md-3">
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="password" class="col-md-4 offset-md-1 col-form-label text-md-right">{{ __('Password') }}</label>

                    <div class="col-md-6 offset-md-3">
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-6 offset-md-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                            <label class="form-check-label" for="remember">
                                {{ __('Remember Me') }}
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-group mb-0">
                    <div class="col-md-8 offset-md-3">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Login') }}
                        </button>

                        @if (Route::has('password.request'))
                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                {{ __('Forgot Your Password?') }}
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-7" style="background-image: url('https://www.xtrafondos.com/wallpapers/resized/ninja-katana-sci-fi-city-neon-lights-5026.jpg?s=large'); background-size: cover; max-height: 100%;" > 
            
        </div>
    </div>
</div>
@endsection
