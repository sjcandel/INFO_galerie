<!DOCTYPE html>
<html>
<head>
	<title>Galerie</title>
	<meta charset="utf-8">
</head>


<body>

<?php
include('galerie.class.php'); 
$u = new Galerie();
$u->chargePOST();
$u->affiche();
$u->insertIntoDB();
?>
    
<p><a href='galerie.php'>Retour</a></p>
</body>
</html>