**TL;DR:** [1198 summaries](https://github.com/dbuytaert/drupal-digests/blob/main/issues) of notable Drupal changes and [209 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules) to help you upgrade. Stay up to date about new additions using the [RSS feeds](#rss-feeds) below.

## Recent changes

AI-generated summaries of [notable Drupal commits](https://github.com/dbuytaert/drupal-digests/blob/main/issues), filtered by impact and community interest.

### Drupal AI

_194 summaries · 14 new this week_

- [#3588987: Add Config Split guidance to the drupal-configuration skill](https://github.com/dbuytaert/drupal-digests/blob/main/issues/ai-best-practices/3588987.md)
- [#3583094: A required input explicitly set to NULL throws ContextException from execute()...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/tool-api/3583094.md)
- [#3585928: Session lock delays elicitation and sampling replies until the lease expires](https://github.com/dbuytaert/drupal-digests/blob/main/issues/mcp-server/3585928.md)

### Drupal Core

_615 summaries · 10 new this week_

- [#3625895: Deprecate and remove Query::__wakeup() and ::__sleep()](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3625895.md)
- [#1411074: Allow kernel tests to share the test environment](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/1411074.md)
- [#3344629: Passing null to parameter #1 ($haystack) of type string is deprecated](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3344629.md)

### Drupal Canvas

_277 summaries · 8 new this week_

- [#3591894: Error message for empty value is displayed for select props even when they're...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3591894.md)
- [#3591848: ∅ is added for empty fields, forcing translation and rendering on the page](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3591848.md)
- [#3592084: Canvas entity edit/delete/translation routes render in the front-end theme...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592084.md)

### Drupal CMS

_112 summaries · 9 new this week_

- [#3591490: Add an initial 'ping' when opting into telemetry](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591490.md)
- [#3591489: Use the Tagify User List widget by default for user reference fields](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591489.md)
- [#3591488: Add telemetry events for Project Browser](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591488.md)


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
_209 rules · 1 new this week_

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
