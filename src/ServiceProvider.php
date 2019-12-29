<?php

namespace KindWork\LoginNotify;

use Statamic\Providers\AddonServiceProvider;

class ServiceProvider extends AddonServiceProvider {
  protected $middleware = [
    'cp' => [Middleware\CheckBrowser::class],
  ];
  
  public function boot() {
    parent::boot();
    $this->loadViewsFrom(__DIR__.'/resources/views', 'login-notify');
  }
}
