<?php
namespace App\Http\Controllers;
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FirebaseService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Google\Cloud\Firestore\FirestoreClient;

use App\Models\User; // Assurez-vous d'avoir un modèle User

class LoginController extends Controller
{
    protected $firebaseService;

    public function __construct()
    {
        $credentials = config('firebase.credentials');
        $this->firestore = new FirestoreClient([
            'keyFilePath' => $credentials,
        ]);
    
        // Initialiser firebaseService avec FirestoreClient
        $this->firebaseService = $this->firestore;
    }
    

    public function showLoginForm()
    {
        return view('login');
    }
    protected function getdeveloppeurs()
    {
        $developpeursCollection = $this->firestore->collection('developpeurs');
        $developpeursSnapshot = $developpeursCollection->documents();
        return $developpeursSnapshot; // Retourner les objets de document directement
    }
    
    public function login(Request $request)
    {
        $email = $request->input('email');
        $mot_de_passe = $request->input('mot_de_passe');
    
        // Chercher le développeur dans la base de données
        $developers = $this->getdeveloppeurs();
    
        foreach ($developers as $developer) {
            $data = $developer->data();
            if (isset($data['email']) && isset($data['mot_de_passe']) && Hash::check($mot_de_passe, $data['mot_de_passe'])) {
                // Authentification réussie
                // Créer un utilisateur temporaire pour l'authentification
                $user = new User();
                $user->id = $developer->id();
                $user->email = $data['email'];
                $user->nom_prenom = $data['nom_prenom'];
                

                
                session(['developpeur_nom_prenom' => $data['nom_prenom']]);

                // Authentifier l'utilisateur
                Auth::login($user);
    
                return redirect()->route('dashboard')->with('success', 'Connexion réussie');
            }
        }
    
        // Authentification échouée
        return redirect()->route('login.form')->with('error', 'Email ou mot de passe incorrect');
    }


    public function logout()
    {
        // Déconnecte l'utilisateur
        Auth::logout();
    
        // Invalide la session
        session()->invalidate();
    
        // Optionnel : Regénère le CSRF token
        session()->regenerateToken();
    
        // Redirige vers la page de connexion avec un message de succès
        return redirect()->route('login.form')->with('success', 'Déconnexion réussie');
    }
}
