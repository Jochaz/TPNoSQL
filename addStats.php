<?php  
    function returnConnection(){
    $cnn = new MongoClient(); 
    return $cnn;
    }
    function getUser($name){
        return returnConnection()->Maets->Joueur->findOne(array('pseudo' => $name));               
    }
    function getNextID(){
        $collection = returnConnection()->Maets->Joueur->find();
        $cpt = 0;
        foreach ($collection as $key) {
            if ($key['_id'] > $cpt){
                $cpt = $key['_id'];
            }
        }
        return $cpt + 1;
    }
    function insertNewPlayer($name, $badge, $game){ 
        $Joueur = array("_id" => getNextID(), "pseudo" => $name, "jeux" => array(array("scores" => 0, "name" => $game, "badges" => array(array("name" => $badge)))));
        returnConnection()->Maets->Joueur->insert($Joueur);
    }

    $urlExploded = explode("/",$_SERVER['REQUEST_URI']);


    if (count($urlExploded) == 3 && 
        strtolower($urlExploded[1]) == 'stats'){
        
        $nomDuJeu = $urlExploded[count($urlExploded) -1];
        $mock = array("player" => "blabla", "badge" => "8K_miles");
        $user = $mock['player'];
        $badge = $mock['badge'];

//        $data = array();
//        $data = json_decode(file_get_contents("php://input"), true);
//        var_dump($data); die();
        $player = getUser($user);
        if (is_null($player)){
            //Si l'utilisateur n'existe pas, on insert
            insertNewPlayer($user, $badge, $nomDuJeu);
            echo 'Insertion réussi';
        } else {
            //Si l'utilisateur existe, on regarde si il a déjà joué au jeu            
            
        }

    }
