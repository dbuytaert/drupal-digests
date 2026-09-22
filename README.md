**TL;DR:** [1080 summaries](https://github.com/dbuytaert/drupal-digests/blob/main/issues) of notable Drupal changes and [208 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules) to help you upgrade. Stay up to date about new additions using the [RSS feeds](#rss-feeds) below.

## Recent changes

AI-generated summaries of [notable Drupal commits](https://github.com/dbuytaert/drupal-digests/blob/main/issues), filtered by impact and community interest.

### Drupal AI

_141 summaries · 10 new this week_

- [#3583025: Show requirement status in tool:info, tool:list, and tool:search](https://github.com/dbuytaert/drupal-digests/blob/main/issues/tool-api/3583025.md)
- [#3585912: STDIO tool calls execute without an authenticated user, so permission-gated...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/mcp-server/3585912.md)
- [#3583023: Emit the JSON Schema from tool:info and pass a Drush invoker](https://github.com/dbuytaert/drupal-digests/blob/main/issues/tool-api/3583023.md)

### Drupal CMS

_95 summaries · 4 new this week_

- [#3591433: Add a drupal_cms_multilingual recipe with content translation features](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591433.md)
- [#3591466: Installing in another language does not translate shipped configuration: the...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591466.md)
- [#3591463: Fix contrast in "Add a page" button on /admin/dashboard](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591463.md)

### Drupal Canvas

_252 summaries · 5 new this week_

- [#3591957: Improve the Brand Kit fonts UI](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3591957.md)
- [#3592032: Canvas AI: Proper chat history management in the backend](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592032.md)
- [#3592096: Headless preview of homepage drafts can serve stale published content from cache](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592096.md)

### Drupal Core

_592 summaries · 11 new this week_

- [#3584639: Remove the Claro theme](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3584639.md)
- [#3576670: Deprecate Claro theme](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3576670.md)
- [#3586217: Remove the Search module](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3586217.md)


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
