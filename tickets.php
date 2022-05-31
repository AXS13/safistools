<?php 

    require __DIR__.'/ticketsType.php';
    require __DIR__.'/functions.php';

    session_start();

        // Vérifier que l'utilisateur demandé existe en BDD et récupérer cet utilisateur
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

        if($isDev)
        {
            $req = $bdd->prepare('SELECT 
                                    t.id as `ticket.id`,
                                    t.title as `ticket.title`,
                                    t.description as `ticket.description`,
                                    t.type as `ticket.type`,
                                    t.state as `ticket.state`,
                                    uCreator.username as `uCreator.username`,
                                    uEditor.username as `uEditor.username`
                                FROM tickets as t
                                LEFT JOIN users as uCreator ON uCreator.id = t.users_id
                                LEFT JOIN users as uEditor ON uEditor.id = t.editor_id');
            $req->execute();
        }
        else
        {
            $req = $bdd->prepare('SELECT 
                                    t.id as `ticket.id`,
                                    t.title as `ticket.title`,
                                    t.description as `ticket.description`,
                                    t.type as `ticket.type`,
                                    t.state as `ticket.state`,
                                    uCreator.username as `uCreator.username`,
                                    uEditor.username as `uEditor.username`
                                FROM tickets as t
                                LEFT JOIN users as uCreator ON uCreator.id = t.users_id
                                LEFT JOIN users as uEditor ON uEditor.id = t.editor_id
                                WHERE :users_id = t.users_id');
            $req->execute(
                array(
                    'users_id' => $_SESSION['utilisateur_id']
                )
            );
        }
        $ticketsList = $req->fetchAll();
?>

<?php $title = "Tickets"; ?>

<?php ob_start();
 if(empty($_SESSION['utilisateur_id']))
 {
    header('Location:login.php');
 }

if(empty($ticketsList))
{
    echo "Il n'y pas encore de tickets...";
}
else
{
?>
<div class="scroller">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Titre</th>
                <th>Description</th>
                <th>Type</th>
                <th>État</th>
                <th>Éditeur</th>
                <th>Créateur</th>
                <?php 
                if($isDev)
                {
                ?>
                <th></th>
                <th></th>
                <th></th>
                <?php 
                }
                ?>
            </tr>
        </thead>
        <tbody>
    <?php
    foreach ($ticketsList as $ticket)
    {
        ?>
        <tr>
            <td><?php echo $ticket['ticket.id'];?></td>
            <td><?php echo $ticket['ticket.title'];?></td>
            <td><?php echo $ticket['ticket.description'];?></td>
            <td>[<?php echo $ticketsType[$ticket['ticket.type']]?>]</td>
            <td>[<?php echo $ticketsState[$ticket['ticket.state']]?>]</td>
            <td><?php echo $ticket['uEditor.username'];?></td>
            <td><?php echo $ticket['uCreator.username'];?></td>
            <?php 
            if($isDev)
            {
            ?>
                <td><a href= "takeTicket.php?id=<?php echo $ticket['ticket.id'];?>" style="color: #E28204;"><strong>S'affecter</strong></a></td>
                <td><a href= "solveTicket.php?id=<?php echo $ticket['ticket.id'];?>" style="color: #049B5A;"><strong>Résoudre</strong></a></td>
                <td><a href= "deleteTicket.php?id=<?php echo $ticket['ticket.id'];?>" style="color: #BC1839;"><strong>Supprimer</strong></a></td>
            <?php 
            }
            ?>
        </tr>
    <?php
    }
    ?>
        </tbody>
    </table>
</div>
<?php
}
?>
<?php $content = ob_get_clean(); ?>

<?php require('template.php'); ?>