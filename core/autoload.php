<?php
if (!function_exists('str_contains')) {
    function str_contains(string $haystack, string $needle): bool
    {
        return '' === $needle || false !== strpos($haystack, $needle);
    }
}
spl_autoload_register(function ($class){
    if (file_exists($class.".php")) include_once($class.".php");
    else{
        $class = str_replace("\\",DIRECTORY_SEPARATOR,$class);
        $path = (str_contains(strtolower($class), "controller"))
            ? ".".DIRECTORY_SEPARATOR."core".DIRECTORY_SEPARATOR."Controllers".DIRECTORY_SEPARATOR."$class.php"
            : ".".DIRECTORY_SEPARATOR."core".DIRECTORY_SEPARATOR."$class.php";

        if (file_exists($path)){
            include_once($path);
        }else{
            die("Error loading class $path");
        }
    }
});
function loadSDK()
{
    $SDKList = dirForScan("core/SDK");
    foreach ($SDKList[1] as $SDK)
        require_once __DIR__ . DIRECTORY_SEPARATOR . "SDK" . DIRECTORY_SEPARATOR . $SDK;
}