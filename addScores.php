<?php  
    function returnConnection(){
    $cnn = new MongoClient(); 
    return $cnn;
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
    function insertNewPlayer($name, $score, $game){ 
        $Joueur = array("_id" => getNextID(), "pseudo" => $name, "jeux" => array(array("scores" => $score, "name" => $game, "badges" => array())));
        returnConnection()->Maets->Joueurs->insert($Joueur);
    }

    $urlExploded = explode("/",$_SERVER['REQUEST_URI']);
    if (count($urlExploded) == 3 && 
        strtolower($urlExploded[1]) == 'scores'){
        
        $nomDuJeu = $urlExploded[count($urlExploded) -1];
        $data = json_decode(file_get_contents("php://input"), true);
        $user = $data['player'];
        $score = $data['score'];    
        $user = 'jojo';
        $score = 665;
        $result = returnConnection()->Maets->Joueurs->findOne(array("pseudo" => $user));
        if (is_null($result)){
            insertNewPlayer($user, $score, $nomDuJeu);
        }
        else{
            $scoreJeu = 0;
            $lesJeux = $result['jeux']; 
            if (!is_null($lesJeux)){
                foreach ($lesJeux as $jeu ) {
                    if ($jeu['name'] == $nomDuJeu){
                        $scoreJeu = $jeu['scores'];
                    }
                }
            }
            
            if (!in_array($nomDuJeu, $result)){
                if ($scoreJeu < $score){
                    returnConnection()->Maets->Joueurs->update(array("pseudo" => $user, "jeux.name" => $nomDuJeu), array('$set' => array("jeux.$.scores" => $score)));
                }
            }
            else {
                returnConnection()->Maets->Joueurs->update(array("pseudo" => $user), array('$push' => array("jeux" => array("scores" => $score, "name" => $nomDuJeu, "badges" => array()))));
            }
        }
    }
