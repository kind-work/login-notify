<?php

namespace KindWork\LoginNotify;

use Statamic\Providers\AddonServiceProvider;

class ServiceProvider extends AddonServiceProvider {
  protected $scripts = [
    __DIR__.'/../dist/js/scripts.js'
  ];
  
  protected $stylesheets = [
    __DIR__.'/../dist/css/styles.css'
  ];

  protected $middleware = [
    'cp' => [Middleware\CheckBrowser::class],
  ];

  protected $fieldtypes = [
    Fieldtypes\LoginNotifyFieldtype::class
  ];

  public function boot() {
    parent::boot();
    $this->loadViewsFrom(__DIR__.'/resources/views', 'login-notify');
  }
}
