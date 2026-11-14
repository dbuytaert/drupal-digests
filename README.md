**TL;DR:** [1643 summaries](https://github.com/dbuytaert/drupal-digests/blob/main/issues) of notable Drupal changes and [211 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules) to help you upgrade. Stay up to date about new additions using the [RSS feeds](#rss-feeds) below.

## Recent changes

AI-generated summaries of [notable Drupal commits](https://github.com/dbuytaert/drupal-digests/blob/main/issues), filtered by impact and community interest.

### Drupal Canvas

_332 summaries · 23 new this week_

- [#3592245: Workspaces PR 13: stop deleting a named workspace when core publishes it](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592245.md)
- [#3592243: Workspaces PR 11: a failed workspace publish can leave Canvas config drafts on...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592243.md)
- [#3592350: Address npm dependency security advisories](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592350.md)

### Drupal AI

_516 summaries · 55 new this week_

- [#3601421: Docker group restart fails on Ubuntu 26.04: no sg or newgrp](https://github.com/dbuytaert/drupal-digests/blob/main/issues/one-line-installer/3601421.md)
- [#3586418: Apply unified UX/design and Provider Config element to Image-and-Audio-to-Video...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/ai/3586418.md)
- [#3586540: Add optional prompt to ImageToVideoInput and clean up the class](https://github.com/dbuytaert/drupal-digests/blob/main/issues/ai/3586540.md)

### Drupal Core

_658 summaries · 14 new this week_

- [#3579889: Replace @return void with native void return types](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3579889.md)
- [#3622391: Use tagged iterators in CacheTagsInvalidator](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3622391.md)
- [#3634655: Update to Symfony 8.2.0-BETA1](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3634655.md)

### Drupal CMS

_137 summaries · 3 new this week_

- [#3591524: Search is 404 for non top-level pages](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591524.md)
- [#3591527: 🤖 drupal_cms_site_template_base leaves two Navigation components enabled in...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591527.md)
- [#3591523: Add the Ágora Transparency site template to the installer](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591523.md)


## Rector rules

[Rector](https://getrector.com) can rewrite PHP code automatically, so you don't have to update deprecated API calls by hand. These [211 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules), extracted from Drupal core issues using AI, handle recent deprecations and new coding patterns.

```bash
git clone --depth 1 https://github.com/dbuytaert/drupal-digests.git
composer require --dev rector/rector

# Rewrite deprecated code (dry run first)
vendor/bin/rector process web/modules/custom \
  --config drupal-digests/rector/all.php --dry-run
```

### Latest rules
_211 rules · 0 new this week_

- [Replace Schema::ensureNotNullPrimaryKey() with validatePrimaryKeySchema()](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/replace-schema-ensurenotnullprimarykey-with-3082239.php)
- [Rename hook_system_info_alter implementations to hook_extension_info_alter](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/rename-hook-system-info-alter-implementations-to-hook-3623862.php)
- [Remove deprecated $error_type argument from doTrustedCallback() calls](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/remove-deprecated-error-type-argument-from-3081025.php)


## RSS feeds

- [Drupal Core](https://dbuytaert.github.io/drupal-digests/feeds/drupal-core.xml)
- [Drupal CMS](https://dbuytaert.github.io/drupal-digests/feeds/drupal-cms.xml)
- [Drupal Canvas](https://dbuytaert.github.io/drupal-digests/feeds/drupal-canvas.xml)
- [Drupal AI](https://dbuytaert.github.io/drupal-digests/feeds/drupal-ai.xml)
- [Rector rules](https://dbuytaert.github.io/drupal-digests/feeds/rector.xml)

---

*AI generated and may contain errors. Created by [Dries Buytaert](https://dri.es/).*
