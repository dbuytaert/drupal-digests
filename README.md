**TL;DR:** [1177 summaries](https://github.com/dbuytaert/drupal-digests/blob/main/issues) of notable Drupal changes and [209 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules) to help you upgrade. Stay up to date about new additions using the [RSS feeds](#rss-feeds) below.

## Recent changes

AI-generated summaries of [notable Drupal commits](https://github.com/dbuytaert/drupal-digests/blob/main/issues), filtered by impact and community interest.

### Drupal Core

_612 summaries · 11 new this week_

- [#3626105: Release runtime theme registries after destruction](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3626105.md)
- [#3568767: Deprecate Media::getRequestTime()](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3568767.md)
- [#3081025: Remove technical debt and complication from when doTrustedCallback() could...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3081025.md)

### Drupal Canvas

_273 summaries · 10 new this week_

- [#3592052: Site template installation fails with Canvas 1.11](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592052.md)
- [#3592145: canvas-page-variant.html.twig removes #main-content](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592145.md)
- [#3591874: Upgrade the Canvas UI to React Router 7](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3591874.md)

### Drupal CMS

_107 summaries · 5 new this week_

- [#3591486: Allow Tagify 2.0.x in drupal_cms_site_template_base](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591486.md)
- [#3591447: Add telemetry to Drupal CMS](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591447.md)
- [#3591382: Implement and document changing the web root during 'composer create-project'...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591382.md)

### Drupal AI

_185 summaries · 16 new this week_

- [#3585940: The answer to an elicitation waits 30 seconds for the session lock over HTTP](https://github.com/dbuytaert/drupal-digests/blob/main/issues/mcp-server/3585940.md)
- [#3585936: ProtocolVersionMiddleware registered as a custom transport middleware rejects...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/mcp-server/3585936.md)
- [#3568162: Tool UI migration from MCP module](https://github.com/dbuytaert/drupal-digests/blob/main/issues/mcp-server/3568162.md)


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
_209 rules · 1 new this week_

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
