**TL;DR:** [1364 summaries](https://github.com/dbuytaert/drupal-digests/blob/main/issues) of notable Drupal changes and [210 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules) to help you upgrade. Stay up to date about new additions using the [RSS feeds](#rss-feeds) below.

## Recent changes

AI-generated summaries of [notable Drupal commits](https://github.com/dbuytaert/drupal-digests/blob/main/issues), filtered by impact and community interest.

### Drupal Core

_634 summaries · 5 new this week_

- [#3594338: Add a command to import configuration](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3594338.md)
- [#3623862: Rename hook_system_info_alter to hook_extension_info_alter](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3623862.md)
- [#3575547: Deprecate StatementPrefetchIterator](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3575547.md)

### Drupal AI

_305 summaries · 34 new this week_

- [#3586702: Emit automator config ID tag and entity_context on chat requests](https://github.com/dbuytaert/drupal-digests/blob/main/issues/ai/3586702.md)
- [#3594806: Show the input and the model's answer when reviewing recorded decisions](https://github.com/dbuytaert/drupal-digests/blob/main/issues/ai-eval/3594806.md)
- [#3594822: Failure mode rates count each reviewer's annotation of the same question](https://github.com/dbuytaert/drupal-digests/blob/main/issues/ai-eval/3594822.md)

### Drupal Canvas

_292 summaries · 8 new this week_

- [#3592122: Canvas AI: get_component_details and get_component_context omit item type and...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592122.md)
- [#3592216: Workspaces PR 2: hard dependency on core Workspaces, Main workspace, install...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592216.md)
- [#3591889: Canvas Editor: Component collapses and becomes un-clickable on the canvas when...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3591889.md)

### Drupal CMS

_133 summaries · 7 new this week_

- [#3591507: Add a hard opt-out for telemetry via settings.php](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591507.md)
- [#3591501: Add an event or param to track use of the launcher](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591501.md)
- [#3591510: Switch `drush si` default site template to Forma](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591510.md)


## Rector rules

[Rector](https://getrector.com) can rewrite PHP code automatically, so you don't have to update deprecated API calls by hand. These [210 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules), extracted from Drupal core issues using AI, handle recent deprecations and new coding patterns.

```bash
git clone --depth 1 https://github.com/dbuytaert/drupal-digests.git
composer require --dev rector/rector

# Rewrite deprecated code (dry run first)
vendor/bin/rector process web/modules/custom \
  --config drupal-digests/rector/all.php --dry-run
```

### Latest rules
_210 rules · 1 new this week_

- [Rename hook_system_info_alter implementations to hook_extension_info_alter](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/rename-hook-system-info-alter-implementations-to-hook-3623862.php)
- [Remove deprecated $error_type argument from doTrustedCallback() calls](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/remove-deprecated-error-type-argument-from-3081025.php)
- [Remove deprecated no-op InstallerTestBase::setUpProfile() calls](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/remove-deprecated-no-op-installertestbase-setupprofile-calls-3520028.php)


## RSS feeds

- [Drupal Core](https://dbuytaert.github.io/drupal-digests/feeds/drupal-core.xml)
- [Drupal CMS](https://dbuytaert.github.io/drupal-digests/feeds/drupal-cms.xml)
- [Drupal Canvas](https://dbuytaert.github.io/drupal-digests/feeds/drupal-canvas.xml)
- [Drupal AI](https://dbuytaert.github.io/drupal-digests/feeds/drupal-ai.xml)
- [Rector rules](https://dbuytaert.github.io/drupal-digests/feeds/rector.xml)

---

*AI generated and may contain errors. Created by [Dries Buytaert](https://dri.es/).*
