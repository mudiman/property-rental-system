<?php 

namespace App\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Log;
use Event;

class EnvironmentServiceProvider extends ServiceProvider
{
    /**
     * List of Local Environment Providers
     * @var array
     */
    protected $localProviders = [
      \Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class,
      \InfyOm\Generator\InfyOmGeneratorServiceProvider::class,
    ];
    
    protected $adminProviders = [
      \Maatwebsite\Excel\ExcelServiceProvider::class,
      \Appointer\Swaggervel\SwaggervelServiceProvider::class,
      \InfyOm\AdminLTETemplates\AdminLTETemplatesServiceProvider::class,
      \Collective\Html\HtmlServiceProvider::class,
    ];

    /**
     * List of only Local Environment Facade Aliases
     * @var array
     */
    protected $facadeAliases = [
        'Debugbar' => \Barryvdh\Debugbar\Facade::class,
        'Excel' => \Maatwebsite\Excel\Facades\Excel::class,
    ];
    protected $adminfacadeAliases = [
      'Excel' => \Maatwebsite\Excel\Facades\Excel::class,
      'Form'  => \Collective\Html\FormFacade::class,
      'Html'  => \Collective\Html\HtmlFacade::class,
    ];

    /**
     * Bootstrap the application services.
     * @return void
     */
    public function boot() {
      if ($this->app->environment() == 'local') {
          $this->registerServiceProviders($this->localProviders);
          $this->registerFacadeAliases($this->facadeAliases);
      }
      if (in_array($this->app->environment(),['local', 'dev', 'stage', 'liveadmin'])) {
          $this->registerServiceProviders($this->adminProviders);
          $this->registerFacadeAliases($this->adminfacadeAliases);
      }
    }

    /**
     * Register the application services.
     * @return void
     */
    public function register() {
      if (config('app.debug') == true) {
          Event::listen('Illuminate\Database\Events\QueryExecuted', function ($query) {
          Log::debug($query->sql);
          Log::debug($query->bindings);
          Log::debug($query->time);
        });
      }
    }

    /**
     * Load local service providers
     */
    protected function registerServiceProviders($providers) {
        foreach ($providers as $provider) {
            $this->app->register($provider);
        }
    }

    /**
     * Load additional Aliases
     */
    public function registerFacadeAliases($facadeAliases) {
        $loader = AliasLoader::getInstance();
        foreach ($facadeAliases as $alias => $facade) {
            $loader->alias($alias, $facade);
        }
    }
}