<?php

namespace App\Services;

use Google\Cloud\Firestore\FirestoreClient;

class FirebaseService
{
    protected $firestore;

    public function __construct()
    {
        $credentials = config('firebase.credentials');

        $this->firestore = new FirestoreClient([
            'keyFilePath' => $credentials,
        ]);
    }

    public function getFirestore()
    {
        return $this->firestore;
    }

    public function getDocuments($collection)
    {
        return $this->firestore->collection($collection)->documents();
    }

    public function getSubCollection($collection, $documentId, $subCollection)
    {
        // Obtenez une référence au document parent
        $documentReference = $this->firestore->collection($collection)->document($documentId);

        // Accédez à la sous-collection à partir de la référence du document
        return $documentReference->collection($subCollection)->documents();
    }

    public function getDocument($collection, $documentId)
    {
        // Retourne un DocumentSnapshot
        return $this->firestore->collection($collection)->document($documentId)->snapshot();
    }

    public function getDocumentByField($collection, $documentId, $subCollection, $field, $value)
    {
        // Obtenez une référence au document parent
        $documentReference = $this->firestore->collection($collection)->document($documentId);

        $query = $documentReference->collection($subCollection)
            ->where($field, '=', $value)
            ->limit(1);

        $documents = $query->documents();

        foreach ($documents as $document) {
            return $document;
        }

        return null;
    }

    public function getPharmacyName($pharmacyId)
    {
        $document = $this->firestore->collection('pharmacies')->document($pharmacyId)->snapshot();

        if ($document->exists()) {
            return $document->get('nom');
        }

        return null;
    }

    public function addDocument($collection, $documentId, $subCollection, $data)
    {
        // Obtenez une référence au document parent
        $documentReference = $this->firestore->collection($collection)->document($documentId);

        // Ajoutez un document à la sous-collection
        return $documentReference->collection($subCollection)->add($data);
    }

    public function updateDocument($collection, $documentId, $subCollection, $subDocumentId, $data)
    {
        // Obtenez une référence au document parent
        $documentReference = $this->firestore->collection($collection)->document($documentId);

        // Mettez à jour un document dans la sous-collection
        return $documentReference->collection($subCollection)->document($subDocumentId)->update($data);
    }

    public function deleteDocument($collection, $documentId, $subCollection, $subDocumentId)
    {
        // Obtenez une référence au document parent
        $documentReference = $this->firestore->collection($collection)->document($documentId);

        // Supprimez un document dans la sous-collection
        return $documentReference->collection($subCollection)->document($subDocumentId)->delete();
    }
}
