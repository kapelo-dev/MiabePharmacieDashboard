<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Cloud\Firestore\FirestoreClient;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    protected $firestore;

    public function __construct()
    {
        $credentials = config('firebase.credentials');
        $this->firestore = new FirestoreClient(['keyFilePath' => $credentials]);
    }

    public function index()
    {
        $users = $this->getUsers();
        Log::info('Users retrieved from Firestore:', ['users' => $users]);
        return view('users.index', ['users' => $users]);
    }

    protected function getUsers()
    {
        try {
            $usersCollection = $this->firestore->collection('utilisateur');
            $usersSnapshot = $usersCollection->documents();
            $users = [];

            foreach ($usersSnapshot as $document) {
                if ($document->exists()) {
                    $users[] = $document->data();
                }
            }
            return $users;
        } catch (\Exception $e) {
            Log::error('Error retrieving users from Firestore:', ['error' => $e->getMessage()]);
            return [];
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'adresse' => 'required|string',
            'createdAt' => 'required|date',
            'email' => 'required|email|unique:users',
            'nom_prenom' => 'required|string',
            'password' => 'required|string|min:8',
            'telephone' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $this->firestore->collection('utilisateurs')->add([
                'adresse' => $request->input('adresse'),
                'createdAt' => $request->input('createdAt'),
                'email' => $request->input('email'),
                'nom_prenom' => $request->input('nom_prenom'),
                'password' => bcrypt($request->input('password')), // Hash the password
                'telephone' => $request->input('telephone'),
            ]);

            return redirect()->route('users.index')->with('success', 'User added successfully.');
        } catch (\Exception $e) {
            Log::error('Error adding user to Firestore:', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to add user.');
        }
    }
}
