<!doctype html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>TODO</title>
</head>
<body>

<?=$teste;?>
<input type="text">
<span <?php ?>>
    <p><?php ?></p>
</span>

<span <?php ?>>
    <p>Success!</p>
</span>

<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Ocorrência</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($ocorrencias as $ocorrencia){?>
    <tr>
        <td><?=$ocorrencia->getId();?></td>
        <td><?=$ocorrencia->getNome();?></td>
        <a href="root/controller/deletar?id=<?=$ocorrencia->getId();?>">Deletar</a>
        <td><input type="text" value="<?=$ocorrencia->getId();?>"></td>
    </tr>
    <?php } ?>
    </tbody>
</table>


</body>
</html>