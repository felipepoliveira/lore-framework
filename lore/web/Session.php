<?php
/**
 * Created by PhpStorm.
 * User: Felipe
 * Date: 16/05/2017
 * Time: 09:28
 */

namespace lore\web;


abstract class Session
{
    /**
     * Return an flag indicating if an value exists in session. If the session is no open it returns false
     * @param $key
     * @return bool
     */
    public static function contains($key){
        if(self::isOpen()) {
            return isset($_SESSION[$key]);
        }else{
            return false;
        }
    }


    /**
     * Destroy and closes the session
     */
    public static function destroy(){
        session_destroy();
    }

    /**
     * Return an value from session. If the session is not open it returns null
     * @param $key string
     * @return mixed|null
     */
    public static function get($key){
        if(Session::isOpen()){
            return $_SESSION[$key];
        }
        return null;
    }

    /**
     * Check if the session is open
     * @return bool
     */
    public static function isOpen()
    {
        return session_status() === PHP_SESSION_ACTIVE;
    }

    /**
     * Open the session
     */
    public static function open(){
        if(!Session::isOpen()){
            session_start();
        }
    }

    /**
     * Open the session and put an value onto it
     * @param $key
     * @param $value
     */
    public static function put($key, $value)
    {
        Session::open();
        $_SESSION[$key] = $value;
    }
}