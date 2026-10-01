**TL;DR:** [1157 summaries](https://github.com/dbuytaert/drupal-digests/blob/main/issues) of notable Drupal changes and [208 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules) to help you upgrade. Stay up to date about new additions using the [RSS feeds](#rss-feeds) below.

## Recent changes

AI-generated summaries of [notable Drupal commits](https://github.com/dbuytaert/drupal-digests/blob/main/issues), filtered by impact and community interest.

### Drupal Core

_605 summaries · 8 new this week_

- [#2265487: ConfigEntity based lists with items containing non-ascii characters are not...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/2265487.md)
- [#3626470: Review Drupal 12 and 11.x composer dependencies for allowed versions with CVEs](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3626470.md)
- [#3507570: Allow recipe to add multiple buttons to CKEditor toolbar](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3507570.md)

### Drupal AI

_180 summaries · 26 new this week_

- [#3583079: A denied Explorer execute submission reports an internal exception message and...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/tool-api/3583079.md)
- [#3583077: Invokers must check the declared permission before processing caller input](https://github.com/dbuytaert/drupal-digests/blob/main/issues/tool-api/3583077.md)
- [#3583071: Inputs and outputs declared with data type "map" are advertised as strings on...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/tool-api/3583071.md)

### Drupal Canvas

_269 summaries · 10 new this week_

- [#3592129: CanvasOauthAuthenticationProvider does not allow OAuth authentication on the...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592129.md)
- [#3592002: Normalize agent-written prop values against their schema and report values the...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592002.md)
- [#3592057: Support mapping multi-valued list fields to array-type component props](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592057.md)

### Drupal CMS

_103 summaries · 8 new this week_

- [#3591479: Add the Varbase Starter site template to the installer](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591479.md)
- [#3591471: Add the Hourglass Corporate and Goodwell site templates to the installer](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591471.md)
- [#3591475: Add the Horizon Aid site template to the installer](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591475.md)


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
