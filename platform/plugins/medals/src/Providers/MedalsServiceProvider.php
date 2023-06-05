<?php

namespace Botble\Medals\Providers;

use Botble\Medals\Models\Medals;
use Illuminate\Support\ServiceProvider;
use Botble\Medals\Repositories\Caches\MedalsCacheDecorator;
use Botble\Medals\Repositories\Eloquent\MedalsRepository;
use Botble\Medals\Repositories\Interfaces\MedalsInterface;
use Illuminate\Support\Facades\Event;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Illuminate\Routing\Events\RouteMatched;

class MedalsServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register()
    {
        $this->app->bind(MedalsInterface::class, function () {
            return new MedalsCacheDecorator(new MedalsRepository(new Medals));
        });

        $this->setNamespace('plugins/medals')->loadHelpers();
    }

    public function boot()
    {
        $this
            ->loadAndPublishConfigurations(['permissions'])
            ->loadMigrations()
            ->loadAndPublishTranslations()
            ->loadAndPublishViews()
            ->loadRoutes(['web', 'api']);

        // if (defined('LANGUAGE_MODULE_SCREEN_NAME')) {
        //     if (defined('LANGUAGE_ADVANCED_MODULE_SCREEN_NAME')) {
        //         // Use language v2
        //         \Botble\LanguageAdvanced\Supports\LanguageAdvancedManager::registerModule(Medals::class, [
        //             'name',
        //         ]);
        //     } else {
        //         // Use language v1
        //         $this->app->booted(function () {
        //             \Language::registerModule([Medals::class]);
        //         });
        //     }
        // }

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()->registerItem([
                'id'          => 'cms-plugins-medals',
                'priority'    => 5,
                'parent_id'   => null,
                'name'        => 'plugins/medals::medals.name',
                'icon'        => 'fa fa-list',
                'url'         => route('medals.index'),
                'permissions' => ['medals.index'],
            ]);
        });
    }
}
