<?php namespace WebReinvent\LvTags;

use Illuminate\Support\ServiceProvider;
use WebReinvent\LvTags\Entities\Tag;
use WebReinvent\LvTags\Observers\CrudObserver;

class LvTagsServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot() {

        $this->handleConfigs();
        $this->handleMigrations();
        // $this->handleViews();
        // $this->handleTranslations();
        // $this->handleRoutes();
        $this->handleObservers();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {

        // Bind any implementations.

    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides() {

        return [];
    }

    private function handleConfigs() {

        $configPath = __DIR__ . '/Config/lvtags.php';

        $this->publishes([$configPath => config_path('lvtags.php')], 'config');

        $this->mergeConfigFrom($configPath, 'lvtags');
    }

    private function handleTranslations() {

        $this->loadTranslationsFrom(__DIR__.'/Resources/lang', 'lvtags');
    }

    private function handleViews() {

        $this->loadViewsFrom(__DIR__.'/Resources/views', 'lvtags');

        $this->publishes([__DIR__.'/Resources/views' => base_path('resources/views/vendor/lvtags')], 'views');
    }

    private function handleMigrations() {

        $this->publishes([__DIR__ . '/Database/Migrations' => database_path('migrations')],  'migrations');
    }

    private function handleRoutes() {

        include __DIR__.'/Routes/web.php';
        include __DIR__.'/Routes/api.php';

    }


    private function handleObservers()
    {
        Tag::observe(CrudObserver::class);
    }

}
