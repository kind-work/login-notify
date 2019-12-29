<?php

namespace KindWork\LoginNotify\Fieldtypes;

use Location;
use Statamic\Facades\User;
use Illuminate\Http\Request;

class LoginNotifyFieldtype extends \Statamic\Fields\Fieldtype {  
  public function preload() {
    return [];
  }
  
  public function preProcess($data) {
    $data = $data ?? [];

    return array_map(function($item) {
      // Get the Google Maps Key, or return false
      $googleMapsKey = env("GOOGLE_MAPS_KEY", false);

      // Look up the location of the IP fresh
      $location = Location::get($item["ip"])->toArray();

      // If Google Maps key add the image (base 64 url)
      if ($googleMapsKey) {
        // Construct the image url
        $imageUrl = "https://maps.googleapis.com/maps/api/staticmap?center=" . $location["latitude"] ."," . $location["longitude"] . "&zoom=13&size=450x450&maptype=roadmap&markers=color:red%7C" . $location["latitude"] ."," . $location["longitude"] . "&key=" . $googleMapsKey;

        // Get the Image
        $image = file_get_contents($imageUrl);

        // If we have the image base 64 encode it so we do not share the key (also key should be restrected to server IP, I hope).
        if ($image !== false) {
          $item["image"] = "data:image/png;base64," . base64_encode($image);
        }
      }

      return array_merge($item, $location);
    }, $data);
  }
}