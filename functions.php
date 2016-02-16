    <?php 
    
        if(isset($_GET['pseudo'])){
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
                $response[] = '<table border="1"><th>Les jeux de ' . $player . '</th>';
                if($joueur['jeux']){
                    foreach ($joueur['jeux'] as $value){
                        
                        foreach ($value as $key => $result){
                            if($key == "name")
                                $response[] = '<tr ><td onclick="getBadges(this);">' . $result . '</td></tr>';
                        }
                
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
        
        
        if(isset($_GET['player']) && $_GET['game']){
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
                                $response[] = '<tr ><td>' . $badge["nom"] . '</td></tr>';
                            } 
                        }

        
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
        
       
    ?>