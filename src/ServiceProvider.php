<?php

namespace KindWork\LoginNotify;

use Illuminate\Support\Facades\Storage;
use Statamic\Providers\AddonServiceProvider;

class ServiceProvider extends AddonServiceProvider {
  protected $scripts = [
    __DIR__.'/../dist/js/scripts.js',
  ];

  // protected $stylesheets = [
  //   __DIR__.'/../dist/css/styles.css',
  // ];

  protected $listen = [
    'Illuminate\Auth\Events\Login' => [
      'KindWork\LoginNotify\Listeners\LoginNotifyListener',
    ],
  ];

  protected $fieldtypes = [
    Fieldtypes\LoginNotifyFieldtype::class,
  ];

  public function boot() {
    parent::boot();

    $this->bootAddonConfig()->bootAddonViews();
  }

  protected function bootAddonConfig() {
    $this->publishes([
      __DIR__.'/../resources/config/login_notify.php' => config_path('login_notify.php'),
    ]);
    return $this;
  }

  protected function bootAddonViews() {
    $this->loadViewsFrom(__DIR__.'/../resources/views', 'login-notify');
    $this->publishes([__DIR__.'/../resources/views' => resource_path('views/vendor/login-notify')], 'login-notify-views');
    return $this;
  }

  public function register() {
    $this->mergeConfigFrom(
      __DIR__.'/../resources/config/login_notify.php', 'login_notify'
    );
  }
}
