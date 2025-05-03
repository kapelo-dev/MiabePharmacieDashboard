<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>
    Material Dashboard 3 by Creative Tim
  </title>
  <!-- Fonts and icons -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" />
  <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
  <!-- CSS Files -->
  <link id="pagestyle" href="../assets/css/material-dashboard.css?v=3.2.0" rel="stylesheet" />
  <style>
    body {
      background: #f4f5f7;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .auth-container {
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      display: flex;
      width: 800px;
      height: 400px;
      overflow: hidden;
      border: 3px solid rgb(42, 135, 44); /* Contour bleu-violet */
    }
    .logo-section {
      background: linear-gradient(to bottom,rgb(17, 203, 82),rgb(62, 252, 37));
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .form-section {
      flex: 1;
      padding: 40px;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }
    .form-control {
      border-radius: 5px;
      padding: 10px;
      font-size: 16px;
      border: 1px solid #ddd;
      margin-bottom: 15px;
    }
    .auth-form-btn {
      background:rgb(23, 213, 61);
      border: none;
      border-radius: 5px;
      padding: 10px;
      font-size: 18px;
      color: #fff;
      cursor: pointer;
    }
    .auth-form-btn:hover {
      background: green;
    }
    .brand-logo img {
      max-width: 80%;
      max-height: 80%;
    }
    .form-group label {
      color: #333;
      font-weight: bold;
    }
    .error-message {
      color: red;
      margin-bottom: 15px;
    }
  </style>
</head>

<body>
  <div class="container-scroller d-flex justify-content-center align-items-center" style="height: 100vh;">
    <div class="auth-container">
      <!-- Left side for logo -->
      <div class="logo-section">
        <div class="brand-logo">
          <img src="images/logo/logo-noir.png" alt="logo"> <!-- Remplacez par le chemin de votre logo -->
        </div>
      </div>
      <!-- Right side for form -->
      <div class="form-section">
        <h1>Se connecter</h1>
        <p class="account-subtitle">Accéder à votre application</p>
        @if (session('error'))
          <div class="error-message">
            {{ session('error') }}
          </div>
        @endif
        <form method="POST" action="{{ route('login') }}">
          @csrf
          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="Email" required autofocus>
            @error('email')
            <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group">
            <label for="mot_de_passe">Mot de passe</label>
            <input type="password" class="form-control @error('mot_de_passe') is-invalid @enderror" id="mot_de_passe" name="mot_de_passe" placeholder="Mot de passe" required>
            @error('mot_de_passe')
            <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <button type="submit" class="auth-form-btn">Se connecter</button>
        </form>
      </div>
    </div>
  </div>
  <!-- Core JS Files -->
  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap.min.js"></script>
  <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/chartjs.min.js"></script>
  <script src="../assets/js/material-dashboard.min.js?v=3.2.0"></script>
  <script src="../assets/js/is_active.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
</body>

</html>
