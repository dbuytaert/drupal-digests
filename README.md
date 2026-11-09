**TL;DR:** [1562 summaries](https://github.com/dbuytaert/drupal-digests/blob/main/issues) of notable Drupal changes and [211 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules) to help you upgrade. Stay up to date about new additions using the [RSS feeds](#rss-feeds) below.

## Recent changes

AI-generated summaries of [notable Drupal commits](https://github.com/dbuytaert/drupal-digests/blob/main/issues), filtered by impact and community interest.

### Drupal AI

_472 summaries · 125 new this week_

- [#3586703: Diff pages show older context item revisions without the revision permission](https://github.com/dbuytaert/drupal-digests/blob/main/issues/ai-context/3586703.md)
- [#3586702: Usage records keep every request path with no limit on number or length](https://github.com/dbuytaert/drupal-digests/blob/main/issues/ai-context/3586702.md)
- [#3586701: Core "view any unpublished content" permission shows unpublished context items](https://github.com/dbuytaert/drupal-digests/blob/main/issues/ai-context/3586701.md)

### Drupal Canvas

_310 summaries · 11 new this week_

- [#3592222: Canvas AI: Stopped turn's message is lost when tool calls are kept in history](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592222.md)
- [#3592265: Improve headless component sync compatibility with page templates](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592265.md)
- [#3530051: Update `ComponentAudit::getConfigEntityDependenciesUsingComponent()` to support...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3530051.md)

### Drupal Core

_646 summaries · 8 new this week_

- [#3633459: Update CKEditor to 49.1.0](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3633459.md)
- [#3384819: Can't add class attribute to hr](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3384819.md)
- [#3619733: Deprecate .module file extension](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3619733.md)

### Drupal CMS

_134 summaries · 1 new this week_

- [#3591521: Remove the hardcoded LLM configuration since it will not work with Amazee](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591521.md)
- [#3591507: Add a hard opt-out for telemetry via settings.php](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591507.md)
- [#3591501: Add an event or param to track use of the launcher](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591501.md)


## Rector rules

[Rector](https://getrector.com) can rewrite PHP code automatically, so you don't have to update deprecated API calls by hand. These [211 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules), extracted from Drupal core issues using AI, handle recent deprecations and new coding patterns.

```bash
git clone --depth 1 https://github.com/dbuytaert/drupal-digests.git
composer require --dev rector/rector

# Rewrite deprecated code (dry run first)
vendor/bin/rector process web/modules/custom \
  --config drupal-digests/rector/all.php --dry-run
```

### Latest rules
_211 rules · 0 new this week_

- [Replace Schema::ensureNotNullPrimaryKey() with validatePrimaryKeySchema()](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/replace-schema-ensurenotnullprimarykey-with-3082239.php)
- [Rename hook_system_info_alter implementations to hook_extension_info_alter](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/rename-hook-system-info-alter-implementations-to-hook-3623862.php)
- [Remove deprecated $error_type argument from doTrustedCallback() calls](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/remove-deprecated-error-type-argument-from-3081025.php)


## RSS feeds

- [Drupal Core](https://dbuytaert.github.io/drupal-digests/feeds/drupal-core.xml)
- [Drupal CMS](https://dbuytaert.github.io/drupal-digests/feeds/drupal-cms.xml)
- [Drupal Canvas](https://dbuytaert.github.io/drupal-digests/feeds/drupal-canvas.xml)
- [Drupal AI](https://dbuytaert.github.io/drupal-digests/feeds/drupal-ai.xml)
- [Rector rules](https://dbuytaert.github.io/drupal-digests/feeds/rector.xml)

---

*AI generated and may contain errors. Created by [Dries Buytaert](https://dri.es/).*
