**TL;DR:** [1343 summaries](https://github.com/dbuytaert/drupal-digests/blob/main/issues) of notable Drupal changes and [209 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules) to help you upgrade. Stay up to date about new additions using the [RSS feeds](#rss-feeds) below.

## Recent changes

AI-generated summaries of [notable Drupal commits](https://github.com/dbuytaert/drupal-digests/blob/main/issues), filtered by impact and community interest.

### Drupal AI

_290 summaries · 28 new this week_

- [#3586639: Status badge contrast (WCAG 1.4.3): Unavailable fails on row hover](https://github.com/dbuytaert/drupal-digests/blob/main/issues/ai-context/3586639.md)
- [#3586541: Make duplicate consumer_id rows resolve consistently and map validator errors...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/ai-context/3586541.md)
- [#3586571: Scheduled publish on save can leave context item Draft in the listing](https://github.com/dbuytaert/drupal-digests/blob/main/issues/ai-context/3586571.md)

### Drupal Core

_631 summaries · 4 new this week_

- [#3218381: #file_value_callbacks bypass the TrustedCallbackInterface protections](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3218381.md)
- [#3398033: Fix deprecated assert.active directive](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3398033.md)
- [#3471113: Update to selenium/standalone-chrome:134+](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3471113.md)

### Drupal CMS

_132 summaries · 9 new this week_

- [#3591501: Add an event or param to track use of the launcher](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591501.md)
- [#3591510: Switch `drush si` default site template to Forma](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591510.md)
- [#3591506: Updates to telemetry for env info, remove user_login, specify recipes &...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591506.md)

### Drupal Canvas

_290 summaries · 6 new this week_

- [#3591889: Canvas Editor: Component collapses and becomes un-clickable on the canvas when...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3591889.md)
- [#3592120: Canvas AI: Give the Canvas Agent editor-facing knowledge about Drupal Canvas](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592120.md)
- [#3592021: Canvas AI: Unify error handling in `canvas_component_agent`'s tools](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592021.md)


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
