<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Cloud\Firestore\FirestoreClient;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class PharmaciesController extends Controller
{
    protected $firestore;

    public function __construct()
    {
        $credentials = config('firebase.credentials');
        $this->firestore = new FirestoreClient([
            'keyFilePath' => $credentials,
        ]);
    }

    public function index()
    {
        $pharmacies = $this->getPharmacies();
        return view('pharmacies.index', ['pharmacies' => $pharmacies]);
    }

    protected function getPharmacies()
    {
        $pharmaciesCollection = $this->firestore->collection('pharmacies');
        $pharmaciesSnapshot = $pharmaciesCollection->documents();
        $pharmacies = [];

        foreach ($pharmaciesSnapshot as $document) {
            if ($document->exists()) {
                $data = $document->data();
                $data['id'] = $document->id();
                $pharmacies[] = $data;
            }
        }

        return $pharmacies;
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'pharmacyId' => 'required|string',
            'pharmacyName' => 'required|string',
            'pharmacyLocation' => 'required|string',
            'pharmacyClosing' => 'required|string',
            'pharmacyOpening' => 'required|string',
            'pharmacyPhone1' => 'required|string',
            'pharmacyPhone2' => 'nullable|string',
            'pharmacyLatitude' => 'required|numeric',
            'pharmacyLongitude' => 'required|numeric',
        ]);

        $pharmacyData = [
            'nom' => $data['pharmacyName'],
            'emplacement' => $data['pharmacyLocation'],
            'fermeture' => $data['pharmacyClosing'],
            'ouverture' => $data['pharmacyOpening'],
            'telephone1' => $data['pharmacyPhone1'],
            'telephone2' => $data['pharmacyPhone2'] ?? null,
            'latitude' => $data['pharmacyLatitude'],
            'longitude' => $data['pharmacyLongitude'],
        ];

        try {
            // Créer la pharmacie
            $this->firestore->collection('pharmacies')->document($data['pharmacyId'])->set($pharmacyData);

            // Créer automatiquement le compte gérant
            $pharmacienData = [
                'nom' => 'Gérant',
                'prenom' => 'Principal',
                'telephone' => $data['pharmacyPhone1'],
                'identifiant' => $data['pharmacyId'],
                'mot_de_passe' => Hash::make('asdf1234'),
                'role' => 'gérant',
            ];

            // Ajouter le compte gérant dans la sous-collection pharmaciens
            $this->firestore->collection('pharmacies')
                ->document($data['pharmacyId'])
                ->collection('pharmaciens')
                ->add($pharmacienData);

            return redirect()->route('pharmacies.index')
                ->with('success', 'Pharmacie enregistrée avec succès. Un compte gérant a été créé automatiquement (Identifiant: ' . $data['pharmacyId'] . ' / Mot de passe: asdf1234)');
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'enregistrement de la pharmacie:', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Erreur lors de l\'enregistrement de la pharmacie.');
        }
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'pharmacyName' => 'required|string',
            'pharmacyLocation' => 'required|string',
            'pharmacyClosing' => 'required|string',
            'pharmacyOpening' => 'required|string',
            'pharmacyPhone1' => 'required|string',
            'pharmacyPhone2' => 'nullable|string',
            'pharmacyLatitude' => 'required|numeric',
            'pharmacyLongitude' => 'required|numeric',
        ]);

        $pharmacyData = [
            'nom' => $data['pharmacyName'],
            'emplacement' => $data['pharmacyLocation'],
            'fermeture' => $data['pharmacyClosing'],
            'ouverture' => $data['pharmacyOpening'],
            'telephone1' => $data['pharmacyPhone1'],
            'telephone2' => $data['pharmacyPhone2'] ?? null,
            'latitude' => $data['pharmacyLatitude'],
            'longitude' => $data['pharmacyLongitude'],
        ];

        try {
            $this->firestore->collection('pharmacies')->document($id)->set($pharmacyData, ['merge' => true]);
            return redirect()->route('pharmacies.index')->with('success', 'Pharmacie mise à jour avec succès.');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour de la pharmacie:', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Erreur lors de la mise à jour de la pharmacie.');
        }
    }

    public function destroy($id)
    {
        try {
            $this->firestore->collection('pharmacies')->document($id)->delete();
            return redirect()->route('pharmacies.index')->with('success', 'Pharmacie supprimée avec succès.');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression de la pharmacie:', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Erreur lors de la suppression de la pharmacie.');
        }
    }

   
    
    public function indexComptes()
    {
        $pharmacies = $this->getPharmacies();
        return view('pharmacies.indexComptes', ['pharmacies' => $pharmacies]);
    }

    public function addPharmacien(Request $request, $pharmacyId)
    {
        // Validate the incoming request data
        $data = $request->validate([
            'pharmacienName' => 'required|string',
            'pharmacienPrenom' => 'nullable|string',
            'pharmacienPhone' => 'required|string',
            'pharmacienIdentifiant' => 'nullable|string',
            'pharmacienPassword' => 'required|string',
            'pharmacienConfirmPassword' => 'required|string|same:pharmacienPassword',
            'pharmacienRole' => 'required|string',
        ]);
    
        // Prepare the data to be added to Firestore
        $pharmacienData = [
            'nom' => $data['pharmacienName'],
            'prenom' => $data['pharmacienPrenom'] ?? null,
            'telephone' => $data['pharmacienPhone'],
            'identifiant' => $data['pharmacienIdentifiant'] ?? null,
            'mot_de_passe' => Hash::make($data['pharmacienPassword']),
            'role' => $data['pharmacienRole'],
        ];
    
        try {
            // Add the pharmacien data to the 'pharmaciens' subcollection
            $this->firestore->collection('pharmacies')->document($pharmacyId)
                ->collection('pharmaciens')->add($pharmacienData);
    
            // Redirect with a success message
            return redirect()->route('pharmacies.indexComptes')->with('success', 'Pharmacien ajouté avec succès.');
        } catch (\Exception $e) {
            // Log the error and redirect back with an error message
            Log::error('Erreur lors de l\'ajout du pharmacien:', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Erreur lors de l\'ajout du pharmacien.');
        }
    }
    
    public function generateAccount($pharmacyId)
    {
        try {
            // Préparer les données du compte
            $pharmacienData = [
                'nom' => 'Gérant',
                'prenom' => 'Principal',
                'telephone' => '',
                'identifiant' => $pharmacyId,
                'mot_de_passe' => Hash::make('asdf1234'),
                'role' => 'gérant',
            ];

            // Vérifier si un compte gérant existe déjà
            $pharmaciensCollection = $this->firestore->collection('pharmacies')
                ->document($pharmacyId)
                ->collection('pharmaciens');
            
            $existingGerant = $pharmaciensCollection
                ->where('role', '=', 'gérant')
                ->where('identifiant', '=', $pharmacyId)
                ->documents();

            if ($existingGerant->isEmpty()) {
                // Ajouter le nouveau compte
                $pharmaciensCollection->add($pharmacienData);
                return redirect()->route('pharmacies.indexComptes')
                    ->with('success', 'Compte gérant généré avec succès. Identifiant: ' . $pharmacyId . ' / Mot de passe: asdf1234');
            } else {
                return redirect()->route('pharmacies.indexComptes')
                    ->with('error', 'Un compte gérant existe déjà pour cette pharmacie.');
            }
        } catch (\Exception $e) {
            Log::error('Erreur lors de la génération du compte:', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Erreur lors de la génération du compte.');
        }
    }

    public function generateAllAccounts()
    {
        try {
            $pharmacies = $this->getPharmacies();
            $comptesGeneres = 0;
            $comptesExistants = 0;

            foreach ($pharmacies as $pharmacy) {
                $pharmacyId = $pharmacy['id'];
                
                // Vérifier si un compte gérant existe déjà
                $pharmaciensCollection = $this->firestore->collection('pharmacies')
                    ->document($pharmacyId)
                    ->collection('pharmaciens');
                
                $existingGerant = $pharmaciensCollection
                    ->where('role', '=', 'gérant')
                    ->where('identifiant', '=', $pharmacyId)
                    ->documents();

                if ($existingGerant->isEmpty()) {
                    // Préparer les données du compte
                    $pharmacienData = [
                        'nom' => 'Gérant',
                        'prenom' => 'Principal',
                        'telephone' => '',
                        'identifiant' => $pharmacyId,
                        'mot_de_passe' => Hash::make('asdf1234'),
                        'role' => 'gérant',
                    ];

                    // Ajouter le nouveau compte
                    $pharmaciensCollection->add($pharmacienData);
                    $comptesGeneres++;
                } else {
                    $comptesExistants++;
                }
            }

            if ($comptesGeneres > 0) {
                return redirect()->route('pharmacies.indexComptes')
                    ->with('success', $comptesGeneres . ' compte(s) gérant généré(s) avec succès. ' . 
                           ($comptesExistants > 0 ? $comptesExistants . ' compte(s) existaient déjà.' : '') .
                           ' Mot de passe par défaut: asdf1234');
            } else {
                return redirect()->route('pharmacies.indexComptes')
                    ->with('info', 'Tous les comptes gérants existent déjà (' . $comptesExistants . ' pharmacies).');
            }

        } catch (\Exception $e) {
            Log::error('Erreur lors de la génération des comptes:', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Erreur lors de la génération des comptes.');
        }
    }

    /**
     * Met à jour les quantités en stock pour tous les produits de toutes les pharmacies
     */
    public function updateAllStocks()
{
    try {
        ini_set('max_execution_time', '18000');
        set_time_limit(18000);

        $pharmaciesCollection = $this->firestore->collection('pharmacies');
        $pharmacies = $pharmaciesCollection->documents();
        
        $nbPharmaciesTraitees = 0;
        $nbProduitsTraites = 0;

        foreach ($pharmacies as $pharmacie) {
            $pharmacieId = $pharmacie->id();
            
            $produitsRef = $pharmaciesCollection
                ->document($pharmacieId)
                ->collection('produits');
            
            $produits = $produitsRef->documents();

            foreach ($produits as $produit) {
                $produitId = $produit->id();
                
                $stocksRef = $pharmaciesCollection
                    ->document($pharmacieId)
                    ->collection('produits')
                    ->document($produitId)
                    ->collection('stock');
                
                $stocks = $stocksRef->documents();
                
                // Par défaut, la quantité est 0
                $quantiteTotale = 0;
                
                // Si des stocks existent, on calcule la somme
                foreach ($stocks as $stock) {
                    $quantiteTotale += intval($stock->data()['quantite_disponible'] ?? 0);
                }
                
                // Mettre à jour la quantité en stock du produit
                $produitsRef->document($produitId)->update([
                    ['path' => 'quantite_en_stock', 'value' => $quantiteTotale]
                ]);

                $nbProduitsTraites++;
            }

            $nbPharmaciesTraitees++;
        }

        return response()->json([
            'success' => true,
            'message' => "Mise à jour terminée. $nbProduitsTraites produits traités dans $nbPharmaciesTraitees pharmacies."
        ]);

    } catch (\Exception $e) {
        \Log::error('Erreur lors de la mise à jour globale des stocks : ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Une erreur est survenue lors de la mise à jour globale des stocks : ' . $e->getMessage()
        ]);
    }
}
}
