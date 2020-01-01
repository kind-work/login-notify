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
    
    $this->publishes([
      __DIR__.'/resources/config/login_notify.php' => config_path('login_notify.php'),
    ]);
    
    $this->loadViewsFrom(__DIR__.'/resources/views', 'login-notify');
  }
  
  public function register() {
    $this->mergeConfigFrom(
      __DIR__.'/resources/config/login_notify.php', 'login_notify'
    );
  }
}
