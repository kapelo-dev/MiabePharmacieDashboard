<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Google\Cloud\Firestore\FirestoreClient;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class FetchFirestoreData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $firestoreConfig;

    public function __construct(array $firestoreConfig)
    {
        $this->firestoreConfig = $firestoreConfig;
    }

    public function handle()
    {
        Log::info('FetchFirestoreData job started');

        // Initialiser le client Firestore avec les informations d'identification passées
        $firestore = new FirestoreClient($this->firestoreConfig);

        // Récupérer les données des pharmacies
        $pharmacies = $this->getPharmacies($firestore);
        Log::info('Pharmacies récupérées depuis Firestore:', ['pharmacies' => $pharmacies]);

        // Récupérer les données des utilisateurs
        $utilisateurs = $this->getUtilisateurs($firestore);
        Log::info('Utilisateurs récupérés depuis Firestore:', ['utilisateurs' => $utilisateurs]);

        // Récupérer les commandes de toutes les pharmacies
        $commandes = $this->getCommandes($firestore);
        Log::info('Commandes récupérées depuis Firestore:', ['commandes' => $commandes]);

        // Stocke les données dans le cache
        Cache::put('pharmacies', $pharmacies, 60);
        Cache::put('utilisateurs', $utilisateurs, 60);
        Cache::put('commandes', $commandes, 60);

        Log::info('FetchFirestoreData job completed');
    }

    protected function getPharmacies(FirestoreClient $firestore)
    {
        $pharmaciesCollection = $firestore->collection('pharmacies');
        $pharmaciesSnapshot = $pharmaciesCollection->documents();
        $pharmacies = [];

        foreach ($pharmaciesSnapshot as $document) {
            if ($document->exists()) {
                $pharmacies[] = $document->data();
            }
        }

        return $pharmacies;
    }

    protected function getUtilisateurs(FirestoreClient $firestore)
    {
        $utilisateursCollection = $firestore->collection('utilisateur');
        $utilisateursSnapshot = $utilisateursCollection->documents();
        $utilisateurs = [];

        foreach ($utilisateursSnapshot as $document) {
            if ($document->exists()) {
                $utilisateurs[] = $document->data();
            }
        }

        return $utilisateurs;
    }

    protected function getCommandes(FirestoreClient $firestore)
    {
        $pharmaciesCollection = $firestore->collection('pharmacies');
        $pharmaciesSnapshot = $pharmaciesCollection->documents();
        $commandes = [];

        foreach ($pharmaciesSnapshot as $pharmacyDocument) {
            if ($pharmacyDocument->exists()) {
                $pharmacyId = $pharmacyDocument->id();
                $commandesCollection = $firestore->collection('pharmacies')->document($pharmacyId)->collection('commandes');
                $commandesSnapshot = $commandesCollection->documents();

                foreach ($commandesSnapshot as $commandeDocument) {
                    if ($commandeDocument->exists()) {
                        $commandeData = $commandeDocument->data();
                        $commandeData['pharmacyId'] = $pharmacyId;
                        $commandes[] = $commandeData;
                    }
                }
            }
        }

        return $commandes;
    }
}
