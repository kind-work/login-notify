<?php

namespace KindWork\LoginNotify\Fieldtypes;

use Config;
use Location;
use GuzzleHttp\Client;
use Statamic\Fields\Fieldtype;

class LoginNotifyFieldtype extends Fieldtype {
  private $googleMapsKey;
  private $location;
  private $lookupIpCache;
  private $mapCache;
  private $mapPercision;
  private $mapZoomLevel;

  public function __construct() {
    $this->googleMapsKey = Config::get("login_notify.google_maps_api_key");
    $this->lookupIpCache = Config::get("login_notify.ip_lookup_cache");
    $this->mapPercision = Config::get("login_notify.map_precision");
    $this->mapCache = Config::get("login_notify.map_cache");
    $this->mapZoomLevel = Config::get("login_notify.map_zoom_level");
  }

  public function preload() {
    return [];
  }

  public function preProcess($data) {
    $data = $data ?? [];

    return array_map(function($item) {
      // Check to see if the IP lookup info is in cache
      $location = cache("ln_ip_" . $item["ip"], false);
      if (!$location) {
        // Look up the location of the IP fresh
        $location = Location::get($item["ip"]);
        // Store in cache for later
        if ($location) {
          $location = $location->toArray();
          cache(["ln_ip_" . $item["ip"] => $location], $this->lookupIpCache);
        }
      }

      if ($location) {
        // Merge $item and $location
        $item = array_merge($item, $location);
      }

      // If Google Maps key add the image (base 64 url)
      if ($this->googleMapsKey && $location) {
        $lat = round($location["latitude"], $this->mapPercision);
        $lng = round($location["longitude"], $this->mapPercision);

        // Check to see if the image is in cache
        $image = cache("ln_img_" . $lat . "_" . $lng);

        // If the image is not in the cache lets get it
        if (!$image) {
          // Construct the image url
          $imageUrl = "https://maps.googleapis.com/maps/api/staticmap?center=" . $lat . "," . $lng . "&zoom=" . $this->mapZoomLevel . "&size=450x450&maptype=roadmap&markers=color:red%7C" . $lat ."," . $lng . "&key=" . $this->googleMapsKey;

          // Get the Image
          $client = new Client();
          $response = $client->request('GET', $imageUrl);
          $image = $response->getBody();

          // Cache the image for later
          cache(["ln_img_" . $lat . "_" . $lng => $image], $this->mapCache);
        }

        // If we have the image base 64 encode it so we do not share the key (also key should be restricted to server IP, I hope).
        if ($image !== false) {
          $item["image"] = "data:image/png;base64," . base64_encode($image);
        }
      }

      return $item;
    }, $data);
  }
}