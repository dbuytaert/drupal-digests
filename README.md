**TL;DR:** [1171 summaries](https://github.com/dbuytaert/drupal-digests/blob/main/issues) of notable Drupal changes and [208 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules) to help you upgrade. Stay up to date about new additions using the [RSS feeds](#rss-feeds) below.

## Recent changes

AI-generated summaries of [notable Drupal commits](https://github.com/dbuytaert/drupal-digests/blob/main/issues), filtered by impact and community interest.

### Drupal Core

_609 summaries · 9 new this week_

- [#3585957: Prevent the navigation sidebar being rendered via big pipe](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3585957.md)
- [#3627057: Remove the navigation_top_bar module from core](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3627057.md)
- [#3516706: Disallow dangerous filenames e.g. command injection characters](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3516706.md)

### Drupal CMS

_106 summaries · 11 new this week_

- [#3591447: Add telemetry to Drupal CMS](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591447.md)
- [#3591382: Implement and document changing the web root during 'composer create-project'...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591382.md)
- [#3591482: Language-neutral content ("und") counts as a template language – Summit is...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591482.md)

### Drupal AI

_185 summaries · 16 new this week_

- [#3585940: The answer to an elicitation waits 30 seconds for the session lock over HTTP](https://github.com/dbuytaert/drupal-digests/blob/main/issues/mcp-server/3585940.md)
- [#3585936: ProtocolVersionMiddleware registered as a custom transport middleware rejects...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/mcp-server/3585936.md)
- [#3568162: Tool UI migration from MCP module](https://github.com/dbuytaert/drupal-digests/blob/main/issues/mcp-server/3568162.md)

### Drupal Canvas

_271 summaries · 8 new this week_

- [#3591874: Upgrade the Canvas UI to React Router 7](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3591874.md)
- [#3592142: ColorFormPopover resets edits in progress when the color list refetches](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592142.md)
- [#3592129: CanvasOauthAuthenticationProvider does not allow OAuth authentication on the...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592129.md)


## Rector rules

[Rector](https://getrector.com) can rewrite PHP code automatically, so you don't have to update deprecated API calls by hand. These [208 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules), extracted from Drupal core issues using AI, handle recent deprecations and new coding patterns.

```bash
git clone --depth 1 https://github.com/dbuytaert/drupal-digests.git
composer require --dev rector/rector

# Rewrite deprecated code (dry run first)
vendor/bin/rector process web/modules/custom \
  --config drupal-digests/rector/all.php --dry-run
```

### Latest rules
_208 rules · 0 new this week_

- [Remove deprecated no-op InstallerTestBase::setUpProfile() calls](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/remove-deprecated-no-op-installertestbase-setupprofile-calls-3520028.php)
- [Replace deprecated update.inc global functions with the DatabaseUpdate service](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/replace-deprecated-update-inc-global-functions-with-the-3391683.php)
- [Replace drupal_attach_tabledrag() with Table::attachTabledrag()](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/replace-drupal-attach-tabledrag-with-table-attachtabledrag-3035343.php)


## RSS feeds

- [Drupal Core](https://dbuytaert.github.io/drupal-digests/feeds/drupal-core.xml)
- [Drupal CMS](https://dbuytaert.github.io/drupal-digests/feeds/drupal-cms.xml)
- [Drupal Canvas](https://dbuytaert.github.io/drupal-digests/feeds/drupal-canvas.xml)
- [Drupal AI](https://dbuytaert.github.io/drupal-digests/feeds/drupal-ai.xml)
- [Rector rules](https://dbuytaert.github.io/drupal-digests/feeds/rector.xml)

---

*AI generated and may contain errors. Created by [Dries Buytaert](https://dri.es/).*
