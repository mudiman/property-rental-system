<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace App\Listeners;

use App\Models\User;
/**
 * Description of UserEventSubscriber
 *
 * @author muda
 */
class UserModelEventSubscriber 
{
  /**
     * Handle user login events.
     */
    public function onUserCreate($event) {}

    /**
     * Register the listeners for the subscriber.
     *
     * @param  Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'eloquent.created: App\Models\User',
            'App\Listeners\UserModelEventSubscriber@onUserCreate'
        );
    }
}
