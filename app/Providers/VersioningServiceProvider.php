<?php namespace App\Providers;

use App\Services\VersionedRoute;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class VersioningServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function register()
    {
        $versionedControllers = config('versionedControllers', []);

        foreach ($versionedControllers as $controllerName) {
            $segments = explode('/',app()->request->getPathInfo());

            $version = ($segments[1] == 'api' && !empty($segments[2])) ? $segments[2] : 'v1';

            //remove the v prefix and cast to int
            $temp = explode('v', $version);
            $version = (isset($temp[1])) ? $temp[1] : 1;

            $this->bindControllerInstanceBasedOnVersion($version, $controllerName);
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    protected function bindControllerInstanceBasedOnVersion($version, $controllerName){
        $className = 'App\\Http\\Controllers\\Api\\V' . $version . '\\' . $controllerName;

        while (!class_exists($className)) {
            if ($version > 1) {
                $className = 'App\\Http\\Controllers\\Api\\V' . $version . '\\' . $controllerName;
            } else {
                $className = 'App\\Http\\Controllers\\Api\\'.$controllerName;
                break;
            }

            $version--;
        }

        // Do the actual binding
        app()->singleton($controllerName, $className);

        Log::debug('Bound '.$controllerName. ' to the '.$className.' concrete instance');
    }
}
