<?php

namespace Botble\Medals;

use Illuminate\Support\Facades\Schema;
use Botble\PluginManagement\Abstracts\PluginOperationAbstract;

class Plugin extends PluginOperationAbstract
{
    public static function remove()
    {
        Schema::dropIfExists('medals');
        Schema::dropIfExists('medals_translations');
    }
}
