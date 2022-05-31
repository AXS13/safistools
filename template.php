<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" type="text/css" href="assets/style.css">
</head>

<body>
    <header class="header">
        <h1 class="logo"><a href="#">Safi's Tools</a></h1>
        <?php
            if(!empty($_SESSION['utilisateur_id']))
            {
        ?>
                <div class="fontSimple">Connecté en tant que : [<strong><?php echo $_SESSION['utilisateur_pseudo'] ?></strong>]</div>
        <?php
            }
        ?>
            <ul class="main-nav">
                <li><a href="tickets.php">Accueil</a></li>
                <li><a href="createTickets.php">Création de ticket</a></li>
                <?php
                if(!empty($_SESSION['utilisateur_id']))
                {
                ?>
                    <li><a class="button" href="logout.php"><strong>Déconnexion</strong></a> </div>
                <?php
                }
                ?>
            </ul>
    </header>

    <br><br>
    <?php echo $content; ?>

</body>

</html>