<?php
return [

    /*
     * PACKAGE: Object
     * FROM: core
     * The object module has the objective of automatize some repetitive work with objects, like:
     * *Load object data from request sent by the user;
     * *Validations;
     * *Array serialization
     */
    "object" => [

        /*
         * MODULE: ObjectLoader
         * The ObjectLoader make automatic loading from data sent by the request of the client and
         * general serialization
         */
        "loader" => [
            "file" => "lore/core/impl/ReflexiveObjectLoader.php",
            "class" => "\\lore\\ReflexiveObjectLoader",
        ],

        /*
         * MODULE: ObjectValidator
         * The ObjectValidator make automatic validations of an object
         */
        "validator" => [
            "file" => "lore/core/impl/ReflexiveObjectValidator.php",
            "class" => "\\lore\\ReflexiveObjectValidator"
        ],
    ],

    /*
     * STATIC COMPONENT: Response
     * FROM: web
     * Store data of the response that will be sent to the client. Data that could be found in a response object:
     * *Response code;
     * *Data that will be sent to the client;
     * *Errors in the client request;
     * *HTTP Headers;
     */
    "response" => [
      "defaultCharset" => "utf-8"
    ],

    /*
     * MODULAR COMPONENT: Router
     * FROM: web
     * Object that will handle the request sent by the client to some PHP script to handle an give an Response.
     * It also stores and handles: web filters; URI rules and etc.
     */
    "router" => [
        "file" => "lore/mvc/impl/PrettyUrlMvcRouter.php",
        "class" => "\\lore\\mvc\\PrettyUrlMvcRouter",
        //"file" => "lore/web/impl/ScriptBasedRouter.php",
        //"class" => "lore\\web\\ScriptBasedRouter",


        /*
         * CONFIGURATION: URI Rules
         * OF: Router
         * Store URI rules that will be implemented and handled by the Router. This rules could works in an
         * map(string => string) where:
         * The key: Is the URI rule
         * The value: The script(s) that will handle this rule
         */
        "rules" => [

            "*/suffix" => ["app/scripts/Script.php"],
            "/prefix/*" => ["app/scripts/Script.php"],
            "*/key/value/*" => ["app/scripts/Script.php"],
            "/specific" => ["app/scripts/Script.php"],
            "/test/*" => ["app/scripts/TestScript.php"],

            /*
             * Prefixed URI:
             * All URIs that starts with '/rest/v1/*' will be handled by scripts in 'app/controllers/rest/v1'
             */
            "/rest/v1/*" => ["app/controllers/rest/v1"],

            /*
             *Suffixed URI:
             * All URIs that ends with '/adm' will be handled by scripts in 'app/controllers/view/admin/*'
             */
            "*/adm" => ["app/controllers/view/admin/*"],

            /*
             *KeyValue URI:
             * All URIs that contains '/admin/' will be handles by scripts in 'app/controllers/view/admin/'
             */
            "*/admin/*" => ["app/controllers/view/admin/*"],

            /*
             * Specific URI:
             * A Simple route configuration. When the URI is equals 'register' it will be handle by
             * the 'Script.php'
             */
            "/register" => ["app/scripts/Script.php"],
        ],

        /*
         * CONFIGURATION: Web Filters
         * OF: Router
         * Store filters to be applied in the client request
         */
        "filter" => [
            "file" => "lore/web/impl/DefaultFilterRouter.php",
            "class" => "\\lore\\web\\impl\\DefaultFilterRouter",

            "*" => ["app/filters/general/*"],
            "/auth/*" => ["app/filters/AuthenticationFilter.php"],
            "*/auth" => ["app/filters/AuthenticationFilter.php"],
        ],
    ],

    /*
     * MODULAR COMPONENT: Resources Manager
     * FROM: web
     * Manage the server resources requested by the client. With this component the server can:
     * *Restrict files that will be sent to the client;
     * *Restrict pure PHP Scripts executions;
     */
    "resourcesManager" => [
       "file" => "lore/web/impl/DefaultResourcesManager.php",
        "class" => "\\lore\\web\\DefaultResourcesManager",

        /*
         * Script processing configurations:
         * allowScriptProcessing: Flag that indicates if the resource manager will allow PHP script execution
         * scriptExtensions: Define PHP Script file extensions
         */
        "allowScriptProcessing" => false,
        "scriptExtensions" => ["inc", "php"],

        /*
         * Mode
         * "allow" => Allow all kinds of file, minus the files that matches the values in "exceptions" array
         * "deny" => Deny all kinds of file, minus the files that matches the values in "exceptions" array
         */
        "mode" => "deny",
        "exceptions" => [".*\\.css", ".*\\.gif", ".*\\.jpg", ".*\\.js", ".*\\.pdf", ".*\\.png"],
    ],

    /*
     * MODULAR COMPONENT: Response Manager
     * FROM: Web
     * Manage the response created by the server framework's components.
     * This module handles:
     * *When the client request an view page;
     * *When the client request a resource (it only send the resource and does not make any filter. Filtering resources
     * is an task of ResourcesManager);
     * *Return an response if the client consumes an webservice like service;
     *
     */
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