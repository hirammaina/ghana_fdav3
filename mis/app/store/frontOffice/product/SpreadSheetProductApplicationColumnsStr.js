Ext.define('Admin.store.frontOffice.product.SpreadSheetProductApplicationColumnsStr', {
    extend: 'Ext.data.Store',
    alias: 'store.spreadsheetproductapplicationcolumnsstr',
    storeId: 'spreadsheetproductapplicationcolumnsstr',
    autoLoad: false,
    defaultRootId: 'root',
    enablePaging: true,
    remoteFilter: true,

    proxy: {
        type: 'ajax',
        url: 'openoffice/getProductsApplicationColumns',
        headers: {
            'Authorization':'Bearer '+access_token
        },
        reader: {
            type: 'json',
            idProperty: 'id',
            rootProperty: 'results',
            messageProperty: 'message',
            totalProperty: 'totalResults'
        }
    }

});
