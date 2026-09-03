**TL;DR:** [1011 summaries](https://github.com/dbuytaert/drupal-digests/blob/main/issues) of notable Drupal changes and [199 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules) to help you upgrade. Stay up to date about new additions using the [RSS feeds](#rss-feeds) below.

## Recent changes

AI-generated summaries of [notable Drupal commits](https://github.com/dbuytaert/drupal-digests/blob/main/issues), filtered by impact and community interest.

### Drupal Canvas

_247 summaries · 5 new this week_

- [#3549232: Canvas AI: Updating page contents with agents](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3549232.md)
- [#3592001: Apply page variant translation overrides to previews](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592001.md)
- [#3592000: Translating a component tree config entity (such as PageVariant) that has an...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592000.md)

### Drupal Core

_546 summaries · 22 new this week_

- [#3583153: Allow to invoke multiple implementations of a specific module with...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3583153.md)
- [#3375423: Deprecate file_managed_file_save_upload(), file_save_upload() and...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3375423.md)
- [#3549134: Move text_with_summary to its own module](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3549134.md)

### Drupal AI

_127 summaries · 5 new this week_

- [#3471408: not_blank_constraint_rule fail on fields with multiple values](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3471408.md)
- [#3525460: Update symfony/expression-language to v7 (Compatibility with module_builder)](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3525460.md)
- [#3601404: Let a config name the command the agent hand-off runs, instead of only opening...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3601404.md)

### Drupal CMS

_91 summaries · 1 new this week_

- [#3591440: Fix installer page backgrounds and add interstitial at the end](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591440.md)
- [#3489408: Enable filenames sanitization](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3489408.md)
- [#3591420: Add Summit site template to site-templates.yml](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591420.md)


## Rector rules

[Rector](https://getrector.com) can rewrite PHP code automatically, so you don't have to update deprecated API calls by hand. These [199 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules), extracted from Drupal core issues using AI, handle recent deprecations and new coding patterns.

```bash
git clone --depth 1 https://github.com/dbuytaert/drupal-digests.git
composer require --dev rector/rector

# Rewrite deprecated code (dry run first)
vendor/bin/rector process web/modules/custom \
  --config drupal-digests/rector/all.php --dry-run
```

### Latest rules
_199 rules · 6 new this week_

- [Replace deprecated locale.module global constants with class constant/enum...](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/replace-deprecated-locale-module-global-constants-with-2831617.php)
- [Replace deprecated file upload functions with service calls](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/replace-deprecated-file-upload-functions-with-service-calls-3375423.php)
- [Update references to text_with_summary field plugins moved out of the text...](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/update-references-to-text-with-summary-field-plugins-moved-3549134.php)


## RSS feeds

- [Drupal Core](https://dbuytaert.github.io/drupal-digests/feeds/drupal-core.xml)
- [Drupal CMS](https://dbuytaert.github.io/drupal-digests/feeds/drupal-cms.xml)
- [Drupal Canvas](https://dbuytaert.github.io/drupal-digests/feeds/drupal-canvas.xml)
- [Drupal AI](https://dbuytaert.github.io/drupal-digests/feeds/drupal-ai.xml)
- [Rector rules](https://dbuytaert.github.io/drupal-digests/feeds/rector.xml)

---

*AI generated and may contain errors. Created by [Dries Buytaert](https://dri.es/).*
