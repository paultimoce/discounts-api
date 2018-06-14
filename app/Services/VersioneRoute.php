<?php namespace App\Services;

use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class VersionedRoute
{
    /**
     * @param $controllerName
     * @param null $action
     * @return string
     */
    public static function getControllerClassPath($controllerName, $action = null)
    {
        $controller = app()->make($controllerName);
        $fullPath = get_class($controller);

        $path = explode('Http\Controllers\\', $fullPath)[1];

        if (!empty($action)) {
            $path .= '@'.$action;
        }

        return $path;
    }

    public static function rebuildControllerConfig()
    {
        $files = Storage::disk('apiControllers')->files();

        $controllers = array_map(function($file){
            return "'".explode('.php', $file)[0]."'";
        }, $files);

        $config = "<?php return [".PHP_EOL.PHP_EOL;
        $config .= implode(",".PHP_EOL, $controllers);
        $config .= PHP_EOL.PHP_EOL."];";

        Storage::disk('config')->put('versionedControllers.php', $config);
        return $controllers;
    }


}