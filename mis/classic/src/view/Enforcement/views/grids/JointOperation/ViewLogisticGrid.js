Ext.define('Admin.view.Enforcement.views.grids.JointOperation.ViewLogisticGrid', {
    extend: 'Ext.grid.Panel',
    xtype: 'viewlogisticsgrid',
    autoScroll: true,
	width: '100%',
    height: Ext.Element.getViewportHeight() - 118,
	tbar: [{
        xtype: 'hiddenfield',
        name: 'isReadOnly'
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
                storeId: 'logisticsstr',
                proxy: {
                    url: 'enforcement/getJointOperationLogistics'
                }
            },
            isLoad: true
        }
    },
	 bbar: [{
			xtype: 'pagingtoolbar',
			width: '100%',
			displayInfo: true,
			displayMsg: 'Showing {0} - {1} of {2} total records',
			emptyMsg: 'No Records',
            beforeLoad: function () {
                this.up('viewlogisticsgrid').fireEvent('refresh', this); 
            }
			
		}],
    
    columns: [ 
    {
        xtype: 'gridcolumn',
        text: ' Name of Item',
        dataIndex: 'name',
        flex: 1
    },      
	{
        xtype: 'gridcolumn',
        text: ' Description',
        dataIndex: 'description',
        flex: 1
    }, 
    {
        xtype: 'gridcolumn',
        text: 'Quantity',
        dataIndex: 'quantity',
        flex: 1
    },
	{
        xtype: 'gridcolumn',
        text: 'Approximate Amount',
        dataIndex: 'amount',
        flex: 1
    },
	]
});