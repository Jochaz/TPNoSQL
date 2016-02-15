<html>
<head>
    <title>Maets</title>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>

</head>
<body>
    <input class ="btn btn-primary" type="submit" onclick="soap()">
    <?php
	    $cnn = new MongoClient(); 
	    $db = $cnn->Maets;
	    $collectionJoueur = $db->Joueur;
	    var_dump($db); die();
	    var_dump($collectionJoueur); die();
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
                alert('toto');
            }
         });
    }
    </script>
</body>
</html>