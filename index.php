<html>
<head>
    <title>Maets</title>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>

</head>
<body>
    <input class ="btn btn-primary" type="submit" onclick="soap()">
    <?php

      $data = array();
      $data = json_decode(file_get_contents("php://input"), true);
      var_dump($data);
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
            success : function(message, code_html, statut){ // code_html contient le HTML renvoyé
               //alert('toto');
               //document.location.href="/stats/Lol"
                $('#result').text(message);
            }
         });
    }
    </script>
</body>
</html>