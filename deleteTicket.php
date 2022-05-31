<?php 
require __DIR__.'/functions.php';

session_start();

try
{
	$bdd = new PDO('mysql:host=mysql-safimohamed.alwaysdata.net;dbname=safimohamed_tpticket;charset=utf8', '261858', 'safi1809');
}
catch (Exception $e)
{
	die('Erreur : ' . $e->getMessage());
}

$utilisateur = getCurrentUser($bdd);
$ticketIdToUpdate = $_GET['id'];
// TODO : valider le paramètre
$isDev = $utilisateur['type'] == 2;

if(!$isDev)
{
	header('Location: tickets.php');
}


try
{
	$req = $bdd->prepare('DELETE FROM `tickets`
						  WHERE id = :ticket_id');

	$req->execute(
		array(
			'ticket_id' => $ticketIdToUpdate
		)
	);

	header('Location: tickets.php');
}
catch (Exception $e)
{
	echo "Un problème est survenu...<br />";
	echo('Erreur : ' . $e->getMessage());
}

?>