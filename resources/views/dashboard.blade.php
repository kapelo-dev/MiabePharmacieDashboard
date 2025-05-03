@extends('layouts.app')

@section('content')
<div class="container-fluid py-2">
      <div class="row">
        <div class="ms-3">
          <h3 class="mb-0 h4 font-weight-bolder">Dashboard</h3>
          <p class="mb-4">
            Check the sales, value and bounce rate by country.
          </p>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-header p-2 ps-3">
              <div class="d-flex justify-content-between">
                <div>
                  <p class="text-sm mb-0 text-capitalize"> Utilisateurs</p>
                  <h4 class="mb-0">{{ count($utilisateurs) }}</h4>
                </div>
                <div class="icon icon-md icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-lg">
                  <i class="material-symbols-rounded opacity-10">person</i>
                </div>
              </div>
            </div>
            <hr class="dark horizontal my-0">
            <div class="card-footer p-2 ps-3">
            <!--   <p class="mb-0 text-sm"><span class="text-success font-weight-bolder">+3% </span>Depuis le dernier mois</p> -->
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-header p-2 ps-3">
              <div class="d-flex justify-content-between">
                <div>
                  <p class="text-sm mb-0 text-capitalize">Pharmacies</p>
                  <h4 class="mb-0">{{ count($pharmacies) }}</h4>
                </div>
                <div class="icon icon-md icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-lg">
                  <i class="material-symbols-rounded opacity-10">leaderboard</i>
                </div>
              </div>
            </div>
            <hr class="dark horizontal my-0">
            <div class="card-footer p-2 ps-3">
             <!--  <p class="mb-0 text-sm"><span class="text-danger font-weight-bolder">-2% </span>Depuis le dernier mois</p> -->
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6">
          <div class="card">
            <div class="card-header p-2 ps-3">
              <div class="d-flex justify-content-between">
                <div>
                  <p class="text-sm mb-0 text-capitalize"> Nombre de gardes</p>
                  <h4 class="mb-0"> {{count($gardes) }}</h4>
                </div>
                <div class="icon icon-md icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-lg">
                  <i class="material-symbols-rounded opacity-10">weekend</i>
                </div>
              </div>
            </div>
            <hr class="dark horizontal my-0">
            <div class="card-footer p-2 ps-3">
             <!--  <p class="mb-0 text-sm"><span class="text-success font-weight-bolder">+5% </span> Ce mois </p> -->
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-header p-2 ps-3">
              <div class="d-flex justify-content-between">
                <div>
                  <p class="text-sm mb-0 text-capitalize">Nombre de commandes total</p>
                  <h4 class="mb-0">{{$nombreCommandesTotal }}</h4>
                </div>
                <div class="icon icon-md icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-lg">
                  <i class="material-symbols-rounded opacity-10">weekend</i>
                </div>
              </div>
            </div>
            <hr class="dark horizontal my-0">
            <div class="card-footer p-2 ps-3">
