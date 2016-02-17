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
<h1><a href="index.php">Maets</a></h1><br /><br />
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



    function removeBadge(badge, jeu) {

        
        var joueur = $('#player').val();
        $.ajax({

            url : 'functions.php',
            //data: 'data= ' + $getValue.value,
            data: 'type=removeBadge' +  '&game=' + jeu + '&player=' + joueur + '&badge=' + badge,

            type : 'GET',

            dataType : 'text',

            success : function(code_html, statut){
                $('#badges').html(code_html);

            }

         });

    }





    function addBadge(joueur, jeu) {

        var badge = $('#badgeAdd').val();
        
    	var message = JSON.stringify({
        		"player": joueur,
        		"badge": badge
        	});
        $.ajax({

            url : '/stats/' + jeu,
            //data: 'data= ' + $getValue.value,
            data: message,
            //contentType: 'application/json',
            type : 'put',
            dataType : 'json',
            success : function(code_html, statut){
                alert('gello');
                //$('#badges').html(code_html);
                console.log('<tr ><td>test</td><td onclick="if (confirm(\'Supprimer ce badge?\')) {removeBadge(\'test\', \'test\')}">X</td></tr>');
                $('#badges table tbody').append('<tr ><td>test</td><td onclick="if (confirm(\'Supprimer ce badge?\')) {removeBadge(\'test\', \'test\')}">X</td></tr>');
                

            }

         });
        $('#badges table tbody').append('<tr ><td>' + badge + '</td><td onclick="if (confirm(\'Supprimer ce badge?\')) {removeBadge(\'' + badge +'\', \'' + jeu +'\')}">X</td></tr>');
        
    }
    
</script>
    
</body>
</html>