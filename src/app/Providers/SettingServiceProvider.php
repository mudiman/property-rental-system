<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Models\Setting;

/**
 * Description of SettingServiceProvider
 *
 * @author muda
 */
class SettingServiceProvider extends ServiceProvider {
    public function boot()
    {
        $configSetting = Cache::get('setting');
        if (empty($configSetting)) {
            $setting = Setting::first();
            $configSetting = Setting::storeInCache($setting);
        }
        foreach ($configSetting as $key => $value) {
            config(["setting.$key" => $value]);
        }
    }
}
