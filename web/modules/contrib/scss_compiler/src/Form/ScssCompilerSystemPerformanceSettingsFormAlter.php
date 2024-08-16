<?php

namespace Drupal\scss_compiler\Form;

use Drupal\Core\Form\FormStateInterface;

/**
 * Adds compiler settings to the performance page.
 *
 * Uses to reduce module file size.
 */
class ScssCompilerSystemPerformanceSettingsFormAlter {

  /**
   * Alters existing settings form.
   */
  public static function formAlter(&$form, FormStateInterface $form_state) {

    $scss_compiler = \Drupal::service('scss_compiler');
    $form['scss_compiler'] = [
      '#type' => 'details',
      '#title' => t('SCSS Compiler'),
      '#open' => TRUE,
      '#weight' => 0,
    ];

    $form['scss_compiler']['scss_cache'] = [
      '#type' => 'checkbox',
      '#title' => t('Enable compiler cache'),
      '#default_value' => $scss_compiler->isCacheEnabled(),
    ];

    $form['scss_compiler']['advanced'] = [
      '#type' => 'details',
      '#title' => t('Advanced'),
    ];

    $form['scss_compiler']['advanced']['scss_sourcemaps'] = [
      '#type' => 'checkbox',
      '#title' => t('Enable sourcemaps'),
      '#default_value' => $scss_compiler->getOption('sourcemaps'),
    ];

    $form['scss_compiler']['advanced']['scss_check_modify_time'] = [
      '#type' => 'checkbox',
      '#title' => t('Check file modified time'),
      '#description' => t('Compiles only files which was changed based on last modified time. Supports only 1 level import.'),
      '#default_value' => $scss_compiler->getOption('check_modify_time'),
    ];

    $form['scss_compiler']['advanced']['scss_output_format'] = [
      '#type' => 'select',
      '#title' => t('Output format'),
      '#description' => t('Default output format is compressed'),
      '#options' => [
        'expanded'    => 'Expanded',
        'nested'      => 'Nested',
        'compact'     => 'Compact',
        'compressed'  => 'Compressed',
        'crunched'    => 'Crunched',
      ],
      '#default_value' => $scss_compiler->getOption('output_format'),
    ];

    $form['scss_compiler']['advanced']['flush_cache_type'] = [
      '#type'     => 'select',
      '#title'    => t('Flush cache behaviour'),
      '#options'  => [
        'system'  => t('Delete old files on each system flush cache'),
        'default' => t('Delete old files on system flush cache if the compiler cache is disabled'),
        'manual'  => t('Delete old files only on manual recompile'),
      ],
      '#default_value' => $scss_compiler->getOption('flush_cache_type'),
    ];

    $form['scss_compiler']['advanced']['actions'] = [
      '#type' => 'actions',
      '#id'   => 'scss_compiler_actions',
      'recompile' => [
        '#type'   => 'submit',
        '#value'  => t('Recompile'),
        '#submit' => ['scss_compiler_recompile'],
      ],
    ];

    $compilerPluginManager = \Drupal::service('plugin.manager.scss_compiler');
    $compilers = $compilerPluginManager->getDefinitions();
    $options = [];
    if (!empty($compilers)) {
      foreach ($compilers as $compiler) {
        foreach ($compiler['extensions'] as $key => $extension) {
          $options[$key][$compiler['id']] = $compiler['name'];
        }
      }
    }

    $form['scss_compiler']['advanced']['node_modules_path'] = [
      '#type' => 'textfield',
      '#title' => t('Node modules path'),
      '#description' => t('For example, /usr/local/lib/node_modules'),
      '#default_value' => $scss_compiler->getOption('node_modules_path'),
    ];

    $form['scss_compiler']['advanced']['plugins'] = [
      '#type' => 'fieldset',
      '#title' => t('Default compilers'),
      '#tree' => TRUE,
    ];
    foreach ($options as $key => $option) {
      $plugins = $form_state->getValue('plugins');
      $active = !empty($plugins[$key]) ? $plugins[$key] : NULL;
      $status = NULL;
      if (empty($active)) {
        if (!empty($scss_compiler->getOption('plugins')[$key])) {
          $active = $scss_compiler->getOption('plugins')[$key];
        }
      }
      if (!empty($compilers[$active]['class'])) {
        $status = $compilers[$active]['class']::getStatus();
        if ($status === TRUE) {
          $status = $compilers[$active]['class']::getVersion();
        }
      }

      $form['scss_compiler']['advanced']['plugins'][$key] = [
        '#type' => 'select',
        '#options' => ['none' => t('Disabled')] + $option,
        '#title' => $key,
        '#default_value' => !empty($scss_compiler->getOption('plugins')[$key]) ? $scss_compiler->getOption('plugins')[$key] : 'none',
        '#field_suffix' => !empty($status) ? $status : '',
        '#ajax' => [
          'wrapper' => $form['#id'],
          'callback' => __CLASS__ . '::ajaxCallback',
        ],
      ];
    }

    $form['#submit'][] = __CLASS__ . '::submit';

  }

  /**
   * Plugins select ajax callback.
   */
  public static function ajaxCallback(&$form) {
    $form['scss_compiler']['advanced']['#open'] = TRUE;
    return $form;
  }

  /**
   * Saves scss compiler settings on form submit.
   */
  public static function submit(&$form, FormStateInterface $form_state) {
    $compilers = $form_state->getValue('plugins');
    foreach ($compilers as $key => $compiler) {
      if ($compiler === 'none') {
        unset($compilers[$key]);
      }
    }

    \Drupal::service('config.factory')->getEditable('scss_compiler.settings')
      ->set('cache', $form_state->getValue('scss_cache'))
      ->set('sourcemaps', $form_state->getValue('scss_sourcemaps'))
      ->set('output_format', $form_state->getValue('scss_output_format'))
      ->set('check_modify_time', $form_state->getValue('scss_check_modify_time'))
      ->set('plugins', $compilers)
      ->set('node_modules_path', $form_state->getValue('node_modules_path'))
      ->set('flush_cache_type', $form_state->getValue('flush_cache_type'))
      ->save();
  }

}
