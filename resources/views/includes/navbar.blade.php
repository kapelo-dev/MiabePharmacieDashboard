<nav class="navbar navbar-expand-lg navbar-dark bg-white fixed-top">
  <div class="container-fluid">
    <!-- Logo ou Nom de l'Application -->
    <a class="navbar-brand">
      <img src="images/logodef.png" alt="Logo" style="max-height: 40px;">
    </a>

    <!-- Bouton de menu pour les petits écrans -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNavDropdown">
      <ul class="navbar-nav ms-auto">
        <!-- Affichage de l'email du développeur en noir -->
        <li class="nav-item">
          <p class="nav-link text-black"><strong>{{ session('developpeur_nom_prenom') }}</strong></p>
        </li>

        <!-- Menu déroulant avec logo utilisateur -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="images/faces/avatar.png" alt="User Logo" class="rounded-circle" style="width: 30px; height: 30px;">
          </a>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuLink">
            <!-- Bouton de déconnexion -->
            <li>
              <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="ti-power-off text-primary"></i> Déconnexion
              </a>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- Formulaire de déconnexion -->
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
