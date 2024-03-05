Ext.define('Admin.view.Enforcement.views.grids.MonitoringCompliance.ProductInformationGrid',{
    extend: 'Ext.grid.Panel',
	controller: 'enforcementvctr',
    xtype: 'productInformationGrid',

    tbar: [
    {
        xtype: 'hiddenfield',
        name: 'isReadOnly'
    },
    {
        xtype: 'button',
        text: 'Add Product',
        iconCls: 'x-fa fa-plus',
        name:'add_productDeatils',
        ui: 'soft-blue',
        handler: 'showProductInformationForm',
        winTitle: 'Product Information Form',
        childXtype: 'productInformationFrm',
        stores: '[]'
    },
    {
        xtype: 'exportbtn'
    }],
    listeners:{
        beforerender: {
            fn: 'setGridStore',
            config: {
                pageSize: 1000,
                autoLoad: false,
                defaultRootId: 'root',
                enablePaging: true,
                storeId: 'productInformationGridStr',
                proxy: {
                    url: 'enforcement/getMonitoringProductInformation'
                }
            },
            isLoad: true
        }
    },
bbar: [{
        xtype: 'pagingtoolbar',
        width: '80%',
        displayInfo: true,
        displayMsg: 'Showing {0} - {1} of {2} total records',
        emptyMsg: 'No Records',
        beforeLoad: function(){
            this.up('grid').fireEvent('refresh', this);
        }
    }],
    columns: [{
        xtype: 'rownumberer'
    },
    {   
        xtype:'gridcolumn',
        dataIndex:'brand_name',
        text:'Product Name',
        flex: 1
    },
    {   
        xtype:'gridcolumn',
        dataIndex:'common_name',
        text:'Common Name',
        flex: 1
    },
    {   
        xtype:'gridcolumn',
        dataIndex:'product_schedule',
        text:'Product Schedule',
        flex: 1
    },
    {
        xtype: 'gridcolumn',
        text: 'Expiry Date',
        dataIndex: 'expiry_date',
        flex: 1
    },
    {
        xtype: 'gridcolumn',
        text: 'Batch Number',
        dataIndex: 'batch_number',
        flex: 1
    },
    {
        xtype: 'gridcolumn',
        dataIndex: 'is_registered',
        text: 'Regestration Status',
        flex: 1,
        renderer: function (value, metaData) {
            if (value == 1) {
                metaData.tdStyle = 'color:white;background-color:green';
                return "Registred";
            }else if(value == 2){
                metaData.tdStyle = 'color:white;background-color:red';
                return "Not Registred";
            }else{
                metaData.tdStyle = 'color:white;background-color:gray';
                return "Not Applicable";
            }

            
        }
    },
    {
        text: 'Options',
        xtype: 'widgetcolumn',
        width: 90,
        widget: {
            width: 75,
            textAlign: 'right',
            xtype: 'splitbutton',
            name:'options',
            iconCls: 'x-fa fa-th-list',
            ui: 'gray',
            menu: {
                xtype: 'menu',
                items: [

                    {
                        text: 'Edit',
                        iconCls: 'x-fa fa-edit',
                        tooltip: 'Edit Record',
                        action: 'edit',
                        childXtype:'productInformationFrm',
                        winTitle: 'Edit Product information',
                        winWidth:'70%',
                        handler: 'showEditConfigParamWinFrm',
                        stores: '[]'
                    },
                    {
                        text: 'Delete',
                        iconCls: 'x-fa fa-trash',
                        tooltip: 'Delete Record',
                        table_name: 'par_monitoring_product_information',
                        storeID: 'productInformationGridStr',
                        action_url: 'enforcement/genericDeleteRecord',
                        action: 'actual_delete',
                        handler: 'deleteRecord',
                    },
                ]
            }
        }
    }
],
});
