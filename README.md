**TL;DR:** [948 summaries](https://github.com/dbuytaert/drupal-digests/blob/main/issues) of notable Drupal changes and [189 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules) to help you upgrade. Stay up to date about new additions using the [RSS feeds](#rss-feeds) below.

## Recent changes

AI-generated summaries of [notable Drupal commits](https://github.com/dbuytaert/drupal-digests/blob/main/issues), filtered by impact and community interest.

### Drupal Core

_501 summaries · 10 new this week_

- [#3592672: Remove the who's new and who's online views](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3592672.md)
- [#3582506: Connection::open() should have SensitiveParameter](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3582506.md)
- [#2672340: user_user_role_insert should not exist](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/2672340.md)

### Drupal Canvas

_237 summaries · 1 new this week_

- [#3591839: Prefix-strip redirect misses default-language prefixes and is overridden by...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3591839.md)
- [#3569120: Canvas AI: Expose Props of Blocks to the Agent](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3569120.md)
- [#3591785: Strengthen AutoSaveManager to generate stable hashes](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3591785.md)

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
