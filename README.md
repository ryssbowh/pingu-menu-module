## Menu Module

## TODO
- [ ] make the nav show active menu item
- [ ] add target attribute

## v1.0.0

## Menus
Menu models only have a name and a machineName (which is not editable), each menu can have children (through `HasItems` contract). 

Items can have children and parent associated to them (through `HasChildren` and `HasParent` contract).

Items can have an url or not (for menu sections), url can be external (starting with http), internal (starting with / or setting a route by its name). If the route is internal, its existence will be checked at creation.