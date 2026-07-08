**TL;DR:** [739 summaries](https://github.com/dbuytaert/drupal-digests/blob/main/issues) of notable Drupal changes and [178 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules) to help you upgrade. Stay up to date about new additions using the [RSS feeds](#rss-feeds) below.

## Recent changes

AI-generated summaries of [notable Drupal commits](https://github.com/dbuytaert/drupal-digests/blob/main/issues), filtered by impact and community interest.

### Drupal Core

_533 summaries · 14 new this week_

- [#3609087: \Drupal\locale\LocaleProjectRepository::buildProjects() does not respect weight...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3609087.md)
- [#3609020: BC layer in locale_translation_get_projects() implemented incorrectly](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3609020.md)
- [#3605554: Translations are never loaded or downloaded for a custom profile](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3605554.md)

### Drupal Canvas

_103 summaries · 6 new this week_

- [#3591669: Add heuristics for exposing fields/props to the content-entity-reference...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3591669.md)
- [#3591727: Deleting optional props from an SDC/code component causes validation errors in...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3591727.md)
- [#3591601: Rudimentary conflict resolution UI](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3591601.md)

### Drupal AI

_86 summaries · 3 new this week_

- [#3600886: Add an "AI audio" field validation constraint (speech-to-text)](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3600886.md)
- [#3595515: Add AI Text Classification field validation rule (mirror of AI Image...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3595515.md)
- [#3600885: Add an "AI moderation" field validation constraint](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3600885.md)

### Drupal CMS

_17 summaries · 0 new this week_

- [#3591409: Unable to add components to any Canvas pages in any site template](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591409.md)
- [#3483394: Build privacy advanced recipe](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3483394.md)
- [#3583960: Replace friendlycaptcha with ALTCHA](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3583960.md)


## Rector rules

[Rector](https://getrector.com) can rewrite PHP code automatically, so you don't have to update deprecated API calls by hand. These [178 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules), extracted from Drupal core issues using AI, handle recent deprecations and new coding patterns.

```bash
git clone --depth 1 https://github.com/dbuytaert/drupal-digests.git
composer require --dev rector/rector

# Rewrite deprecated code (dry run first)
vendor/bin/rector process web/modules/custom \
  --config drupal-digests/rector/all.php --dry-run
```

### Latest rules
_178 rules · 0 new this week_

- [Replace deprecated locale submit callbacks with service method calls](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/replace-deprecated-locale-submit-callbacks-with-service-3595084.php)
- [Replace CsrfTokenGenerator::get('rest') with TOKEN_KEY constant](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/replace-csrftokengenerator-get-rest-with-token-key-constant-3585891.php)
- [Replace deprecated locale translation status functions with LocaleSource service](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/replace-deprecated-locale-translation-status-functions-with-3590050.php)


## RSS feeds

- [Drupal Core](https://dbuytaert.github.io/drupal-digests/feeds/drupal-core.xml)
- [Drupal CMS](https://dbuytaert.github.io/drupal-digests/feeds/drupal-cms.xml)
- [Drupal Canvas](https://dbuytaert.github.io/drupal-digests/feeds/drupal-canvas.xml)
- [Drupal AI](https://dbuytaert.github.io/drupal-digests/feeds/drupal-ai.xml)
- [Rector rules](https://dbuytaert.github.io/drupal-digests/feeds/rector.xml)

---

*AI generated and may contain errors. Created by [Dries Buytaert](https://dri.es/).*
