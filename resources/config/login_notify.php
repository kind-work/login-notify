<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Google Maps API Key
    |--------------------------------------------------------------------------
    |
    | This value is your google maps API key valid for the IP address of the
    | server(s) that your website runs on and has the Google Maps Static API
    | enabled. If this key is provided maps will be shown. This should be stored
    | in an enviroment variable so as to not publish the key inadvertently.
    |
    */

    "google_maps_api_key" => env("GOOGLE_MAPS_KEY", false),


    /*
    |--------------------------------------------------------------------------
    | Cookie TTL (minutes)
    |--------------------------------------------------------------------------
    |
    | This value is sets the time to live in minutes for the browser cookie
    | this makes sure we do not notify you too much about logging in with the
    | same browser. The default is 10080 (1 week).
    |
    */

    "cookie_ttl_minutes" => 10080,


    /*
    |--------------------------------------------------------------------------
    | IP Lookup Cache (minutes)
    |--------------------------------------------------------------------------
    |
    | How long to cache IP lookups. The default is 10080 (1 week).
    |
    */

    "ip_lookup_cache" => 10080,
    
    /*
    |--------------------------------------------------------------------------
    | Map Cache (minutes)
    |--------------------------------------------------------------------------
    |
    | How long to cache static maps (to reduce # of Google API calls).
    | The default is 10080 (1 week).
    |
    */

    "map_cache" => 10080,
    
    /*
    |--------------------------------------------------------------------------
    | Lat / Lng percision
    |--------------------------------------------------------------------------
    |
    | The number of decmil places to keep for latitude & longitude.
    | More gives more precision but less cacheability.
    |
    */

    "map_precision" => 2,


    /*
    |--------------------------------------------------------------------------
    | Map Zoom Level (0-21+)
    |--------------------------------------------------------------------------
    |
    | The zoom level of the map as documented by the google Maps Static API
    | https://developers.google.com/maps/documentation/maps-static/dev-guide#Zoomlevels
    | 
    | 1: World
    | 5: Landmass/continent
    | 10: City
    | 15: Streets
    | 20: Buildings
    |
    */
    "map_zoom_level" => 5,

];
