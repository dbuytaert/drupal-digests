**TL;DR:** [793 summaries](https://github.com/dbuytaert/drupal-digests/blob/main/issues) of notable Drupal changes and [181 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules) to help you upgrade. Stay up to date about new additions using the [RSS feeds](#rss-feeds) below.

## Recent changes

AI-generated summaries of [notable Drupal commits](https://github.com/dbuytaert/drupal-digests/blob/main/issues), filtered by impact and community interest.

### Drupal Core

_561 summaries · 18 new this week_

- [#3611853: Correct error deprecation message for...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3611853.md)
- [#3611529: Moderation state field definitions can have an incorrect target bundle](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3611529.md)
- [#3595652: Deprecate module.inc contents](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3595652.md)

### Drupal AI

_103 summaries · 14 new this week_

- [#3609590: Switch to official MCP PHP SDK Client](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3609590.md)
- [#3588043: Add drupal-gitlab skill: GitLab CLI guidance tailored to git.drupalcode.org](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3588043.md)
- [#3601377: Support conditional (depends_on) custom questions in .drupalaibp.json](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3601377.md)

### Drupal Canvas

_112 summaries · 1 new this week_

- [#3549885: Prevent deleting Code Components if there are usages in forward revisions](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3549885.md)
- [#3544213: Harden XB content dependency calculation infrastructure to not cause a PHP...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3544213.md)
- [#3583854: [upstream] Validate LanguageConfigOverrides targeting Canvas config entities](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3583854.md)

### Drupal CMS

_17 summaries · 0 new this week_

- [#3591409: Unable to add components to any Canvas pages in any site template](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591409.md)
- [#3483394: Build privacy advanced recipe](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3483394.md)
- [#3583960: Replace friendlycaptcha with ALTCHA](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3583960.md)


## Rector rules

[Rector](https://getrector.com) can rewrite PHP code automatically, so you don't have to update deprecated API calls by hand. These [181 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules), extracted from Drupal core issues using AI, handle recent deprecations and new coding patterns.

```bash
git clone --depth 1 https://github.com/dbuytaert/drupal-digests.git
composer require --dev rector/rector

# Rewrite deprecated code (dry run first)
vendor/bin/rector process web/modules/custom \
  --config drupal-digests/rector/all.php --dry-run
```

### Latest rules
_181 rules · 2 new this week_

- [Replace module_set_weight() and module_config_sort() with ModuleWeight service](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/replace-module-set-weight-and-module-config-sort-with-3595652.php)
- [Replace Htmx::triggerAfterSettleHeader() and triggerAfterSwapHeader() with...](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/replace-htmx-triggeraftersettleheader-and-3607711.php)
- [Add missing $imageDerivativeUtilities argument to ImageFormatter constructor...](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/add-missing-imagederivativeutilities-argument-to-3609124.php)


## RSS feeds

- [Drupal Core](https://dbuytaert.github.io/drupal-digests/feeds/drupal-core.xml)
- [Drupal CMS](https://dbuytaert.github.io/drupal-digests/feeds/drupal-cms.xml)
- [Drupal Canvas](https://dbuytaert.github.io/drupal-digests/feeds/drupal-canvas.xml)
- [Drupal AI](https://dbuytaert.github.io/drupal-digests/feeds/drupal-ai.xml)
- [Rector rules](https://dbuytaert.github.io/drupal-digests/feeds/rector.xml)

---

*AI generated and may contain errors. Created by [Dries Buytaert](https://dri.es/).*
