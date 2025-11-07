<?php
require_once "Database.php";

$errors = [];
$url = "http://localhost/RegistrazioneXample/";

if(!empty($_POST)){
    if(($_POST['password'] ?? "") !== $_POST['password2']){
        $errors["passoword"] = "Passwords do not match";
    }
    $pdo = Database::getInstance()->getConnection();
    $st = $pdo->prepare("SELECT * FROM utenti WHERE username = :username");
    $st->bindValue(':username', $_POST['username']);
    $utente = $st->fetch();
    if($utente){
        $errors["utente"] = "Username already exists";
    }
    $sta = $pdo->prepare("SELECT * FROM utenti WHERE username = :email");
    $sta->bindValue(':email', $_POST['email']);
    $email = $st->fetch();
    if($email){
        $errors["email"] = "Email already exists";
    }

    if(empty($errors)){
        require_once "email_verification_service.php";
        $token = generateEmailVerificationToken();
        $stat = $pdo->prepare("INSERT INTO utenti (username, password, nome, cognome, email, verification_token, verification_expires) 
                                VALUES (:username, :password,:nome,:cognome, :email, :token, :expires)");
        $stat->execute([
            ':username' => $_POST['username'],
            ':password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
            ':nome' => $_POST['nome'],
            ':cognome' => $_POST['cognome'],
            ':email' => $_POST['email'],
            ':token' => $token[0],
            ':expires' => ($token[1] instanceof  DateTimeImmutable) ? $token[1]->format("Y-m-d H:i:s") : $token[1],
        ]);

        $urlto = urldecode($url . "confirm_verification.php?token=" . $token[0]);
        sendVerificationMail($_POST['email'], $_POST['username'], $urlto);
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="index.css">
    <title>Registrazione</title>
</head>
<body>
<div>
    <?php if(!empty($errors)) {
        echo "<p>";
        foreach ($errors as $error) {
            echo $error."<br>";
        }
        echo "</p>";
    } ?>
    <h1>Sign Up</h1>
    <form action="" method="post">
        <div class="row">
            <label for="email">Email : </label>
            <input type="email" name="email" id="email" required placeholder="email">
        </div>
        <div class="row">
            <label for="user">Username : </label>
            <input type="text" name="username" id="user" required placeholder="username">
        </div>
        <div class="row">
            <label for="name">Name : </label>
            <input type="text" name="nome" id="name" required placeholder="name">
        </div>
        <div class="row">
            <label for="surname">Surname : </label>
            <input type="text" name="cognome" id="surname" required placeholder="surname">
        </div>
        <div class="row">
            <label for="password">Password : </label>
            <input type="password" name="password" id="password" required placeholder="password">
        </div>
        <div class="row">
            <label for="password">Confirm password : </label>
            <input type="password" name="password2" id="password" required placeholder="password">
        </div>
        <input type="submit" value="Create">
    </form>
</div>
</body>
</html>