**TL;DR:** [1285 summaries](https://github.com/dbuytaert/drupal-digests/blob/main/issues) of notable Drupal changes and [209 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules) to help you upgrade. Stay up to date about new additions using the [RSS feeds](#rss-feeds) below.

## Recent changes

AI-generated summaries of [notable Drupal commits](https://github.com/dbuytaert/drupal-digests/blob/main/issues), filtered by impact and community interest.

### Drupal AI

_257 summaries · 64 new this week_

- [#3594805: Eval Commons schemas are out of date with the 1.1.x targets](https://github.com/dbuytaert/drupal-digests/blob/main/issues/ai-eval/3594805.md)
- [#3586791: Add Refusal Answer type to support OpenAI Decision API](https://github.com/dbuytaert/drupal-digests/blob/main/issues/ai/3586791.md)
- [#3589000: drupal-a11y-dynamic recommends FocusCommand, which does not exist in core (use...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/ai-best-practices/3589000.md)

### Drupal Core

_623 summaries · 10 new this week_

- [#3627629: Remove search module coverage from robots.txt](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3627629.md)
- [#3594335: Add a module and theme uninstall command](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3594335.md)
- [#3250827: [policy, no patch] Use declare(strict_types=1) in new code](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3250827.md)

### Drupal CMS

_123 summaries · 11 new this week_

- [#3591496: Drupal CMS 2.2.4 : Read-only installs have no site templates and the template...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591496.md)
- [#3591494: CMS 2.2.4 causes contrib DrupalCmsCompatibilityTest to fail. Replace with a new...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591494.md)
- [#3591424: Recipes cannot be installed via UI due to bug with...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591424.md)

### Drupal Canvas

_282 summaries · 5 new this week_

- [#3592127: Checkbox shows stale content after publishing the page](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592127.md)
- [#3592132: Allow modules to add operations to the Canvas content list](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592132.md)
- [#3591748: Hydrate components with diamond dependencies](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3591748.md)


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
