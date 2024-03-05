
 Ext.define('Admin.view.Enforcement.views.grids.Investigation.SeizureProductsListGrid', {
    extend: 'Ext.grid.Panel',
    xtype: 'seizureProductsListGrid',// caseDecisionsLogGrid
    controller: 'enforcementvctr',
    autoScroll: true,
    width: '100%',
    height: Ext.Element.getViewportHeight() - 118,
    tbar: [{
        xtype: 'exportbtn'
    }, {
        xtype: 'hiddenfield',
        name: 'application_code'
    }, {
        xtype: 'hiddenfield',
        name: 'module_id'
    }],
    plugins: [{
        ptype: 'gridexporter'
    }],
    // features: [{
    //     ftype: 'grouping',
    //     startCollapsed: true,
    //     groupHeaderTpl: 'Service Type => {[values.rows[0].data.recommendation]} ({rows.length})',
    //     hideGroupedHeader: false,
    //     enableGroupingMenu: false
    // }],
    listeners: {
        beforerender: {
            fn: 'setGridStore',
            config: {
                pageSize: 1000,
                storeId: 'seizureProductsListGridStr',///CaseDecisionsLogGrid
                groupField: 'recommendation_id',
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
        dataIndex: 'suspected_entity',
        text: 'Suspected Entity',
        flex: 1
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
 
],
    bbar: [{
        xtype: 'pagingtoolbar',
        width: '100%',
        displayInfo: true,
        displayMsg: 'Showing {0} - {1} of {2} total records',
        emptyMsg: 'No Records',
        beforeLoad: function () {
            var grid = this.up('grid'),
                store = this.getStore(),
                application_code = grid.down('hiddenfield[name=application_code]').getValue(),
                module_id = grid.down('hiddenfield[name=module_id]').getValue();

            store.getProxy().extraParams = {
                application_code: application_code,
                module_id: module_id
            };
        }
    }]
});
