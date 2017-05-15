<?php
return [
    "router" => [
        "file" => "lore/mvc/PrettyUrlMvcRouter.php",
        "class" => "\\lore\\mvc\\PrettyUrlMvcRouter",
    ],

    "resourcesManager" => [
       "file" => "lore/web/DefaultResourcesManager.php",
        "class" => "\\lore\\web\\DefaultResourcesManager",

        "allow" => ["*.css", "*.js", "*.jpe?g", "*.png", "*.gif", "*.pdf"],
        "deny" => ["*.sql"]
    ],

    "responseManager" => [
        "file" => "lore/web/DefaultResponseManager.php",
        "class" => "\\lore\\web\\DefaultResponseManager",

        "service" => [
            "defaultType" => "txt" //can be: html, json, txt or xml
        ]
    ],
];