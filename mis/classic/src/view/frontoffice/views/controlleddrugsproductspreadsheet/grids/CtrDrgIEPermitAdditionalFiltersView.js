Ext.define('Admin.view.frontoffice.controlleddrugsproductspreadsheet.grids.CtrDrgIEPermitAdditionalFiltersView', {
    extend: 'Ext.form.Panel',  
    scroll: true,
    collapsible: true,
    autoScroll: true,
  //  collapsed:true,
    width: '100%',
    height: '100%',
    xtype: 'ctrdrgiepermitadditionalfiltersview',
    layout: 'form',
    title: 'Select Addtional Filters',
    margin: 2,
    defaults: {
            xtype: 'checkbox',
            labelAlign: 'right',
            margin: 5,
            labelSeparator: ':',
        },
    items: [ {
            boxLabel: 'Received From Date',
            name: 60,
            checked: false,
            listeners: {
                change: 'func_showhideSpreasheetColumn'
            }
        }, {
            boxLabel: 'Received To Date',
            name: 69,
            checked: false,
            listeners: {
                change: 'func_showhideSpreasheetColumn'
            }
        }, {
            boxLabel: 'Approved From Date',
            name: 70,
            checked: false,
            listeners: {
                change: 'func_showhideSpreasheetColumn'
            }
        }, {
            boxLabel: 'Approved To Date',
            name: 71,
            checked: false,
            listeners: {
                change: 'func_showhideSpreasheetColumn'
            }
        }, {
            boxLabel: 'Permit Expiry From Date',
            name: 72,
            checked: false,
            listeners: {
                change: 'func_showhideSpreasheetColumn'
            }
        }, {
            boxLabel: 'Expiry To Date',
            name: 73,
            checked: false,
            listeners: {
                change: 'func_showhideSpreasheetColumn'
            }
        }
  ]
});