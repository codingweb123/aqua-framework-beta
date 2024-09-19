<?php
/**
 * @param $name
 * @return void
 */
function component($name): void
{
    include getcwd() . "resources" . DIRECTORY_SEPARATOR . "compiled" . DIRECTORY_SEPARATOR . "components" . DIRECTORY_SEPARATOR . "$name.aq.php";
}
/**
 * @param string $viewname
 * @param $vars
 * @return void
 */
function view(string $viewName, $vars = null): void
{
    $viewNameMd5 = md5((str_contains($viewName, ".") ? str_replace(".", DIRECTORY_SEPARATOR, $viewName) : $viewName).".aq.php");
    $path = getcwd() . DIRECTORY_SEPARATOR . "resources" . DIRECTORY_SEPARATOR . "compiled" . DIRECTORY_SEPARATOR . "$viewNameMd5.aq.php";
    if (file_exists($path)) {
        if (!$vars) include_once $path;
        else {
            $__AQUA_VARIABLES = [];
            extract($vars);
            foreach ($vars as $key => $var) {
                if ($key == "slot") $__AQUA_VARIABLES[$key][$var[0]] = $var[1];
                else $__AQUA_VARIABLES[$key] = $var;
            }
            include_once $path;
        }
    } else ddd("View Error: \nView $viewName (DEV: $viewNameMd5) cannot be loaded\nFile not found resources/views/$viewName.aq.php ");
}
/**
 * @param array $giveVars
 * @param string $name
 * @return bool
 */
function getSlot(array $giveVars, string $name): bool
{
    return isset($giveVars["slot"][$name]);
}
/**
 * @param string $message
 * @return void
 */
function flashMessage(string $message): void
{
    $_SESSION["flash_message"] = $message;
}
/**
 * @param bool $sleep
 * @return void
 */
function removeFlashMessage(bool $sleep = true): void
{
    if ($sleep) usleep(200);
    unset($_SESSION["flash_message"]);
}
/**
 * @param $var
 * @return bool|string
 */
function echoVarOrJson($var): bool|string
{
    return is_string($var) ? $var : json_encode($var, true);
}
/**
 * @param $variables
 * @param $variable
 * @return false|string
 */
function checkVariableAndLoad($variables, $variable): bool|string
{
    if (isset($variables[$variable])) return echoVarOrJson($variables[$variable]);
    elseif (isset($variable)) return echoVarOrJson($variable);
    else return false;
}
/**
 * @param array $views
 * @param bool|null $vars
 * @return void
 */
function views(array $views, bool|null $vars = null): void
{
    foreach ($views as $view) $vars ? view($view, $vars) : view($view);
}
/**
 * @param $any
 * @return mixed
 */
function urlencoded_recursive($any): mixed
{
    if (is_string($any)) {
        return preg_replace('/[\x00-\x1F\x7F]/u', '', nl2br(decodeVulnerables(str_replace("\\", "/", str_replace("`", "'", str_replace('"', "'", $any))))));
    } elseif (is_array($any)) {
        $result = [];
        foreach ($any as $key => $value) {
            $result[decodeVulnerables(str_replace("\\", "/", str_replace("`", "'", str_replace('"', "'", $key))))] = urlencoded_recursive($value);
        }
        return $result;
    } elseif (is_object($any)) {
        $result = new stdClass();
        foreach ($any as $key => $value) {
            $result->{decodeVulnerables(str_replace("\\", "/", str_replace("`", "'", str_replace('"', "'", $key))))} = urlencoded_recursive($value);
        }
        return $result;
    } else return $any;
}
/**
 * @param string $string
 * @return string
 */
function encodeVulnerables(string $string): string
{
    $replaces = [
        "'" => "{QUOTE}",
        "\"" => "{DOUBLE-QUOTE}",
        "`" => "{APOSTROPHE}",
        "<" => "{HTML-ARROW-LEFT}",
        ">" => "{HTML-ARROW-RIGHT}"
    ];
    return htmlspecialchars(str_replace(array_keys($replaces), array_values($replaces), $string));
}
function decodeVulnerablesInArray($array)
{
    foreach ($array as $key => $value) $array[$key] = decodeVulnerables($value);
    return $array;
}
function encodeVulnerablesInArray($array)
{
    foreach ($array as $key => $value)
        if (!is_array($array[$key]))
            $array[$key] = encodeVulnerables($value);
        else
            $array[$key] = encodeVulnerablesInArray($value);
    return $array;
}
/**
 * @param $string
 * @return string
 */
