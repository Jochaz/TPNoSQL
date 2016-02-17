<html>
<head>
    <title>Maets</title>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
<meta charset="UTF-8">
<style>
    #div{
        width: 70%;
        margin: auto;
        background-color:#FF9900;
    }
</style>
</head>
<body>
<h1><a href="index.php">Maets</a></h1><br /><br />
    <div id="Recherche">
        Comparaison
        <br />
        <br />
        Nom du joueur 1: 
        <br />
        <input id="pseudo1" type="text" value="">
        <br />
        <br />
        Nom du joueur 2:
        <br />
        <input id="pseudo2" type="text" value="">
        <br /><br />
        Nom du jeu :
        <br />
        <input id="jeu" type="text" value="">
        <br /><br />
        <input type="submit" value="Afficher" onclick="getCompare()">
        <br/>
    </div>
    <div class="parent">
        <div id='jeux'></div>
        <div style="margin-left: 30px;" id='badges'></div>
    </div>

    
    
<script>
    function getCompare(){
        var joueur1 = $('#pseudo1').val();
        var joueur2 = $('#pseudo2').val();
        var jeu = $('#jeu').val();
        $.ajax({

            url : 'functions.php',
            //data: 'data= ' + $getValue.value,
            data: 'type=compare' + '&pseudo1=' + joueur1 + '&pseudo2=' + joueur2 + '&game=' + jeu,

            type : 'GET',

            dataType : 'text', // On désire recevoir du HTML

            success : function(code_html, statut){ // code_html contient le HTML renvoyé
                $('#jeux').html(code_html);
                $('#badges').html("");
            }

         });
    }
    
</script>
    
</body>
</html>