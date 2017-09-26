<?php
return [

    "lore/mysql" => [
        "file" => "lore/persistence/impl/relational/RelationalRepository.php",
        "class" => "lore\\persistence\\RelationalRepository",

        "rdbms" => "mysql",
        "host" => "localhost:3306",
        "database" => "lore",
        "user" => "root",
        "password" => "root"
    ],

    "todo/mysql" => [
        "file" => "lore/persistence/impl/relational/RelationalRepository.php",
        "class" => "lore\\persistence\\RelationalRepository",

        "rdbms" => "mysql",
        "host" => "localhost:3306",
        "database" => "todo",
        "user" => "root",
        "password" => "root"
    ]
];