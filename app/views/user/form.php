<?php
use lore\Lore;
use lore\mvc\View;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>
    <form action="<?=Lore::url("users/")?>" method="post">
        <fieldset id="fsUser">
            <h3>User info</h3>
            <div class="form-group">
                <label for="inputName">Name</label>
                <?= View::input("name", "type='text' id='inputName'") ?>
                <?= Lore::error("model.name", "<div class='alert'>{value}</div>") ?>
            </div>
            <div class="form-group">
                <label for="inputEmail">Email</label>
                <input type="email" id="inputPhone" name="email" value="<?=Lore::data("model.email")?>">
                <?= Lore::error("model.email", "<div class='alert'>{value}</div>") ?>
            </div>
            <div class="form-group">
                <label for="inputPassword">Password</label>
                <input type="password" id="inputPassword" name="password" value="<?=Lore::data("model.senha")?>">
                <?= Lore::error("model.senha", "<div class='alert'>{value}</div>") ?>
            </div>
            <button type="submit">Save</button>
        </fieldset>
    </form>
</body>
</html>