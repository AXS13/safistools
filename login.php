<?php 
    session_start();

    if(isset($_POST['pseudo'])){
        // Récupération des données depuis le formulaire
        $pseudo = $_POST['pseudo'];
        $password = $_POST['password'];


        // Vérifier que l'utilisateur demandé existe en BDD et récupérer cet utilisateur
        try
        {
            $bdd = new PDO('mysql:host=localhost;dbname=tpticket;charset=utf8', 'root', '');
        }
        catch (Exception $e)
        {
            die('Erreur : ' . $e->getMessage());
        }

        $req = $bdd->prepare('SELECT * FROM users WHERE username = :pseudo');
        $req->execute(
            array(
                'pseudo' => $pseudo
            )
        );

        $utilisateur = $req->fetch();
        if(empty($utilisateur))
        {
            ?>
            <script>
                alert("Aucun utilisateur trouvé...");
            </script>
            <?php
        }
        else
        {
            //echo "L'utilisateur trouvé est le numéro " . $utilisateur['S_id'] . "<br />";
            //echo "Le mot de passe essayé est : " . $password . "<br />";
            //echo "Le hash en bdd est : " . $utilisateur['password'] . "<br />";

            // Les mots de passe correspondent-ils ?
            if($password != $utilisateur['password'])
            {
                echo "Les mots de passe ne correspondent pas...";
            }
            else
            {
                // Les mots de passe correspondent
                
                // Enregistrer l'utilisateur en session
                $_SESSION['utilisateur_id'] = $utilisateur['id'];
                $_SESSION['utilisateur_pseudo'] = $utilisateur['username'];

                // Rediriger l'utilisateur vers la page d'accueil
                header('Location: tickets.php');
                
            }
        }
    }
?>

<?php $title = "Connexion"; ?>

<?php ob_start(); ?>
<form action="login.php" method="POST">
    <h1>Espace de Connexion</h1>

    <div class="question">
        <input type="text" name="pseudo" required>
        <label>Identifiant</label>
    </div>

    <div class="question">
        <input type="password" name="password" required>
        <label>Mot de passe</label>
    </div>

    <button type="submit" id='submit'>Connexion</button>
</form>
<?php $content = ob_get_clean(); ?>

<?php require('template.php'); ?>
