<style type="text/css">
table {
border-collapse: collapse;
width: 300px;
font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
font-size: 14px;
color: #333;
}

th {
background-color: #e0e0e0;
border: thin solid #dadada;
padding: 4px;
}

td {
background-color: #f5f5f5;
border: thin solid #dadada;
padding: 4px;
text-align: center;
}
 </style>

<?php

$users = array (
    'user1' => array ('800','/home/user1/torrents'),
    'user2' => array ('100','/home/user2/torrents'),
    'user3' => array ('200','/home/user3/torrents'),
    'user4' => array ('500','/home/user4/torrents'),
    'user5' => array ('200','/home/user5/torrents'));

$couleur = array (
	'vert' => "green",
	'orange' => "orange",
	'rouge' => "red");

echo "<table><tr><th>Utilisateurs</th><th>En cours</th><th>Maximum</th></tr>";
foreach ($users as $user => $max){
	$dossier=taille_dossier($max[1]);
	$limit=$max[0]*1024*1024*1024;
	$color='#333';
	$pourcentage=($dossier*100)/$limit;
	if($pourcentage<75){$color=$couleur['vert'];}
        elseif($pourcentage<90){$color=$couleur['orange'];}
        else{$color=$couleur['rouge'];}

    echo '<tr style="color:'.$color.';">
		<td>'.$user.'</td>
		<td>'.convertir($dossier).'</td>
		<td>'.$max[0].' Go</td></tr>';
}
echo "</table>";

function taille_dossier($rep){
	$racine=@opendir($rep);
	$taille=0;
	while($dossier=@readdir($racine)){
		if(!in_array($dossier, Array("..", "."))){
			if(is_dir("$rep/$dossier")){$taille+=taille_dossier("$rep/$dossier");}
			else{$taille+=@filesize("$rep/$dossier");}}}
	@closedir($racine);
	return $taille;
}

function convertir($taille){
	if($taille<1024){$taille.=" o";}
	else{
		if($taille<1024*1024){
			$taille=number_format($taille/1024, 2);
			$taille.=" Ko";}
		else{
			if($taille<1024*1024*1024){
				$taille=number_format($taille/(1024*1024), 2);
				$taille.=" Mo";}
			else{
				if($taille<1024*1024*1024*1024){
					$taille=number_format($taille/(1024*1024*1024), 2);
					$taille.=" Go";}}}}
	return $taille;
}
?>
