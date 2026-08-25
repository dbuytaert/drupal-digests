**TL;DR:** [971 summaries](https://github.com/dbuytaert/drupal-digests/blob/main/issues) of notable Drupal changes and [190 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules) to help you upgrade. Stay up to date about new additions using the [RSS feeds](#rss-feeds) below.

## Recent changes

AI-generated summaries of [notable Drupal commits](https://github.com/dbuytaert/drupal-digests/blob/main/issues), filtered by impact and community interest.

### Drupal Core

_518 summaries · 13 new this week_

- [#3614825: Allow additional modules to be installed alongside the profile modules](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3614825.md)
- [#3614401: Allow install profiles to opt out of being installed](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3614401.md)
- [#3610009: Stop discovery of hooks in include files](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3610009.md)

### Drupal AI

_122 summaries · 2 new this week_

- [#3601382: Default Option- Picks a name for the project if it already exists.](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3601382.md)
- [#3601396: Handoff prompt: Enter should open the shell immediately, Esc to skip; --yolo...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3601396.md)
- [#3588977: Behavioral eval runner: the default (claude) provider runs the agent inside the...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3588977.md)

### Drupal Canvas

_241 summaries · 4 new this week_

- [#3591970: Components library UI is broken if a single component preview rendering fails:...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3591970.md)
- [#3591916: Component version hashes go stale on single-process installs: prop-shape...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3591916.md)
- [#3591885: Add `document` object $ref for linking locally hosted documents](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3591885.md)

### Drupal CMS

_90 summaries · 0 new this week_

- [#3489408: Enable filenames sanitization](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3489408.md)
- [#3591420: Add Summit site template to site-templates.yml](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591420.md)
- [#3577804: The installer should set a state flag to remember what site template was applied](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3577804.md)


## Rector rules

[Rector](https://getrector.com) can rewrite PHP code automatically, so you don't have to update deprecated API calls by hand. These [190 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules), extracted from Drupal core issues using AI, handle recent deprecations and new coding patterns.

```bash
git clone --depth 1 https://github.com/dbuytaert/drupal-digests.git
composer require --dev rector/rector

# Rewrite deprecated code (dry run first)
vendor/bin/rector process web/modules/custom \
  --config drupal-digests/rector/all.php --dry-run
```

### Latest rules
_190 rules · 1 new this week_

- [Add $memoryCache argument to UpdateRegistry instantiations](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/add-memorycache-argument-to-updateregistry-instantiations-3303751.php)
- [Replace deprecated installer-specific extension list classes](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/replace-deprecated-installer-specific-extension-list-classes-2934063.php)
- [Rename NavigationShortcutsBlock to ShortcutNavigationBlock](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/rename-navigationshortcutsblock-to-shortcutnavigationblock-3581816.php)


## RSS feeds

- [Drupal Core](https://dbuytaert.github.io/drupal-digests/feeds/drupal-core.xml)
- [Drupal CMS](https://dbuytaert.github.io/drupal-digests/feeds/drupal-cms.xml)
- [Drupal Canvas](https://dbuytaert.github.io/drupal-digests/feeds/drupal-canvas.xml)
- [Drupal AI](https://dbuytaert.github.io/drupal-digests/feeds/drupal-ai.xml)
- [Rector rules](https://dbuytaert.github.io/drupal-digests/feeds/rector.xml)

---

*AI generated and may contain errors. Created by [Dries Buytaert](https://dri.es/).*
