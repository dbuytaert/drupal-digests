**TL;DR:** [957 summaries](https://github.com/dbuytaert/drupal-digests/blob/main/issues) of notable Drupal changes and [189 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules) to help you upgrade. Stay up to date about new additions using the [RSS feeds](#rss-feeds) below.

## Recent changes

AI-generated summaries of [notable Drupal commits](https://github.com/dbuytaert/drupal-digests/blob/main/issues), filtered by impact and community interest.

### Drupal Core

_507 summaries · 13 new this week_

- [#3569118: Deprecate the Shortcut module](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3569118.md)
- [#3614955: Move library overrides for Toolbar from Claro and Default Admin to Toolbar](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3614955.md)
- [#3389365: Fix and re-enable MigrateBlockContentTranslationTest](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3389365.md)

### Drupal Canvas

_240 summaries · 3 new this week_

- [#3591916: Component version hashes go stale on single-process installs: prop-shape...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3591916.md)
- [#3591885: Add `document` object $ref for linking locally hosted documents](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3591885.md)
- [#3591968: Forbid the reserved component tree root UUID as a component instance...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3591968.md)

### Drupal CMS

_90 summaries · 0 new this week_

- [#3489408: Enable filenames sanitization](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3489408.md)
- [#3591420: Add Summit site template to site-templates.yml](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591420.md)
- [#3577804: The installer should set a state flag to remember what site template was applied](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3577804.md)

### Drupal AI

_120 summaries · 0 new this week_

- [#3588977: Behavioral eval runner: the default (claude) provider runs the agent inside the...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3588977.md)
- [#3553475: Deletion of chunks vectors fails upon saving a node after editing](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3553475.md)
- [#3612042: Enable RateLimits](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3612042.md)


## Rector rules

[Rector](https://getrector.com) can rewrite PHP code automatically, so you don't have to update deprecated API calls by hand. These [189 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules), extracted from Drupal core issues using AI, handle recent deprecations and new coding patterns.

```bash
git clone --depth 1 https://github.com/dbuytaert/drupal-digests.git
composer require --dev rector/rector

# Rewrite deprecated code (dry run first)
vendor/bin/rector process web/modules/custom \
  --config drupal-digests/rector/all.php --dry-run
```

### Latest rules
_189 rules · 2 new this week_

- [Replace deprecated installer-specific extension list classes](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/replace-deprecated-installer-specific-extension-list-classes-2934063.php)
- [Rename NavigationShortcutsBlock to ShortcutNavigationBlock](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/rename-navigationshortcutsblock-to-shortcutnavigationblock-3581816.php)
- [Replace options_allowed_values() with the OptionsAllowedValuesInterface service](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/replace-options-allowed-values-with-the-2171397.php)


## RSS feeds

- [Drupal Core](https://dbuytaert.github.io/drupal-digests/feeds/drupal-core.xml)
- [Drupal CMS](https://dbuytaert.github.io/drupal-digests/feeds/drupal-cms.xml)
- [Drupal Canvas](https://dbuytaert.github.io/drupal-digests/feeds/drupal-canvas.xml)
- [Drupal AI](https://dbuytaert.github.io/drupal-digests/feeds/drupal-ai.xml)
- [Rector rules](https://dbuytaert.github.io/drupal-digests/feeds/rector.xml)

---

*AI generated and may contain errors. Created by [Dries Buytaert](https://dri.es/).*
