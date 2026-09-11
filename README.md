**TL;DR:** [1040 summaries](https://github.com/dbuytaert/drupal-digests/blob/main/issues) of notable Drupal changes and [207 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules) to help you upgrade. Stay up to date about new additions using the [RSS feeds](#rss-feeds) below.

## Recent changes

AI-generated summaries of [notable Drupal commits](https://github.com/dbuytaert/drupal-digests/blob/main/issues), filtered by impact and community interest.

### Drupal Core

_573 summaries · 19 new this week_

- [#3391683: Convert initial functions in update.inc file to a class](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3391683.md)
- [#3035343: Deprecate drupal_attach_tabledrag(). Move its logic in Table form element](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3035343.md)
- [#3618971: Use Admin theme in installer](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3618971.md)

### Drupal AI

_129 summaries · 2 new this week_

- [#3620132: Uses ai_search_tracker instead of default](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3620132.md)
- [#3390907: Protect / help avoid validation logic problems](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3390907.md)
- [#3471408: not_blank_constraint_rule fail on fields with multiple values](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3471408.md)

### Drupal Canvas

_247 summaries · 0 new this week_

- [#3549232: Canvas AI: Updating page contents with agents](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3549232.md)
- [#3592001: Apply page variant translation overrides to previews](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592001.md)
- [#3592000: Translating a component tree config entity (such as PageVariant) that has an...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592000.md)

### Drupal CMS

_91 summaries · 0 new this week_

- [#3591440: Fix installer page backgrounds and add interstitial at the end](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591440.md)
- [#3489408: Enable filenames sanitization](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3489408.md)
- [#3591420: Add Summit site template to site-templates.yml](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591420.md)


## Rector rules

[Rector](https://getrector.com) can rewrite PHP code automatically, so you don't have to update deprecated API calls by hand. These [207 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules), extracted from Drupal core issues using AI, handle recent deprecations and new coding patterns.

```bash
git clone --depth 1 https://github.com/dbuytaert/drupal-digests.git
composer require --dev rector/rector

# Rewrite deprecated code (dry run first)
vendor/bin/rector process web/modules/custom \
  --config drupal-digests/rector/all.php --dry-run
```

### Latest rules
_207 rules · 7 new this week_

- [Replace deprecated update.inc global functions with the DatabaseUpdate service](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/replace-deprecated-update-inc-global-functions-with-the-3391683.php)
- [Replace drupal_attach_tabledrag() with Table::attachTabledrag()](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/replace-drupal-attach-tabledrag-with-table-attachtabledrag-3035343.php)
- [Replace views_invalidate_cache() with Views::invalidateCache()](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/replace-views-invalidate-cache-with-views-invalidatecache-941970.php)


## RSS feeds

- [Drupal Core](https://dbuytaert.github.io/drupal-digests/feeds/drupal-core.xml)
- [Drupal CMS](https://dbuytaert.github.io/drupal-digests/feeds/drupal-cms.xml)
- [Drupal Canvas](https://dbuytaert.github.io/drupal-digests/feeds/drupal-canvas.xml)
- [Drupal AI](https://dbuytaert.github.io/drupal-digests/feeds/drupal-ai.xml)
- [Rector rules](https://dbuytaert.github.io/drupal-digests/feeds/rector.xml)

---

*AI generated and may contain errors. Created by [Dries Buytaert](https://dri.es/).*
