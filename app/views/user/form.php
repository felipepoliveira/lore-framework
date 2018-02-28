<?php
use lore\mvc\View;
?>
<!doctype html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>TODO</title>
    <style>
        input{
            display: block;
        }
    </style>
</head>
<body>
    <form method="post" action="<?=View::url("user/register")?>">
        <label for="inputName">
            <?=View::input("name", 'type="text" id="inputName" placeholder="John"')?>
            <?=View::error("model.name", "<div class='alert alert-danger'>{{value}}</div>")?>
        </label>
        <label for="inputEmail">
            <?=View::input("email", 'type="text" id="inputEmail" placeholder="john@email.com"')?>
            <?=View::error("model.email", "<div class='alert alert-danger'>{{value}}</div>")?>
        </label>
        <label for="inputPublicPlace">
            <?=View::textarea("address.publicPlace", 'id="inputPublicPlace" placeholder="Str. Avenue, 123"')?>
            <?=View::error("model.address.publicPlace", "<div class='alert alert-danger'>{{value}}</div>")?>
        </label>
        <label for="inputPassword">
            <input type="password" type="password" id="inputPassword" placeholder="Your secret password" name="password">
            <?=View::error("model.password", "<div class='alert alert-danger'>{{value}}</div>")?>
        </label>
        <button type="submit">Enviar</button>
    </form>
</body>
</html>