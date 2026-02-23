
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset ('assets/img/image.png') }}">


    <title>Vit Equine Center System</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">


    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">


</head>
<body>

<section class="vh-100">
  <div class="container-fluid">
    <div class="row">
        <div class="col-sm-6 px-0 d-none d-sm-block">
            <img src="{{ asset('assets/img/image.png') }}"
          alt="Login image" class="w-100 vh-100" style="object-fit: cover; object-position: left;">
        </div>
        <div class="col-12 d-sm-none px-0">
            <img src="{{ asset('assets/img/image.png') }}"
          alt="Login image" class="w-100" style="object-fit: cover; object-position: left; height: 250px;">
        </div>
        <div class="col-sm-6 text-black">

          <div class="d-flex align-items-center h-custom-2 px-5 ms-xl-4 mt-5 pt-2 pt-xl-0 mt-xl-n5">
              <form style="width: 23rem;" method="POST" action="{{ route('login') }}">
                      @csrf


                  <div data-mdb-input-init class="form-outline mb-4">
                  <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                      <div>
                          <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                          @error('email')
                              <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                              </span>
                          @enderror
                      </div>
                  </div>
                  <div data-mdb-input-init class="form-outline mb-4">
                  <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>
                      <div>
                          <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                          @error('password')
                              <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                              </span>
                              @enderror
                          </div>
                  </div>

                  <div class="pt-1 mb-4">
                      <div class="form-check">
                          <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                              <label class="form-check-label" for="remember">
                                  {{ __('Remember Me') }}
                              </label>
                      </div>
                  </div>
                  <div class="pt-1 mb-4 text-center">
                      <button type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-block mb-3 w-50">
                          {{ __('Login') }}
                      </button>
                  </div>
                  <h1 class="text-center">Vit Animal System</h1>
                          <br>
                          <hr>
                      <p style="color: hsl(217, 10%, 50.8%)">
                      a Software that helps you manage animal husbandry
                      in a smart and easy way. You can track animal health,
                      veterinary appointments, costs, and profits all in one place.
                      This way, you can focus on improving productivity
                      without the hassle of paperwork or manual calculations.
                      </p>
              </form>
          </div>

        </div>
    </div>
  </div>
</section>
</body>
</html>


