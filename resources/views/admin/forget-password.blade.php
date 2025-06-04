<!-- resources/views/admin/forget-password.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Reset Password - Luxury Toys</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

  <!-- Google Font: Montserrat -->
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Montserrat&display=swap" rel="stylesheet" />
</head>
<body class="vh-100 m-0 p-0" style="font-family: 'Montserrat', sans-serif;">

  <div class="container-fluid h-100 m-0 p-0">
    <div class="row g-0 h-100">

      <div class="col-md-4 bg-black d-flex justify-content-center align-items-center">
        <div class="text-center">
          <img src="{{ asset('storage/logo-white.png') }}" alt="Logo" style="max-width: 220px;" class="mb-2">
        </div>
      </div>

      <div class="col-md-8 d-flex justify-content-center align-items-center bg-white">
        <div class="border rounded-4 shadow p-5" style="max-width: 480px; width: 100%;">
          <h2 class="fw-bold mb-4 text-center">RESET PASSWORD</h2>

         
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

          <form method="POST" action="{{ route('admin.forget-password.submit') }}">
            @csrf

            <div class="mb-3">
              <label for="email" class="form-label small">Admin Email</label>
              <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" 
                     value="{{ old('email') }}" required autofocus>
              @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <button type="submit" class="btn btn-dark fw-bold w-100 rounded-pill mb-3">VERIFY EMAIL</button>
          </form>

          <div class="text-center">
            <a href="{{ route('admin.login') }}" class="text-decoration-underline text-dark">Back to Login</a>
          </div>
        </div>
      </div>

    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>