<?php

namespace App\Providers;


class FractalServiceProvider extends \Illuminate\Support\ServiceProvider {

  /**
   * Define your route model bindings, pattern filters, etc.
   *
   * @return void
   */
  public function boot() {
    $fractal = $this->app->make('League\Fractal\Manager');

    response()->macro('item', function ($item, \League\Fractal\TransformerAbstract $transformer, $status = 200, array $headers = []) use ($fractal) {
      $resource = new \League\Fractal\Resource\Item($item, $transformer);
      return $resource;
    });

    response()->macro('collection', function ($collection, \League\Fractal\TransformerAbstract $transformer, $status = 200, array $headers = []) use ($fractal) {
      $resource = new \League\Fractal\Resource\Collection($collection, $transformer);

      return $resource;
    });
  }
}