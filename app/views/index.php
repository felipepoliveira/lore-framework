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
    <title>Loretitle</title>
</head>
<body>
    {{teste}}{{teste}}{{teste}}
    {{other}}
    {{@section}}
    <a href="<?=View::url("register")?>">Register</a>
    <a href="<?=View::url("login")?>">Sign in</a>
    {{@sectionend}}
</body>
</html>