function decodeVulnerables($string): string
{
    $replaces = [
        "'" => "{QUOTE}",
        "\"" => "{DOUBLE-QUOTE}",
        "`" => "{APOSTROPHE}",
        " " => "{HTML-ARROW-LEFT}",
        "  " => "{HTML-ARROW-RIGHT}"
    ];
    foreach ($replaces as $key => $replace) $string = str_replace($replace, $key, $string);
    return $string;
}
/**
 * @param $string
 * @return string
 */
function decodeVulnerablesWithHTML($string): string
{
    $replaces = [
        "'" => "{QUOTE}",
        "\"" => "{DOUBLE-QUOTE}",
        "`" => "{APOSTROPHE}",
        "<" => "{HTML-ARROW-LEFT}",
        ">" => "{HTML-ARROW-RIGHT}"
    ];
    foreach ($replaces as $key => $replace) $string = str_replace($replace, $key, $string);
    return $string;
}
/**
 * @param ...$vars
 * @return array
 */
function wcompact(...$vars): array
{
    $varsArray = [];
    foreach ($vars as $_ => $var) {
        global $$var;
        $GLOBALS[$var] = $$var;
        $varsArray[$var] = $$var;
    }
    return $varsArray;
}
/**
 * @return string
 */
function get_root_path(): string
{
    $filePath = str_replace(getcwd(), "", getcwd());
    $filePathSlashes = explode(DIRECTORY_SEPARATOR, $filePath);
    unset($filePathSlashes[0]);
    return str_repeat(".." . DIRECTORY_SEPARATOR, count($filePathSlashes));
}
/**
 * @param string|null $any
 * @return string
 */
function resource(string|null $any = null): string
{
    $any = ltrim(rtrim($any, "/"), "/");
    $url = (isset($_SERVER["HTTPS"]) && ($_SERVER["HTTPS"] == 1 || $_SERVER["HTTPS"] == "on"))
        ? "https://" . $_SERVER["SERVER_NAME"] . str_replace("index.php", "", $_SERVER["SCRIPT_NAME"])
        : "http://" . $_SERVER["SERVER_NAME"] . str_replace("index.php", "", $_SERVER["SCRIPT_NAME"]);
    return $any ? $url . $any : $url;
}
/**
 * @param ...$names
 * @return bool|array
 */
function dirForScan(...$names): bool|array
{
    $migrationsPath = get_root_path();
    foreach ($names as $name) $migrationsPath .= $name.DIRECTORY_SEPARATOR;
    $migrations = scanAllDirs($migrationsPath);
    return [$migrationsPath, $migrations];
}
function scanAllDirs($dir): array
{
    $result = [];
    foreach(scandir($dir) as $filename) {
        if ($filename[0] === '.') continue;
        $filePath = $dir . DIRECTORY_SEPARATOR . $filename;
        if (is_dir($filePath)) {
            foreach (scanAllDirs($filePath) as $childFilename) {
                $result[] = $filename . DIRECTORY_SEPARATOR . $childFilename;
            }
        } else {
            $result[] = $filename;
        }
    }
    return $result;
}
/**
 * @param ...$names
 * @return string
 */
function get_path_to_file(...$names): string
{
    $path = get_root_path();
    foreach ($names as $name) $path .= $name.DIRECTORY_SEPARATOR;
    return $path;
}
class PaperWork{
    public static function get(): array
    {
        return encodeVulnerablesInArray($_GET);
    }
    public static function post(): array
    {
        return encodeVulnerablesInArray($_POST);
    }
    public static function ip(): string|null
    {
        $userInfo = new UserInfo();
        $ip = $userInfo->get_ip();
        return ($ip != "UNKNOWN" && $ip != "127.0.0.1") ? $ip : null;
    }
    public static function server(): array
    {
        return encodeVulnerablesInArray($_SERVER);
    }
    public static function session(): array
    {
        return encodeVulnerablesInArray($_SESSION);
    }
    public static function cookie(): array
    {
        return encodeVulnerablesInArray($_COOKIE);
    }
    public static function vars(): array
    {
        return $__AQUA_VARIABLES ?? [];
    }
    public static function config(): array
    {
        global $config;
        return $config ?? [];
    }
    public static function returnVars($vars): array
    {
        return $vars;
    }
    public static function createDataToSQL(array $data): array
    {
        return [implode(",", array_keys($data)), "'" . implode("','", array_values($data)) . "'"];
    }
    public static function updateDataToSQL(array $data): string
    {
        $SQL = "SET ";
        $i = 0;
        $count = count($data);
        foreach ($data as $key => $value)
        {
            $i != $count - 1 
                ? $SQL .= $key ."='". $value ."', "
                : $SQL .= $key ."='". $value ."'";
            $i++;
        }
        return $SQL;
    }
}