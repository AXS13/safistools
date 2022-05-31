<?php
function getCurrentUser($bdd)
{
$req = $bdd->prepare('SELECT * FROM users WHERE id = :id');
        $req->execute(
            array(
                'id' => $_SESSION['utilisateur_id']
            )
        );

    $utilisateur = $req->fetch();
    return $utilisateur;
}    
?>