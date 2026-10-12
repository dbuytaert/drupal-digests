**TL;DR:** [1257 summaries](https://github.com/dbuytaert/drupal-digests/blob/main/issues) of notable Drupal changes and [209 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules) to help you upgrade. Stay up to date about new additions using the [RSS feeds](#rss-feeds) below.

## Recent changes

AI-generated summaries of [notable Drupal commits](https://github.com/dbuytaert/drupal-digests/blob/main/issues), filtered by impact and community interest.

### Drupal AI

_239 summaries · 54 new this week_

- [#3586640: [Meta] ai_disclosure: EU AI Act Article 50 transparency module](https://github.com/dbuytaert/drupal-digests/blob/main/issues/ai-initiative/3586640.md)
- [#3594782: --as-user and caller_uid do not switch the Drupal account](https://github.com/dbuytaert/drupal-digests/blob/main/issues/ai-eval/3594782.md)
- [#3594783: The run drops the agent's own error when it does not answer](https://github.com/dbuytaert/drupal-digests/blob/main/issues/ai-eval/3594783.md)

### Drupal CMS

_119 summaries · 12 new this week_

- [#3591493: Add package name to opt-in ping for telemetry](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591493.md)
- [#3591491: 🤖 AI-assisted review of the telemetry feature](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591491.md)
- [#3591492: drupal_cms_site_template_base 2.2.3 requires Tagify 2, so 11 site templates of...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591492.md)

### Drupal Core

_620 summaries · 8 new this week_

- [#3625998: Update CKEditor5 version for Drupal core 10.6, 11.3, 11.4, 11.5](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3625998.md)
- [#3613347: Re-use container in kernel tests](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3613347.md)
- [#3594337: Add a command to export configuration](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3594337.md)

### Drupal Canvas

_279 summaries · 6 new this week_

- [#3591894: Error message for empty value is displayed for select props even when they're...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3591894.md)
- [#3591848: ∅ is added for empty fields, forcing translation and rendering on the page](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3591848.md)
- [#3592084: Canvas entity edit/delete/translation routes render in the front-end theme...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592084.md)


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
