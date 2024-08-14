<?php 

class AccountSettingsForm{

    public function __construct(EntityTypeManagerInterface $entity_type_manager, PermissionHandler $permission_handler) {
        $this->entityTypeManager = $entity_type_manager;
        $this->permissionHandler = $permission_handler;
        $this->roleStorage = $entity_type_manager->getStorage('user_role');
      }

    public function cleanPermissions() {
        $permissions = array_keys($this->permissionHandler->getPermissions());
        /** @var \Drupal\user\Entity\Role[] $roles */
        $roles = $this->roleStorage->loadMultiple();
    
        /** @var \Drupal\user\RoleStorageInterface $role */
        foreach ($roles as $role) {
          $role_permissions = $role->getPermissions();
          $diff_permissions_in_role = array_diff($role_permissions, $permissions);
          if ($diff_permissions_in_role) {
            foreach ($diff_permissions_in_role as $permission) {
              $confirm = $this->confirm('Revoke ' . $permission . ' for ' . $role->id() . '?');
              if ($confirm) {
                $this->io()->note('Revoked');
                $role->revokePermission($permission);
              }
            }
            $role->save();
          }
        }
      }
    
}
?>
