**TL;DR:** [999 summaries](https://github.com/dbuytaert/drupal-digests/blob/main/issues) of notable Drupal changes and [195 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules) to help you upgrade. Stay up to date about new additions using the [RSS feeds](#rss-feeds) below.

## Recent changes

AI-generated summaries of [notable Drupal commits](https://github.com/dbuytaert/drupal-digests/blob/main/issues), filtered by impact and community interest.

### Drupal AI

_127 summaries · 6 new this week_

- [#3471408: not_blank_constraint_rule fail on fields with multiple values](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3471408.md)
- [#3525460: Update symfony/expression-language to v7 (Compatibility with module_builder)](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3525460.md)
- [#3601404: Let a config name the command the agent hand-off runs, instead of only opening...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3601404.md)

### Drupal Core

_538 summaries · 30 new this week_

- [#3616645: Form #value_callback does not support CallableResolver-style callables](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3616645.md)
- [#3580703: Deprecate update.module functions](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3580703.md)
- [#3584347: Deprecate and replace system_admin_compact_mode()](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3584347.md)

### Drupal Canvas

_243 summaries · 2 new this week_

- [#3591981: Provide consistent Code Component metadata validation across CLI and Drupal](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3591981.md)
- [#3591972: Code editor has no "Document" prop type for the document object shape](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3591972.md)
- [#3591970: Components library UI is broken if a single component preview rendering fails:...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3591970.md)

### Drupal CMS

_91 summaries · 1 new this week_

- [#3591440: Fix installer page backgrounds and add interstitial at the end](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591440.md)
- [#3489408: Enable filenames sanitization](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3489408.md)
- [#3591420: Add Summit site template to site-templates.yml](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591420.md)


## Rector rules

[Rector](https://getrector.com) can rewrite PHP code automatically, so you don't have to update deprecated API calls by hand. These [195 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules), extracted from Drupal core issues using AI, handle recent deprecations and new coding patterns.

```bash
git clone --depth 1 https://github.com/dbuytaert/drupal-digests.git
composer require --dev rector/rector

# Rewrite deprecated code (dry run first)
vendor/bin/rector process web/modules/custom \
  --config drupal-digests/rector/all.php --dry-run
```

### Latest rules
_195 rules · 6 new this week_

- [Replace deprecated update.module global functions with service calls](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/replace-deprecated-update-module-global-functions-with-3580703.php)
- [Replace user_login_finalize() and user_logout() with services](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/replace-user-login-finalize-and-user-logout-with-services-2012976.php)
- [Replace _user_mail_notify() calls with NotificationHandler methods](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/replace-user-mail-notify-calls-with-notificationhandler-3539178.php)


## RSS feeds

- [Drupal Core](https://dbuytaert.github.io/drupal-digests/feeds/drupal-core.xml)
- [Drupal CMS](https://dbuytaert.github.io/drupal-digests/feeds/drupal-cms.xml)
- [Drupal Canvas](https://dbuytaert.github.io/drupal-digests/feeds/drupal-canvas.xml)
- [Drupal AI](https://dbuytaert.github.io/drupal-digests/feeds/drupal-ai.xml)
- [Rector rules](https://dbuytaert.github.io/drupal-digests/feeds/rector.xml)

---

*AI generated and may contain errors. Created by [Dries Buytaert](https://dri.es/).*
