=== Be API - Maintenance Mode ===
Contributors: beapi, maximeculea, rahe
Donate link: http://paypal.me/BeAPI
Tags: Admin, Maintenance, Under Construction, Development, 503, SEO, Offline, Multisite
Requires at least: 4.7
Requires php: 5.6
Tested up to: 4.9.8
Stable tag: 1.0.1
License: GPLv3 or later
License URI: https://github.com/BeAPI/beapi-maintenance-mode/blob/master/LICENSE.md

Puts your WordPress site into maintenance mode by sending a 'Error 503: Access Denied/Forbidden' status to all unauthenticated clients.

== Description ==

This simple and lightweight WordPress maintenance plugin puts the site into maintenance mode. The *major advantage* compared to existing plugins, is that there is no options, it is *ready to use*!

While in maintenance mode, it was think to :
- Not impact your SEO by sending a '503 Service Unavailable' status to all unauthenticated clients. This means that it will inhibits search engines from both losing your site's existing content and indexing your maintenance page as well, so your site will not lose its rankings while it is out of service. Content will even be hidden from consumers of the site's RSS or Atom feeds.
- Make continually work the login/loggout process.
- It handle the *activate process in multisite*, which a lot of existing plugins don't.
- If people are logged in they will have the site displayed as normal/expected.

## How ?

We would like to precise that this plugin is simple as pie, anyone can use it but still dev-oriented. The idea is to not have a bunch of options to set before using it, in fact not only one! Because, it is a really pain when working on a multisite.
That is why there as still two filter for developers, which come in the section below.

## For developers

### IPs whitelist

In certain conditions, it is useful to not trigger the maintenance mode. That's why you can add a file to your project (mu-plugins) to specify a range of IPs to whitelist. At the agency we use it with our VPN IP.
Please find an example of implementation on the following [github](https://github.com/BeAPI/bea-plugin-defaults/blob/master/default-beapi-maintenance-mode.php) : https://github.com/BeAPI/bea-plugin-defaults/blob/master/default-beapi-maintenance-mode.php

### Customize the maintenance mode template

TODO

### Composer

TODO :
- so its possible possible to activate by composer
- or use our dedicated composer library for maintenance

## Who ?

Created by [Be API](https://beapi.fr), the French WordPress leader agency since 2009. Based in Paris, we are more than 30 people and always [hiring](https://beapi.workable.com) some fun and talented guys. So we will be pleased to work with you.

This plugin is only maintained, which means we do not guarantee some free support. Consider reporting an [issue](https://github.com/BeAPI/bea-media-analytics/issues) and be patient.

To facilitate the process of submitting an issue and quicker answer, we only use Github, so don't use WP.Org support, it will not be considered.

== Installation ==

# Requirements

- Tested up to 4.9.8
- PHP 5.6+

# WordPress

- Download and install using the built-in WordPress plugin installer.
- Site activate in the "Plugins" area of the admin.

== Screenshots ==

== Changelog ==

= 1.0.1 - 02 Nov 2018 =
- [#4](https://github.com/BeAPI/beapi-maintenance-mode/issues/4) : implement maintenance mode to not disturb the wp-activate process.

= 1.0.0 - 02 Nov 2018 =
- First release