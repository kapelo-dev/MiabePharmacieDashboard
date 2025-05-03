<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Cloud\Firestore\FirestoreClient;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

use Carbon\Carbon;

class DashboardController extends Controller
{
    protected $firestore;

    public function __construct()
    {
        // Charge les informations d'identification Firebase depuis le fichier de configuration
        $credentials = config('firebase.credentials');

        // Initialise le client Firestore avec les informations d'identification
        $this->firestore = new FirestoreClient([
            'keyFilePath' => $credentials,
        ]);
    }

    public function index()
    {
        // Récupère les données des pharmacies depuis Firestore
        $pharmacies = $this->getPharmacies();
        Log::info('Pharmacies récupérées depuis Firestore:', ['pharmacies' => $pharmacies]);
    
        // Récupère les données des utilisateurs depuis Firestore
        $utilisateurs = $this->getUtilisateurs();
        Log::info('Utilisateurs récupérés depuis Firestore:', ['utilisateurs' => $utilisateurs]);
    
        $gardes = $this->getGardes();
        Log::info('Gardes récupérés depuis Firestore:', ['gardes' => $gardes]);
    
        $inscriptionsParJour = $this->getInscriptionsParJour();
        Log::info('Inscriptions récupérées depuis Firestore:', ['inscriptionsParJour' => $inscriptionsParJour]);
    
        list($commandesParJour, $nombreCommandesTotal) = $this->getCommandesParJour();
        Log::info('cpj récupérées depuis Firestore:', ['commandesParJour' => $commandesParJour]);
    
        // Passe les données à la vue
        return view('dashboard', [
            'pharmacies' => $pharmacies,
            'utilisateurs' => $utilisateurs,
            'gardes' => $gardes,
            'inscriptionsParJour' => $inscriptionsParJour,
            'commandesParJour' => $commandesParJour,
            'nombreCommandesTotal' => $nombreCommandesTotal,
        ]);
    }


    protected function getPharmacies()
    {
        $pharmaciesCollection = $this->firestore->collection('pharmacies');
        $pharmaciesSnapshot = $pharmaciesCollection->documents();
        $pharmacies = [];

        foreach ($pharmaciesSnapshot as $document) {
            if ($document->exists()) {
                $pharmacies[] = $document->data();
            }
        }

        return $pharmacies;
    }

    protected function getUtilisateurs()
{
    $utilisateursCollection = $this->firestore->collection('utilisateur');
    $utilisateursSnapshot = $utilisateursCollection->documents();
    $utilisateurs = [];

    foreach ($utilisateursSnapshot as $document) {
        if ($document->exists()) {
            $data = $document->data();
            // Assurez-vous que 'createdAt' est un champ valide dans chaque document
            if (isset($data['createdAt'])) {
                $utilisateurs[] = $data;
            }
        }
    }

    // Trier les utilisateurs par 'createdAt' le plus récent
    usort($utilisateurs, function($a, $b) {
        $dateA = strtotime($a['createdAt']);
        $dateB = strtotime($b['createdAt']);
        return $dateB - $dateA; // Tri décroissant
    });

    return $utilisateurs;
}


    protected function getGardes()
    {
        $gardesCollection = $this->firestore->collection('gardes');
        $gardesSnapshot = $gardesCollection->documents();
        $gardes = [];   

        foreach ($gardesSnapshot as $document) {
            if ($document->exists()) {
                $gardes[] = $document->data();
            }
        }

        return $gardes;
    }


    protected function getInscriptionsParJour()
{
    // Obtenez la date du début de la semaine (lundi)
    $startOfWeek = new \DateTime('monday this week');
    $endOfWeek = clone $startOfWeek;
    $endOfWeek->modify('+6 days');

    // Initialisez un tableau pour stocker les inscriptions par jour
    $inscriptionsParJour = [
        'Lundi' => 0,
        'Mardi' => 0,
        'Mercredi' => 0,
        'Jeudi' => 0,
        'Vendredi' => 0,
        'Samedi' => 0,
        'Dimanche' => 0,
    ];

    // Récupérez les documents de la collection 'utilisateur'
    $utilisateursCollection = $this->firestore->collection('utilisateur');
    $utilisateursSnapshot = $utilisateursCollection->documents();

    foreach ($utilisateursSnapshot as $document) {
        if ($document->exists()) {
            $data = $document->data();
            $createdAt = new \DateTime($data['createdAt']);

            // Vérifiez si la date de création est dans la semaine en cours
            if ($createdAt >= $startOfWeek && $createdAt <= $endOfWeek) {
                // Incrémentez le compteur pour le jour correspondant
                $jour = $createdAt->format('l');
                switch ($jour) {
                    case 'Monday':
                        $inscriptionsParJour['Lundi']++;
                        break;
                    case 'Tuesday':
                        $inscriptionsParJour['Mardi']++;
                        break;
                    case 'Wednesday':
                        $inscriptionsParJour['Mercredi']++;
                        break;
                    case 'Thursday':
                        $inscriptionsParJour['Jeudi']++;
                        break;
                    case 'Friday':
                        $inscriptionsParJour['Vendredi']++;
                        break;
                    case 'Saturday':
                        $inscriptionsParJour['Samedi']++;
                        break;
                    case 'Sunday':
                        $inscriptionsParJour['Dimanche']++;
                        break;
                }
            }
        }
    }

    return $inscriptionsParJour;
}

protected function getCommandesParJour()
{
    $commandesParJour = [];
    $nombreCommandesTotal = 0; // Initialize a variable to keep track of the total number of orders
    $utilisateursCollection = $this->firestore->collection('utilisateur');

    foreach ($utilisateursCollection->documents() as $utilisateurDocument) {
        if ($utilisateurDocument->exists()) {
            $historiqueCollection = $this->firestore->collection('utilisateur')
                                                     ->document($utilisateurDocument->id())
                                                     ->collection('historique');

            foreach ($historiqueCollection->documents() as $historiqueDocument) {
                if ($historiqueDocument->exists()) {
                    $date = \DateTime::createFromFormat('d-m-Y', $historiqueDocument->id()); // Convertir l'ID en objet DateTime
                    $commandes = $historiqueDocument->data()['commandesFaites'] ?? [];
                    $nombreCommandes = count($commandes);
                    $nombreCommandesTotal += $nombreCommandes; // Add to the total number of orders

                    // Utiliser la date comme clé pour garantir un tri correct
                    $commandesParJour[$date->format('Y-m-d')] = $nombreCommandes;
                }
            }
        }
    }

    // Trier les commandes par jour du plus ancien au plus récent
    ksort($commandesParJour);

    return [$commandesParJour, $nombreCommandesTotal];
}
}

