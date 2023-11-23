/**
 * Created by Kip on 6/27/2019.
 */
Ext.define('Admin.view.usermanagement.views.grids.DigitalSignatureManagementGrid', {
    extend: 'Ext.grid.Panel',
    xtype: 'digitalsignaturemanagementgrid',
    header: false,
    autoScroll: true,
    autoHeight: true,
    width: '100%',
    viewConfig: {
        deferEmptyText: false,
        emptyText: 'Nothing to display'
    },
    tbar: [{
        xtype: 'exportbtn'
    }, {
        xtype: 'tbspacer',
        width: 60
    }, {
        xtype: 'displayfield',
        hidden: true,
        value: 'Double click to update signature details!!',
        fieldStyle: {
            'color': 'green',
            'font-weight': 'bold'
        }
    }],
    plugins: [
        {
            ptype: 'gridexporter'
        }
    ],
    export_title: 'Active System Users Signs',
    bbar: [{
        xtype: 'pagingtoolbar',
        width: '100%',
        displayInfo: true,
        displayMsg: 'Showing {0} - {1} of {2} total records',
        emptyMsg: 'No Records'
    }],
    features: [{
        ftype: 'searching',
        minChars: 2,
        mode: 'local'
    }],
    listeners: {
        beforerender: {
            fn: 'setUserGridsStore',
            config: {
                pageSize: 100,
                storeId: 'digitalsignaturemanagementstr',
                proxy: {
                    url: 'usermanagement/getUserDigitalSignatures'
                }
            },
            isLoad: true
        }
    },
    columns: [{
        xtype: 'gridcolumn',
        dataIndex: 'fullnames',
        text: 'Full Names',
        flex: 1
    }, {
        xtype: 'gridcolumn',
        dataIndex: 'email',
        text: 'System Email Address',
        flex: 1,
        renderer: function (value) {
            return Ext.String.format('<a href="mailto:{0}">{1}</a>', value, value);
        }
    },{
        xtype: 'gridcolumn',
        dataIndex: 'email',
        text: 'Digital Signature Email Address',
        flex: 1,
        renderer: function (value) {
            return Ext.String.format('<a href="mailto:{0}">{1}</a>', value, value);
        }
    },  {
        xtype: 'gridcolumn',
        dataIndex: 'user_signature64',
        text: 'User Signature',
        flex: 1,
        renderer: function (val) {
            if (val) {
                user_signature64 = "data:image/jpg;base64," + val;
                return '<img src='+user_signature64+' width="75" height="50">';
            } 
        }
    }, {
        xtype: 'widgetcolumn',
        text: 'Options',
        width: 100,
        widget: {
            textAlign: 'left',
            xtype: 'splitbutton',
            ui: 'gray',
            width: 75,
            iconCls: 'x-fa fa-th-list',
            menu: {
                xtype: 'menu',
                items: [{
                    text: 'Update Signature',
                    iconCls: 'x-fa fa-upload',
                    tooltip: 'Update Signature',
                    handler: 'showUploadUserDigitalSignature'
                }]
            }
        }

    }]
});
