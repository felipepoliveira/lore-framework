<?php
return [
    "router" => [
        "file" => "lore/mvc/PrettyUrlMvcRouter.php",
        "class" => "\\lore\\mvc\\PrettyUrlMvcRouter",
    ],

    "responseManager" => [
        "file" => "lore/core/DefaultResponseManager.php",
        "class" => "\\lore\\DefaultResponseManager",

        "service" => [
            "defaultType" => "txt" //can be: html, json, txt or xml
        ]
    ],
];