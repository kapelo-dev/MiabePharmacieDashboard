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
                        <h6 class="mb-0">Liste des pharmacies : {{count($pharmacies)}}</h6>
                        <div class="d-flex gap-2">
<!--                             <button id="updateAllStocksBtn" type="button" class="btn btn-warning btn-sm d-flex align-items-center">
                                <i class="fas fa-sync-alt me-2"></i>
                                Mettre à jour tous les stocks
                            </button> -->
                            <button type="button" class="btn btn-success btn-sm d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#newPharmacyModal">
                                <i class="fas fa-plus me-2"></i>
                                Nouvelle pharmacie
                            </button>
                        </div>
                    </div>
                    <div class="mt-3 ps-3">
                        <input type="text" id="searchPharmacy" class="form-control" placeholder="Rechercher une pharmacie..." style="width: 200px; height: 30px; border: 2px solid #000; padding: 5px;">
                    </div>


                    <div class="table-responsive p-0">
                        <table class="table align-items-center justify-content-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nom</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Emplacement</th>
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
                                        <td>
                                            <div class="d-flex flex-column justify-content-center">
                                                <span class="text-sm">{{ $pharmacy['emplacement'] ?? 'N/A' }}</span>
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
                                            <button class="btn btn-link text-secondary mb-0" data-bs-toggle="modal" data-bs-target="#editPharmacyModal{{ $pharmacy['id'] }}">
                                                <i class="fa fa-edit text-xs"></i>
                                            </button>
                                            <form action="{{ route('pharmacies.destroy', $pharmacy['id']) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-link text-danger mb-0" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette pharmacie ?')">
                                                    <i class="fa fa-trash text-xs"> </i>
                                                </button>
                                            </form>
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
    <footer class="footer py-4">
        <div class="container-fluid">
          <!--   <div class="row align-items-center justify-content-lg-between">
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

<!-- Modal pour ajouter une nouvelle pharmacie -->
<div class="modal fade" id="newPharmacyModal" tabindex="-1" aria-labelledby="newPharmacyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content bg-white text-dark">
            <div class="modal-header border-bottom border-secondary">
                <h5 class="modal-title" id="newPharmacyModalLabel">Ajouter une nouvelle pharmacie</h5>
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
                <form id="newPharmacyForm" class="row g-3" method="POST" action="{{ route('pharmacies.store') }}">
                    @csrf
                    <div class="col-md-6">
                        <label for="pharmacyId" class="form-label">ID du document</label>
                        <input type="text" class="form-control border-visible" id="pharmacyId" name="pharmacyId" required>
                    </div>
                    <div class="col-md-6">
                        <label for="pharmacyName" class="form-label">Nom</label>
                        <input type="text" class="form-control border-visible" id="pharmacyName" name="pharmacyName" required>
                    </div>
                    <div class="col-md-6">
                        <label for="pharmacyLocation" class="form-label">Emplacement</label>
                        <input type="text" class="form-control border-visible" id="pharmacyLocation" name="pharmacyLocation" required>
                    </div>
                    <div class="col-md-6">
                        <label for="pharmacyClosing" class="form-label">Fermeture</label>
                        <input type="text" class="form-control border-visible" id="pharmacyClosing" name="pharmacyClosing" required>
                    </div>
                    <div class="col-md-6">
                        <label for="pharmacyOpening" class="form-label">Ouverture</label>
                        <input type="text" class="form-control border-visible" id="pharmacyOpening" name="pharmacyOpening" required>
                    </div>
                    <div class="col-md-6">
                        <label for="pharmacyPhone1" class="form-label">Téléphone 1</label>
                        <input type="text" class="form-control border-visible" id="pharmacyPhone1" name="pharmacyPhone1" required>
                    </div>
                    <div class="col-md-6">
                        <label for="pharmacyPhone2" class="form-label">Téléphone 2</label>
                        <input type="text" class="form-control border-visible" id="pharmacyPhone2" name="pharmacyPhone2">
                    </div>
                    <div class="col-md-6">
                        <label for="pharmacyLatitude" class="form-label">Latitude</label>
                        <input type="text" class="form-control border-visible" id="pharmacyLatitude" name="pharmacyLatitude"  required>
                    </div>
                    <div class="col-md-6">
                        <label for="pharmacyLongitude" class="form-label">Longitude</label>
                        <input type="text" class="form-control border-visible" id="pharmacyLongitude" name="pharmacyLongitude"  required>
                    </div>
                    <div class="col-12">
                        <div id="map" style="height: 600px;"></div>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-success">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modals pour modifier une pharmacie -->
