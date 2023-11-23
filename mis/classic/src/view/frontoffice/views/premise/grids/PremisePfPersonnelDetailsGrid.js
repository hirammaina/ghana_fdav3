Ext.define('Admin.view.frontoffice.premise.grids.PremisePfPersonnelDetailsGrid', {
    extend: 'Ext.grid.Panel',
    controller: 'spreadsheetpremisectr',
    xtype: 'premisepfpersonneldetailsgrid',
    autoScroll: true,
    autoHeight: true,
    width: '100%',
    config: {
        isWin: 0,
        isOnline: 0,
        isCompare: 0
    },
    viewConfig: {
        deferEmptyText: false,
        emptyText: 'Nothing to display',
        getRowClass: function (record, rowIndex, rowParams, store) {
            var is_enabled = record.get('is_enabled');
            if (is_enabled == 0 || is_enabled === 0) {
                return 'invalid-row';
            }
        }
    },
    tbar: [{
        xtype: 'hiddenfield',
        name: 'isReadOnly'
    }, {
        xtype: 'hiddenfield',
        name: 'is_temporal',
        value: 0
    }, {
        xtype: 'hiddenfield',
        name: 'premise_id'
    }, {
        xtype: 'button',
        text: 'Add Personnel',
        iconCls: 'x-fa fa-plus',
        ui: 'soft-green',
        bind: {
            hidden: '{isReadOnly}'
        },
        name: 'add_personnel',
        winTitle: 'Premise Personnel Details',
        childXtype: 'premisespfpersonneldetailsfrm',
        handler:'showPfAddPremiseResponsibleWinFrm',
        winWidth: '65%',
        storeID: 'pfpremisepersonneldetailsstr',
        action_url: 'premiseregistration/savePremisePersonnelLinkageDetails',
        stores: '[]'
    }, {
        xtype: 'exportbtn'
    }],
    plugins: [
        {
            ptype: 'gridexporter'
        }
    ],
    export_title: 'Premise Personnel Details',
    bbar: [{
        xtype: 'pagingtoolbar',
        width: '100%',
        displayInfo: true,
        displayMsg: 'Showing {0} - {1} of {2} total records',
        emptyMsg: 'No Records',
        beforeLoad: function () {
            var store = this.getStore(),
            grid=this.up('grid'),
            panel=grid.up('panel'),
            mainpnl=panel.up('panel'),
            wizardpnl=mainpnl.up('panel'),
            parentpanel=wizardpnl.up('panel'),
            premise_id = parentpanel.down('hiddenfield[name=premise_id]').getValue();
            grid.down('hiddenfield[name=premise_id]').setValue(premise_id);
                store.getProxy().extraParams = {
                premise_id:premise_id
            };
        }
    }],
    features: [{
        ftype: 'searching',
        minChars: 2,
        mode: 'local'
    }],
    listeners: {
        beforerender: {
            fn: 'setPremiseRegGridsStore',
            config: {
                pageSize: 1000,
                storeId: 'pfpremisepersonneldetailsstr',
                proxy: {
                    url: 'premiseregistration/getPremisePersonnelDetails'
                }
            },
            isLoad: true
        }
    },
    columns: [{
        xtype: 'gridcolumn',
        dataIndex: 'name',
        text: 'Name',
        flex: 1
    }, {
        xtype: 'gridcolumn',
        dataIndex: 'telephone_no',
        text: 'Telephone No',
        flex: 1
    }, {
        xtype: 'gridcolumn',
        dataIndex: 'email_address',
        text: 'Email address',
        flex: 1
    }, {
        xtype: 'gridcolumn',
        dataIndex: 'postal_address',
        text: 'Postal Address',
        flex: 1
    }, {
        xtype: 'gridcolumn',
        dataIndex: 'position',
        text: 'Position',
        flex: 1
    }, {
        xtype: 'gridcolumn',
        dataIndex: 'registration_no',
        text: 'Registration No',
        flex: 1
    }, {
        xtype: 'gridcolumn',
        dataIndex: 'study_field',
        text: 'Field of Study',
        flex: 1
    }, {
        xtype: 'gridcolumn',
        dataIndex: 'qualification',
        text: 'Qualification',
        flex: 1
    }, {
        xtype: 'gridcolumn',
        dataIndex: 'institution',
        text: 'Institution',
        flex: 1
    }, {
        xtype: 'datecolumn',
        dataIndex: 'start_date',
        text: 'Start DateE',
        flex: 1,
        renderer: Ext.util.Format.dateRenderer('d/m/Y')
    }, {
        xtype: 'datecolumn',
        dataIndex: 'end_date',
        text: 'End Date',
        flex: 1,
        renderer: Ext.util.Format.dateRenderer('d/m/Y')
    }, {
        text: 'Options',
        xtype: 'widgetcolumn',
        width: 90,
        widget: {
            width: 75,
            textAlign: 'left',
            xtype: 'splitbutton',
            iconCls: 'x-fa fa-th-list',
            ui: 'gray',
            menu: {
                xtype: 'menu',
                items: [{
                    text: 'Personnel Details',
                    iconCls: 'x-fa fa-user',
                    winTitle: 'Premise Personnel Details',
                    childXtype: 'premisespfpersonneldetailsfrm',
                    winWidth: '65%',
                    handler: 'showEditPfPersonnelDetail',
                    storeID: 'pfpremisepersonneldetailsstr',
                    action_url: 'premiseregistration/savePremisePersonnelLinkageDetails',
                    stores: '[]'
                }, {
                     text: 'Delete',
                    iconCls: 'x-fa fa-trash',
                    tooltip: 'Delete Record',
                    table_name: 'tra_premises_personnel',
                    storeID: 'pfpremisepersonneldetailsstr',
                    action_url: 'premiseregistration/deletePremiseRegRecord',
                    action: 'actual_delete',
                    handler: 'deletePfPremiseOtherdetails'
                }]
            }
        }
    }]
});

