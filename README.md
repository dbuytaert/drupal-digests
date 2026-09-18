**TL;DR:** [1062 summaries](https://github.com/dbuytaert/drupal-digests/blob/main/issues) of notable Drupal changes and [208 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules) to help you upgrade. Stay up to date about new additions using the [RSS feeds](#rss-feeds) below.

## Recent changes

AI-generated summaries of [notable Drupal commits](https://github.com/dbuytaert/drupal-digests/blob/main/issues), filtered by impact and community interest.

### Drupal Canvas

_249 summaries · 2 new this week_

- [#3592090: Support multilingual previews in Canvas Headless](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592090.md)
- [#3592062: Support rendering SVGs in SDC and code components with an image prop](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592062.md)
- [#3549232: Canvas AI: Updating page contents with agents](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3549232.md)

### Drupal AI

_132 summaries · 3 new this week_

- [#3436728: Check the status of a consumer](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3436728.md)
- [#3601362: DDEV: expose a VNC X display for non-headless agent-browser, plus optional...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/one-line-installer/3601362.md)
- [#3601383: Curated npx skills (agent-browser, drupal-module-finder, superpowers,...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/one-line-installer/3601383.md)

### Drupal CMS

_93 summaries · 2 new this week_

- [#3591463: Fix contrast in "Add a page" button on /admin/dashboard](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591463.md)
- [#3591452: Site template with multilingual demo content only works if its intended default...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591452.md)
- [#3591440: Fix installer page backgrounds and add interstitial at the end](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591440.md)

### Drupal Core

_588 summaries · 15 new this week_

- [#3623097: Remove remaining Gin and Claro implementation names from Default Admin theme,...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3623097.md)
- [#3623843: Remove Backbone.js and Underscore.js, not used in core anymore](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3623843.md)
- [#3595083: Deprecate the Olivero theme](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3595083.md)


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
_208 rules · 2 new this week_

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
