## Menu Module

## TODO
- [ ] make the nav show active menu item
- [ ] add target attribute

## v2.0.0
- removed `DeletableMenu` and `DeletableMenuItem` middlewares
- 

## v1.1.2
- added `EventServiceProvider`
- added menu caching
- added events when menu/menuItems are saved and deleted
- auto generate menu items machine names
- added deletable to menu and menu_items table
- added `DeletableMenu` and `DeletableMenuItem` middlewares
- added docs
- added `EmptyMenuCache` and `EmptyMenuItemCache` listeners
- added `MenuCacheChanged` and `MenuItemCacheChanged` events
- give perms to admin at installation
- added `MenuItemDoesntExists` and `MenuDoesntExists` Exceptions
- added `Menus` facade

## v1.1.1
- renamed api in ajax
- added ajax routes

## v1.0.0
- added permission
- added pingu-menu as js module
- integrate permissions

### Menus
Menu models only have a name and a machineName (which is not editable), each menu can have children (through `HasItems` contract). 

Items can have children and parent associated to them (through `HasChildren` and `HasParent` contract).

Items can have an url or not (for menu sections), url can be external (starting with http), internal (starting with / or setting a GET route by its name). If the route is internal, its existence will be checked at creation.

Each menuItem can have a permission to check the visibility. If an item isn't visible to a user but has children that are visible, it will displayed as a span.

Menus and menuItems can be saved a non-deletable (not through the ui), middlewares are used to check that a menu or item is deletable.

### Caching
Menus and MenuItems are cached to reduce db queries. By using the facade `Menus` you are retrieving from the cache.

### Events
- `MenuCacheChanged` listened by `EmptyMenuCache`
- `MenuItemCacheChanged` listened by `EmptyMenuItemCache`