<!--               <p class="mb-0 text-sm"><span class="text-success font-weight-bolder">+55% </span>than last week</p>
 -->            </div>
          </div>
        </div>
      </div>
      <div class="row">
      <div class="col-lg-6 col-md-6 mt-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h6 class="mb-0">Inscriptions de la semaine</h6>
                    <p class="text-sm">Performance des inscriptions</p>
                    <div class="pe-2">
                        <div class="chart">
                            <canvas id="inscriptions-chart" class="chart-canvas" height="170"></canvas>
                        </div>
                    </div>
                    <hr class="dark horizontal">
                    <div class="d-flex">
                        <i class="material-symbols-rounded text-sm my-auto me-1">schedule</i>
                        <p class="mb-0 text-sm">Données de la semaine en cours</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-md-6 mt-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h6 class="mb-0">Évolution des Commandes</h6>
                    <p class="text-sm">Nombre total de commandes effectuées par jour.</p>
                    <div class="pe-2">
                        <div class="chart">
                            <canvas id="commandes-chart" class="chart-canvas" height="170"></canvas>
                        </div>
                    </div>
                    <hr class="dark horizontal">
                    <div class="d-flex">
                        <i class="material-symbols-rounded text-sm my-auto me-1">schedule</i>
                        <p class="mb-0 text-sm">Mis à jour à l'instant </p>
                    </div>
                </div>
            </div>
        </div>
       <!--  <div class="col-lg-4 mt-4 mb-3">
          <div class="card">
            <div class="card-body">
              <h6 class="mb-0 ">Completed Tasks</h6>
              <p class="text-sm ">Last Campaign Performance</p>
              <div class="pe-2">
                <div class="chart">
                  <canvas id="chart-line-tasks" class="chart-canvas" height="170"></canvas>
                </div>
              </div>
              <hr class="dark horizontal">
              <div class="d-flex ">
                <i class="material-symbols-rounded text-sm my-auto me-1">schedule</i>
                <p class="mb-0 text-sm">just updated</p>
              </div>
            </div>
          </div>
        </div> -->
      </div>
      <div class="row mb-4">
        <div class="col-lg-8 col-md-6 mb-md-0 mb-4">
         <div class="card">
                <div class="card-header pb-0">
                    <div class="row">
                        <div class="col-lg-6 col-7">
                            <h6>Pharmacies</h6>
                            <p class="text-sm mb-0">
                                <i class="fa fa-check text-info" aria-hidden="true"></i>
                                <span class="font-weight-bold ms-1">{{ count($pharmacies) }} Enregistrées</span>
                            </p>
                        </div>
                        <div class="col-lg-6 col-5 my-auto text-end">
                            <div class="dropdown float-lg-end pe-4">
                                <a class="cursor-pointer" id="dropdownTable" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa fa-ellipsis-v text-secondary"></i>
                                </a>
                                <ul class="dropdown-menu px-2 py-3 ms-sm-n4 ms-n5" aria-labelledby="dropdownTable">
                                    <li><a class="dropdown-item border-radius-md" href="javascript:;">Action</a></li>
                                    <li><a class="dropdown-item border-radius-md" href="javascript:;">Another action</a></li>
                                    <li><a class="dropdown-item border-radius-md" href="javascript:;">Something else here</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                   <!--  <div class="mt-3">
                        <input type="text" id="searchPharmacy" class="form-control" placeholder="Rechercher une pharmacie...">
                    </div> -->
                </div>
                <div class="card-body px-0 pb-2" style="max-height: 400px; overflow-y: auto;">
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nom</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Emplacement</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Fermeture</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Ouverture</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Téléphone</th>
                                </tr>
                            </thead>
                            <tbody id="pharmacyTable">
                                @foreach ($pharmacies as $pharmacy)
                                    <tr>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $pharmacy['nom'] ?? 'N/A' }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column justify-content-center">
                                                <span class="text-sm">{{ $pharmacy['emplacement'] ?? 'N/A' }}</span>
                                            </div>
                                        </td>
                                        <td class="align-middle text-center text-sm">
                                            <span class="text-xs font-weight-bold">{{ $pharmacy['fermeture'] ?? 'N/A' }}</span>
                                        </td>
                                        <td class="align-middle text-center text-sm">
                                            <span class="text-xs font-weight-bold">{{ $pharmacy['ouverture'] ?? 'N/A' }}</span>
                                        </td>
                                        <td class="align-middle text-center text-sm">
                                            <span class="text-xs font-weight-bold">
                                                {{ $pharmacy['telephone1'] ?? 'N/A' }}
                                                @if(isset($pharmacy['telephone2']) && !empty($pharmacy['telephone2']))
                                                    / {{ $pharmacy['telephone2'] }}
                                                @endif
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div> 
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
    <div class="card h-100">
        <div class="card-header pb-0">
            <h6>Utilisateurs inscrits récemment</h6>
        </div>
        <div class="card-body p-3">
            <div class="timeline timeline-one-side">
                @foreach (array_slice($utilisateurs, 0, 6) as $utilisateur)
                    <div class="timeline-block mb-3">
                        <div class="d-flex px-2 py-1">
                            <div>
                                <img src="{{ $utilisateur['photo_url'] ?? 'images/faces/avatar.png' }}"
                                     class="avatar avatar-sm me-3 border-radius-10" alt="user">
                            </div>
                            <div class="d-flex flex-column justify-content-center">
                                <h6 class="mb-0 text-sm">{{ $utilisateur['nom_prenom'] ?? 'N/A' }}</h6>
                                <p class="text-xs text-secondary mb-0">
                                    {{ !empty($utilisateur['email']) ? $utilisateur['email'] : ($utilisateur['telephone'] ?? 'N/A') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

      </div>
    <!--   <footer class="footer py-4  ">
        <div class="container-fluid">
          <div class="row align-items-center justify-content-lg-between">
            <div class="col-lg-6 mb-lg-0 mb-4">
              <div class="copyright text-center text-sm text-muted text-lg-start">
                © <script>
                  document.write(new Date().getFullYear())
                </script>,
                made with <i class="fa fa-heart"></i> by
                <a href="https://www.creative-tim.com" class="font-weight-bold" target="_blank">Creative Tim</a>
                for a better web.
              </div>
            </div>
            <div class="col-lg-6">
              <ul class="nav nav-footer justify-content-center justify-content-lg-end">
                <li class="nav-item">
                  <a href="https://www.creative-tim.com" class="nav-link text-muted" target="_blank">Creative Tim</a>
                </li>
                <li class="nav-item">
                  <a href="https://www.creative-tim.com/presentation" class="nav-link text-muted" target="_blank">About Us</a>
                </li>
                <li class="nav-item">
                  <a href="https://www.creative-tim.com/blog" class="nav-link text-muted" target="_blank">Blog</a>
                </li>
                <li class="nav-item">
                  <a href="https://www.creative-tim.com/license" class="nav-link pe-0 text-muted" target="_blank">License</a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </footer> -->
    </div>
@endsection
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Graphique des inscriptions
        const inscriptionsData = {
            labels: ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'],
            datasets: [{
                label: 'Nombre d\'inscriptions',
                data: [
                    {{ $inscriptionsParJour['Lundi'] ?? 0 }},
                    {{ $inscriptionsParJour['Mardi'] ?? 0 }},
                    {{ $inscriptionsParJour['Mercredi'] ?? 0 }},
                    {{ $inscriptionsParJour['Jeudi'] ?? 0 }},
                    {{ $inscriptionsParJour['Vendredi'] ?? 0 }},
                    {{ $inscriptionsParJour['Samedi'] ?? 0 }},
                    {{ $inscriptionsParJour['Dimanche'] ?? 0 }}
                ],
                backgroundColor: 'rgba(54, 235, 123, 0.54)',
                borderColor: 'rgb(22, 196, 60)',
                borderWidth: 1
            }]
        };

        const inscriptionsConfig = {
            type: 'bar',
            data: inscriptionsData,
            options: {
                scales: {
                    x: { beginAtZero: true },
                    y: { beginAtZero: true }
                }
            }
        };

        const inscriptionsCtx = document.getElementById('inscriptions-chart').getContext('2d');
        new Chart(inscriptionsCtx, inscriptionsConfig);

        // Graphique des commandes
        const commandesData = {
            labels: Object.keys({{ Js::from($commandesParJour) }}),
            datasets: [{
                label: 'Nombre de commandes',
                data: Object.values({{ Js::from($commandesParJour) }}),
                borderColor: 'rgba(54, 162, 235, 1)',
                fill: false,
                tension: 0.1
            }]
        };

        const commandesConfig = {
            type: 'line',
            data: commandesData,
            options: {
                scales: {
                    x: { beginAtZero: true },
                    y: { beginAtZero: true }
                }
            }
        };

        const commandesCtx = document.getElementById('commandes-chart').getContext('2d');
        new Chart(commandesCtx, commandesConfig);
    });
</script>
@endsection