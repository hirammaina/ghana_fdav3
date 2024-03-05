/**
 */
Ext.define('Admin.view.Enforcement.views.grids.MonitoringCompliance.PeerRecommendationGrid', {
    extend: 'Ext.grid.Panel',
    xtype: 'peerRecommendationGrid',
    controller: 'commoninterfacesVctr',
    autoScroll: true,
    width: '100%',
    height: Ext.Element.getViewportHeight() - 108,
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
    features: [{
        ftype: 'grouping',
        startCollapsed: true,
        groupHeaderTpl: 'Service Type => {[values.rows[0].data.recommendation]} ({rows.length})',
        hideGroupedHeader: false,
        enableGroupingMenu: false
    }],
    listeners: {
        beforerender: {
            fn: 'setPremiseRegGridsStore',
            config: {
                pageSize: 1000,
                storeId: 'applicationRecommendationLogGridStr',
                groupField: 'recommendation_id',
                proxy: {
                    url: 'enforcement/getPeerRecommendationLogs'
                }
            },
            isLoad: true
        }
    },
    columns: [{
        xtype: 'rownumberer',
        text: 'S/N'
    },{
        xtype: 'gridcolumn',
        dataIndex: 'remarks',
        text: 'Summary',
        flex: 1,
        tdCls: 'wrap'
    },{
        xtype: 'gridcolumn',
        dataIndex: 'stage_name',
        text: 'Process',
        flex: 1,
        tdCls: 'wrap'
    },{
        xtype: 'gridcolumn',
        dataIndex: 'recommendation_date',
        text: 'Recommendation Date',
        flex: 1,
        tdCls: 'wrap'
    }, {
        xtype: 'gridcolumn',
        dataIndex: 'recommendation_id',
        text: 'Recommendation',
        align: 'center',
        flex: 1,
        // hidden: false,
        renderer: function (value, metaData) {
            if (value == 1) {
                metaData.tdStyle = 'color:white;background-color:ref';
                return "Fine/Sunction";
            }else if(value ==2){
                metaData.tdStyle = 'color:white;background-color:red';
                return "Full Investigation";
            }else if(value ==3){
                metaData.tdStyle = 'color:white;background-color:red';
                return "Corrective Action";
            }else if(value ==4){
                metaData.tdStyle = 'color:white;background-color:red';
                return "External referral to other law enforcement agencies";
            }else if(value ==5){
                metaData.tdStyle = 'color:white;background-color:green';
                return "Compliant";
            }else{
                metaData.tdStyle = 'color:white;background-color:grey';
                return "Pending";
            }   
        }
    }],
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
