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
            $this->firestore->collection('pharmacies')->document($data['pharmacyId'])->set($pharmacyData);
            return redirect()->route('pharmacies.index')->with('success', 'Pharmacie enregistrée avec succès.');
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
    

    


}
