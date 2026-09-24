**TL;DR:** [1105 summaries](https://github.com/dbuytaert/drupal-digests/blob/main/issues) of notable Drupal changes and [208 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules) to help you upgrade. Stay up to date about new additions using the [RSS feeds](#rss-feeds) below.

## Recent changes

AI-generated summaries of [notable Drupal commits](https://github.com/dbuytaert/drupal-digests/blob/main/issues), filtered by impact and community interest.

### Drupal Core

_597 summaries · 10 new this week_

- [#3095257: Option for _none is removed once a field has a value and can cause accidental...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3095257.md)
- [#3037054: Deprecate drupal_static_reset() and drupal_static()](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3037054.md)
- [#3067979: Exclude test files from release packages](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3067979.md)

### Drupal AI

_154 summaries · 23 new this week_

- [#3583021: Remove the ai_tools_property_alter hook implementation superseded by the schema...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/tool-api/3583021.md)
- [#3583041: Issue #3583041: Keep a multiple definition's description on the array](https://github.com/dbuytaert/drupal-digests/blob/main/issues/tool-api/3583041.md)
- [#3583029: Enforce required outputs in ToolBase::execute() so a success cannot omit a...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/tool-api/3583029.md)

### Drupal Canvas

_259 summaries · 11 new this week_

- [#3592115: Add Angular support to the Headless SDK and templates](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592115.md)
- [#3592030: Canvas AI: Remove obsolete region-based AI instructions and the unused page...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592030.md)
- [#3592009: Have date props display in the format of the selected locale](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592009.md)

### Drupal CMS

_95 summaries · 4 new this week_

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
