<?php

namespace KindWork\LoginNotify\Listeners;

use Log;
use Config;
use Browser;
use Closure;
use Carbon\Carbon;
use Statamic\Facades\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use KindWork\LoginNotify\Mail\LoginNotifyMailer;

class LoginNotifyListener {
  /**
   * Create the event listener.
   *
   * @param  Request  $request
   * @return void
   */
  public function __construct(Request $request) {
    $this->request = $request;
  }

  public function handle(Login $event) {
    $request = $this->request;

    // If the browser is not registered let's register it
    if (!$request->session()->get("login_notify_registered_browser")) {
      // Get the current user for later
      $user = $event->user;

      // Get the login notify cookie if it exists
      $cookie = Cookie::get("login_notify");
      // Get the valid cookies for the user, if any, if not an empty array
      $validCookies = isset($user->data()["login_notify_valid_cookies"]) ? $user->data()["login_notify_valid_cookies"] : [];

      // If the cookie is in the list of valid cookies for the user skip, otherwise ...
      if (count($validCookies) < 1 || !in_array($cookie, array_keys($validCookies))) {
        // Make a random string
        $value = Str::random(32);

        // Add this to the list of valid cookies for the user
        $validCookies[$value] = [
          "app" => Config::get("app.name"),
          "browser" => Browser::browserFamily(),
          "os" => Browser::platformName(),
          "ip" => $request->ip(),
          "at" => Carbon::now()->toDayDateTimeString(),
        ];

        // Update the list of valid cookies for the user
        $user->set("login_notify_valid_cookies", $validCookies);
        $user->save();

        // Set the cookie in the browser with a lifetime of 1 week
        Cookie::queue("login_notify", $value, Config::get("login_notify.cookie_ttl_minutes"));

        // Send the user an email saying they have logged in in a new browser
        Mail::to($user->email())->send(new LoginNotifyMailer($validCookies[$value]));
      }

      // Savein the session that this browser is registered (for faster execution).
      $request->session()->put("login_notify_registered_browser", true);
    }
  }
}
