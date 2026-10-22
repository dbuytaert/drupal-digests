**TL;DR:** [1325 summaries](https://github.com/dbuytaert/drupal-digests/blob/main/issues) of notable Drupal changes and [209 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules) to help you upgrade. Stay up to date about new additions using the [RSS feeds](#rss-feeds) below.

## Recent changes

AI-generated summaries of [notable Drupal commits](https://github.com/dbuytaert/drupal-digests/blob/main/issues), filtered by impact and community interest.

### Drupal Canvas

_287 summaries · 5 new this week_

- [#3592226: Workspaces PR 3: workspace-scoped auto-save (the core change)](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592226.md)
- [#3592109: CLI pull/push loses entity references and options in component link props](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592109.md)
- [#3592225: Workspaces PR 3: workspace-scoped auto-save (the core change)](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592225.md)

### Drupal CMS

_129 summaries · 6 new this week_

- [#3591508: Trust the cweagans/composer-patches plugin by default](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591508.md)
- [#3591505: Update ping to weekly and add increment property](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591505.md)
- [#3591455: Update search recipe to create blocks instead of a views page](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591455.md)

### Drupal AI

_279 summaries · 22 new this week_

- [#3582968: Tool Explorer execute form: masked exception on access denial, duplicate...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/tool-api/3582968.md)
- [#3589006: Default skills output_directory to .claude/skills when Claude Code is detected](https://github.com/dbuytaert/drupal-digests/blob/main/issues/ai-best-practices/3589006.md)
- [#3583083: Entity input violation tells an in-process invoker that it cannot pass entities](https://github.com/dbuytaert/drupal-digests/blob/main/issues/tool-api/3583083.md)

### Drupal Core

_630 summaries · 7 new this week_

- [#3398033: Fix deprecated assert.active directive](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3398033.md)
- [#3471113: Update to selenium/standalone-chrome:134+](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3471113.md)
- [#3029364: "None" Argument Validator for Views Contains an Undefined Variable](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3029364.md)


## Rector rules

[Rector](https://getrector.com) can rewrite PHP code automatically, so you don't have to update deprecated API calls by hand. These [209 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules), extracted from Drupal core issues using AI, handle recent deprecations and new coding patterns.

```bash
git clone --depth 1 https://github.com/dbuytaert/drupal-digests.git
composer require --dev rector/rector

# Rewrite deprecated code (dry run first)
vendor/bin/rector process web/modules/custom \
  --config drupal-digests/rector/all.php --dry-run
```

### Latest rules
_209 rules · 0 new this week_

- [Remove deprecated $error_type argument from doTrustedCallback() calls](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/remove-deprecated-error-type-argument-from-3081025.php)
- [Remove deprecated no-op InstallerTestBase::setUpProfile() calls](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/remove-deprecated-no-op-installertestbase-setupprofile-calls-3520028.php)
- [Replace deprecated update.inc global functions with the DatabaseUpdate service](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/replace-deprecated-update-inc-global-functions-with-the-3391683.php)


## RSS feeds

- [Drupal Core](https://dbuytaert.github.io/drupal-digests/feeds/drupal-core.xml)
- [Drupal CMS](https://dbuytaert.github.io/drupal-digests/feeds/drupal-cms.xml)
- [Drupal Canvas](https://dbuytaert.github.io/drupal-digests/feeds/drupal-canvas.xml)
- [Drupal AI](https://dbuytaert.github.io/drupal-digests/feeds/drupal-ai.xml)
- [Rector rules](https://dbuytaert.github.io/drupal-digests/feeds/rector.xml)

---

*AI generated and may contain errors. Created by [Dries Buytaert](https://dri.es/).*
