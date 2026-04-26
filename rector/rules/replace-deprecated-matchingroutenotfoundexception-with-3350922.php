<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3350922
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Replaces Drupal\Core\Routing\MatchingRouteNotFoundException with its
// parent class
// Symfony\Component\Routing\Exception\ResourceNotFoundException. The
// Drupal class was deprecated in drupal:11.1.0 and will be removed in
// drupal:12.0.0. Because it was an empty subclass of the Symfony
// exception, all usages (catch blocks, throw statements, type hints) are
// safely rewritten to the Symfony parent class.
//
// Before:
//   use Drupal\Core\Routing\MatchingRouteNotFoundException;
//   
//   try {
//     $url = Url::createFromRequest($request);
//   } catch (MatchingRouteNotFoundException $e) {
//     return;
//   }
//
// After:
//   use Symfony\Component\Routing\Exception\ResourceNotFoundException;
//   
//   try {
//     $url = Url::createFromRequest($request);
//   } catch (ResourceNotFoundException $e) {
//     return;
//   }


use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\Name\RenameClassRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(RenameClassRector::class, [
        'Drupal\Core\Routing\MatchingRouteNotFoundException' => 'Symfony\Component\Routing\Exception\ResourceNotFoundException',
    ]);
};
