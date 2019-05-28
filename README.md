## Menu Module

## TODO
- [ ] make the nav show active menu item
- [ ] add target attribute

## v1.0.0
- added permission
- added pingu-menu as js module
- integrate permissions

## Menus
Menu models only have a name and a machineName (which is not editable), each menu can have children (through `HasItems` contract). 

Items can have children and parent associated to them (through `HasChildren` and `HasParent` contract).

Items can have an url or not (for menu sections), url can be external (starting with http), internal (starting with / or setting a route by its name). If the route is internal, its existence will be checked at creation.

Each menuItem can have a permission to check the visibility. If an item isn't visible to a user but has children that are visible, it will displayed as a span.