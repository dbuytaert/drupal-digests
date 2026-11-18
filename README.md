**TL;DR:** [1681 summaries](https://github.com/dbuytaert/drupal-digests/blob/main/issues) of notable Drupal changes and [211 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules) to help you upgrade. Stay up to date about new additions using the [RSS feeds](#rss-feeds) below.

## Recent changes

AI-generated summaries of [notable Drupal commits](https://github.com/dbuytaert/drupal-digests/blob/main/issues), filtered by impact and community interest.

### Drupal AI

_547 summaries · 67 new this week_

- [#3583135: An optional map property's default value never reaches the tool](https://github.com/dbuytaert/drupal-digests/blob/main/issues/tool-api/3583135.md)
- [#3586867: Raise the contrast of muted and tinted text in the AI API Explorers](https://github.com/dbuytaert/drupal-digests/blob/main/issues/ai/3586867.md)
- [#3586876: Chat Explorer fails with "Drupal is not defined" because its library declares...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/ai/3586876.md)

### Drupal Canvas

_336 summaries · 22 new this week_

- [#3592359: Add a canvas_ssr submodule for server-side rendering of Code Components](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592359.md)
- [#3518272: Support all entity types with configurable displays for content templates](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3518272.md)
- [#3592207: Show notification and block user actions when maintenance mode is activated](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592207.md)

### Drupal Core

_661 summaries · 9 new this week_

- [#3638074: Update to Symfony 8.2.0-BETA2](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3638074.md)
- [#3634418: Implement better messaging for Assert for css in libraries discovery](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3634418.md)
- [#2703349: Show as expanded checkbox is not necessary when No menu entry is selected](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/2703349.md)

### Drupal CMS

_137 summaries · 2 new this week_

- [#3591524: Search is 404 for non top-level pages](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591524.md)
- [#3591527: 🤖 drupal_cms_site_template_base leaves two Navigation components enabled in...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591527.md)
- [#3591523: Add the Ágora Transparency site template to the installer](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591523.md)


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
