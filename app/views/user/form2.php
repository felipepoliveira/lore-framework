<?php
use lore\mvc\View;

//die(var_dump(\lore\Lore::app()->getResponse()->getData()));

?>
<!doctype html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Lore</title>
</head>
<body>
<main>
    <form action="<?=View::url("rest/v1/user")?>" method="post">
        <div>
            <label>
                <?=View::input("email", "type='text' id='inputEmail'")?>
            </label>
        </div>
        <div>
            <label>
                <?=View::input("senha", 'type="password"')?>
            </label>
        </div>
        <div>
            <label>
                <input type="text" placeholder="Confirm password">
            </label>
        </div>
        <div>
            <button type="submit">Save</button>
        </div>
    </form>
</main>
</body>
</html>
