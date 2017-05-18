<?php
use lore\Lore;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Loretask</title>
</head>
<body>
    <main>
        <form action="<?=Lore::url("user/save");?>" method="post">
            <div class="form-group">
                <label for="inputName">Name</label>
                <input type="text" id="inputName" name="name" value="">
                <?=Lore::error("User.name", "<div class='alert alert-danger'>%%</div>")?>
            </div>
            <div class="form-group">
                <label for="inputLastName">Last name</label>
                <input type="text" id="inputLastName" name="lastName" >
                <?=Lore::error("User.lastName", "<div class='alert alert-danger'>%%</div>")?>
            </div>
            <div class="form-group">
                <label for="inputEmail">Email</label>
                <input type="text" id="inputEmail" name="email">
                <?=Lore::error("User.email", "<div class='alert alert-danger'>%%</div>")?>
            </div>
            <div class="form-group">
                <label for="inputPassword">Senha</label>
                <input type="password" id="inputPassword" name="password" >
                <?=Lore::error("User.password", "<div class='alert alert-danger'>%%</div>")?>
            </div>
            <div class="form-group">
                <label for="inputConfirmPassword">Confirm password</label>
                <input type="password" id="inputConfirmPassword" name="password">
            </div>
            <button type="submit">Sign up</button>
        </form>
    </main>
</body>
</html>