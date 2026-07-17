**TL;DR:** [779 summaries](https://github.com/dbuytaert/drupal-digests/blob/main/issues) of notable Drupal changes and [179 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules) to help you upgrade. Stay up to date about new additions using the [RSS feeds](#rss-feeds) below.

## Recent changes

AI-generated summaries of [notable Drupal commits](https://github.com/dbuytaert/drupal-digests/blob/main/issues), filtered by impact and community interest.

### Drupal Core

_552 summaries · 16 new this week_

- [#2863785: Avoid PHP notice when comparing config without uuid](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/2863785.md)
- [#3532506: Never bypass basic validation when saving a config object](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3532506.md)
- [#3603333: Errors when stream wrappers instantiate services in constructors because...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3603333.md)

### Drupal AI

_99 summaries · 12 new this week_

- [#3601375: Post-install hand-off: open the project shell on inactivity instead of on Enter](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3601375.md)
- [#3601376: Allow .drupalaibp.json to append or replace the welcome text](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3601376.md)
- [#3601363: Add --config-path to install from a local, HTTPS, or repo-hosted...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3601363.md)

### Drupal Canvas

_111 summaries · 0 new this week_

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
_179 rules · 0 new this week_

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
