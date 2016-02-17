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
        if($_GET['type'] == "badgesByJeuByJoueur" && isset($_GET['player']) && isset($_GET['game'])){
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
                $response[] = "<input type='hidden' id='player' value='" . $player ."'>";
                $response[] = '<table align="center" border="1"><th>Les badges de ' . $player . ' pour le jeu ' . $game . '</th>';
                if($joueur['jeux']){
                    foreach ($joueur['jeux'] as $value){
                        if($value['name'] == $game){
                            foreach ($value['badges'] as $badge){
                                $response[] = '<tr ><td>' . $badge["name"] . '</td><td onclick="if (confirm(\'Supprimer ce badge?\')) {removeBadge(\'' . $badge["name"] .'\', \'' . $game .'\')}">X</td></tr>';
                                
                               // $response[] = '<tr ><td>' . $badge["name"] . '</td><td onclick="removeBadge(\'' . $badge["name"] .'\', \'' . $game .'\')">X</td></tr>';
                            } 
                        }

        
                    }
                }
                $response[] =  '</table>';
                $response[] = '<br /><input  id="badgeAdd" type="text" value=""><input type="submit" value="AJouter le badge" onclick="addBadge(\'' . $player . '\',\'' . $game . '\')" >';
            }else{
                $response[] =  'Cet utilisateur n\'existe pas';
            }
            
            
            echo implode("", $response);
            die();
        } 
 
        // recherche classement par jeu (meilleur score)
        if($_GET['type'] == "classementByJeu" && isset($_GET['jeu'])){
            $game = $_GET['jeu'];
            $cnn = new MongoClient();
            $db = $cnn->Maets;
            $collectionJoueur = $db->Joueurs;
            //$query = array('jeux.name' => $game);
            
           
             $query =array(
                            array('$match' => 
                                            array('jeux.name' => $game)
                                
                            ),
                            array('$project' => 
                                            array('jeux' => 
                                                        array('$filter' => 
                                                                        array("input" => '$jeux', 'as' => 'jeu', 'cond' => 
                                                                                                                       array('$eq'=> ['$$jeu.name', $game])
                                                                            
                                                                               )
                                                            
                                                            ), 'pseudo' => 1
                                                
                                                   )
                                   ),
                            array('$sort' => array('jeux.scores' => -1)),
                            array('$limit' => 5)
                
              );
            
            
            $jeu = $collectionJoueur->aggregateCursor($query);
            
            //$jeu->sort(array('jeux.scores' => -1));
            //$jeu->limit(5);
            $response = array();
            $response[] = '<table align="center" border="1"><tr><th>Nom du joueur</th>';
            $response[] = '<th>Meilleur score</th></tr>';
            foreach ($jeu as $doc) {   
                
               // print_r($doc);die();
                if($doc){

                    if($doc['jeux']){
                        foreach ($doc['jeux'] as $value){
                                $response[] = '<tr onclick="getBadges(this)" data-player="' . $doc["pseudo"]. '"><td>' . $doc["pseudo"] . '</td>';
                                $response[] = '<td>' . $value["scores"] . '</td></tr>';
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
        
        
        
        
        
        // recherche de badges par jeu et par joueur
        if($_GET['type'] == "removeBadge" && isset($_GET['player']) && isset($_GET['game']) && isset($_GET['badge'])){
            $player = $_GET['player'];
            $game = $_GET['game'];
            $badge = $_GET['badge'];
            $cnn = new MongoClient();
            $db = $cnn->Maets;
            $collectionJoueur = $db->Joueurs;
            
            $result = $collectionJoueur->update(
                array('pseudo' => $player, "jeux.badges.name" => $badge),
                array('$pull' =>
                    array('jeux.$.badges' => array('name' => $badge))
                )
            );
            
            if(!$result) die('erreur');
            
            $query = array( "pseudo" => $player);
            $champs = array('jeux' => true);
            $joueur = $collectionJoueur->findOne($query);
        
        
            $response = array();
        
            if($joueur){
                $response[] = "<input type='hidden' id='player' value='" . $player ."'>";
                
                $response[] = '<table align="center" border="1"><th>Les badges de ' . $player . ' pour le jeu ' . $game . '</th>';
                if($joueur['jeux']){
                    foreach ($joueur['jeux'] as $value){
                        if($value['name'] == $game){
                            foreach ($value['badges'] as $badge){
                                $response[] = '<tr ><td>' . $badge["name"] . '</td><td onclick="if (confirm(\'Supprimer ce badge?\')){ removeBadge(\'' . $badge["name"] .'\', \'' . $game .'\')}">X</td></tr>';
                            }
                        }
        
        
                    }
                }
                $response[] =  '</table>';
                
                $response[] = '<br /><input id="badgeAdd" type="text" value=""><input type="submit" value="AJouter le badge" onclick="addBadge(\'' . $player . '\',\'' . $game . '\')" >';
            }else{
                $response[] =  'Cet utilisateur n\'existe pas';
            }
        
        
            echo implode("", $response);
            die();
        }
        
        