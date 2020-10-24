<?php

namespace KindWork\LoginNotify\Listeners;

use Config;
use Browser;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cookie;
use KindWork\LoginNotify\Mail\LoginNotifyMailer;

class LoginNotifyListener {
  private $app;
  private $cookieTtlMinutes;
  private $browser;
  private $cookie;
  private $now;
  private $os;
  private $request;

  /**
   * Create the event listener.
   *
   * @param  Request  $request
   * @return void
   */
  public function __construct(Request $request) {
    $this->app = Config::get("app.name");
    $this->cookieTtlMinutes = Config::get("login_notify.cookie_ttl_minutes");
    $this->browser = Browser::browserFamily();
    // Get the login notify cookie if it exists
    $this->cookie = Cookie::get("login_notify");
    $this->now = $now = Carbon::now();
    $this->os = Browser::platformName();
    $this->request = $request;
  }

  public function handle(Login $event) {
    // If the browser is not registered let's register it
    if (!$this->request->session()->get("login_notify_registered_browser")) {
      // Get the current user for later
      $user = $event->user;


      // Get the valid cookies for the user, if any, if not an empty array
      $validCookies = isset($user->data()["login_notify_valid_cookies"]) ? $user->data()["login_notify_valid_cookies"] : [];

      // Remove expired cookies
      foreach($validCookies as $key=>$validCookie) {
        $expireTime = Carbon::parse($validCookie["at"])->addMinutes($this->cookieTtlMinutes);
        if ($this->now->greaterThan($expireTime)) {
          unset($validCookies[$key]);
        }
      }

      // If the cookie is in the list of valid cookies for the user skip, otherwise ...
      if (count($validCookies) < 1 || !in_array($this->cookie, array_keys($validCookies))) {
        // Make a random string
        $value = Str::random(32);

        // Add this to the list of valid cookies for the user
        $validCookies[$value] = [
          "browser" => $this->browser,
          "os" => $this->os,
          "ip" => $this->request->ip(),
          "at" => $this->now->toDayDateTimeString(),
        ];

        // Update the list of valid cookies for the user
        $user->set("login_notify_valid_cookies", $validCookies);
        $user->save();

        // Set the cookie in the browser with a lifetime of 1 week
        Cookie::queue("login_notify", $value, $this->cookieTtlMinutes);

        // Send the user an email saying they have logged in in a new browser
        Mail::to($user->email())->send(
          new LoginNotifyMailer(
            array_merge(
              $validCookies[$value],
              ["app" => $this->app]
            )
          )
        );
      }

      // Savein the session that this browser is registered (for faster execution).
      $this->request->session()->put("login_notify_registered_browser", true);
    }
  }
}
