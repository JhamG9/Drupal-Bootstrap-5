<?php

namespace Drupal\rip\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\rip\Batch\RipBatch;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a form for removing invalid permissions.
 */
class RipForm extends FormBase {

  /**
   * The RIP batch injection.
   *
   * @var \Drupal\rip\Batch\RipBatch
   */
  protected RipBatch $batch;

  /**
   * Constructs a new RipForm object.
   */
  public function __construct(RipBatch $batch) {
    $this->batch = $batch;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): static {
    return new static($container->get('rip.manager'));
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'rip_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {

    $form['description'] = [
      '#markup' => '<p>' . $this->t('Search and remove invalid permissions.') . '</p>',
    ];

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
      '#button_type' => 'primary',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $this->batch->start();
  }

}
