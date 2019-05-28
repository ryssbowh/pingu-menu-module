import * as h from 'pingu-helpers';

const Menu = (() => {

	function getCreateItemForm(menu){
		return h.post('/api/menus/'+menu+'/create');
	}

	function loadItem(item){
		return h.get('/api/menus/item/'+item);
	}

	return {
		getCreateItemForm: getCreateItemForm,
		loadItem: loadItem
	};

})();

export default Menu;