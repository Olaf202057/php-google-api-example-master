<?php

namespace common;

require_once  dirname(__DIR__) . "/vendor/autoload.php";

class GoogleApiExample
{
    static function googleApiAutoloader($class_name)
    {
        $class_name = ltrim($class_name, '\\');
        $file_name ='';
        if ($lastNsPos = strripos($class_name, '\\')) {
            $namespace = substr($class_name, 0, $lastNsPos);
            $class_name = substr($class_name, $lastNsPos + 1);
            $file_name = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
        }
        $file_name .= str_replace('_', DIRECTORY_SEPARATOR, $class_name) . '.php';
        $google_dir = dirname(__DIR__) . "/$file_name";
        if (file_exists($google_dir)) {
            include $google_dir;
            return 1;
        }
        return 0;

    }
}

spl_autoload_register('common\GoogleApiExample::googleApiAutoloader', true, true);




