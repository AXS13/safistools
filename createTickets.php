<?php

    require __DIR__.'/ticketsType.php';
    require __DIR__.'/functions.php';

    session_start();
    
    // To do 
    // Vérifier que $_SESSION['utilisateur_id'] existe, sinon rediriger.

    try
    {
        $bdd = new PDO('mysql:host=mysql-safimohamed.alwaysdata.net;dbname=safimohamed_tpticket;charset=utf8', '261858', 'safi1809');
    }
    catch (Exception $e)
    {
        die('Erreur : ' . $e->getMessage());
    }

    $utilisateur = getCurrentUser($bdd);
    $isDev = $utilisateur['type'] == 2;

    $authorizedTicketsTypeForUsers = ['1'];

    if(isset($_POST['titre'])){
        // Récupération des données depuis le formulaire
        $titre = $_POST['titre'];
        $description = $_POST['description'];
        $type = $_POST['type'];

        try
        {
            $req = $bdd->prepare('INSERT INTO tickets (title, description, type, users_id, state)
                                  VALUES (:title, :description, :type, :users_id, 1)');

            $req->execute(
                array(
                    'title' => htmlspecialchars($titre),
                    'description' => htmlspecialchars($description),
                    'type' => $type,
                    'users_id' => $_SESSION['utilisateur_id']
                )
            );
            header('Location: tickets.php');
        }
        catch (Exception $e)
        {
            echo "Un problème est survenu...<br />";
            echo('Erreur : ' . $e->getMessage());
        }
    }
?>

<?php $title = "Création de ticket"; ?>

<?php ob_start();
 if(empty($_SESSION['utilisateur_id']))
 {
    header('Location:login.php');
 } 
 ?>
<form action="createTickets.php" method="POST">
    <h1>Espace de Création</h1>

    <div class="question">
        <input type="text" name="titre" required>
        <label>Titre</label>
    </div>

    <div class="question">
        <input type="text" name="description" required></input>
        <label>Description</label>
    </div>

    <div class="question">
        <select name="type">

            <?php
            foreach ($ticketsType as $key => $value) {
                if($isDev || in_array($key, $authorizedTicketsTypeForUsers)) {
                    ?>
                    <option value="<?php echo $key ?>"><?php echo $value ?></option>
                    <?php
                }
            }
            ?>

        </select>
    </div>

    <button type="submit" id='submit'>Envoyer</button>
</form>
<?php $content = ob_get_clean(); ?>

<?php require('template.php'); ?>
<!--
<div class="question">
        <input type="password" name="password" required>
        <label>Mot de passe</label>
    </div>

    <button type="submit" id='submit'>Connexion</button>
</form>
-->