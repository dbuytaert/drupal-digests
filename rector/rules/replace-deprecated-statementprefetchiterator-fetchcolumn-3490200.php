<?php
declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Replaces calls to the deprecated
 * StatementPrefetchIterator::fetchColumn() with fetchField(), which is
 * the recommended replacement. fetchColumn() was deprecated in Drupal
 * 11.2.0 and will be removed in Drupal 12.0.0. Both methods accept the
 * same optional column-index argument, so the rewrite is argument-
 * preserving.
 *
 * Before:
 *   $value = $stmt->fetchColumn();
 *   $value2 = $stmt->fetchColumn(2);
 *
 * After:
 *   $value = $stmt->fetchField();
 *   $value2 = $stmt->fetchField(2);
 *
 * @see https://www.drupal.org/node/3490200
 * @deprecated drupal:11.2.0
 * @removed drupal:12.0.0
 */


use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\ValueObject\MethodCallRename;

return RectorConfig::configure()
    ->withConfiguredRule(RenameMethodRector::class, [
        new MethodCallRename(
            'Drupal\\Core\\Database\\StatementPrefetchIterator',
            'fetchColumn',
            'fetchField',
        ),
    ]);
