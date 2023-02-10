<?php
// Si l'ID est vide !!!
if(empty($_GET['id']))
{
    header('location:index.php');
    exit;
}
require_once('config.php');
require_once('core/class.client.php');
require_once('core/class.ticket.php');
require_once('core/class.partie.php');
$verif_connect = Client::getConnexion();
// Si le client est connecté
if($verif_connect)
{
    $client = new Client($_COOKIE['id']);
    $ticket = new Ticket($_GET['id']);
    //$partie = new Partie($_GET['id']);
    // On vérifie si le client a assez de crédit !!!
    if($client->getCredit() >= $ticket->getPrix())
    {
        // On met à jour le crédit du client
        $nouveau_credit = $client->getCredit()-$ticket->getPrix();
        // On met à jour le crédit
        $client->setCredit($nouveau_credit);
        // On update le client dans la BDD
        $client->editer();
    }
    //echo $ticket->getId();
    $req = $db->prepare('SELECT * FROM `table_partie` WHERE Partie_Ticket_ID = :id  ORDER BY RAND() LIMIT 1');
    $req->bindValue(':id',$ticket->getId(),PDO::PARAM_INT);
    $req->execute();
    if($req->rowCount() == 1)
    {
        $partie = $req->fetch(PDO::FETCH_OBJ);
    }
    else
    {
        echo 'Aucune partie';
    }
}   
?>
<html>
    <body>
        <div class="container" id="js-container">
        <canvas class="canvas" id="js-canvas" width="300" height="300"></canvas>
        <link rel="stylesheet" href="assets/css/style.css">
        <form class="form" style="visibility: hidden;" method="POST" action="action.php?e=crediter">
        <h2><?php echo $partie->Partie_Valeur; ?></h2>
        <div>
        </div>
        <br>
        <div>
        <input type="hidden" name="valeur" value="<?php echo $partie->Partie_Valeur; ?>">
            <input type="submit" name="submit" id="submit" value="Submit">
        </div>
        <script type="text/javascript" src="assets/js/canvas.js"></script>
    </body>
</html>