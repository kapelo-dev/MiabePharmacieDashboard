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
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-success shadow-dark border-radius-lg pt-4 pb-3">
                        <h6 class="text-white text-capitalize ps-3">Informations du personnels</h6>
                    </div>
                </div>
                <div class="card-body px-0 pb-2">
                <div class="d-flex justify-content-between align-items-center px-3 mb-3">
                        <h6 class="mb-0"> </h6>
                        <button type="button" class="btn bg-gradient-dark w-10 mb-0 toast-btn" data-bs-toggle="modal" data-bs-target="#newPharmacyModal">
                            Nouveau
                        </button>
                    </div>
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Photo</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Rôle</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Adresse</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date de création</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Téléphone</th>

                                    <th class="text-secondary opacity-7"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($developpeurs as $developpeur)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div>
                                                <img src="{{ $developpeur['photo_url'] ?? 'images/faces/avatar.png' }}"
                                                     class="avatar avatar-sm me-3 border-radius-10" alt="developpeur">
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $developpeur['nom_prenom'] ?? 'N/A' }}</h6>
                                                <p class="text-xs text-secondary mb-0">{{ $developpeur['email'] ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm">{{ $developpeur['role'] ?? 'N/A' }}</h6>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm">{{ $developpeur['adresse'] ?? 'N/A' }}</h6>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ isset($developpeur['createdAt']) ? Carbon\Carbon::parse($developpeur['createdAt'])->format('d/m/Y H:i') : 'N/A' }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $developpeur['telephone'] ?? 'N/A' }}</p>
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
</div>

<!-- Modal pour ajouter un membre du personnel -->
<div class="modal fade" id="newPharmacyModal" tabindex="-1" aria-labelledby="newPharmacyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content bg-white text-dark">
            <div class="modal-header border-bottom border-secondary">
                <h5 class="modal-title" id="newPharmacyModalLabel">Ajouter un membre du personnel</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="max-height: 60vh; overflow-y: auto;">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form id="newPharmacyForm" class="row g-3" method="POST" action="{{ route('personnels.store') }}">
                    @csrf
                    <div class="col-md-6">
                        <label for="nom_prenom" class="form-label">Nom et prénom</label>
                        <input type="text" class="form-control border-visible" id="nom_prenom" name="nom_prenom" required>
                    </div>
                    <div class="col-md-6">
                        <label for="adresse" class="form-label">Adresse</label>
                        <input type="text" class="form-control border-visible" id="adresse" name="adresse" required>
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control border-visible" id="email" name="email" required>
                    </div>
                    <div class="col-md-6">
                        <label for="mot_de_passe" class="form-label">Mot de passe</label>
                        <input type="password" class="form-control border-visible" id="mot_de_passe" name="mot_de_passe" required>
                    </div>
                   
                    
                    <div class="col-12">
                        <button type="submit" class="btn btn-success">Enregistrer</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
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
</script>
@endsection
