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
                        <h6 class="text-white text-capitalize ps-3">Informations des utilisateurs</h6>
                    </div>
                </div>
                <div class="card-body px-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Photo</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Adresse</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date de création</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Téléphone</th>
                                    <th class="text-secondary opacity-7"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div>
                                                <img src="{{ $user['photo_url'] ?? 'images/faces/avatar.png' }}"
                                                     class="avatar avatar-sm me-3 border-radius-10" alt="user">
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $user['nom_prenom'] ?? 'N/A' }}</h6>
                                                <p class="text-xs text-secondary mb-0">{{ $user['email'] ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td>
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm">{{ $user['adresse'] ?? 'N/A' }}</h6>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ isset($user['createdAt']) ? Carbon\Carbon::parse($user['createdAt'])->format('d/m/Y H:i') : 'N/A' }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $user['telephone'] ?? 'N/A' }}</p>
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
@endsection
