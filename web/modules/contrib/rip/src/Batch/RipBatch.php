<?php

namespace Drupal\rip\Batch;

use Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException;
use Drupal\Component\Plugin\Exception\PluginNotFoundException;
use Drupal\Core\Batch\BatchBuilder;
use Drupal\Core\DependencyInjection\DependencySerializationTrait;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\user\PermissionHandler;
use Drupal\user\RoleInterface;

/**
 * Provides a batch to remove invalid permissions.
 */
final class RipBatch {

  use DependencySerializationTrait;

  /**
   * The messenger.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  private MessengerInterface $messenger;

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  private EntityTypeManagerInterface $entityTypeManager;

  /**
   * The permission handler.
   *
   * @var \Drupal\user\PermissionHandler
   */
  private PermissionHandler $permissionHandler;

  /**
   * The batch builder.
   *
   * @var \Drupal\Core\Batch\BatchBuilder
   */
  private BatchBuilder $batchBuilder;

  /**
   * The number of updated roles.
   *
   * @var int
   */
  public int $roles = 0;

  /**
   * The number of removed permissions.
   *
   * @var int
   */
  public int $permissions = 0;

  /**
   * Constructs a new RipBatch object.
   */
  public function __construct(
      MessengerInterface $messenger,
      EntityTypeManagerInterface $entity_type_manager,
      PermissionHandler $permission_handler
  ) {
    $this->messenger = $messenger;
    $this->entityTypeManager = $entity_type_manager;
    $this->permissionHandler = $permission_handler;
    $this->batchBuilder = new BatchBuilder();
  }

  /**
   * The Starting point for batch.
   */
  public function start(): void {

    $this->batchBuilder
      ->setTitle('RIP Batch')
      ->setInitMessage('Initializing. <br/><b style="color: #f00;">Navigating away will stop the process.</b>')
      ->setProgressMessage('Completed @current of @total. <br/><b style="color: #f00;">Navigating away will stop the process.</b>')
      ->setErrorMessage('Batch has encountered an error.');

    $permissions = array_keys($this->permissionHandler->getPermissions());

    try {
      $roles = $this->entityTypeManager
        ->getStorage('user_role')
        ->loadMultiple();
    }
    catch (InvalidPluginDefinitionException | PluginNotFoundException $e) {
      $this->messenger->addMessage('Unabled to load roles.', 'error');
      $roles = [];
    }

    /** @var \Drupal\user\RoleInterface[] $roles */
    foreach ($roles as $role) {
      $role_permissions = $role->getPermissions();
      $diff_permissions_in_role = array_diff($role_permissions, $permissions);
      if ($diff_permissions_in_role) {
        $this->batchBuilder->addOperation([$this, 'processFindingPermissions'], [
          $role, $diff_permissions_in_role,
        ]);
      }
      else {
        $this->messenger->addMessage('No invalid permissions exists');
      }
      $this->roles++;
    }

    $this->batchBuilder->setFinishCallback([$this, 'finish']);
    $batch = $this->batchBuilder->toArray();
    batch_set($batch);
  }

  /**
   * Batch processor.
   *
   * @param \Drupal\user\RoleInterface $role
   *   The role containing invalid permissions.
   * @param array|null $diff_permissions_in_role
   *   Invalid permissions.
   * @param array $context
   *   The context array.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public function processFindingPermissions(
    RoleInterface $role,
    array|null $diff_permissions_in_role,
    array &$context
  ): void {
    foreach ($diff_permissions_in_role ?? [] as $permission) {
      $role->revokePermission($permission);
      $this->permissions++;
    }
    $role->save();
  }

  /**
   * Batch finish callback.
   *
   * @param bool $success
   *   If success.
   * @param array $results
   *   Results.
   * @param array $operations
   *   Batch operations.
   */
  public function finish(
    bool $success,
    array $results,
    array $operations
  ): void {
    if ($success) {
      if ($this->permissions > 0) {
        $message = "{$this->permissions} Invalid permissions from {$this->roles} Roles successfully removed.";
        $this->messenger->addMessage($message);
      }
    }
    else {
      $error_operation = reset($operations);
      $arguments = print_r($error_operation[1], TRUE);
      $message = "An error occurred while processing {$error_operation[0]} with arguments: {$arguments}";
      $this->messenger->addMessage($message, 'error');
    }
  }

}
