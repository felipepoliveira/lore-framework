<?php
return [
    "controllers" => [
        "dirs" => ["app/controllers", "app/controllers/api"],

        "api" => [
            "defaultType" => "json" //Can be: html; json; raw; xml;
        ],
    ],

    "models" => [
        "validator" => [
            "file" => "lore/mvc/ReflexiveValidator.php",
            "class" => "\\lore\\mvc\\ReflexiveValidator"
        ],
    ],

    "views" => [
        "dirs" => ["app/views"],
    ],
];