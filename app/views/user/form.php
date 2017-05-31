<?php
use lore\Lore;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>
    <form action="<?=Lore::url("user/save")?>" method="post">
        <fieldset id="fsUser">
            <h3>User info</h3>
            <div class="form-group">
                <label for="inputName">Name</label>
                <input type="text" id="inputName" name="name" value="<?=Lore::data("model.name")?>">
                <?= Lore::error("User.name", "<div class='alert'>%%</div>") ?>
            </div>
            <div class="form-group">
                <label for="inputPhone">Phone</label>
                <input type="tel" id="inputPhone" name="phone" value="<?=Lore::data("model.phone")?>">
                <?= Lore::error("User.phone", "<div class='alert'>%%</div>") ?>
            </div>
        </fieldset>
        <fieldset id="fsUserAddress">
            <h3>Address</h3>
            <div class="form-group">
                <label for="inputCity">City</label>
                <input type="text" id="inputCity" name="address.city" value="<?=Lore::data("model.address.city")?>">
                <?= Lore::error("Address.city", "<div class='alert'>%%</div>") ?>
            </div>
            <div class="form-group">
                <label for="inputPublicPlace">Public place</label>
                <input type="text" id="inputPublicPlace" name="address.publicPlace"
                       value="<?=Lore::data("model.address.publicPlace")?>">
                <?= Lore::error("Address.publicPlace", "<div class='alert'>%%</div>") ?>
            </div>
            <div class="form-group">
                <label for="inputNumber">Number</label>
                <input type="number" id="inputNumber" name="address.number"
                       value="{{model->address->number}}"
                       value="<?=Lore::data("model.address.number")?>">
                <?= Lore::error("Address.number", "<div class='alert'>%%</div>") ?>
            </div>
        </fieldset>
        <button type="submit">Save</button>
    </form>
</body>
</html>