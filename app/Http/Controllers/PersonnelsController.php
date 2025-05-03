<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Cloud\Firestore\FirestoreClient;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class PersonnelsController extends Controller
{
    protected $firestore;

    public function __construct()
    {
        $credentials = config('firebase.credentials');
        $this->firestore = new FirestoreClient(['keyFilePath' => $credentials]);
    }

    public function index()
    {
        $developpeurs = $this->getDeveloppeurs();
        Log::info('Developpeurs retrieved from Firestore:', ['developpeurs' => $developpeurs]);
        return view('developpeurs.index', ['developpeurs' => $developpeurs]);
    }

    protected function getDeveloppeurs()
    {
        try {
            $developpeursCollection = $this->firestore->collection('developpeurs');
            $developpeursSnapshot = $developpeursCollection->documents();
            $developpeurs = [];

            foreach ($developpeursSnapshot as $document) {
                if ($document->exists()) {
                    $developpeurs[] = $document->data();
                }
            }
            return $developpeurs;
        } catch (\Exception $e) {
            Log::error('Error retrieving developpeurs from Firestore:', ['error' => $e->getMessage()]);
            return [];
        }
    }

    public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'adresse' => 'required|string',
        'email' => 'required|email',
        'nom_prenom' => 'required|string',
        'mot_de_passe' => 'required|string|min:8',
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    try {
        $this->firestore->collection('developpeurs')->add([
            'adresse' => $request->input('adresse'),
            'email' => $request->input('email'),
            'nom_prenom' => $request->input('nom_prenom'),
            'mot_de_passe' => bcrypt($request->input('mot_de_passe')), // Hash the password
            'createdAt' => now() // Set the current timestamp
        ]);

        return redirect()->route('developpeurs.index')->with('success', 'Personnel added successfully.');
    } catch (\Exception $e) {
        Log::error('Error adding personnel to Firestore:', ['error' => $e->getMessage()]);
        return redirect()->back()->with('error', 'Failed to add personnel.');
    }
}
    public function destroy($id)
    {
        try {
            $this->firestore->collection('developpeurs')->document($id)->delete();
            return redirect()->route('developpeurs.index')->with('success', 'Personnel deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting personnel from Firestore:', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to delete personnel.');
        }
    }

    public function edit($id)
    {
        try {
            $document = $this->firestore->collection('developpeurs')->document($id)->snapshot();
            if ($document->exists()) {
                return view('developpeurs.edit', ['developpeur' => $document->data()]);
            } else {
                return redirect()->route('developpeurs.index')->with('error', 'Personnel not found.');
            }
        } catch (\Exception $e) {
            Log::error('Error retrieving personnel for edit:', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to retrieve personnel for edit.');
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'adresse' => 'required|string',
            'email' => 'required|email',
            'nom_prenom' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $this->firestore->collection('personnels')->document($id)->set([
                'adresse' => $request->input('adresse'),
                'email' => $request->input('email'),
                'nom_prenom' => $request->input('nom_prenom'),
                // Update the password only if it's provided
                'mot_de_passe' => bcrypt($request->input('mot_de_passe')),
                'updatedAt' => now() // Set the current timestamp
            ], ['merge' => true]);

            return redirect()->route('personnels.index')->with('success', 'Personnel updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating personnel in Firestore:', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to update personnel.');
        }
    }
}
