**TL;DR:** [775 summaries](https://github.com/dbuytaert/drupal-digests/blob/main/issues) of notable Drupal changes and [179 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules) to help you upgrade. Stay up to date about new additions using the [RSS feeds](#rss-feeds) below.

## Recent changes

AI-generated summaries of [notable Drupal commits](https://github.com/dbuytaert/drupal-digests/blob/main/issues), filtered by impact and community interest.

### Drupal AI

_96 summaries · 10 new this week_

- [#3601371: Make the build script only move the main script on tags](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3601371.md)
- [#3601370: Agent Ready Drupal Build Kit](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3601370.md)
- [#3601332: Adopt Bashly (bashly.dev) to generate the installer from a single YAML-defined...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3601332.md)

### Drupal Core

_551 summaries · 19 new this week_

- [#3532506: Never bypass basic validation when saving a config object](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3532506.md)
- [#3603333: Errors when stream wrappers instantiate services in constructors because...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3603333.md)
- [#3606411: Return the correct typehint when Drupal::classResolver() is called with a class...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3606411.md)

### Drupal Canvas

_111 summaries · 8 new this week_

- [#3544213: Harden XB content dependency calculation infrastructure to not cause a PHP...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3544213.md)
- [#3583854: [upstream] Validate LanguageConfigOverrides targeting Canvas config entities](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3583854.md)
- [#3591789: Deleting a content entity translation leaves its auto-save snapshot behind;...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3591789.md)

### Drupal CMS

_17 summaries · 0 new this week_

- [#3591409: Unable to add components to any Canvas pages in any site template](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591409.md)
- [#3483394: Build privacy advanced recipe](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3483394.md)
- [#3583960: Replace friendlycaptcha with ALTCHA](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3583960.md)


## Rector rules

[Rector](https://getrector.com) can rewrite PHP code automatically, so you don't have to update deprecated API calls by hand. These [179 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules), extracted from Drupal core issues using AI, handle recent deprecations and new coding patterns.

```bash
git clone --depth 1 https://github.com/dbuytaert/drupal-digests.git
composer require --dev rector/rector

# Rewrite deprecated code (dry run first)
vendor/bin/rector process web/modules/custom \
  --config drupal-digests/rector/all.php --dry-run
```

### Latest rules
_179 rules · 1 new this week_

- [Add missing $imageDerivativeUtilities argument to ImageFormatter constructor...](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/add-missing-imagederivativeutilities-argument-to-3609124.php)
- [Replace deprecated locale submit callbacks with service method calls](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/replace-deprecated-locale-submit-callbacks-with-service-3595084.php)
- [Replace CsrfTokenGenerator::get('rest') with TOKEN_KEY constant](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/replace-csrftokengenerator-get-rest-with-token-key-constant-3585891.php)


## RSS feeds

- [Drupal Core](https://dbuytaert.github.io/drupal-digests/feeds/drupal-core.xml)
- [Drupal CMS](https://dbuytaert.github.io/drupal-digests/feeds/drupal-cms.xml)
- [Drupal Canvas](https://dbuytaert.github.io/drupal-digests/feeds/drupal-canvas.xml)
- [Drupal AI](https://dbuytaert.github.io/drupal-digests/feeds/drupal-ai.xml)
- [Rector rules](https://dbuytaert.github.io/drupal-digests/feeds/rector.xml)

---

*AI generated and may contain errors. Created by [Dries Buytaert](https://dri.es/).*
