# Bug Fixes TODO

## Bugs Fixed:

- [x] 1. Fix UserStore.php - Email unique validation for PATCH (use `$this->route('user')` instead of `$this->user`)
- [x] 2. Fix UserStore.php - Make password nullable for PATCH requests
- [x] 3. Fix UserController.php - `update` method to allow optional password
- [x] 4. Fix UserController.php - `show` method (removed delete logic, restored proper show functionality)
- [x] 5. Fix UserController.php - `destroy` method (added proper delete logic)
- [x] 6. Fix index.blade.php - Delete link route (use `user.destroy` instead of `user.show`)
- [x] 7. Fix routes/web.php - Added missing logout route
- [x] 8. Added collapsible/expanded sidebar functionality

## Summary:
All bugs have been fixed and the collapsible sidebar feature has been added.

