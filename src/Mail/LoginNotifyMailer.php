<?php

namespace KindWork\LoginNotify\Mail;

use Config;
use Location;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class LoginNotifyMailer extends Mailable implements ShouldQueue {
  use Queueable, SerializesModels;

  public $browser;
  public $location;
  public $mapImage;

  /**
   * Create a new message instance.
   *
   * @return void
   */
  public function __construct($browser) {
    // Get the Google Maps Key, or return false
    $googleMapsKey = Config::get("login_notify.google_maps_api_key");

    // Set the browser info to be use in the email template
    $this->browser = $browser;

    // Check to see if the IP is in cache & set to be used in template
    $this->location = cache("ln_ip_" . $browser["ip"], false);

    if (!$this->location) {
      // Get location info fresh if not
      $this->location = Location::get($browser["ip"]);
      if ($this->location) {
        $this->location = $this->location->toArray();
        // Store it in the cache for later
        cache(["ln_ip_" . $browser["ip"] => $this->location], Config::get("login_notify.ip_lookup_cache"));
      }
    }

    if ($this->location) {
      // Round lat / lng
      $lat = round($this->location["latitude"], Config::get("login_notify.map_precision"));
      $lng = round($this->location["longitude"], Config::get("login_notify.map_precision"));

      // Construct the url for the image to be attached / inlined into the email using the location
      $this->mapImage = $googleMapsKey ? "https://maps.googleapis.com/maps/api/staticmap?center=" . $lat . "," . $lng . "&zoom=" . Config::get("login_notify.map_zoom_level") . "&size=600x300&maptype=roadmap&markers=color:red%7C" . $lat ."," . $lng . "&key=" . $googleMapsKey : false;
    }
  }

  /**
   * Build the message.
   *
   * @return $this
   */
  public function build() {
    // Set up the email
    $email = $this->subject(Config::get('app.name') . " new login")
                  ->view("login-notify::email")
                  ->text("login-notify::email-plain");

    // If there is an image attach it
    if ($this->mapImage) {
      $email = $email->attach($this->mapImage, [
                  "as" => "map.png",
                  "mime" => "image/png",
                ]);
    }

    // return the email for sending
    return $email;
  }
}