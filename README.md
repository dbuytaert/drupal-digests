**TL;DR:** [1281 summaries](https://github.com/dbuytaert/drupal-digests/blob/main/issues) of notable Drupal changes and [209 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules) to help you upgrade. Stay up to date about new additions using the [RSS feeds](#rss-feeds) below.

## Recent changes

AI-generated summaries of [notable Drupal commits](https://github.com/dbuytaert/drupal-digests/blob/main/issues), filtered by impact and community interest.

### Drupal CMS

_122 summaries · 10 new this week_

- [#3591494: CMS 2.2.4 causes contrib DrupalCmsCompatibilityTest to fail. Replace with a new...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591494.md)
- [#3591424: Recipes cannot be installed via UI due to bug with...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591424.md)
- [#3591495: Telemetry: switch from opt-in to opt-out](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591495.md)

### Drupal AI

_256 summaries · 66 new this week_

- [#3586791: Add Refusal Answer type to support OpenAI Decision API](https://github.com/dbuytaert/drupal-digests/blob/main/issues/ai/3586791.md)
- [#3589000: drupal-a11y-dynamic recommends FocusCommand, which does not exist in core (use...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/ai-best-practices/3589000.md)
- [#3586784: Let Decision models declare a minimum number of choice options](https://github.com/dbuytaert/drupal-digests/blob/main/issues/ai/3586784.md)

### Drupal Core

_622 summaries · 9 new this week_

- [#3594335: Add a module and theme uninstall command](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3594335.md)
- [#3250827: [policy, no patch] Use declare(strict_types=1) in new code](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3250827.md)
- [#3625998: Update CKEditor5 version for Drupal core 10.6, 11.3, 11.4, 11.5](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3625998.md)

### Drupal Canvas

_281 summaries · 6 new this week_

- [#3592132: Allow modules to add operations to the Canvas content list](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592132.md)
- [#3591748: Hydrate components with diamond dependencies](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3591748.md)
- [#3591894: Error message for empty value is displayed for select props even when they're...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3591894.md)


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