@foreach ($pharmacies as $pharmacy)
    <div class="modal fade" id="editPharmacyModal{{ $pharmacy['id'] }}" tabindex="-1" aria-labelledby="editPharmacyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content bg-white text-dark">
                <div class="modal-header border-bottom border-secondary">
                    <h5 class="modal-title" id="editPharmacyModalLabel">Modifier la pharmacie</h5>
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
                    <form id="editPharmacyForm" class="row g-3" method="POST" action="{{ route('pharmacies.update', $pharmacy['id']) }}">
                        @csrf
                        @method('PUT')
                        <div class="col-md-6">
                            <label for="editPharmacyId" class="form-label">ID du document</label>
                            <input type="text" class="form-control border-visible" id="editPharmacyId" name="pharmacyId" value="{{ $pharmacy['id'] }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label for="editPharmacyName" class="form-label">Nom</label>
                            <input type="text" class="form-control border-visible" id="editPharmacyName" name="pharmacyName" value="{{ $pharmacy['nom'] ?? '' }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="editPharmacyLocation" class="form-label">Emplacement</label>
                            <input type="text" class="form-control border-visible" id="editPharmacyLocation" name="pharmacyLocation" value="{{ $pharmacy['emplacement'] }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="editPharmacyClosing" class="form-label">Fermeture</label>
                            <input type="text" class="form-control border-visible" id="editPharmacyClosing" name="pharmacyClosing" value="{{ $pharmacy['fermeture'] }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="editPharmacyOpening" class="form-label">Ouverture</label>
                            <input type="text" class="form-control border-visible" id="editPharmacyOpening" name="pharmacyOpening" value="{{ $pharmacy['ouverture'] }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="editPharmacyPhone1" class="form-label">Téléphone 1</label>
                            <input type="text" class="form-control border-visible" id="editPharmacyPhone1" name="pharmacyPhone1" value="{{ $pharmacy['telephone1'] }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="editPharmacyPhone2" class="form-label">Téléphone 2</label>
                            <input type="text" class="form-control border-visible" id="editPharmacyPhone2" name="pharmacyPhone2" value="{{ $pharmacy['telephone2'] ?? '' }}">
                        </div>
                        <div class="col-md-6">
                            <label for="editPharmacyLatitude" class="form-label">Latitude</label>
                            <input type="text" class="form-control border-visible" id="editPharmacyLatitude" name="pharmacyLatitude" value="{{ $pharmacy['latitude'] ?? '' }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="editPharmacyLongitude" class="form-label">Longitude</label>
                            <input type="text" class="form-control border-visible" id="editPharmacyLongitude" name="pharmacyLongitude" value="{{ $pharmacy['longitude'] ?? '' }}" required>
                        </div>
                        <div class="col-12">
                            <div id="editMap" style="height: 600px;"></div>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-success">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('searchPharmacy');
        const tableRows = document.querySelectorAll('#pharmacyTable tr');

        // Gestionnaire de recherche
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

        // Gestionnaire de mise à jour des stocks
        const updateStocksBtn = document.getElementById('updateAllStocksBtn');
        if (updateStocksBtn) {
            updateStocksBtn.addEventListener('click', function() {
                if(confirm('Êtes-vous sûr de vouloir mettre à jour tous les stocks de toutes les pharmacies ?')) {
                    console.log('Début de la mise à jour des stocks...');
                    fetch("{{ route('pharmacies.updateAllStocks') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                    })
                    .then(response => {
                        console.log('Réponse reçue:', response);
                        if (!response.ok) {
                            throw new Error('Erreur réseau: ' + response.status);
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Données reçues:', data);
                        alert(data.message);
                        if (data.success) {
                            // Optionnel : rafraîchir la page après la mise à jour
                            window.location.reload();
                        }
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        alert('Erreur lors de la mise à jour des stocks: ' + error.message);
                    });
                }
            });
        }

        // Initialiser la carte OpenStreetMap
        const map = L.map('map').setView([6.1767, 1.2064], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        const marker = L.marker([6.1767, 1.2064], { draggable: true }).addTo(map);

        marker.on('dragend', function (event) {
            const position = marker.getLatLng();
            document.getElementById('pharmacyLatitude').value = position.lat;
            document.getElementById('pharmacyLongitude').value = position.lng;
        });

        map.on('click', function (event) {
            const position = event.latlng;
            marker.setLatLng(position);
            document.getElementById('pharmacyLatitude').value = position.lat;
            document.getElementById('pharmacyLongitude').value = position.lng;
        });

        // Ajouter une barre de recherche pour la carte
        const searchControl = new L.Control.Search({
            url: 'https://nominatim.openstreetmap.org/search?format=json&q={s}',
            jsonpParam: 'json_callback',
            propertyName: 'display_name',
            marker: marker,
            autoCollapse: true,
            autoType: false,
            minLength: 2
        });

        map.addControl(searchControl);

        // Initialiser la carte pour les modals de modification
        document.querySelectorAll('[id^="editPharmacyModal"]').forEach(modal => {
            const editMap = L.map('editMap').setView([6.1767, 1.2064], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(editMap);

            const editMarker = L.marker([6.1767, 1.2064], { draggable: true }).addTo(editMap);

            editMarker.on('dragend', function (event) {
                const position = editMarker.getLatLng();
                modal.querySelector('#editPharmacyLatitude').value = position.lat;
                modal.querySelector('#editPharmacyLongitude').value = position.lng;
            });

            editMap.on('click', function (event) {
                const position = event.latlng;
                editMarker.setLatLng(position);
                modal.querySelector('#editPharmacyLatitude').value = position.lat;
                modal.querySelector('#editPharmacyLongitude').value = position.lng;
            });

            const editSearchControl = new L.Control.Search({
                url: 'https://nominatim.openstreetmap.org/search?format=json&q={s}',
                jsonpParam: 'json_callback',
                propertyName: 'display_name',
                marker: editMarker,
                autoCollapse: true,
                autoType: false,
                minLength: 2
            });

            editMap.addControl(editSearchControl);
        });
    });
</script>
@endsection

@section('styles')
<style>
    .form-control.border-visible {
        border: 2px solid #ced4da !important;
        border-radius: 0.375rem !important;
    }
    .form-control.border-visible:focus {
        border-color: #80bdff !important;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25) !important;
    }
    .modal-content.bg-white {
        background-color: #ffffff !important;
        color: #000000 !important;
    }
    .modal-header.border-bottom {
        border-bottom: 1px solid #6c757d !important;
    }
    .btn-close-white {
        filter: invert(1) grayscale(100%);
    }
    .modal-body {
        max-height: 60vh;
        overflow-y: auto;
    }
    #map, #editMap {
        width: 100%;
        height: 600px;
    }
</style>
@endsection

@section('head')
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet-search/dist/leaflet-search.min.css" />
<script src="https://unpkg.com/leaflet-search/dist/leaflet-search.min.js"></script>
@endsection
