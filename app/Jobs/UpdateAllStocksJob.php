<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Google\Cloud\Firestore\FirestoreClient;
use Illuminate\Support\Facades\Log;

class UpdateAllStocksJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $jobId;
    public $timeout = 18000; // Timeout du job à 5 heures

    public function __construct()
    {
        $this->jobId = uniqid('stock_update_');
        Log::info("[Job {$this->jobId}] Job créé et prêt à être exécuté");
    }

    public function handle()
    {
        try {
            Log::info("[Job {$this->jobId}] Démarrage du job");
            Log::info("[Job {$this->jobId}] Configuration du timeout à 5 heures");
            
            set_time_limit(18000); // Augmenter le temps d'exécution PHP à 5 heures
            ini_set('max_execution_time', 18000); // Forcer le temps d'exécution maximum
            
            Log::info("[Job {$this->jobId}] Début de la mise à jour globale des stocks");
            
            $firestore = new FirestoreClient([
                'keyFilePath' => config('firebase.credentials'),
            ]);
            
            $pharmaciesCollection = $firestore->collection('pharmacies');
            $pharmaciesSnapshot = $pharmaciesCollection->documents();
            $pharmacies = [];
            
            foreach ($pharmaciesSnapshot as $document) {
                if ($document->exists()) {
                    $data = $document->data();
                    $data['id'] = $document->id();
                    $pharmacies[] = $data;
                }
            }
            
            Log::info("[Job {$this->jobId}] Nombre de pharmacies trouvées: " . count($pharmacies));
            
            $nbPharmacies = 0;
            $nbProduits = 0;
            
            foreach ($pharmacies as $pharmacy) {
                $pharmacieId = $pharmacy['id'];
                Log::info("[Job {$this->jobId}] Traitement de la pharmacie: {$pharmacieId}");
                
                $produitsRef = $firestore
                    ->collection('pharmacies')
                    ->document($pharmacieId)
                    ->collection('produits');
                    
                $produits = $produitsRef->documents();
                $nbProduitsPharm = 0;
                
                foreach ($produits as $produit) {
                    $produitId = $produit->id();
                    $produitData = $produit->data();
                    $quantiteActuelle = isset($produitData['quantite_en_stock']) ? intval($produitData['quantite_en_stock']) : 0;
                    
                    Log::info("[Job {$this->jobId}] Traitement du produit: {$produitId} de la pharmacie: {$pharmacieId}, quantité actuelle: {$quantiteActuelle}");
                    
                    $stocksRef = $firestore
                        ->collection('pharmacies')
                        ->document($pharmacieId)
                        ->collection('produits')
                        ->document($produitId)
                        ->collection('stock');
                        
                    $stocks = $stocksRef->documents();
                    $quantiteTotale = 0;
                    $stocksCount = 0;
                    
                    foreach ($stocks as $stock) {
                        $stockData = $stock->data();
                        if (isset($stockData['quantite_disponible'])) {
                            $quantite = intval($stockData['quantite_disponible']);
                            Log::info("[Job {$this->jobId}] Stock trouvé pour {$produitId}: {$quantite}");
                            $quantiteTotale += $quantite;
                            $stocksCount++;
                        }
                    }
                    
                    if ($stocksCount === 0) {
                        $quantiteTotale = $quantiteActuelle;
                        Log::info("[Job {$this->jobId}] Aucun stock trouvé pour {$produitId}, conservation de la quantité actuelle: {$quantiteTotale}");
                    }
                    
                    try {
                        $firestore->collection('pharmacies')
                            ->document($pharmacieId)
                            ->collection('produits')
                            ->document($produitId)
                            ->set([
                                'quantite_en_stock' => $quantiteTotale
                            ], ['merge' => true]);
                            
                        Log::info("[Job {$this->jobId}] Mise à jour réussie pour le produit: {$produitId}");
                        $nbProduits++;
                        $nbProduitsPharm++;
                    } catch (\Exception $e) {
                        Log::error("[Job {$this->jobId}] Erreur lors de la mise à jour du produit {$produitId}: " . $e->getMessage());
                    }
                }
                
                Log::info("[Job {$this->jobId}] Fin du traitement de la pharmacie {$pharmacieId}. Nombre de produits traités: {$nbProduitsPharm}");
                $nbPharmacies++;
            }
            
            Log::info("[Job {$this->jobId}] Fin de la mise à jour globale. Total: {$nbProduits} produits dans {$nbPharmacies} pharmacies");
            
        } catch (\Exception $e) {
            Log::error("[Job {$this->jobId}] Erreur lors de la mise à jour globale des stocks : " . $e->getMessage());
            throw $e;
        }
    }
} 