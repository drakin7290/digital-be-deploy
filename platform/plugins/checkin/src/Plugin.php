<?php

namespace Botble\Checkin;

use Illuminate\Support\Facades\Schema;
use Botble\PluginManagement\Abstracts\PluginOperationAbstract;

class Plugin extends PluginOperationAbstract
{
    public static function remove()
    {
        Schema::dropIfExists('checkins');
        Schema::dropIfExists('checkins_translations');
    }
}
