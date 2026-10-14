# CI/CD Workflows

The four workflows in `.github/workflows/` call the shared ones in
[palasthotel/github-workflows](https://github.com/palasthotel/github-workflows). How
they work, every input and what to do when a deploy fails is described there, in
[docs/wp-plugin.md](https://github.com/palasthotel/github-workflows/blob/main/docs/wp-plugin.md).

What is specific to this plugin:

| | |
|---|---|
| wordpress.org slug | `content-observer` |
| version file | `package.json` (`release-type: node`) |
| build step | `npm ci && npm run build` in `pr.yml` and the deploy - `public/dist/` (the admin pages) is not in the repository |
| `required-files` | the built `dist/modifications.*` and `dist/settings.*`, the composer autoloader |
| composer | `public/composer.json` only defines the autoloader; the pack writes `public/vendor/`, which is not in the repository |
| `php -l` | 8.0 to 8.4 - `Requires PHP: 8.0` |
