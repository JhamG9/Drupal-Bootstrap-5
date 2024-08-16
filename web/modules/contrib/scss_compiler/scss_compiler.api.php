<?php

/**
 * @file
 * Hooks related to SCSS compiler module.
 */

/**
 * Add additional scss import paths.
 *
 * For example need to import Foundation framework into your scss file, you can
 * define path where framework place and use @import foundation.
 *
 * @param array $additional_import_paths
 *   The array with additional paths.
 */
function hook_scss_compiler_import_paths_alter(array &$additional_import_paths) {
  $additional_import_paths[] = \Drupal::service('file_system')->realpath('vendor/zurb/foundation/scss');
}

/**
 * Alter compiler variables.
 *
 * @param \Drupal\scss_compiler\ScssCompilerAlterStorage $storage
 *   Storage with variables.
 */
function hook_scss_compiler_variables_alter(\Drupal\scss_compiler\ScssCompilerAlterStorage $storage) {

  // Alter variables in all files.
  $storage->set([
    'mainColor' => '#f00',
  ]);

  // Alter variables based on module/theme name. As example alter variables in
  // all files which defined in my_module.
  $storage->set([
    'mainColor' => '#f00',
  ], 'my_module');

  // Alter variables based on file path. As example alter variables on
  // styles.scss in my_module. Supports tokens like @my_module.
  $storage->setByFile([
    'mainColor' => '#f00',
  ], 'modules/custom/my_module/styles.scss');
  $storage->setByFile([
    'mainColor' => '#f00',
  ], '@my_module/styles.scss');

}
