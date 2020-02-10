## Menu Module

## TODO
- [ ] make the nav show active menu item (including multi level children)
- [ ] add target attribute

### Menus
Menu models only have a name and a machineName (which is not editable), each menu can have children (through `HasItems` contract). 

Items can have children and parent associated to them (through `HasChildren` and `HasParent` contract).

Items can have an url or not (for menu sections), url can be external (starting with http), internal (starting with / or setting a GET route by its name). If the route is internal, its existence will be checked at creation.

Each menuItem can have a permission to check the visibility. If an item isn't visible to a user but has children that are visible, it will displayed as a span.

Menus and menuItems can be saved as non-deletable (not through the ui), middlewares are used to check that a menu or item is deletable.

### Caching
Menus and MenuItems are cached to reduce db queries. By using the facade `Menus` you are retrieving from the cache.

Menus are built for each role and saved in cache. That cache is emptied when permission are saved or when the Menu cache changes (see Events below)

### Events
- `MenuCacheChanged` listened by `EmptyMenuCache`
- `MenuItemCacheChanged` listened by `EmptyMenuItemCache`