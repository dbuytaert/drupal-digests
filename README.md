**TL;DR:** [1275 summaries](https://github.com/dbuytaert/drupal-digests/blob/main/issues) of notable Drupal changes and [209 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules) to help you upgrade. Stay up to date about new additions using the [RSS feeds](#rss-feeds) below.

## Recent changes

AI-generated summaries of [notable Drupal commits](https://github.com/dbuytaert/drupal-digests/blob/main/issues), filtered by impact and community interest.

### Drupal CMS

_121 summaries · 12 new this week_

- [#3591424: Recipes cannot be installed via UI due to bug with...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591424.md)
- [#3591495: Telemetry: switch from opt-in to opt-out](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591495.md)
- [#3591493: Add package name to opt-in ping for telemetry](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591493.md)

### Drupal AI

_252 summaries · 66 new this week_

- [#3594800: Admin forms drop stored settings on save](https://github.com/dbuytaert/drupal-digests/blob/main/issues/ai-eval/3594800.md)
- [#3585947: Multi-round elicitation fails on protocol 2026-07-28 without a request state...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/mcp-server/3585947.md)
- [#3583032: Split title from description in the JSON Schema instead of prefixing the label...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/tool-api/3583032.md)

### Drupal Canvas

_281 summaries · 7 new this week_

- [#3592132: Allow modules to add operations to the Canvas content list](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592132.md)
- [#3591748: Hydrate components with diamond dependencies](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3591748.md)
- [#3591894: Error message for empty value is displayed for select props even when they're...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3591894.md)

### Drupal Core

_621 summaries · 8 new this week_

- [#3250827: [policy, no patch] Use declare(strict_types=1) in new code](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3250827.md)
- [#3625998: Update CKEditor5 version for Drupal core 10.6, 11.3, 11.4, 11.5](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3625998.md)
- [#3613347: Re-use container in kernel tests](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3613347.md)


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
