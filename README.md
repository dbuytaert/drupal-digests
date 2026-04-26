*Updated April 26, 2026*

**TL;DR:** [503 summaries](https://github.com/dbuytaert/drupal-digests/blob/main/issues) of notable Drupal changes and [142 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules) to help you upgrade. Stay up to date about new additions using the [RSS feeds](#rss-feeds) below.

## Recent changes

AI-generated summaries of [notable Drupal commits](https://github.com/dbuytaert/drupal-digests/blob/main/issues), filtered by impact and community interest.

**Drupal Core**
_376 summaries · 161 new this week_
- [#3393274: The theme must be passed as a query argument](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3393274.md)
- [#3195427: Olivero does not support core's responsive tables API](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3195427.md)
- [#3035340: Deprecate core/modules/views_ui/admin.inc](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3035340.md)

**Drupal Canvas**
_52 summaries · 13 new this week_
- [#3574994: Resizable right sidebar for Drupal Canvas](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3574994.md)
- [#3586342: Symmetrically translatable component trees, STEP 1: introduce...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3586342.md)
- [#3585978: Include page paths and descriptions in the CLI push/pull commands](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3585978.md)

**Drupal AI**
_65 summaries · 13 new this week_
- [#3585786: Add universal pre-load and post-load hooks to...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3585786.md)
- [#3581363: Add drupal:mdx-fill event support to MDX editor for external content injection](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3581363.md)
- [#3542602: Use an existing entity option for the entity reference automator type](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3542602.md)

**Drupal CMS**
_10 summaries · 0 new this week_
- [#3580694: The project template should always place config outside the web root by default](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3580694.md)
- [#3579163: Add support for listing paid site templates in the installer](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3579163.md)
- [#3579729: Add an `overwrite` option to `drush site:export`](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3579729.md)


## Rector rules

[Rector](https://getrector.com) can rewrite PHP code automatically, so you don't have to update deprecated API calls by hand. These [142 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules), extracted from Drupal core issues using AI, handle recent deprecations and new coding patterns.

```bash
git clone --depth 1 https://github.com/dbuytaert/drupal-digests.git
composer require --dev rector/rector

# Rewrite deprecated code (dry run first)
vendor/bin/rector process web/modules/custom \
  --config drupal-digests/rector/all.php --dry-run
```

### Latest rules
_142 rules · 132 new this week_

- [Rename hook_preprocess_block_content_add_list to hook_preprocess_entity_add_list](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/rename-hook-preprocess-block-content-add-list-to-hook-3346394.php)
- [Remove deprecated trustData() calls and save(TRUE) argument in config](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/remove-deprecated-trustdata-calls-and-save-true-argument-in-3347842.php)
- [Replace deprecated MatchingRouteNotFoundException with ResourceNotFoundException](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/replace-deprecated-matchingroutenotfoundexception-with-3350922.php)


## RSS feeds

- [Drupal Core](https://dbuytaert.github.io/drupal-digests/feeds/drupal-core.xml)
- [Drupal CMS](https://dbuytaert.github.io/drupal-digests/feeds/drupal-cms.xml)
- [Drupal Canvas](https://dbuytaert.github.io/drupal-digests/feeds/drupal-canvas.xml)
- [Drupal AI](https://dbuytaert.github.io/drupal-digests/feeds/drupal-ai.xml)
- [Rector rules](https://dbuytaert.github.io/drupal-digests/feeds/rector.xml)

---

*AI generated and may contain errors. Created by [Dries Buytaert](https://dri.es/).*
