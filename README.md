**TL;DR:** [749 summaries](https://github.com/dbuytaert/drupal-digests/blob/main/issues) of notable Drupal changes and [179 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules) to help you upgrade. Stay up to date about new additions using the [RSS feeds](#rss-feeds) below.

## Recent changes

AI-generated summaries of [notable Drupal commits](https://github.com/dbuytaert/drupal-digests/blob/main/issues), filtered by impact and community interest.

### Drupal Core

_534 summaries · 12 new this week_

- [#3609363: Regression: install_import_translations() no longer lets contrib/custom...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3609363.md)
- [#3609087: \Drupal\locale\LocaleProjectRepository::buildProjects() does not respect weight...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3609087.md)
- [#3609020: BC layer in locale_translation_get_projects() implemented incorrectly](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3609020.md)

### Drupal Canvas

_111 summaries · 13 new this week_

- [#3544213: Harden XB content dependency calculation infrastructure to not cause a PHP...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3544213.md)
- [#3583854: [upstream] Validate LanguageConfigOverrides targeting Canvas config entities](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3583854.md)
- [#3591789: Deleting a content entity translation leaves its auto-save snapshot behind;...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3591789.md)

### Drupal AI

_87 summaries · 1 new this week_

- [#3548526: Support File Handling](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3548526.md)
- [#3600886: Add an "AI audio" field validation constraint (speech-to-text)](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3600886.md)
- [#3595515: Add AI Text Classification field validation rule (mirror of AI Image...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3595515.md)

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
