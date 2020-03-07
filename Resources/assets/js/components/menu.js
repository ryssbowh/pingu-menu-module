const Menu = (() => {

    function getCreateItemForm(menu)
    {
        return Helpers.post('/api/menus/'+menu+'/create');
    }

    function loadItem(item)
    {
        return Helpers.get('/api/menus/item/'+item);
    }

    return {
        getCreateItemForm: getCreateItemForm,
        loadItem: loadItem
    };

})();

export default Menu;