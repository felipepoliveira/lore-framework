<?php
return [
    "controllers" => [
        "dirs" => ["app/controllers", "app/controllers/api"],
    ],

    "models" => [
        "dirs" => ["app/models"],

        "loader" => [
            "file" => "lore/mvc/impl/ReflexiveModelLoader.php",
            "class" => "\\lore\\mvc\\ReflexiveModelLoader",
        ],

        "validator" => [
            "file" => "lore/mvc/impl/ReflexiveModelValidator.php",
            "class" => "\\lore\\mvc\\ReflexiveModelValidator"
        ],
    ],
    "views" => [
        "dirs" => ["app/views", "app/views/2"],
    ],
];