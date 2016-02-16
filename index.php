<html>
<head>
    <title>Maets</title>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>

</head>
<body>
    <input class ="btn btn-primary" type="submit" onclick="soap()">
    <?php
        function returnConnection(){
        $cnn = new MongoClient(); 
        return $cnn;
        }
        function getUser($name){
            return returnConnection()->Maets->Joueur->findOne(array('pseudo' => $name));               
        }
        function getNextID(){
            return count(returnConnection()->Maets->Joueur->find()) + 1;
        }
        function insertNewPlayer($name, $badge, $game){
            $Joueur = array("_id" => getNextID(), "pseudo" => $name, "jeux" => array("scores" => 0, "name" => $game, "badges" => array("name" => $badge)));
            returnConnection()->Maets->Joueur->insert($Joueur);
        }
        $user = getUser('Kibin enyere');
        if (!isset($user)){
            //Si l'utilisateur n'existe pas, on insert
            insertNewPlayer("Jochaz", "Premier match up", "League of Legends");
            echo 'ok';
        }


    ?>
    <div id='result'>
    
    </div>
    <script>
    function soap() {
    	var message = {
    		player: 'John',
    		badge: 'Coucou'
    	}
        $.ajax({

            url : '/stats/Lol',
            
            //data: 'data= ' + $getValue.value,
            data: message,
            type : 'PUT',
            dataType : 'text', // On désire recevoir du HTML
            success : function(code_html, statut){ // code_html contient le HTML renvoyé
               //alert('toto');
               //document.location.href="/stats/Lol"
                console.log(message);
            }
         });
    }
    </script>
</body>
</html>