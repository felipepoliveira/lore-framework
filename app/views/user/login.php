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
        <form action="<?=Lore::url("user/authenticate");?>" method="post">
            <div class="form-group">
                <label for="inputEmail">Email</label>
                <input type="text" id="inputEmail" name="email">
                <input type="text"  name="address.address" value="R. Arraial da Piranga">
                <?=Lore::error("User.email", "<div class='alert alert-danger'>%%</div>")?>
            </div>
            <div class="form-group">
                <label for="inputPassword">Senha</label>
                <input type="password" id="inputPassword" name="password">
                <?=Lore::error("User.password", "<div class='alert alert-danger'>%%</div>")?>
            </div>
            <button type="submit">Entrar</button>
        </form>
    </main>
</body>
</html>