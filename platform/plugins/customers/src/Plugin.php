<?php

namespace Botble\Customers;

use Illuminate\Support\Facades\Schema;
use Botble\PluginManagement\Abstracts\PluginOperationAbstract;

class Plugin extends PluginOperationAbstract
{
    public static function remove()
    {
        Schema::dropIfExists('customers');
        Schema::dropIfExists('customers_translations');
    }
}
