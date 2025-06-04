
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1" name="viewport"/>
  <title>Forget Password - Luxury Toys</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet"/>
</head>
<body class="vh-100" style="font-family: 'Montserrat', sans-serif;">
  <div class="container-fluid p-0">
    <div class="row g-0 position-relative h-100">
      <!-- Left side  -->
      <div class="col-md-4 position-relative bg-white" style="min-height: 100vh;">
        <!-- Navbar-style logo in the top left -->
        <div class="position-absolute top-0 start-0 m-3 d-flex align-items-center">
          <img src="{{ asset('storage/logo.png') }}" alt="Logo" height="100">
        </div>
      </div>
      
      <!-- Right side  -->
      <div class="col-md-8 d-none d-md-block">
        <img src="{{ asset('storage/forget-password.webp') }}" alt="Luxury Cars" class="w-100 vh-100 object-fit-cover"/>
      </div>
      
      <!-- Forget Password modal -->
      <div class="position-absolute top-50 start-0 translate-middle-y bg-white rounded-4 p-4 p-lg-5 shadow-lg ms-4" style="max-width: 800px; z-index: 10;">
        <h1 class="fw-bold m-0 display-5 mb-4 mb-lg-5">FORGET PASSWORD</h1>
        

        <!-- Success Messages -->
        @if(session('success'))
          <div class="alert alert-success mb-4">
            {{ session('success') }}
          </div>
        @endif
        
        <!-- Error Messages -->
        @if(session('error'))
          <div class="alert alert-danger mb-4">
            {{ session('error') }}
          </div>
        @endif
        
        <form action="{{ route('user.forget-password.submit') }}" method="POST">
          @csrf
          <div class="mb-3 mb-lg-4">
            <label for="email" class="form-label fs-5">Email</label>
            <input type="email" name="email" class="form-control form-control-lg @error('email') is-invalid @enderror" 
                   id="email" value="{{ old('email') }}" required />
            @error('email')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
  
          <button type="submit" class="btn btn-dark btn-lg rounded-pill fw-bold px-4 px-lg-5 py-3 mb-3 mb-lg-4 w-100">
            VERIFY EMAIL
          </button>
        </form>

        <div class="d-flex justify-content-center fs-6">
          <a href="{{ route('user.login') }}" class="text-decoration-underline text-dark">Back to Login</a>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>