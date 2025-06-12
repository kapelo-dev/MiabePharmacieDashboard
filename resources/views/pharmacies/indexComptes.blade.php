@extends('layouts.app')

@section('content')
<div class="container-fluid py-2">
    <div class="row">
        <div class="ms-3">
            <h3 class="mb-0 h4 font-weight-bolder">Tableau des pharmacies</h3>
            <p class="mb-4">
                Recherchez et filtrez les pharmacies enregistrées.
            </p>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-lg-12 col-md-12 mb-md-0 mb-4">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-success shadow-dark border-radius-lg pt-4 pb-3">
                        <h6 class="text-white text-capitalize ps-3">Pharmacies</h6>
                        
                    </div>
                </div>
                <div class="card-body px-0 pb-2">
                    <div class="d-flex justify-content-between align-items-center px-3 mb-3">
                        <h6 class="mb-0">Liste des pharmacies : {{ count($pharmacies) }}</h6>
                        <form action="{{ route('pharmacies.generateAllAccounts') }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn bg-gradient-info">
                                <i class="fa fa-key"></i> Générer tous les comptes
                            </button>
                        </form>
                    </div>
                    <div class="mt-3 ps-3">
                            <input type="text" id="searchPharmacy" class="form-control" placeholder="Rechercher une pharmacie...">
                    </div>
                    <div class="table-responsive p-0">
                        <table class="table align-items-center justify-content-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nom</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Téléphone</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="pharmacyTable">
                                @foreach ($pharmacies as $pharmacy)
                                    <tr>
                                        <td>
                                            <div class="d-flex px-2">
                                                <div class="my-auto">
                                                    <h6 class="mb-0 text-sm">{{ $pharmacy['nom'] ?? 'N/A' }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="align-middle text-center text-sm">
                                            <span class="text-xs font-weight-bold">
                                                {{ $pharmacy['telephone1'] ?? 'N/A' }}
                                                @if(isset($pharmacy['telephone2']) && !empty($pharmacy['telephone2']))
                                                    / {{ $pharmacy['telephone2'] }}
                                                @endif
                                            </span>
                                        </td>
                                        <td class="align-middle text-center text-sm">
                                            <button type="button" class="btn bg-gradient-success mb-0 toast-btn" data-bs-toggle="modal" data-bs-target="#addPharmacienModal{{ $pharmacy['id'] }}">
                                              <i class="fa fa-plus"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal pour ajouter un pharmacien -->
    @foreach ($pharmacies as $pharmacy)
        <div class="modal fade" id="addPharmacienModal{{ $pharmacy['id'] }}" tabindex="-1" aria-labelledby="addPharmacienModalLabel{{ $pharmacy['id'] }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content bg-white">
                    <div class="modal-header border-bottom">
                        <h5 class="modal-title" id="addPharmacienModalLabel{{ $pharmacy['id'] }}">Ajouter un Pharmacien</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('pharmacies.addPharmacien', $pharmacy['id']) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="pharmacienName{{ $pharmacy['id'] }}" class="form-label">Nom du Pharmacien</label>
                                <input type="text" class="form-control border-visible" id="pharmacienName{{ $pharmacy['id'] }}" name="pharmacienName" required>
                            </div>
                            <div class="mb-3">
                                <label for="pharmacienPrenom{{ $pharmacy['id'] }}" class="form-label">Prénom du Pharmacien</label>
                                <input type="text" class="form-control border-visible" id="pharmacienPrenom{{ $pharmacy['id'] }}" name="pharmacienPrenom" required>
                            </div>
                            <div class="mb-3">
                                <label for="pharmacienPhone{{ $pharmacy['id'] }}" class="form-label">Téléphone</label>
                                <input type="text" class="form-control border-visible" id="pharmacienPhone{{ $pharmacy['id'] }}" name="pharmacienPhone" required>
                            </div>
                            <div class="mb-3">
                                <label for="pharmacienIdentifiant{{ $pharmacy['id'] }}" class="form-label">Identifiant</label>
                                <input type="text" class="form-control border-visible" id="pharmacienIdentifiant{{ $pharmacy['id'] }}" name="pharmacienIdentifiant" required>
                            </div>
                            <div class="mb-3">
                                <label for="pharmacienPassword{{ $pharmacy['id'] }}" class="form-label">Mot de passe</label>
                                <input type="password" class="form-control border-visible" id="pharmacienPassword{{ $pharmacy['id'] }}" name="pharmacienPassword" required>
                            </div>
                            <div class="mb-3">
                                <label for="pharmacienConfirmPassword{{ $pharmacy['id'] }}" class="form-label">Confirmer le mot de passe</label>
                                <input type="password" class="form-control border-visible" id="pharmacienConfirmPassword{{ $pharmacy['id'] }}" name="pharmacienConfirmPassword" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Rôle</label>
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" id="roleGerant{{ $pharmacy['id'] }}" name="pharmacienRole" value="gérant" required>
                                    <label class="form-check-label" for="roleGerant{{ $pharmacy['id'] }}">Gérant</label>
                                </div>
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" id="roleCaissier{{ $pharmacy['id'] }}" name="pharmacienRole" value="caissier" required>
                                    <label class="form-check-label" for="roleCaissier{{ $pharmacy['id'] }}">Caissier</label>
                                </div>
                            </div>

                            
                            <button type="submit" class="btn bg-gradient-success w-100"> Ajouter </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <footer class="footer py-4">
        <div class="container-fluid">
            <!-- <div class="row align-items-center justify-content-lg-between">
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
            </div> -->
        </div>
    </footer>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('searchPharmacy');
        const tableRows = document.querySelectorAll('#pharmacyTable tr');

        searchInput.addEventListener('keyup', function () {
            const filter = searchInput.value.toLowerCase();
            tableRows.forEach(row => {
                const cells = row.querySelectorAll('td');
                let match = false;
                cells.forEach(cell => {
                    if (cell.textContent.toLowerCase().includes(filter)) {
                        match = true;
                    }
                });
                row.style.display = match ? '' : 'none';
            });
        });
    });
</script>
@endsection



@section('head')
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet-search/dist/leaflet-search.min.css" />
<script src="https://unpkg.com/leaflet-search/dist/leaflet-search.min.js"></script>
@endsection

