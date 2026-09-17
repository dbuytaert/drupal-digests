# Drupal Digest dataset (`digest.db`)

A SQLite database with the Drupal Digest's summaries and its
runnable Rector rules. We publish it so you can query, visualize,
or build on the data however you like. Experiments, improvements,
and contributions are welcome.

One column needs care: `summaries.ai_disclosures` is a JSON list
of `{uid, username, tool, cid}` records, one per comment whose
author disclosed using AI for their work on the issue. NULL means
the comment thread could not be read (so nothing is known either
way) and must be excluded from any disclosure-rate denominator;
`[]` means the thread was read and nobody disclosed. It measures
disclosure, not AI use: absence of a disclosure is not evidence
that no AI was involved.
