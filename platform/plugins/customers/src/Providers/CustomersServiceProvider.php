<?php

namespace Botble\Customers\Providers;

use Botble\Customers\Models\Customers;
use Illuminate\Support\ServiceProvider;
use Botble\Customers\Repositories\Caches\CustomersCacheDecorator;
use Botble\Customers\Repositories\Eloquent\CustomersRepository;
use Botble\Customers\Repositories\Interfaces\CustomersInterface;
use Illuminate\Support\Facades\Event;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Illuminate\Routing\Events\RouteMatched;

class CustomersServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register()
    {
        $this->app->bind(CustomersInterface::class, function () {
            return new CustomersCacheDecorator(new CustomersRepository(new Customers));
        });

        $this->setNamespace('plugins/customers')->loadHelpers();
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
        //         \Botble\LanguageAdvanced\Supports\LanguageAdvancedManager::registerModule(Customers::class, [
        //             'name',
        //         ]);
        //     } else {
        //         // Use language v1
        //         $this->app->booted(function () {
        //             \Language::registerModule([Customers::class]);
        //         });
        //     }
        // }

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()->registerItem([
                'id'          => 'cms-plugins-customers',
                'priority'    => 5,
                'parent_id'   => null,
                'name'        => 'plugins/customers::customers.name',
                'icon'        => 'fa fa-list',
                'url'         => route('customers.index'),
                'permissions' => ['customers.index'],
            ]);
        });
    }
}
