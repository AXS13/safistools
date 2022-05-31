<?php
    include 'logger.php';
    try {
        throw new ErrorException ("Erreur de Zinzin", 3, 54);
    }
    catch(ErrorException $e) {
        Logger::logWarn($e);
    }
    
?>