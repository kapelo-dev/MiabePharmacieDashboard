@extends('layouts.app')

@section('content')
@section('styles')
<style>
    /* Masquer le checkbox par défaut */
    .form-check-input {
        display: none;
    }

    /* Styliser le label pour qu'il ressemble à un checkbox */
    .form-check-label {
        position: relative;
        padding-left: 30px;
        cursor: pointer;
    }

    /* Créer un pseudo-élément pour le checkbox */
    .form-check-label::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        width: 20px;
        height: 20px;
        border: 2px solid #ccc;
        border-radius: 4px;
        background-color: #fff;
    }

    /* Changer la couleur du checkbox lorsqu'il est coché */
    .form-check-input:checked + .form-check-label::before {
        background-color: green;
        border-color: green;
    }

    /* Ajouter une coche au centre du checkbox */
    .form-check-input:checked + .form-check-label::after {
        content: '✔';
        position: absolute;
        left: 4px;
        top: 0;
        color: white;
        font-size: 16px;
    }

    .scrollable-modal {
        max-height: 60vh;
        overflow-y: auto;
    }
</style>
@endsection

<div class="container-fluid py-2">
    <!-- En-tête -->
    <div class="row">
        <div class="ms-3">
            <h3 class="mb-0 h4 font-weight-bolder">Tableau des gardes</h3>
            <p class="mb-4">Recherchez et filtrez les gardes enregistrées.</p>
        </div>
    </div>

    <!-- Tableau -->
    <div class="row mb-4">
        <div class="col-lg-12 col-md-12 mb-md-0 mb-4">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-success shadow-dark border-radius-lg pt-4 pb-3">
                        <h6 class="text-white text-capitalize ps-3">Gardes</h6>
                        
                    </div>
                </div>
                
                <div class="mt-3 ps-3">
                    <input type="text" id="searchGarde" class="form-control" placeholder="Rechercher..." style="width: 200px; height: 30px; border: 2px solid #000; padding: 5px;">
                </div>

                <div class="card-body px-0 pb-2">
                    <div class="d-flex justify-content-between align-items-center px-3 mb-3">
                        <h6 class="mb-0">Liste des gardes : {{ count($gardes) }}</h6>
                        <button class="btn bg-gradient-dark w-10 mb-0 toast-btn" data-bs-toggle="modal" data-bs-target="#newGardeModal">
                            Nouveau
                        </button>
                    </div>

                    <div class="table-responsive p-0">
                        <table class="table align-items-center justify-content-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 py-1">ID</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 py-1">Date de début</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 py-1">Date de fin</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 py-1">Actif</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 py-1">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="gardeTable">
                                @forelse($gardes as $garde)
                                    <tr>
                                        <td class="py-1">
                                            <div class="d-flex px-2">
                                                <div class="my-auto">
                                                    <h9 class="mb-0 text-sm">{{ $garde['id'] ?? 'N/A' }}</h9>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="align-middle text-center text-sm py-1">
                                            <span class="text-xs font-weight-bold">{{ isset($garde['dateDebut']) ? \Carbon\Carbon::parse($garde['dateDebut'])->format('d/m/Y H:i') : 'N/A' }}</span>
                                        </td>
                                        <td class="align-middle text-center text-sm py-1">
                                            <span class="text-xs font-weight-bold">{{ isset($garde['dateFin']) ? \Carbon\Carbon::parse($garde['dateFin'])->format('d/m/Y H:i') : 'N/A' }}</span>
                                        </td>
                                        <td class="align-middle text-center text-sm py-1">
                                            <span class="text-xs font-weight-bold">
                                                {{ isset($garde['estActive']) && $garde['estActive'] ? '✅' : '❌' }}
                                            </span>
                                        </td>
                                        <td class="align-middle text-center text-sm py-1">
                                            <button class="btn btn-link text-secondary mb-0" data-bs-toggle="modal" data-bs-target="#detailsModal{{ $garde['id'] }}">
                                                <i class="fa fa-eye text-xs"></i>
                                            </button>
                                            <button class="btn btn-link text-secondary mb-0" data-bs-toggle="modal" data-bs-target="#editModal{{ $garde['id'] }}">
                                                <i class="fa fa-edit text-xs"></i>
                                            </button>
                                            <form action="{{ route('gardes.destroy', $garde['id']) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-link text-danger mb-0" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette garde ?')">
                                                    <i class="fa fa-trash text-xs"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="align-middle text-center text-sm py-1">Aucune garde trouvée</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Nouvelle Garde -->
    <div class="modal fade" id="newGardeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nouvelle Garde</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('gardes.store') }}">
                    @csrf
                    <div class="modal-body scrollable-modal">
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="mb-3">
                            <label>ID Garde</label>
                            <input type="text" name="gardeId" class="form-control" value="{{ old('gardeId') }}" required>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Date de début</label>
                                <input type="datetime-local" name="gardeDateDebut" class="form-control" value="{{ old('gardeDateDebut') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label>Date de fin</label>
                                <input type="datetime-local" name="gardeDateFin" class="form-control" value="{{ old('gardeDateFin') }}" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label>Statut</label>
                            <select name="gardeEstActive" class="form-select" required>
                                <option value="1" {{ old('gardeEstActive') ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ !old('gardeEstActive') ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Pharmacies participantes</label>
                            <input type="text" id="searchPharmacy" class="form-control mb-3" placeholder="Rechercher une pharmacie...">
                            <div class="row" id="pharmacyList">
                                @foreach($pharmacies as $id => $nom)
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="gardePharmacies[]" value="{{ $id }}" id="pharm{{ $id }}" {{ in_array($id, old('gardePharmacies', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="pharm{{ $id }}">{{ $nom }}</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-success">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modals Détails -->
    @foreach($gardes as $garde)
        <div class="modal fade" id="detailsModal{{ $garde['id'] }}">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Détails Garde {{ $garde['id'] }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Période:</strong>
                            {{ isset($garde['dateDebut']) ? \Carbon\Carbon::parse($garde['dateDebut'])->format('d/m/Y H:i') : 'N/A' }} -
                            {{ isset($garde['dateFin']) ? \Carbon\Carbon::parse($garde['dateFin'])->format('d/m/Y H:i') : 'N/A' }}
                        </p>
                        <p><strong>Statut:</strong> {{ isset($garde['estActive']) && $garde['estActive'] ? 'Active' : 'Inactive' }}</p>
                        <p><strong>Pharmacies:</strong></p>
                        <ul>
                            @foreach($garde['pharmaciesIds'] as $pharmaId)
                                <span class="badge badge-sm bg-gradient-success">{{ $pharmacies[$pharmaId] ?? 'Pharmacie inconnue' }}</span>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Modals Modifier -->
    @foreach($gardes as $garde)
        <div class="modal fade" id="editModal{{ $garde['id'] }}">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Modifier Garde {{ $garde['id'] }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form method="POST" action="{{ route('gardes.update', $garde['id']) }}">
                        @csrf
                        @method('PUT')
                        <div class="modal-body scrollable-modal">
                            @if($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <div class="mb-3">
                                <label>ID Garde</label>
                                <input type="text" name="gardeId" class="form-control" value="{{ $garde['id'] }}" readonly>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label>Date de début</label>
                                    <input type="datetime-local" name="gardeDateDebut" class="form-control" value="{{ isset($garde['dateDebut']) ? \Carbon\Carbon::parse($garde['dateDebut'])->format('Y-m-d\TH:i') : '' }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label>Date de fin</label>
                                    <input type="datetime-local" name="gardeDateFin" class="form-control" value="{{ isset($garde['dateFin']) ? \Carbon\Carbon::parse($garde['dateFin'])->format('Y-m-d\TH:i') : '' }}" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label>Statut</label>
                                <select name="gardeEstActive" class="form-select" required>
                                    <option value="1" {{ isset($garde['estActive']) && $garde['estActive'] ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ isset($garde['estActive']) && !$garde['estActive'] ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Pharmacies participantes</label>
                                <input type="text" id="searchPharmacyEdit" class="form-control mb-3" placeholder="Rechercher une pharmacie...">
                                <div class="row" id="pharmacyListEdit">
                                    @foreach($pharmacies as $id => $nom)
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="gardePharmacies[]" value="{{ $id }}" id="editPharm{{ $garde['id'] }}_{{ $id }}" {{ in_array($id, $garde['pharmaciesIds']) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="editPharm{{ $garde['id'] }}_{{ $id }}">{{ $nom }}</label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                            <button type="submit" class="btn btn-success">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('searchGarde');
        const tableRows = document.querySelectorAll('#gardeTable tr');

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

        const pharmacySearchInput = document.getElementById('searchPharmacy');
        const pharmacyRows = document.querySelectorAll('#pharmacyList .form-check');

        pharmacySearchInput.addEventListener('keyup', function () {
            const filter = pharmacySearchInput.value.toLowerCase();
            pharmacyRows.forEach(row => {
                const label = row.querySelector('.form-check-label');
                if (label.textContent.toLowerCase().includes(filter)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        const pharmacySearchInputEdit = document.getElementById('searchPharmacyEdit');
        const pharmacyRowsEdit = document.querySelectorAll('#pharmacyListEdit .form-check');

        pharmacySearchInputEdit.addEventListener('keyup', function () {
            const filter = pharmacySearchInputEdit.value.toLowerCase();
            pharmacyRowsEdit.forEach(row => {
                const label = row.querySelector('.form-check-label');
                if (label.textContent.toLowerCase().includes(filter)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
</script>
@endsection
