<html>
<head>
    <title>Maets</title>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
<meta charset="UTF-8">


<style media="screen" type="text/css">

.parent div{
	float: left;
	clear: none;
}

table

{
    border-collapse: collapse; 
}

td
{
    border: 1px solid black;
	padding: 4px;
}
th
{
	padding: 4px;
}

</style>


</head>
<body>
<h3>Maets</h3>
        Nom du jeu:
        <input id=jeu type="text" name="nom" value="">
        <input type="submit" value="Afficher" onclick="getJeu()">
        <br/> <br />
    <div class="parent">
        <div id='classement'></div>
        <div style="margin-left: 30px;" id='badges'></div>
    </div>

    
    
<script>
    function getJeu() {
        
        var jeu = $('#jeu').val();
        $.ajax({

            url : 'functions.php',
            //data: 'data= ' + $getValue.value,
            data: 'type=classementByJeu' + '&jeu=' + jeu,

            type : 'GET',

            dataType : 'text', // On désire recevoir du HTML

            success : function(code_html, statut){ // code_html contient le HTML renvoyé
                $('#classement').html(code_html);
                $('#badges').html("");
            }

         });

    }


    function getBadges(element) {

    	console.log(element);
    	$('#classement tr').each(function () {
    		this.style["background-color"] = "FCFEFE";
    	});

    	element.style["background-color"] = "#D3CBFB";
        //console.log();
        var jeu = $('#jeu').val();
        
        var joueur = element.getAttribute("data-player");
        console.log(joueur);
        $.ajax({

            url : 'functions.php',
            //data: 'data= ' + $getValue.value,
            data: 'type=badgesByJeuByJoueur' +  '&game=' + jeu + '&player=' + joueur,

            type : 'GET',

            dataType : 'text',

            success : function(code_html, statut){
                $('#badges').html(code_html);

            }

         });

    }
    
</script>
    
</body>
</html>