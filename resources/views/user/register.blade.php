<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1" name="viewport" />
  <title>Register - Luxury Toys</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet" />
</head>

<body class="vh-100" style="font-family: 'Montserrat', sans-serif;">
  <div class="container-fluid p-0">
    <div class="row g-0 position-relative h-100">

      <div class="col-md-10 d-none d-md-block">
        <img src="{{ asset('storage/register.webp') }}" alt="Luxury Cars" class="w-100 vh-100 object-fit-cover" />
      </div>


      <div class="col-md-2 d-flex flex-column justify-content-center align-items-center position-relative bg-white"
        style="min-height: 100vh;">

      </div>


      <div class="position-absolute top-50 end-0 translate-middle-y bg-white rounded-4 p-4 p-lg-5 shadow-lg me-4"
        style="max-width: 600px; z-index: 10;">
        <div class="d-flex align-items-center mb-4 mb-lg-5">
          <div class="me-3 me-lg-4" style="width: 50px; height: 50px;">
            <img src="{{ asset('storage/logo.png') }}" alt="Logo" class="img-fluid" />
          </div>
          <h1 class="fw-bold m-0 display-5">REGISTER</h1>
        </div>

        @if ($errors->any())
        <div class="alert alert-danger mb-4">
          <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('user.register') }}">
          @csrf
          <div class="mb-3 mb-lg-4">
            <label for="name" class="form-label fs-5">Full Name</label>
            <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" id="name"
              name="name" value="{{ old('name') }}" required autofocus />
            @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3 mb-lg-4">
            <label for="email" class="form-label fs-5">Email</label>
            <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" id="email"
              name="email" value="{{ old('email') }}" required />
            @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3 mb-lg-4">
            <label for="password" class="form-label fs-5">Password</label>
            <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror"
              id="password" name="password" required />
            @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3 mb-lg-4">
            <label for="password_confirmation" class="form-label fs-5">Confirm Password</label>
            <input type="password" class="form-control form-control-lg" id="password_confirmation"
              name="password_confirmation" required />
          </div>

          <button type="submit" class="btn btn-dark btn-lg rounded-pill fw-bold px-4 px-lg-5 py-3 mb-3 mb-lg-4 w-100">
            CREATE ACCOUNT
          </button>
        </form>

        <div class="d-flex justify-content-center fs-6">
          <span class="me-2">Already have an account?</span>
          <a href="{{ route('user.login') }}" class="text-decoration-underline text-dark">Log in</a>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>