<?php  
    function returnConnection(){
    $cnn = new MongoClient(); 
    return $cnn;
    }
    function getUser($name){
        return returnConnection()->Maets->Joueurs->findOne(array('pseudo' => $name));               
    }
    function getNextID(){
        $collection = returnConnection()->Maets->Joueurs->find();
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
        returnConnection()->Maets->Joueurs->insert($Joueur);
    }

    $urlExploded = explode("/",$_SERVER['REQUEST_URI']);


    if (count($urlExploded) == 3 && 
        strtolower($urlExploded[1]) == 'stats'){
        
        $nomDuJeu = $urlExploded[count($urlExploded) -1];
        $data = json_decode(file_get_contents("php://input"), true);
        $user = $data['player'];
        $badge = $data['badge'];

        $player = getUser($user);
        if (is_null($player)){
            //Si l'utilisateur n'existe pas, on insert
            insertNewPlayer($user, $badge, $nomDuJeu);
        } else {
            //Si l'utilisateur existe, on regarde si il a déjà joué au jeu            
            $lesJeux = $player['jeux']; 
            $trouveLeJeu = False;
            foreach ($lesJeux as $key) {
                if (!is_null($key['name'])){
                    if ($key['name'] == $nomDuJeu){
                        $trouveLeJeu = True;
                    }
                }    
            }

            if (!$trouveLeJeu){
                //Si le joueur a deja jouer au jeu alors on ajoute le badge associé
                returnConnection()->Maets->Joueurs->update(array("pseudo" => $user), array('$push' => array("jeux" => array("scores" => 0, "name" => $nomDuJeu, "badges" => array(array("name" => $badge))))));
            }
            else{
                //Sinon on ajoute le jeu et le badge associé
                returnConnection()->Maets->Joueurs->update(array("pseudo" => $user, "jeux.name" => $nomDuJeu), array('$push' => array("jeux.$.badges" => array("name" => $badge))));
            }
        }

    }
