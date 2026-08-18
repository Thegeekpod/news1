<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login – News 1 Admin Panel</title>
  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  
  <link rel="stylesheet" href="{{ asset('css/admin-style.css') }}">
</head>

<body class="login-body">

  <div class="login-container">
    <div class="login-logo">
      <span>News</span><span>1</span>
    </div>
    
    <h1>Admin Panel Login</h1>
    
    @if ($errors->any())
      <ul class="error-list">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    @endif
    
    <form action="{{ route('login.submit') }}" method="POST">
      @csrf
      
      <div class="form-group">
        <label for="email" class="form-label">Email Address</label>
        <div class="form-control-wrap">
          <i class="fas fa-envelope form-control-icon"></i>
          <input type="email" name="email" id="email" class="form-control" placeholder="admin@news1.com" value="{{ old('email') }}" required autofocus>
        </div>
      </div>
      
      <div class="form-group">
        <label for="password" class="form-label">Password</label>
        <div class="form-control-wrap">
          <i class="fas fa-lock form-control-icon"></i>
          <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required>
        </div>
      </div>
      
      <button type="submit" class="btn-login">
        <i class="fas fa-sign-in-alt"></i> Login
      </button>
    </form>
    
    <a href="{{ route('home') }}" class="btn-back">
      <i class="fas fa-arrow-left"></i> Go to Portal Homepage
    </a>
  </div>

</body>

</html>
