*Updated April 29, 2026*

**TL;DR:** [528 summaries](https://github.com/dbuytaert/drupal-digests/blob/main/issues) of notable Drupal changes and [170 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules) to help you upgrade. Stay up to date about new additions using the [RSS feeds](#rss-feeds) below.

## Recent changes

AI-generated summaries of [notable Drupal commits](https://github.com/dbuytaert/drupal-digests/blob/main/issues), filtered by impact and community interest.

### Drupal Core

_398 summaries · 80 new this week_

- [#2907780: Add a field purger service](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/2907780.md)
- [#3587415: Avoid querying for last installed schema definitions twice](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3587415.md)
- [#3184242: Support brotli compression of assets](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3184242.md)

### Drupal Canvas

_54 summaries · 14 new this week_

- [#3573776: Canvas needs a way for server to send notifications and trigger actions in the...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3573776.md)
- [#3586959: Validate image prop example URLs in Code Component metadata files](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3586959.md)
- [#3574994: Resizable right sidebar for Drupal Canvas](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3574994.md)

### Drupal AI

_65 summaries · 6 new this week_

- [#3585786: Add universal pre-load and post-load hooks to...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3585786.md)
- [#3581363: Add drupal:mdx-fill event support to MDX editor for external content injection](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3581363.md)
- [#3542602: Use an existing entity option for the entity reference automator type](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3542602.md)

### Drupal CMS

_11 summaries · 1 new this week_

- [#3580694: The project template should always place config outside the web root by default](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3580694.md)
- [#3579163: Add support for listing paid site templates in the installer](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3579163.md)
- [#3579729: Add an `overwrite` option to `drush site:export`](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3579729.md)


## Rector rules

[Rector](https://getrector.com) can rewrite PHP code automatically, so you don't have to update deprecated API calls by hand. These [170 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules), extracted from Drupal core issues using AI, handle recent deprecations and new coding patterns.

```bash
git clone --depth 1 https://github.com/dbuytaert/drupal-digests.git
composer require --dev rector/rector

# Rewrite deprecated code (dry run first)
vendor/bin/rector process web/modules/custom \
  --config drupal-digests/rector/all.php --dry-run
```

### Latest rules
_170 rules · 126 new this week_

- [Replace deprecated field_purge_batch() with FieldPurger service](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/replace-deprecated-field-purge-batch-with-fieldpurger-2907780.php)
- [Replace deprecated system.performance css.gzip/js.gzip config keys with...](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/replace-deprecated-system-performance-css-gzip-js-gzip-3184242.php)
- [Replace PHPUnit TestCase::getName() with name() for PHPUnit 10 compatibility](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/replace-phpunit-testcase-getname-with-name-for-phpunit-10-3217904.php)


## RSS feeds

- [Drupal Core](https://dbuytaert.github.io/drupal-digests/feeds/drupal-core.xml)
- [Drupal CMS](https://dbuytaert.github.io/drupal-digests/feeds/drupal-cms.xml)
- [Drupal Canvas](https://dbuytaert.github.io/drupal-digests/feeds/drupal-canvas.xml)
- [Drupal AI](https://dbuytaert.github.io/drupal-digests/feeds/drupal-ai.xml)
- [Rector rules](https://dbuytaert.github.io/drupal-digests/feeds/rector.xml)

---

*AI generated and may contain errors. Created by [Dries Buytaert](https://dri.es/).*
