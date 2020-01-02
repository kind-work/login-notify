![Statamic 3.0+](https://img.shields.io/badge/Statamic-3.0+-FF269E?style=for-the-badge&link=https://statamic.com)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/5e227618f67b446da74a29b19b252d06)](https://www.codacy.com/manual/jcohlmeyer/login-notify?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=kind-work/login-notify&amp;utm_campaign=Badge_Grade)

## Login Notify for Statamic 3

Statamic Login Notify is a middleware addon for [Statamic 3](https://github.com/statamic/cms) that sends email notifications when a user logs into a new device.

## Pricing

Statamic Login Notify is commercial software. You do not need a licence for development but when you are ready to deploy the site to production please purchase a licence per site on the [Statamic Marketplace](https://statamic.com/marketplace/addons/login-notify).

## Install

### Install the addon using composer

```composer require kind-work/login-notify```

### Copy over the assets

```php artisan vendor:publish --provider="KindWork\LoginNotify\ServiceProvider" --force```

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
      -
        handle: name
        field:
          type: text
          display: Name
      -
        handle: email
        field:
          type: text
          input: email
          display: 'Email Address'
      -
        handle: roles
        field:
          type: user_roles
          width: 50
      -
        handle: groups
        field:
          type: user_groups
          width: 50
      -
        handle: avatar
        field:
          type: assets
          max_files: 1
      -
        handle: login_notify_valid_cookies
        field:
          type: login_notify
          localizable: false
          display: 'Remembered Browsers'
```
