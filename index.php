<?php
require_once '_connec.php';

$pdo = new \PDO(DSN, USER, PASS);

//ERRORS
$errors = [];
if (!empty($_POST)) {
    $friend = array_map('trim', $_POST);
    $friend = array_map('htmlentities', $friend);
    $firstname = $friend["firstname"];
    $lastname = $friend["lastname"];

    if (empty($friend['firstname'])) {
        $errors[] = '=> Veuillez renseigner votre prénom.';
    }

    if (empty($friend['lastname'])) {
        $errors[] = 'Veuillez renseigner votre nom.';
    }

    if (strlen($friend['firstname']) > 45) {
        $errors[] = 'Le prénom est trop long, ne pas dépasser 45 caractères.';
    }

    if (strlen($friend['lastname']) > 45) {
        $errors[] = 'Le nom est trop long, ne pas dépasser 45 caractères.';
    }
    //NOT ERROR=>SAVE VALUES
    if (empty($errors)) {
        $query = "INSERT INTO friend (firstname, lastname) VALUES (:firstname, :lastname)";
        $statement = $pdo->prepare($query);
        $statement->bindValue(':firstname', $firstname, \PDO::PARAM_STR);
        $statement->bindValue(':lastname', $lastname, \PDO::PARAM_STR);
        $statement->execute();
        header("Location: /");
    }
}
?>

<!-- ////////////////////////////////////// HTML ////////////////////////////////////// -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Friends</title>
</head>

<body>
    <h1>Friends !</h1>

    <?php
    $query = "SELECT * FROM friend";
    $statement = $pdo->query($query);
    $friends = $statement->fetchAll();
    ?>

    <?php foreach ($errors as $error) : ?>
        <p><?= $error ?></p>
    <?php endforeach; ?>

    <ul>
        <?php foreach ($friends as $friend) : ?>
            <li>
                <?= $friend['firstname'] ?> <?= $friend['lastname']; ?>
            </li>
        <?php endforeach; ?>
    </ul>

    <form action="/index.php" method="post">
        <div>
            <label for="firstname">Prénom: </label>
            <input id="firstname" name="firstname" type="text">
        </div>
        <div>
            <label for="lastname">Nom: </label>
            <input id="lastname" name="lastname" type="text"></input>
        </div>
        <input type="submit">
    </form>
</body>

</html>