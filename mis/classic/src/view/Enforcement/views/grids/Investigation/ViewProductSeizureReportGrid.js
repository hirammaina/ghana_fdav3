
 Ext.define('Admin.view.Enforcement.views.grids.Investigation.ViewProductSeizureReportGrid', {
    extend: 'Ext.grid.Panel',
    xtype: 'viewProductSeizureReportGrid',
    controller: 'enforcementvctr',
    autoScroll: true,
    width: '100%',
    height: Ext.Element.getViewportHeight() - 118,
    tbar: [
        {
            xtype: 'button',
            text: 'Add Seizure Products',
            iconCls: 'x-fa fa-plus',
            ui: 'soft-blue',
            handler: 'showAddSezuireProductWin',
            winTitle: 'Seizure Products',
            childXtype: 'addSeizureProductsFrm',
            stores: '[]'
        },
        {
        xtype: 'exportbtn'
    }, 
    {
        xtype: 'hiddenfield',
        name: 'application_code'
    }, {
        xtype: 'hiddenfield',
        name: 'module_id'
    }],
    plugins: [{
        ptype: 'gridexporter'
    }],
    listeners: {
        beforerender: {
            fn: 'setGridStore',
            config: {
                pageSize: 1000,
                storeId: 'newProductseizureGridStr',
                proxy: {
                    url: 'enforcement/getseizureProductsListGrid'
                }
            },
            isLoad: true
        }
    },
    columns: [{
        xtype: 'rownumberer',
        text: 'S/N'
    },
    {
        xtype: 'gridcolumn',
        dataIndex: 'premise_name',
        text: 'Premise Name',
        flex: 1
    },
    {
        xtype: 'gridcolumn',
        dataIndex: 'brand_name',
        text: 'Product Brand Name',
        flex: 1
    },
    {
        xtype: 'gridcolumn',
        dataIndex: 'common_name',
        text: 'Common Name',
        flex: 1
    },
    {
        xtype: 'gridcolumn',
        dataIndex: 'batch_number',
        text: 'Batch Number',
        flex: 1
    },
    {
        xtype: 'gridcolumn',
        dataIndex: 'expiry_date',
        text: 'Product Expiry Date',
        flex: 1
    },
    {
        xtype: 'gridcolumn',
        dataIndex: 'quantity',
        text: 'Quantity',
        flex: 1
    },
    {
        xtype: 'gridcolumn',
        dataIndex: 'seizure_confirmation_id',
        text: 'Seizure Confirmation',
        flex: 1,
        renderer: function (value, metaData) {
            if (value == 1) {
                metaData.tdStyle = 'color:white;background-color:green';
                return "Siezed";
            }else if(value == 2){
                metaData.tdStyle = 'color:white;background-color:red';
                return "Not Seize";
            }else{
                metaData.tdStyle = 'color:white;background-color:gray';
                return "Pending";
            }

            
        }
    },
    {
        xtype: 'gridcolumn',
        dataIndex: 'exhibit_request_approval_id',
        text: 'Exhibit Approval',
        flex: 1,
        renderer: function (value, metaData) {
            if (value == 1) {
                metaData.tdStyle = 'color:white;background-color:green';
                return "Exhibit Approved";
            }else if(value == 2){
                metaData.tdStyle = 'color:white;background-color:red';
                return "Rejected";
            }else{
                metaData.tdStyle = 'color:white;background-color:gray';
                return "Pending";
            }

            
        }
    },
    {
        xtype: 'gridcolumn',
        dataIndex: 'released_confirmation_id',
        text: 'Products Released Confirmation',
        flex: 1,
        renderer: function (value, metaData) {
            if (value == 1) {
                metaData.tdStyle = 'color:white;background-color:red';
                return "Released";
            }else if(value == 2){
                metaData.tdStyle = 'color:white;background-color:green';
                return "Not Released";
            }else{
                metaData.tdStyle = 'color:white;background-color:gray';
                return "Pending";
            }

            
        }
    },
    {
        xtype: 'gridcolumn',
        dataIndex: 'destruction_id',
        text: 'Destruction Confirmation',
        flex: 1,
        renderer: function (value, metaData) {
            if (value == 1) {
                metaData.tdStyle = 'color:white;background-color:red';
                return "destroyed";
            }else if(value == 2){
                metaData.tdStyle = 'color:white;background-color:green';
                return "Not destroyed";
            }else{
                metaData.tdStyle = 'color:white;background-color:gray';
                return "Pending";
            }

            
        }
    },
 
],
    bbar: [{
        xtype: 'pagingtoolbar',
        width: '100%',
        displayInfo: true,
        displayMsg: 'Showing {0} - {1} of {2} total records',
        emptyMsg: 'No Records',
        beforeLoad: function () {
            this.up('grid').fireEvent('refresh', this);
    
        }
    }]
});
