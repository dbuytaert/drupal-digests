**TL;DR:** [1313 summaries](https://github.com/dbuytaert/drupal-digests/blob/main/issues) of notable Drupal changes and [209 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules) to help you upgrade. Stay up to date about new additions using the [RSS feeds](#rss-feeds) below.

## Recent changes

AI-generated summaries of [notable Drupal commits](https://github.com/dbuytaert/drupal-digests/blob/main/issues), filtered by impact and community interest.

### Drupal Canvas

_285 summaries · 5 new this week_

- [#3592225: Workspaces PR 3: workspace-scoped auto-save (the core change)](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592225.md)
- [#3592211: Workspaces PR 1: retire the 1.x external-edit conflict detection](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592211.md)
- [#3592085: Canvas AI: Let the user stop a running chat turn](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592085.md)

### Drupal AI

_273 summaries · 29 new this week_

- [#3630882: Store the OAuth2 client identifier on tokens](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3630882.md)
- [#3588999: Add drupal-ckeditor5-plugins skill: CKEditor 5 plugin customization, version...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/ai-best-practices/3588999.md)
- [#3583065: Report item and property positions in the violation path, not the message](https://github.com/dbuytaert/drupal-digests/blob/main/issues/tool-api/3583065.md)

### Drupal CMS

_126 summaries · 7 new this week_

- [#3591502: Installer writes Composer's cache into <project root>/.composer when installing...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591502.md)
- [#3591500: Add some new admin page events to benchmark](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591500.md)
- [#3591504: Telemetry checkbox prevents Basic site settings from being saved](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591504.md)

### Drupal Core

_629 summaries · 9 new this week_

- [#3471113: Update to selenium/standalone-chrome:134+](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3471113.md)
- [#3029364: "None" Argument Validator for Views Contains an Undefined Variable](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3029364.md)
- [#2879023: Batch is dependent on the order of query string parameters](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/2879023.md)


## Rector rules

[Rector](https://getrector.com) can rewrite PHP code automatically, so you don't have to update deprecated API calls by hand. These [209 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules), extracted from Drupal core issues using AI, handle recent deprecations and new coding patterns.

```bash
git clone --depth 1 https://github.com/dbuytaert/drupal-digests.git
composer require --dev rector/rector

# Rewrite deprecated code (dry run first)
vendor/bin/rector process web/modules/custom \
  --config drupal-digests/rector/all.php --dry-run
```

### Latest rules
_209 rules · 0 new this week_

- [Remove deprecated $error_type argument from doTrustedCallback() calls](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/remove-deprecated-error-type-argument-from-3081025.php)
- [Remove deprecated no-op InstallerTestBase::setUpProfile() calls](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/remove-deprecated-no-op-installertestbase-setupprofile-calls-3520028.php)
- [Replace deprecated update.inc global functions with the DatabaseUpdate service](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/replace-deprecated-update-inc-global-functions-with-the-3391683.php)


## RSS feeds

- [Drupal Core](https://dbuytaert.github.io/drupal-digests/feeds/drupal-core.xml)
- [Drupal CMS](https://dbuytaert.github.io/drupal-digests/feeds/drupal-cms.xml)
- [Drupal Canvas](https://dbuytaert.github.io/drupal-digests/feeds/drupal-canvas.xml)
- [Drupal AI](https://dbuytaert.github.io/drupal-digests/feeds/drupal-ai.xml)
- [Rector rules](https://dbuytaert.github.io/drupal-digests/feeds/rector.xml)

---

*AI generated and may contain errors. Created by [Dries Buytaert](https://dri.es/).*
