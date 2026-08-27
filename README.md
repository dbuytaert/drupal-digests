**TL;DR:** [985 summaries](https://github.com/dbuytaert/drupal-digests/blob/main/issues) of notable Drupal changes and [193 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules) to help you upgrade. Stay up to date about new additions using the [RSS feeds](#rss-feeds) below.

## Recent changes

AI-generated summaries of [notable Drupal commits](https://github.com/dbuytaert/drupal-digests/blob/main/issues), filtered by impact and community interest.

### Drupal Core

_529 summaries · 24 new this week_

- [#3472624: Ensure the UI dialog instance is valid in Drupal.dialog.resetSize](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3472624.md)
- [#3619447: Avoid rebuilding the router twice when uninstalling a profile in the installer](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3619447.md)
- [#1189464: Add an 'instant' queue runner](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/1189464.md)

### Drupal AI

_124 summaries · 4 new this week_

- [#3601394: ensure_ddev refuses any distro outside Debian/RHEL, even when Docker and DDEV...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3601394.md)
- [#3601400: Management mode hangs for ~60s with no output: `ddev exec true` starts the...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3601400.md)
- [#3601382: Default Option- Picks a name for the project if it already exists.](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3601382.md)

### Drupal Canvas

_242 summaries · 3 new this week_

- [#3591972: Code editor has no "Document" prop type for the document object shape](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3591972.md)
- [#3591970: Components library UI is broken if a single component preview rendering fails:...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3591970.md)
- [#3591916: Component version hashes go stale on single-process installs: prop-shape...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3591916.md)

### Drupal CMS

_90 summaries · 0 new this week_

- [#3489408: Enable filenames sanitization](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3489408.md)
- [#3591420: Add Summit site template to site-templates.yml](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591420.md)
- [#3577804: The installer should set a state flag to remember what site template was applied](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3577804.md)


## Rector rules

[Rector](https://getrector.com) can rewrite PHP code automatically, so you don't have to update deprecated API calls by hand. These [193 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules), extracted from Drupal core issues using AI, handle recent deprecations and new coding patterns.

```bash
git clone --depth 1 https://github.com/dbuytaert/drupal-digests.git
composer require --dev rector/rector

# Rewrite deprecated code (dry run first)
vendor/bin/rector process web/modules/custom \
  --config drupal-digests/rector/all.php --dry-run
```

### Latest rules
_193 rules · 4 new this week_

- [Replace _user_mail_notify() calls with NotificationHandler methods](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/replace-user-mail-notify-calls-with-notificationhandler-3539178.php)
- [Replace deprecated locale.module underscore functions with LocaleJs service...](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/replace-deprecated-locale-module-underscore-functions-with-3618358.php)
- [Replace deprecated locale.module global functions with class calls](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/replace-deprecated-locale-module-global-functions-with-3616277.php)


## RSS feeds

- [Drupal Core](https://dbuytaert.github.io/drupal-digests/feeds/drupal-core.xml)
- [Drupal CMS](https://dbuytaert.github.io/drupal-digests/feeds/drupal-cms.xml)
- [Drupal Canvas](https://dbuytaert.github.io/drupal-digests/feeds/drupal-canvas.xml)
- [Drupal AI](https://dbuytaert.github.io/drupal-digests/feeds/drupal-ai.xml)
- [Rector rules](https://dbuytaert.github.io/drupal-digests/feeds/rector.xml)

---

*AI generated and may contain errors. Created by [Dries Buytaert](https://dri.es/).*
