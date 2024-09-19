<?php
require_once "helper.php";
$Database = new Database\Database;
loadSDK();
$Database::checkForErrorsAndThrow();
$Templater = new Templater\Templater;
$Router = new Router\Router;
if (isset(PaperWork::session()["id"]))
{
    function unsetExcept($keys) {
        foreach (PaperWork::session() as $key => $value)
            if (!in_array($key, $keys))
                unset($_SESSION[$key]);
    }
    // AUTO UPDATING USERS DATA
    $__AQUA_SESSION = $Database::query("SELECT * FROM `users` WHERE id = " . PaperWork::session()["id"]);
    $res = method_exists($__AQUA_SESSION, "fetchArray") ? $__AQUA_SESSION->fetchArray() : null;
    unsetExcept(["flash_message"]);
    if (is_array($res)) {
        foreach ($res as $k => $value) {
            if ($k == "0")
                continue;
            if (!((int) $k))
                $_SESSION[$k] = $value;
        }
    }
}