# Content Observer (WordPress-Plugin)

Efficiently observe content changes between WordPress instances. One site records what
changed - posts, deleted posts, or anything a theme or plugin reports - and the sites
observing it learn about those changes and react to them in their own code.

The plugin moves no content by itself. It transports *modifications* (content id,
content type, create/update/delete, time); what an observing site does with them is up
to a hook in that site's code.

## Concepts

- **Observable**: a site whose changes others want to know about.
- **Observer**: a site that wants to know about another site's changes.
- **Modification**: `content_id`, `content_type` (`post` by default, any string you
  choose), `type` (`create`, `update`, `delete`) and the time of the change. Saving and
  deleting posts records modifications automatically.

Both sites have the plugin and an API key (Content Observer → Settings). Under
Content Observer → Manage the sites are connected; the connection exchanges the keys.

## How changes travel

- **notify** (observable → observer): sends new modifications to every observer, hourly
  by cron or with `wp content-observer notify`.
- **fetch** (observer → observable): asks the observable for its new modifications,
  with `wp content-observer fetch` or the Fetch button.
- **apply** (on the observer): runs the hooks below for every modification since the
  last run, hourly by cron or with the Apply button.

Both schedules can be disabled under Settings if a system cron runs the WP-CLI commands
instead.

## Hooks

```php
use Palasthotel\WordPress\ContentObserver\Model\SiteModificationAction;

add_action( 'content_observer_on_modifications', function ( SiteModificationAction $action ) {
	foreach ( $action->modifications as $mod ) {
		// $action->site->url, $mod->content_id, $mod->content_type, $mod->type
	}
} );
```

| Hook | Type | |
|---|---|---|
| `content_observer_on_modifications` | action | modifications of any observed site |
| `content_observer_on_modifications_{site_id}` | action | modifications of one site |
| `content_observer_remote_request_header` | filter | headers for requests to other sites, e.g. basic auth |

Recording your own modifications:

```php
use Palasthotel\WordPress\ContentObserver\Model\Modification;

content_observer_add_modification(
	Modification::build( 'main-navigation', 'wp-menu' )->update()
);
```

## WP-CLI

```sh
wp content-observer ping
wp content-observer connect
wp content-observer notify
wp content-observer fetch
wp content-observer modifications
```

## Repository layout

| Path | |
|---|---|
| `public/` | the plugin as it ships to wordpress.org |
| `src/` | TypeScript sources of the admin pages, built into `public/dist/` |
| `content-observer.php` | development wrapper that loads `public/`, never shipped |
| `.github/` | workflows, see [.github/WORKFLOWS.md](.github/WORKFLOWS.md) |

See [CONTRIBUTING.md](CONTRIBUTING.md) for the local setup and commit conventions.

## License

[GPL-3.0-or-later](LICENSE). Versions up to 1.2.3 were published under the GPL-2.0
named in their plugin header.
