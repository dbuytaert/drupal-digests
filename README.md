**TL;DR:** [1127 summaries](https://github.com/dbuytaert/drupal-digests/blob/main/issues) of notable Drupal changes and [208 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules) to help you upgrade. Stay up to date about new additions using the [RSS feeds](#rss-feeds) below.

## Recent changes

AI-generated summaries of [notable Drupal commits](https://github.com/dbuytaert/drupal-digests/blob/main/issues), filtered by impact and community interest.

### Drupal AI

_169 summaries · 37 new this week_

- [#3583045: tool:info --format=json prints [] instead of {} for a tool with no inputs or...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/tool-api/3583045.md)
- [#3582967: Harden the 'administer tool' permission](https://github.com/dbuytaert/drupal-digests/blob/main/issues/tool-api/3582967.md)
- [#3583060: Structure-reading constraints on a map input throw instead of validating](https://github.com/dbuytaert/drupal-digests/blob/main/issues/tool-api/3583060.md)

### Drupal Core

_600 summaries · 11 new this week_

- [#3625969: Twig's 3.30 TypeError: Twig\Runtime\EscaperRuntime::escape(): Argument #4...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3625969.md)
- [#3581349: Remove Syndicate block config](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3581349.md)
- [#3621277: Update JavaScript dependencies for 12.0.0-beta1](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3621277.md)

### Drupal Canvas

_263 summaries · 14 new this week_

- [#3592099: Add headless previews for entities](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592099.md)
- [#3592101: Make React Code Components portable across Drupal and headless frontends](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592101.md)
- [#3592128: Remove experimental Canvas Headless properties and CLI flag](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592128.md)

### Drupal CMS

_95 summaries · 2 new this week_

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
