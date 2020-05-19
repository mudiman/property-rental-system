<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use App\Http\Validators\EmptyIfValidator;
use Illuminate\Database\Eloquent\Relations\Relation;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
      Validator::resolver(function($translator, $data, $rules, $messages, $attributes)
      {
          return new EmptyIfValidator($translator, $data, $rules, $messages, $attributes);
      });
      
      Validator::extend('poly_exists', function ($attribute, $value, $parameters, $validator) {
        if (!$objectType = array_get($validator->getData(), $parameters[0], false)) {
            return false;
        }
        $relations = Relation::morphMap();
        if (!isset($relations[$objectType])) return true;
        return !empty(resolve($relations[$objectType])->find($value));
      });
      \Stripe\Stripe::setApiKey(config('payment.stripe.secret_key'));
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app['request']->server->set('HTTPS', true);
        
        Relation::morphMap([
          'user' => \App\Models\User::class,
          'property' => \App\Models\Property::class,
          'tenancy' => \App\Models\Tenancy::class,
          'offer' => \App\Models\Offer::class,
          'score' => \App\Models\Score::class,
          'user_service' => \App\Models\UserService::class,
          'property_pro' => \App\Models\PropertyPro::class,
          'property_service' => \App\Models\PropertyService::class,
          'viewing' => \App\Models\Viewing::class,
          'viewing_request' => \App\Models\ViewingRequest::class,
          'reference' => \App\Models\Reference::class,
          'payin' => \App\Models\Payin::class,
          'payout' => \App\Models\Payout::class,
          'transaction' => \App\Models\Transaction::class,
          'like' => \App\Models\Like::class,
        ]);
        
    }
}
