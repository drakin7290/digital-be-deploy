<?php

namespace Botble\Checkin\Providers;

use Botble\Checkin\Models\Checkin;
use Illuminate\Support\ServiceProvider;
use Botble\Checkin\Repositories\Caches\CheckinCacheDecorator;
use Botble\Checkin\Repositories\Eloquent\CheckinRepository;
use Botble\Checkin\Repositories\Interfaces\CheckinInterface;
use Illuminate\Support\Facades\Event;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Illuminate\Routing\Events\RouteMatched;

class CheckinServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register()
    {
        $this->app->bind(CheckinInterface::class, function () {
            return new CheckinCacheDecorator(new CheckinRepository(new Checkin));
        });

        $this->setNamespace('plugins/checkin')->loadHelpers();
    }

    public function boot()
    {
        $this
            ->loadAndPublishConfigurations(['permissions'])
            ->loadMigrations()
            ->loadAndPublishTranslations()
            ->loadAndPublishViews()
            ->loadRoutes(['web']);

        if (defined('LANGUAGE_MODULE_SCREEN_NAME')) {
            if (defined('LANGUAGE_ADVANCED_MODULE_SCREEN_NAME')) {
                // Use language v2
                \Botble\LanguageAdvanced\Supports\LanguageAdvancedManager::registerModule(Checkin::class, [
                    'name',
                ]);
            } else {
                // Use language v1
                $this->app->booted(function () {
                    \Language::registerModule([Checkin::class]);
                });
            }
        }

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()->registerItem([
                'id'          => 'cms-plugins-checkin',
                'priority'    => 5,
                'parent_id'   => null,
                'name'        => 'plugins/checkin::checkin.name',
                'icon'        => 'fa fa-list',
                'url'         => route('checkin.index'),
                'permissions' => ['checkin.index'],
            ]);
        });
    }
}
