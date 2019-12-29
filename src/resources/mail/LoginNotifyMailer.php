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
    $googleMapsKey = env("GOOGLE_MAPS_KEY", false);

    // Set the browser info to be use in the email template
    $this->browser = $browser;

    // Get location info & set to be used in template
    $this->location = Location::get($browser["ip"])->toArray();

    // Construct the url for the image to be attached / inlined into the email using the location
    $this->mapImage = $googleMapsKey ? "https://maps.googleapis.com/maps/api/staticmap?center=" . $this->location["latitude"] ."," . $this->location["longitude"] . "&zoom=13&size=600x300&maptype=roadmap&markers=color:red%7C" . $this->location["latitude"] ."," . $this->location["longitude"] . "&key=" . $googleMapsKey : false;
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