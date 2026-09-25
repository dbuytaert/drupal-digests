**TL;DR:** [1115 summaries](https://github.com/dbuytaert/drupal-digests/blob/main/issues) of notable Drupal changes and [208 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules) to help you upgrade. Stay up to date about new additions using the [RSS feeds](#rss-feeds) below.

## Recent changes

AI-generated summaries of [notable Drupal commits](https://github.com/dbuytaert/drupal-digests/blob/main/issues), filtered by impact and community interest.

### Drupal AI

_161 summaries · 30 new this week_

- [#3583028: Accept integer map keys and any entity as an entity input](https://github.com/dbuytaert/drupal-digests/blob/main/issues/tool-api/3583028.md)
- [#3583012: Ship a use-drupal-tools agent skill so agents discover, inspect, and run Tool...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/tool-api/3583012.md)
- [#3583048: Constraints declared on a ListInputDefinition are never validated](https://github.com/dbuytaert/drupal-digests/blob/main/issues/tool-api/3583048.md)

### Drupal Core

_599 summaries · 11 new this week_

- [#3581349: Remove Syndicate block config](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3581349.md)
- [#3621277: Update JavaScript dependencies for 12.0.0-beta1](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3621277.md)
- [#3095257: Option for _none is removed once a field has a value and can cause accidental...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3095257.md)

### Drupal Canvas

_260 summaries · 12 new this week_

- [#3592097: Include language alternates in Headless content API](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592097.md)
- [#3592115: Add Angular support to the Headless SDK and templates](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592115.md)
- [#3592030: Canvas AI: Remove obsolete region-based AI instructions and the unused page...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592030.md)

### Drupal CMS

_95 summaries · 3 new this week_

- [#3591433: Add a drupal_cms_multilingual recipe with content translation features](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591433.md)
- [#3591466: Installing in another language does not translate shipped configuration: the...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591466.md)
- [#3591463: Fix contrast in "Add a page" button on /admin/dashboard](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591463.md)


## Rector rules

[Rector](https://getrector.com) can rewrite PHP code automatically, so you don't have to update deprecated API calls by hand. These [208 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules), extracted from Drupal core issues using AI, handle recent deprecations and new coding patterns.

```bash
git clone --depth 1 https://github.com/dbuytaert/drupal-digests.git
composer require --dev rector/rector

# Rewrite deprecated code (dry run first)
vendor/bin/rector process web/modules/custom \
  --config drupal-digests/rector/all.php --dry-run
```

### Latest rules
_208 rules · 0 new this week_

- [Remove deprecated no-op InstallerTestBase::setUpProfile() calls](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/remove-deprecated-no-op-installertestbase-setupprofile-calls-3520028.php)
- [Replace deprecated update.inc global functions with the DatabaseUpdate service](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/replace-deprecated-update-inc-global-functions-with-the-3391683.php)
- [Replace drupal_attach_tabledrag() with Table::attachTabledrag()](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/replace-drupal-attach-tabledrag-with-table-attachtabledrag-3035343.php)


## RSS feeds

- [Drupal Core](https://dbuytaert.github.io/drupal-digests/feeds/drupal-core.xml)
- [Drupal CMS](https://dbuytaert.github.io/drupal-digests/feeds/drupal-cms.xml)
- [Drupal Canvas](https://dbuytaert.github.io/drupal-digests/feeds/drupal-canvas.xml)
- [Drupal AI](https://dbuytaert.github.io/drupal-digests/feeds/drupal-ai.xml)
- [Rector rules](https://dbuytaert.github.io/drupal-digests/feeds/rector.xml)

---

*AI generated and may contain errors. Created by [Dries Buytaert](https://dri.es/).*
