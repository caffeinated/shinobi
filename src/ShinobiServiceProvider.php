<?php

namespace Caffeinated\Shinobi;

use Exception;
use Illuminate\Support\Facades\Gate;
use Caffeinated\Shinobi\Models\Role;
use Illuminate\Support\Facades\Blade;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Caffeinated\Shinobi\Facades\Shinobi;
use Caffeinated\Shinobi\Models\Permission;

class ShinobiServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return null
     */
    public function boot()
    {
        $this->publishConfig();
        $this->publishMigrations();
        $this->loadMigrations();

        $this->registerGates();
        $this->registerBladeDirectives();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/shinobi.php', 'shinobi'
        );

        $this->app->singleton('shinobi', function ($app) {
            $auth = $app->make('Illuminate\Contracts\Auth\Guard');

            return new \Caffeinated\Shinobi\Shinobi($auth);
        });
    }

    /**
     * Register the permission gates.
     * 
     * @return void
     */
    protected function registerGates()
    {
        Gate::before(function($user, $permission) {
            try {
                if (method_exists($user, 'hasPermissionTo')) {
                    $permission = Shinobi::permission()->where('slug', $permission)->firstOrFail();

                    return $user->hasPermissionTo($permission) ?: null;
                }
            } catch (Exception $e) {
                // 
            }
        });
    }

    /**
     * Register the blade directives.
     *
     * @return void
     */
    protected function registerBladeDirectives()
    {
        Blade::directive('role', function ($expression) {
            return "<?php if (\\Shinobi::isRole({$expression})): ?>";
        });

        Blade::directive('endrole', function ($expression) {
            return '<?php endif; ?>';
        });
    }

    /**
     * Publish the config file.
     * 
     * @return void
     */
    protected function publishConfig()
    {
        $this->publishes([
            __DIR__.'/../config/shinobi.php' => config_path('shinobi.php'),
        ], 'config');
    }

    /**
     * Publish the migration files.
     * 
     * @return void
     */
    protected function publishMigrations()
    {
        $this->publishes([
            __DIR__.'/../migrations/' => database_path('migrations'),
        ], 'migrations');
    }

    /**
     * Load our migration files.
     * 
     * @return void
     */
    protected function loadMigrations()
    {
        if (config('shinobi.migrate', true)) {
            $this->loadMigrationsFrom(__DIR__.'/../migrations');
        }
    }
}
