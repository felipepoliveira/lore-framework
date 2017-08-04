<?php
return [
    "response" => [
      "defaultCharset" => "utf-8"
    ],

    "router" => [
        "file" => "lore/mvc/impl/PrettyUrlMvcRouter.php",
        "class" => "\\lore\\mvc\\PrettyUrlMvcRouter",
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
         * default: deny
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