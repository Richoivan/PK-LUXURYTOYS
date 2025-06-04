<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1" name="viewport"/>
  <title>Login - Luxury Toys</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Montserrat&display=swap" rel="stylesheet"/>
</head>
<body style="font-family: 'Montserrat', sans-serif;" class="vh-100">
  <div class="container-fluid p-0">
    <div class="row g-0 position-relative h-100">
      <div class="col-md-6 d-none d-md-block">
        <img src="{{ asset('storage/login.jpg') }}" alt="Luxury Cars" class="w-100 vh-100 object-fit-cover"/>
      </div>
      
      <div class="col-md-6 d-flex flex-column justify-content-center align-items-center position-relative bg-white" style="min-height: 100vh;">
        <div class="d-flex flex-column align-items-center">
          <div class="d-flex justify-content-center align-items-center mt-4">
            <img alt="Logo" src="{{ asset('storage/logo.png') }}" class="w-90 h-90"/>
          </div>
        </div>
      </div>
      
      <div class="position-absolute top-50 start-50 translate-middle bg-white rounded-4 p-4 shadow-lg" style="max-width: 400px; z-index: 10;">
        @if(session('error'))
          <div class="alert alert-danger">
            {{ session('error') }}
          </div>
        @endif
         @if(session('success'))
          <div class="alert alert-success">
            {{ session('success') }}
          </div>
        @endif

        <h1 class="fw-bold mb-4 fs-1">LOG IN</h1>
        
        <form method="POST" action="{{ route('user.login') }}">
          @csrf
          <div class="mb-3">
            <label for="email" class="form-label small">Email</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autofocus />
            @error('email')
              <span class="invalid-feedback">{{ $message }}</span>
            @enderror
          </div>
          
          <div class="mb-3">
            <label for="password" class="form-label small">Password</label>
            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required />
            @error('password')
              <span class="invalid-feedback">{{ $message }}</span>
            @enderror
          </div>
          
          <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }} />
            <label class="form-check-label small" for="remember">
              Remember me
            </label>
          </div>
          
          <button type="submit" class="btn btn-dark rounded-pill fw-bold px-4 w-100 mb-3">LOG IN</button>
        </form>
        
        <div class="d-flex justify-content-between small">
          <a href="{{ route('user.forget-password') }}" class="text-decoration-underline text-dark">Forget Password</a>
          <a href="{{ route('user.register') }}" class="text-decoration-underline text-dark">Don't have an account?</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>