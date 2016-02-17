    <?php 
    
        if (!isset($_GET['type'])) die();
        
        // recherche de jeux par joueur
        if($_GET['type'] == "jeuxByJoueur" && isset($_GET['pseudo'])){
            $player = $_GET['pseudo'];
            $cnn = new MongoClient();
            $db = $cnn->Maets;
            $collectionJoueur = $db->Joueurs;
                    
            $query = array( "pseudo" => $player);
            $champs = array('jeux' => true);
            $joueur = $collectionJoueur->findOne($query);

            //var_dump($joueur);
            //die();
            
            $response = array();
            
            if($joueur){
                $response[] = "<input type='hidden' id='player' value='" . $player ."'>";
                $response[] = '<table border="1"><th>Les jeux de ' . $player . '</th><th> High scores </th>';
                if($joueur['jeux']){
                    foreach ($joueur['jeux'] as $value){
                        $response[] = '<tr onclick="getBadges(this)" data-game="' . $value['name']. '">';
                        $response[] = '<td>' . $value['name'] . '</td>';
                        $response[] = '<td>' . $value['scores'] . '</td>';
                        $response[] = '</tr>';
                    }
                }else{
                    $response[] = 'Aucun jeu n\'a été trouvé pour cet utilisateur';                    
                }
                $response[] =  '</table>';
            }else{
                $response[] =  'Cet utilisateur n\'existe pas';
            }
            echo implode("", $response);
            die();
        }
        
        // recherche de badges par jeu et par joueur
        if($_GET['type'] == "badgesByJeuByJoueur" && isset($_GET['player']) && $_GET['game']){
            $player = $_GET['player'];
            $game = $_GET['game'];
            $cnn = new MongoClient();
            $db = $cnn->Maets;
            $collectionJoueur = $db->Joueurs;
            $query = array( "pseudo" => $player);
            $champs = array('jeux' => true);
            $joueur = $collectionJoueur->findOne($query);
            
            
            $response = array();
        
            if($joueur){
                $response[] = '<table align="center" border="1"><th>Les badges de ' . $player . ' pour le jeu ' . $game . '</th>';
                if($joueur['jeux']){
                    foreach ($joueur['jeux'] as $value){
                        if($value['name'] == $game){
                            foreach ($value['badges'] as $badge){
                                $response[] = '<tr ><td>' . $badge["name"] . '</td></tr>';
                            } 
                        }

        
                    }
                }
                $response[] =  '</table>';
            }else{
                $response[] =  'Cet utilisateur n\'existe pas';
            }
            
            
            echo implode("", $response);
            die();
        } 
        
        
        // recherche classement par jeu (meilleur score)
        if($_GET['type'] == "classementByJeu" && $_GET['jeu']){
            $game = $_GET['jeu'];
            $cnn = new MongoClient();
            $db = $cnn->Maets;
            $collectionJoueur = $db->Joueurs;
            $query = array( 'jeux.name' => $game);
            $jeu = $collectionJoueur->find($query);
            $jeu->sort(array('jeux.scores' => -1));
            $jeu->limit(5);
            $response = array();
            $response[] = '<h4>Top 5 pour le jeu ' . $game . '</h4>';
            $response[] = '<table align="center" border="1"><tr><th>Nom du joueur</th>';
            $response[] = '<th>Meilleur score</th></tr>';
            foreach ($jeu as $doc) {
                if($doc){

                    if($doc['jeux']){
                        foreach ($doc['jeux'] as $value){
                            if($value['name'] == $game){
                                $response[] = '<tr onclick="getBadges(this)" data-player="' . $doc["pseudo"]. '"><td>' . $doc["pseudo"] . '</td>';
                                $response[] = '<td>' . $value["scores"] . '</td></tr>';
                            }
                        }
            
            
                    }else{
                        $response[] = 'Aucun jeu n\'a été trouvé pour cet utilisateur';
                    }

                }else{
                    $response[] =  'Cet utilisateur n\'existe pas';
                }
            }
            
            $response[] =  '</table>';
            
        
        
            echo implode("", $response);
            die();
        }


        if($_GET['type'] == "compare" && isset($_GET['pseudo1']) && isset($_GET['pseudo2']) && isset($_GET['game'])){
            $game = $_GET['game'];
            $joueur1 = $_GET['pseudo1'];
            $joueur2 = $_GET['pseudo2'];
            $cnn = new MongoClient();
            $db = $cnn->Maets;
            $collectionJoueur = $db->Joueurs;
            $joueur1 = $collectionJoueur->findOne(array("pseudo" => $joueur1));
            $joueur2 = $collectionJoueur->findOne(array("pseudo" => $joueur2));  
            if (!is_null($joueur1) && !is_null($joueur2)){
                $jeuxJoueur1 = $joueur1['jeux'];
                $jeuxJoueur2 = $joueur2['jeux'];
                if (!is_null($jeuxJoueur1) && !is_null($jeuxJoueur2)){
                    $game1 = false;
                    $game2 = false;
                    foreach ($jeuxJoueur1 as $key) {
                        if ($key['name'] == $game){
                            $game1 = true;
                            $gameJoueur1 = $key;
                        }
                    }
                    
                    foreach ($jeuxJoueur2 as $key) {
                        if ($key['name'] == $game){
                            $game2 = true;
                            $gameJoueur2 = $key;
                        }
                    }
                    if ($game1 && $game2){
                        echo '<h4 align="center">Comparaison pour le jeu ' . $game . '</h4>';
                        echo '<table align="center" border="1"><tr><th>Nom du joueur '.$_GET['pseudo1'].'</th></tr>';
                        $badgesJoueur1 = $gameJoueur1['badges'];
                        $badgesJoueur2 = $gameJoueur2['badges'];
                        //echo '<tr>';

                        foreach ($badgesJoueur1 as $key) {
                            echo '<tr><td>'.$key['name'].'</td></tr>';
                        }
                        echo '</table>';
                        echo '<br/>';
                        echo '<table align="center" border="1"><tr><th>Nom du joueur '.$_GET['pseudo2'].'</th></tr>';
                        foreach ($badgesJoueur2 as $key) {
                            echo '<tr><td>'.$key['name'].'</td></tr>';
                        }
                        //echo '</tr>';
                       
                    }
                }
            }         

        }
        
        
      