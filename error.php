<?php
    include 'logger.php';
    try {
        throw new ErrorException ("Erreur classique 1", 3, 54);
    }
    catch(ErrorException $e) {
        Logger::logWarn($e);
    }
    
?>