**TL;DR:** [1024 summaries](https://github.com/dbuytaert/drupal-digests/blob/main/issues) of notable Drupal changes and [203 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules) to help you upgrade. Stay up to date about new additions using the [RSS feeds](#rss-feeds) below.

## Recent changes

AI-generated summaries of [notable Drupal commits](https://github.com/dbuytaert/drupal-digests/blob/main/issues), filtered by impact and community interest.

### Drupal Core

_559 summaries · 19 new this week_

- [#3561302: Register equivalent updates on site install](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3561302.md)
- [#3452493: Remove images that have been replaced with svg files](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3452493.md)
- [#2025089: Deprecate user_role_grant_permissions(), user_role_revoke_permissions() and...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/2025089.md)

### Drupal Canvas

_247 summaries · 4 new this week_

- [#3549232: Canvas AI: Updating page contents with agents](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3549232.md)
- [#3592001: Apply page variant translation overrides to previews](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592001.md)
- [#3592000: Translating a component tree config entity (such as PageVariant) that has an...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592000.md)

### Drupal AI

_127 summaries · 0 new this week_

- [#3471408: not_blank_constraint_rule fail on fields with multiple values](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3471408.md)
- [#3525460: Update symfony/expression-language to v7 (Compatibility with module_builder)](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3525460.md)
- [#3601404: Let a config name the command the agent hand-off runs, instead of only opening...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3601404.md)

### Drupal CMS

_91 summaries · 0 new this week_

- [#3591440: Fix installer page backgrounds and add interstitial at the end](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591440.md)
- [#3489408: Enable filenames sanitization](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3489408.md)
- [#3591420: Add Summit site template to site-templates.yml](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591420.md)


## Rector rules

[Rector](https://getrector.com) can rewrite PHP code automatically, so you don't have to update deprecated API calls by hand. These [203 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules), extracted from Drupal core issues using AI, handle recent deprecations and new coding patterns.

```bash
git clone --depth 1 https://github.com/dbuytaert/drupal-digests.git
composer require --dev rector/rector

# Rewrite deprecated code (dry run first)
vendor/bin/rector process web/modules/custom \
  --config drupal-digests/rector/all.php --dry-run
```

### Latest rules
_203 rules · 7 new this week_

- [Convert markFutureUpdateEquivalent() call to #[MarkFutureUpdateEquivalent]...](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/convert-markfutureupdateequivalent-call-to-3561302.php)
- [Replace user_role_*_permissions() functions with RoleInterface methods](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/replace-user-role-permissions-functions-with-roleinterface-2025089.php)
- [Rewrite deprecated update.compare.inc functions to...](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/rewrite-deprecated-update-compare-inc-functions-to-3580705.php)


## RSS feeds

- [Drupal Core](https://dbuytaert.github.io/drupal-digests/feeds/drupal-core.xml)
- [Drupal CMS](https://dbuytaert.github.io/drupal-digests/feeds/drupal-cms.xml)
- [Drupal Canvas](https://dbuytaert.github.io/drupal-digests/feeds/drupal-canvas.xml)
- [Drupal AI](https://dbuytaert.github.io/drupal-digests/feeds/drupal-ai.xml)
- [Rector rules](https://dbuytaert.github.io/drupal-digests/feeds/rector.xml)

---

*AI generated and may contain errors. Created by [Dries Buytaert](https://dri.es/).*
