<?php

namespace KindWork\LoginNotify\Fieldtypes;

use Config;
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
      $googleMapsKey = Config::get("login_notify.google_maps_api_key");

      // Check to see if the IP lookup info is in cache
      $location = cache("ln_ip_" . $item["ip"], false);
      if (!$location) {
        // Look up the location of the IP fresh
        $location = Location::get($item["ip"])->toArray();
        // Store in cache for later
        cache(["ln_ip_" . $item["ip"] => $location], Config::get("login_notify.ip_lookup_cache")); 
      }

      // If Google Maps key add the image (base 64 url)
      if ($googleMapsKey) {
        $lat = round($location["latitude"], Config::get("login_notify.map_precision"));
        $lng = round($location["longitude"], Config::get("login_notify.map_precision"));

        // Check to see if the image is in cache
        $image = cache("ln_img_" . $lat . "_" . $lng);
        
        // If the image is not in the cache lets get it
        if (!$image) {
          // Construct the image url
          $imageUrl = "https://maps.googleapis.com/maps/api/staticmap?center=" . $lat . "," . $lng . "&zoom=" . Config::get("login_notify.map_zoom_level") . "&size=450x450&maptype=roadmap&markers=color:red%7C" . $lat ."," . $lng . "&key=" . $googleMapsKey;
  
          // Get the Image
          $image = file_get_contents($imageUrl); 
          
          // Cache the image for later
          cache(["ln_img_" . $lat . "_" . $lng => $image], Config::get("login_notify.map_cache"));
        }

        // If we have the image base 64 encode it so we do not share the key (also key should be restrected to server IP, I hope).
        if ($image !== false) {
          $item["image"] = "data:image/png;base64," . base64_encode($image);
        }
      }

      return array_merge($item, $location);
    }, $data);
  }
}