**TL;DR:** [538 summaries](https://github.com/dbuytaert/drupal-digests/blob/main/issues) of notable Drupal changes and [179 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules) to help you upgrade. Stay up to date about new additions using the [RSS feeds](#rss-feeds) below.

## Recent changes

AI-generated summaries of [notable Drupal commits](https://github.com/dbuytaert/drupal-digests/blob/main/issues), filtered by impact and community interest.

### Drupal Core

_406 summaries · 65 new this week_

- [#3530640: Disable the user.login.http route by default and move to REST](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3530640.md)
- [#3533299: Deprecate node access rebuild functions](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3533299.md)
- [#3586606: Improve performance of HelpTopicsSyntaxTest](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3586606.md)

### Drupal Canvas

_56 summaries · 7 new this week_

- [#3581110: Add Multi-Value List Text/Integer Prop Support (UI)](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3581110.md)
- [#3572553: Save Multi-Value Prop Configuration for Code Components](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3572553.md)
- [#3573776: Canvas needs a way for server to send notifications and trigger actions in the...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3573776.md)

### Drupal AI

_65 summaries · 0 new this week_

- [#3585786: Add universal pre-load and post-load hooks to...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3585786.md)
- [#3581363: Add drupal:mdx-fill event support to MDX editor for external content injection](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3581363.md)
- [#3542602: Use an existing entity option for the entity reference automator type](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3542602.md)

### Drupal CMS

_11 summaries · 1 new this week_

- [#3580694: The project template should always place config outside the web root by default](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3580694.md)
- [#3579163: Add support for listing paid site templates in the installer](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3579163.md)
- [#3579729: Add an `overwrite` option to `drush site:export`](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3579729.md)


## Rector rules

[Rector](https://getrector.com) can rewrite PHP code automatically, so you don't have to update deprecated API calls by hand. These [179 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules), extracted from Drupal core issues using AI, handle recent deprecations and new coding patterns.

```bash
git clone --depth 1 https://github.com/dbuytaert/drupal-digests.git
composer require --dev rector/rector

# Rewrite deprecated code (dry run first)
vendor/bin/rector process web/modules/custom \
  --config drupal-digests/rector/all.php --dry-run
```

### Latest rules
_179 rules · 95 new this week_

- [Replace deprecated UserAuthenticationController with...](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/replace-deprecated-userauthenticationcontroller-with-3530640.php)
- [Replace deprecated node_access_rebuild() and node_access_needs_rebuild() with...](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/replace-deprecated-node-access-rebuild-and-node-access-3533299.php)
- [Replace deprecated EntityTypeInterface::getOriginalClass() with...](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/replace-deprecated-entitytypeinterface-getoriginalclass-3557461.php)


## RSS feeds

- [Drupal Core](https://dbuytaert.github.io/drupal-digests/feeds/drupal-core.xml)
- [Drupal CMS](https://dbuytaert.github.io/drupal-digests/feeds/drupal-cms.xml)
- [Drupal Canvas](https://dbuytaert.github.io/drupal-digests/feeds/drupal-canvas.xml)
- [Drupal AI](https://dbuytaert.github.io/drupal-digests/feeds/drupal-ai.xml)
- [Rector rules](https://dbuytaert.github.io/drupal-digests/feeds/rector.xml)

---

*AI generated and may contain errors. Created by [Dries Buytaert](https://dri.es/).*
