**TL;DR:** [549 summaries](https://github.com/dbuytaert/drupal-digests/blob/main/issues) of notable Drupal changes and [181 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules) to help you upgrade. Stay up to date about new additions using the [RSS feeds](#rss-feeds) below.

## Recent changes

AI-generated summaries of [notable Drupal commits](https://github.com/dbuytaert/drupal-digests/blob/main/issues), filtered by impact and community interest.

### Drupal Core

_415 summaries · 11 new this week_

- [#3579661: Call to a member function getPath() on null in...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3579661.md)
- [#3570634: Remove fallback classloader and related code in AttributeClassDiscovery](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3570634.md)
- [#3587182: Update CKEditor 5 to 48.0.1](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3587182.md)

### Drupal AI

_67 summaries · 2 new this week_

- [#3580850: Integrate with the MDX editor](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3580850.md)
- [#3575412: Create an Automator for Document Loader](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3575412.md)
- [#3585786: Add universal pre-load and post-load hooks to...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3585786.md)

### Drupal Canvas

_56 summaries · 0 new this week_

- [#3581110: Add Multi-Value List Text/Integer Prop Support (UI)](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3581110.md)
- [#3572553: Save Multi-Value Prop Configuration for Code Components](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3572553.md)
- [#3573776: Canvas needs a way for server to send notifications and trigger actions in the...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3573776.md)

### Drupal CMS

_11 summaries · 0 new this week_

- [#3580694: The project template should always place config outside the web root by default](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3580694.md)
- [#3579163: Add support for listing paid site templates in the installer](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3579163.md)
- [#3579729: Add an `overwrite` option to `drush site:export`](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3579729.md)


## Rector rules

[Rector](https://getrector.com) can rewrite PHP code automatically, so you don't have to update deprecated API calls by hand. These [181 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules), extracted from Drupal core issues using AI, handle recent deprecations and new coding patterns.

```bash
git clone --depth 1 https://github.com/dbuytaert/drupal-digests.git
composer require --dev rector/rector

# Rewrite deprecated code (dry run first)
vendor/bin/rector process web/modules/custom \
  --config drupal-digests/rector/all.php --dry-run
```

### Latest rules
_181 rules · 40 new this week_

- [Remove $options BC-layer parameter from Symfony Constraint constructors](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/remove-options-bc-layer-parameter-from-symfony-constraint-3569096.php)
- [Replace integer #access values with booleans in render arrays](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/replace-integer-access-values-with-booleans-in-render-arrays-3526250.php)
- [Replace deprecated node_access_grants() with...](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/replace-deprecated-node-access-grants-with-nodegrantshelper-2473041.php)


## RSS feeds

- [Drupal Core](https://dbuytaert.github.io/drupal-digests/feeds/drupal-core.xml)
- [Drupal CMS](https://dbuytaert.github.io/drupal-digests/feeds/drupal-cms.xml)
- [Drupal Canvas](https://dbuytaert.github.io/drupal-digests/feeds/drupal-canvas.xml)
- [Drupal AI](https://dbuytaert.github.io/drupal-digests/feeds/drupal-ai.xml)
- [Rector rules](https://dbuytaert.github.io/drupal-digests/feeds/rector.xml)

---

*AI generated and may contain errors. Created by [Dries Buytaert](https://dri.es/).*
