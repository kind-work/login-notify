# Login Notify for Statamic 3

[![Statamic 3.0+](https://img.shields.io/badge/Statamic-3.0%2B-FF269E)](https://statamic.com)
[![Commercial License](https://img.shields.io/badge/License-Commercial-yellow)](https://statamic.com/marketplace/addons/login-notify)
[![Codacy Badge](https://app.codacy.com/project/badge/Grade/868d24822a32419eb4df43c52302e8ae)](https://www.codacy.com/gh/kind-work/login-notify/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=kind-work/login-notify&amp;utm_campaign=Badge_Grade)

Statamic Login Notify is a middleware addon for [Statamic 3](https://github.com/statamic/cms) that sends email notifications when a user logs into a new device.

## Requirements
* PHP 7.2+
* Statamic v3+
* Laravel 7+

## Installation

### Install the addon using composer

```bash
composer require kind-work/login-notify
```

## Location Map

If you would like to send a map showing the location of the login in the email you will need to provide a valid key for the Google Maps Static API. I recommend you restrict this by IP address(es) to the IP(s) used by your servers.

Once you obtain this key add it to your environment variable: `GOOGLE_MAPS_KEY`

## Location Lookup

Location lookup is done using the [Laravel Location](https://github.com/stevebauman/location) package. Refer to the documentation for this package to customize the location lookups.

## Fieldtype (forget sessions)

If you would like to give users the ability to forget browsers where they have previously logged in you can add the Login Notify field type to the user blueprint with the key `login_notify_valid_cookies`.

```yaml
title: User
sections:
  main:
    display: Main
    fields:
      ...
      -
        handle: login_notify_valid_cookies
        field:
          type: login_notify
          localizable: false
          display: 'Remembered Browsers'
```

## Changelog
Please see the [Release Notes](https://statamic.com/addons/jrc9designstudio/login-notify/release-notes) for more information what has changed recently.

## Security
If you discover any security-related issues, please email [security@kind.work](mailto:security@kind.work) instead of using the issue tracker.

## License
This is commercial software. You may use the package for your sites. Each site requires its own license. You can purchase a licence from [The Statamic Marketplace](https://statamic.com/addons/jrc9designstudio/login-notify).
