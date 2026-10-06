**TL;DR:** [1182 summaries](https://github.com/dbuytaert/drupal-digests/blob/main/issues) of notable Drupal changes and [209 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules) to help you upgrade. Stay up to date about new additions using the [RSS feeds](#rss-feeds) below.

## Recent changes

AI-generated summaries of [notable Drupal commits](https://github.com/dbuytaert/drupal-digests/blob/main/issues), filtered by impact and community interest.

### Drupal AI

_186 summaries · 17 new this week_

- [#3585937: Tool call arguments and results end up in the Drupal log](https://github.com/dbuytaert/drupal-digests/blob/main/issues/mcp-server/3585937.md)
- [#3585940: The answer to an elicitation waits 30 seconds for the session lock over HTTP](https://github.com/dbuytaert/drupal-digests/blob/main/issues/mcp-server/3585940.md)
- [#3585936: ProtocolVersionMiddleware registered as a custom transport middleware rejects...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/mcp-server/3585936.md)

### Drupal Canvas

_274 summaries · 7 new this week_

- [#3591930: Apply brand kit colour changes optimistically](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3591930.md)
- [#3592052: Site template installation fails with Canvas 1.11](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592052.md)
- [#3592145: canvas-page-variant.html.twig removes #main-content](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592145.md)

### Drupal Core

_613 summaries · 12 new this week_

- [#3344629: Passing null to parameter #1 ($haystack) of type string is deprecated](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3344629.md)
- [#3626105: Release runtime theme registries after destruction](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3626105.md)
- [#3568767: Deprecate Media::getRequestTime()](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3568767.md)

### Drupal CMS

_109 summaries · 6 new this week_

- [#3591481: Web installer fails on Apache with mod_php: Composer needs HOME or COMPOSER_HOME](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591481.md)
- [#3591485: Support downloading necessary binaries into vendor/bin](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591485.md)
- [#3591486: Allow Tagify 2.0.x in drupal_cms_site_template_base](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591486.md)


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
