=== Content Observer ===
Contributors: palasthotel, edwardbock, janaeggebrecht
Donate link: https://palasthotel.de/
Tags: sync, multisite, content, developer, wp-cli
Requires at least: 6.0
Tested up to: 7.1
Requires PHP: 8.0
Stable tag: 1.2.4
License: GPL-3.0-or-later
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Efficiently observe content changes between WordPress instances.

== Description ==

Content Observer tells one WordPress site what changed on another. The observed site records modifications - saved and deleted posts, and anything a theme or plugin reports - and the sites observing it receive them and react in their own code, for example to refresh a cached teaser list or a mirrored menu.

The plugin moves no content by itself. It transports the modification (content id, content type, create/update/delete and time); what happens next is up to the `content_observer_on_modifications` action on the observing site.

* Connect sites as observer, observable or both, secured by an API key
* Modifications are pushed hourly (notify) or pulled on demand (fetch)
* Admin pages to manage connections and inspect and apply modifications
* WP-CLI commands for every step, so a system cron can replace WP-Cron

Typical uses:

* A portal clears its cached teaser list as soon as an article on the news site changes
* Several sites rebuild a mirrored navigation when the main site's menu changes
* A central search site re-indexes changed posts and drops deleted ones
* An archive site stores a copy of every article, so it survives a later deletion

Developer documentation: [github.com/palasthotel/wp-content-observer](https://github.com/palasthotel/wp-content-observer)

== External services ==

The plugin only talks to the WordPress sites an administrator connects under Content Observer → Manage. It sends them this site's URL, its API key and the list of modifications, and requests theirs. No other service is contacted.

== Installation ==

1. Install the plugin on every site that observes or is observed, from Plugins > Add New or by uploading `content-observer.zip`
1. Activate it
1. Enter an API key under Content Observer → Settings on each site
1. Connect the sites under Content Observer → Manage
1. React to the `content_observer_on_modifications` action in your theme or plugin

== Frequently Asked Questions ==

= Does it copy posts between sites? =

No. It reports which content changed. Copying or refreshing content is done by your own code reacting to the modifications.

= Can I record changes other than posts? =

Yes. `content_observer_add_modification( Modification::build( $id, 'my-type' )->update() )` records a modification of any content type.

== Screenshots ==

1. Modifications fetched from an observed site, with buttons to apply them from a point in time
2. Manage page: the sites observing this one and the sites it observes, each with a connection test
3. Connect a new site with its URL, slug, API key and relation type
4. Settings: this site's API key and the WP-Cron schedules

== Upgrade Notice ==

= 1.2.4 =
Security release. A site without an API key no longer accepts requests from other sites - set a key under Content Observer → Settings before updating if the field is empty.

== Changelog ==

= 1.2.4 =
**Bug Fixes**
* connection test can no longer make the site request arbitrary URLs (4f494f5)
* escape admin notice logs and keep API keys out of them (08b8c06)
* escape the API key field on the settings page (8fc9ae1)
* modifications page disappears after applying modifications (c3f6bb8)
* protect Fetch, Notify and Apply against cross-site requests (1ae6a34)
* reject requests to other sites' routes when no API key is set (7775e3d)

= 1.2.3 =
* Major library updates
* all js(x) files converted to typescript
* fix: use admin_url passed from php for ajax calls

= 1.2.1 =
* Feature: Admin notices logger

= 1.2.0 =
* New modifications page

= 1.1.0 =
* Library update
* all js api calls are cors independent

= 1.0.0 =
* First release
