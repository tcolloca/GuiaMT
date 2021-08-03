<?php

echo "Hello world";

$api_key = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/api_key.private");

$private_key = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/private_key.private");

// $keyFile = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/credentials.json");

// echo $keyFile;

// echo json_decode($keyFile, true);

// use Google\Cloud\Firestore\FirestoreClient;

// putenv("GOOGLE_CLOUD_PROJECT=guiamt-309818");
// putenv("GCLOUD_PROJECT=guiamt-309818");
// $firestore = new FirestoreClient([
//         'projectId' => 'guiamt-309818',
//         'keyFile' => json_decode($keyFile, true)
//     ]);

// $mushResearch = $firestore->collection('mush_research');
// print_r($mushResearch->documents());
// $query = $mushResearch->where('research_id', '==', '4');
// $snapshot = $query->documents();
// print_r($snapshot);


$firestoreClient = new MrShan0\PHPFirestore\FirestoreClient('guiamt-3222b', $api_key, [
    'database' => '(default)',
]);

// $collections = $firestoreClient->listDocuments('mush_research', [
//     'pageSize' => 1000
// ]);
// echo print_r($collections);

// $document_data = $firestoreClient->getDocument("mush_research/1");
// echo $document_data.getAbsoluteName();

function initFirestoreClient() {
  $api_key = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/api_key.private");
  return new MrShan0\PHPFirestore\FirestoreClient('guiamt-309818', $api_key, [
    'database' => '(default)',
  ]);
}

function toKeyValueObject($document) {
  return $document->toArray();
}

function getCollection($firestoreClient, $collectionName) {
  $documents = $firestoreClient->listDocuments($collectionName, [
    'pageSize' => 10000
  ]);
  return array_map("toKeyValueObject", $documents["documents"]);
}

function getDocument($firestoreClient, $collectionName, $id) {
  return $firestoreClient->getDocument($collectionName."/".$id);
}

function getGameTable($firestoreClient, $game, $table) {
  return getCollection($firestoreClient, $game."_".$table);
}

function getTags($firestoreClient, $table, $id) {
  return getDocument($firestoreClient, $game."_tag", $id);
}

echo print_r(getTags(initFirestoreClient(), "research", 1));

?>