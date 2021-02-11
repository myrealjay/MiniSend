<?php

namespace App\Providers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Finder\SplFileInfo;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        collect(File::allFiles(app_path('Repositories')))->each(function (SplFileInfo $file) {
            $repositoryClassFile = str_replace(['.php', '/'], ['', '\\'], $file->getRelativePathname());
            $repositoryClass = "App\\Repositories\\" . $repositoryClassFile;
            $repositoryInterface = "{$repositoryClass}Interface";

            if (class_exists($repositoryClass) && interface_exists($repositoryInterface)) {
                $this->app->bind($repositoryInterface, static function() use ($repositoryClass){
                    return new $repositoryClass();
                });
            }
        });
    }
}
