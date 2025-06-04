<!DOCTYPE html>
<html lang="en">


<head>
   <meta charset="UTF-8">
   <title>@yield('title', 'Admin Page')</title>
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
   <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Montserrat&display=swap"
       rel="stylesheet">
   <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
   <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
   <style>


   </style>
</head>


<body style="font-family: 'Montserrat', sans-serif;">


   <div class="bg-black text-white p-4 d-flex align-items-center justify-content-between">
       <div class="d-flex align-items-center">
           <a href="{{ route('admin.dashboard') }}" class="me-4 ms-5">
               <img src="{{ asset('storage/logo-white.png') }}" alt="Logo" style="height: 60px;">
           </a>
           <h4 class="mb-0 fw-bold">@yield('header', 'Welcome to Admin Page')</h4>
       </div>
      
       <div class="me-5">
            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-light rounded-pill px-4 py-2 d-flex align-items-center">
                    <i class="fas fa-sign-out-alt me-2"></i>
                    <span>Exit</span>
                </button>
            </form>
        </div>

   </div>


   @yield('content')


   <!-- Bootstrap JS -->
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


   @yield('scripts')
</body>


</html>
