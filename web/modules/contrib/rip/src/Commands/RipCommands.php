<?php

namespace Drupal\rip\Commands;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\user\PermissionHandler;
use Drush\Commands\DrushCommands;

/**
 * A Drush command file.
 *
 * @package Drupal\rip\Commands
 */
class RipCommands extends DrushCommands {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected EntityTypeManagerInterface $entityTypeManager;

  /**
   * The permission handler.
   *
   * @var \Drupal\user\PermissionHandler
   */
  protected PermissionHandler $permissionHandler;

  /**
   * Constructs a new RipCommands object.
   */
  public function __construct(
      EntityTypeManagerInterface $entity_type_manager,
      PermissionHandler $permission_handler
  ) {
    parent::__construct();
    $this->entityTypeManager = $entity_type_manager;
    $this->permissionHandler = $permission_handler;
  }

  /**
   * Removes the invalid permissions from roles.
   *
   * @command remove-invalid-permissions
   * @aliases rip
   * @usage remove-invalid-permissions
   *   Removes invalid permissions.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public function removeInvalidPermissions(): void {
    $permissions = array_keys($this->permissionHandler->getPermissions());

    $roles = $this->entityTypeManager
      ->getStorage('user_role')
      ->loadMultiple();

    /** @var \Drupal\user\RoleInterface[] $roles */
    foreach ($roles as $role) {
      $role_permissions = $role->getPermissions();
      $diff_permissions_in_role = array_diff($role_permissions, $permissions);

      if ($diff_permissions_in_role) {

        foreach ($diff_permissions_in_role as $permission) {

          $confirm = $this->io()->confirm('Remove ' . $permission . ' for ' . $role->id() . '?');
          if ($confirm) {
            $this->io()->note('Removed');
            $role->revokePermission($permission);
          }
        }

        $role->save();
      }
    }

    $this->logger()?->success('Invalid permissions removed.');
  }

}
