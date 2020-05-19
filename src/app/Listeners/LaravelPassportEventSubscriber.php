<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace App\Listeners;

use App\Models\User;
use App\Models\Device;
use Illuminate\Support\Facades\Request;
use Laravel\Passport\Events\AccessTokenCreated;
use Laravel\Passport\Events\RefreshTokenCreated;

/**
 * Description of UserEventSubscriber
 *
 * @author muda
 */
class LaravelPassportEventSubscriber 
{
  /**
     * Handle user login events.
     */
    public function accessTokenCreated(AccessTokenCreated $event)
    {
      $input = Request::input();
      if (isset($input['device_id']) && isset($input['type'])) {
        $input['token_id'] =  $event->tokenId;
        $input['user_id'] = $event->userId;
        $device = Device::where('device_id' ,$input['device_id'])->first();
        if (empty($device)) {
          Device::updateOrCreate(
              [
                'user_id' => $input['user_id'],
                'type' => $input['type'],
              ],
              $input
          );
        } else {
          Device::updateOrCreate(
              [
                'device_id' => $input['device_id'],
              ],
              $input
          );
        }
      }
      
      if (isset($input['timezone'])) {
        User::updateOrCreate(
            [
              'id' => $event->userId,
            ],
            [
              'timezone' => $input['timezone']
            ]
        );
      }
    }

    public function refreshAccessToken(RefreshTokenCreated $event)
    {
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'Laravel\Passport\Events\AccessTokenCreated',
            'App\Listeners\LaravelPassportEventSubscriber@accessTokenCreated'
        );
        
        $events->listen(
            'Laravel\Passport\Events\RefreshTokenCreated',
            'App\Listeners\LaravelPassportEventSubscriber@refreshAccessToken'
        );
    }
}
