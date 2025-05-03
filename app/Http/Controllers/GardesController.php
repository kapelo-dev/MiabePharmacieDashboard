<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Cloud\Firestore\FirestoreClient;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class GardesController extends Controller
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
        $gardes = $this->getGardes();
        $pharmacies = $this->getPharmacies();
        return view('gardes.index', compact('gardes', 'pharmacies'));
    }

    protected function getGardes()
    {
        $gardes = [];
        $documents = $this->firestore->collection('gardes')->documents();

        foreach ($documents as $document) {
            if ($document->exists()) {
                $data = $document->data();
                $data['id'] = $document->id();
                $data['pharmaciesIds'] = $data['pharmaciesIds'] ?? [];
                $gardes[] = $data;
            }
        }

        return $gardes;
    }

    protected function getPharmacies()
    {
        $pharmacies = [];
        $documents = $this->firestore->collection('pharmacies')->documents();

        foreach ($documents as $document) {
            if ($document->exists()) {
                $pharmacies[$document->id()] = $document->data()['nom'] ?? 'N/A';
            }
        }

        return $pharmacies;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'gardeId' => 'required|string',
            'gardeDateDebut' => 'required|date',
            'gardeDateFin' => 'required|date|after:gardeDateDebut',
            'gardeEstActive' => 'required|boolean',
            'gardePharmacies' => 'required|array|min:1',
            'gardePharmacies.*' => 'string'
        ]);

        // Convertir en booléen
        $validated['gardeEstActive'] = filter_var($validated['gardeEstActive'], FILTER_VALIDATE_BOOLEAN);

        try {
            $this->firestore->collection('gardes')->document($validated['gardeId'])->set([
                'dateDebut' => Carbon::parse($validated['gardeDateDebut']),
                'dateFin' => Carbon::parse($validated['gardeDateFin']),
                'est_active' => $validated['gardeEstActive'],
                'pharmaciesIds' => $validated['gardePharmacies'],
            ]);

            return redirect()->route('gardes.index')->with('success', 'Garde créée avec succès!');
        } catch (\Exception $e) {
            Log::error('Erreur création garde: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de la création')->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'gardeDateDebut' => 'required|date',
            'gardeDateFin' => 'required|date|after:gardeDateDebut',
            'gardeEstActive' => 'required|boolean',
            'gardePharmacies' => 'required|array|min:1',
            'gardePharmacies.*' => 'string'
        ]);

        $validated['gardeEstActive'] = filter_var($validated['gardeEstActive'], FILTER_VALIDATE_BOOLEAN);

        try {
            $this->firestore->collection('gardes')->document($id)->set([
                'dateDebut' => Carbon::parse($validated['gardeDateDebut']),
                'dateFin' => Carbon::parse($validated['gardeDateFin']),
                'estActive' => $validated['gardeEstActive'],
                'pharmaciesIds' => $validated['gardePharmacies'],
            ], ['merge' => true]);

            return redirect()->route('gardes.index')->with('success', 'Garde mise à jour avec succès!');
        } catch (\Exception $e) {
            Log::error('Erreur mise à jour garde: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de la mise à jour')->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $this->firestore->collection('gardes')->document($id)->delete();
            return redirect()->route('gardes.index')->with('success', 'Garde supprimée avec succès!');
        } catch (\Exception $e) {
            Log::error('Erreur suppression garde: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de la suppression');
        }
    }
}
