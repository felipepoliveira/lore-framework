<?php
namespace lore\web;

/**
 * Represent an route rule that is defined in the configuration file: project => router => rules.
 * The rules can be:
 * ------------------------------------------------
 * Prefixed: An valid uri that ends with '*'
 * uri: api/*
 * use: "rest/*" => "app/controllers/rest/*"
 * ===
 * Example 1
 * configured uri: "rest/v1/*" => "app/controllers/rest/v1/*" (all uri that starts with "rest/v1" will be handled by
 * any script in "app/controllers/rest/v1" folder
 * requested uri: "rest/v1/user"
 * produces the uri: user/
 * to be handles by
 *===
 * Example 2
 * configured uri: "adm/*" => "app/controllers/view/AdminController.php" (all uri that starts with "adm/*" will be
 * handled specifically by the script "app/controllers/view/AdminController.php"
 * requested uri: "adm/user/delete/1"
 * produces the uri: "user/delete/1"
 *
 * @package lore\web
 */
class RouteRule
{

    /*
     * The prefix tag
     */
    protected const PREFIX_TAG = "/*";

    /**
     * The raw uri requested by the client
     * @var string
     */
    private $rawUri;

    /**
     * The processed uri by the rule
     * @var string
     */
    private $producedUri;

    /**
     * Scripts that will handle the request
     * @var array
     */
    private $scripts = [];

    /**
     * The request made by the client
     * @var Request
     */
    private $request;


    function __construct(Request $request, $uri, $scripts)
    {
        $this->digestUri($request, $uri, $scripts);
    }

    protected function digestUri(Request $request, $uri, $scripts){
        if(self::isValidUri($uri)){
            //Store the request of the client
            $this->request = $request;
            $this->scripts = $scripts;

            //Check special cases
            if(self::isPrefixed($uri)){
                echo "Is prefixed";
            }
        }else{
            throw new \Exception("The uri $uri is not a valid URI");
        }
    }

    public static function isPrefixed($uri){
        return strrpos($uri, self::PREFIX_TAG) === (strlen($uri) - strlen(self::PREFIX_TAG));
    }

    public static function producePrefixedUri($uri){

    }

    public static function isValidUri($uri){
        return true;
    }
}