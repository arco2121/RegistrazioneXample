<?php
require_once "Database.php";

if(!empty($_GET['token'])) {
    $st = Database::getInstance()->getConnection()->prepare("SELECT * FROM utenti WHERE verification_token = :token AND verification_expires < NOW() and email_verified = false");
    $st->bindParam(':token', $_GET['token']);
    $st-> execute();
    $ver = $st->fetch();
    if($ver) {
        echo "Verification expired, sign up again!";
        $st = Database::getInstance()->getConnection()->prepare("delete from UTENTI WHERE verification_token = :id AND verification_expires < NOW()");
        $st->bindParam(':id', $_GET['token']);
        $st->execute();
        echo "Success verification";
        die();
    }
    $st = Database::getInstance()->getConnection()->prepare("SELECT * FROM utenti WHERE verification_token = :token AND verification_expires > NOW() and email_verified = false");
    $st->bindParam(':token', $_GET['token']);
    $st-> execute();
    $utente = $st->fetch();
    if($utente){
        $st = Database::getInstance()->getConnection()->prepare("update UTENTI SET email_verified = true, verification_token = null, verification_expires = null WHERE id = :id AND verification_expires > NOW()");
        $st->bindParam(':id', $utente['id']);
        $st->execute();
        echo "Success verification";
        die();
    }
    echo "Token shit";
    die();
}
echo "Token not valid";