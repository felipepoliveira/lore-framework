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
        <?=View::html("<h1>Errors was detected!</h1>", View::hasErrors())?>
        <form action="<?=View::url("user/save")?>" method="post">
            <div>
                <label>
                    <?=View::input("email")?>
                </label>
                <?=View::error("model.email", "<div class='alert'>{{value}}</div>")?>
            </div>
            <div>
                <label>
                    <?=View::input("senha")?>
                </label>
                <?=View::error("model.senha", "<div class='alert'>{{value}}</div>")?>
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
