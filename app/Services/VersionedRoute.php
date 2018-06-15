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
}