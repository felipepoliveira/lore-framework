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
                <label for="inputName">Name</label>
                <input type="text" id="inputName" name="name">
            </div>
        </form>
    </main>
</body>
</html>