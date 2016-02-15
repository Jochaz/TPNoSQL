<?php
    $data = array();
    $put_vars = array();
    parse_str(file_get_contents("php://input"), $put_vars);
    $data = $put_vars;
    var_dump($data); 

    $cnn = new MongoClient(); 
    $db = $cnn->Maets;
    $collectionJoueur = $db->Joueurs;

    echo $collectionJoueur;