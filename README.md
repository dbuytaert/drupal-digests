**TL;DR:** [1718 summaries](https://github.com/dbuytaert/drupal-digests/blob/main/issues) of notable Drupal changes and [211 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules) to help you upgrade. Stay up to date about new additions using the [RSS feeds](#rss-feeds) below.

## Recent changes

AI-generated summaries of [notable Drupal commits](https://github.com/dbuytaert/drupal-digests/blob/main/issues), filtered by impact and community interest.

### Drupal CMS

_141 summaries · 4 new this week_

- [#3586147: Accessibility tools -- switch to Editoria11y 3.x](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3586147.md)
- [#3591532: Drupal CMS installer denies access after site template selection on Drupal core...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591532.md)
- [#3591533: setComponentTree fails when a second module with components is installed in the...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591533.md)

### Drupal Core

_663 summaries · 5 new this week_

- [#3638970: Update JavaScript dependencies for 12.0.0](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3638970.md)
- [#3639248: Remove deprecated class properties](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3639248.md)
- [#3638074: Update to Symfony 8.2.0-BETA2](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3638074.md)

### Drupal AI

_568 summaries · 52 new this week_

- [#3586711: Two CCC Scheduler permissions are never checked, and the item form saves...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/ai-context/3586711.md)
- [#3586721: Translators can change the shared scopes of a context item from the translation...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/ai-context/3586721.md)
- [#3586746: Site Section patterns do not match paths that have a language prefix](https://github.com/dbuytaert/drupal-digests/blob/main/issues/ai-context/3586746.md)

### Drupal Canvas

_346 summaries · 12 new this week_

- [#3592391: Workspaces PR 28: in a workspace the user can view, the Canvas API refuses...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592391.md)
- [#3592390: canvas_oauth should automatically create the CLI user, and consumer, upon...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592390.md)
- [#3592387: Workspaces PR 27: list, activate and delete only default-provider workspaces...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592387.md)


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
