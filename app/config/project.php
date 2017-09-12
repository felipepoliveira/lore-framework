<?php
return [
    "response" => [
      "defaultCharset" => "utf-8"
    ],

    "router" => [
        "file" => "lore/mvc/impl/PrettyUrlMvcRouter.php",
        "class" => "\\lore\\mvc\\PrettyUrlMvcRouter",
        "rules" => [
            "/rest/v1/*" => ["app/controllers/rest/v1"], //Any URI the starts with "rest/v1/" will be handled by files inside "app/controllers/api/rest/v1/"
            "/aUniqueURI" => "app/controllers/view/ASpecialController.php", // //The URI "aUniqueURI" will be handled by ASpecialController.php
        ],

        "filter" => [
            "file" => "lore/web/impl/DefaultFilterRouter.php",
            "class" => "\\lore\\web\\impl\\DefaultFilterRouter",

            "*" => "app/filters/DefaultFilter.php", //Any URI will be filtered by the "DefaultFilter.php"
            "app/*" => "app/filters/AppFilter.php", //Any URI that starts with "app/*" will be handled by the "AppFilter.php"
            "*/auth/*" => "app/filters/AuthenticationFilter.php", //Any URI that contains "auth" will be handled by the "AuthenticationFilter.php"
        ],
    ],

    "resourcesManager" => [
       "file" => "lore/web/impl/DefaultResourcesManager.php",
        "class" => "\\lore\\web\\DefaultResourcesManager",

        /*
         * Script processing configurations:
         * allowScriptProcessing: Flag that indicates if the resource manager will process php scripts
         * scriptExtensions: Define file php script file extensions
         */
        "allowScriptProcessing" => true,
        "scriptExtensions" => ["inc", "php"],

        /*
         * Mode
         * "allow" => Allow all kinds of file, minus the files that matches the values in "exceptions" array
         * "deny" => Deny all kinds of file, minus the files that matches the values in "exceptions" array
         */
        "mode" => "deny",
        "exceptions" => [".*\\.css", ".*\\.gif", ".*\\.jpg", ".*\\.js", ".*\\.pdf", ".*\\.png"],
    ],

    "responseManager" => [
        "file" => "lore/web/impl/DefaultResponseManager.php",
        "class" => "\\lore\\web\\DefaultResponseManager",

        //The data formatter handler
        "dataFormatter" => [
            "file" => "lore/web/impl/DefaultDataFormatter.php",
            "class" => "lore\\web\\DefaultDataFormatter",

            "defaultFormatType" => "json" //can be: json, txt or xml
        ],
    ],

    "stringProvider" => [
        "file" => "lore/core/impl/DefaultStringProvider.php",
        "class" => "\\lore\\DefaultStringProvider",

        "dirs" => ["app/strings"],
    ],
];