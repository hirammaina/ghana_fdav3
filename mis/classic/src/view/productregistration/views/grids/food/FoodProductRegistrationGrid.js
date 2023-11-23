Ext.define('Admin.view.productregistration.views.grids.food.FoodProductRegistrationGrid', {
    extend: 'Admin.view.productregistration.views.grids.common_grids.ProductRegistrationGrid',
    controller: 'productregistrationvctr',
    xtype: 'foodproductregistrationgrid',
    itemId: 'foodproductregistrationgrid',
    store: 'foodproductregistrationstr',
    listeners:{
        beforerender:function(grid){
            grid.store.load();
        }
    },
    bbar: [{
        xtype: 'pagingtoolbar',
        width: '100%',
        displayInfo: true,
        
    store: 'foodproductregistrationstr',
        displayMsg: 'Showing {0} - {1} of {2} total records',
        emptyMsg: 'No Records',
        beforeLoad: function () {

            this.up('foodproductregistrationgrid').fireEvent('refresh', this);

        }
    }],
});