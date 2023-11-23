Ext.define('Admin.view.frontoffice.importexport.grids.ControlledDrgSpreadSheetIEVisibleColumns', {
    extend: 'Ext.form.Panel',  
    width: '100%',
    xtype: 'controlleddrgspreadsheetievisiblecolumns',
    title: 'Select Visibled Columns',
     titleCollapse: true,
    layout: 'vbox',
        margin: 2,
        collapsible: true,
        autoScroll: true,
        defaults: {
            xtype: 'checkbox',
            labelAlign: 'right',
            margin: 5,
            labelSeparator: ':',
            hideLabel: true
        },
  items:[  {
    boxLabel: 'Tracking No',
    name: 1,
    checked: true,
    listeners: {
        change: 'func_showhideSpreasheetColumn'
    }
},{
    boxLabel: 'Reference No',
    name: 2,
    checked: true,
    listeners: {
        change: 'func_showhideSpreasheetColumn'
    }
},{
    boxLabel: 'Permit Product Type',
    name: 3,
    checked: false,
    listeners: {
        change: 'func_showhideSpreasheetColumn'
    }
},{
    boxLabel: 'Permit Use',
    name: 4,
    checked: false,
    listeners: {
        change: 'func_showhideSpreasheetColumn'
    }
},{
    boxLabel: 'Permit Product Category',
    name: 5,
    checked: false,
    listeners: {
        change: 'func_showhideSpreasheetColumn'
    }
},{
    boxLabel: 'Applicant',
    name: 6,
    checked: false,
    listeners: {
        change: 'func_showhideSpreasheetColumn'
    }
},{
    boxLabel: 'Applicant Postal Address',
    name: 7,
    checked: false,
    listeners: {
        change: 'func_showhideSpreasheetColumn'
    }
},{
    boxLabel: 'Applicant Physical Address',
    name: 8,
    checked: false,
    listeners: {
        change: 'func_showhideSpreasheetColumn'
    }
},{
    boxLabel: 'Applicant Telephone No.',
    name: 9,
    checked: false,
    listeners: {
        change: 'func_showhideSpreasheetColumn'
    }
},{
    boxLabel: 'Applicant Mobile No.',
    name: 10,
    checked: false,
    listeners: {
        change: 'func_showhideSpreasheetColumn'
    }
},{
    boxLabel: 'Applicant Email Address',
    name: 11,
    checked: false,
    listeners: {
        change: 'func_showhideSpreasheetColumn'
    }
},{
    boxLabel: 'Applicant Country',
    name: 12,
    checked: false,
    listeners: {
        change: 'func_showhideSpreasheetColumn'
    }
},{
    boxLabel: 'Applicant Region',
    name: 13,
    checked: false,
    listeners: {
        change: 'func_showhideSpreasheetColumn'
    }
},{
    boxLabel: 'Premises Name',
    name: 14,
    checked: false,
    listeners: {
        change: 'func_showhideSpreasheetColumn'
    }
},{
    boxLabel: 'Premises Postal Address',
    name: 15,
    checked: false,
    listeners: {
        change: 'func_showhideSpreasheetColumn'
    }
},{
    boxLabel: 'Premises Physical Address',
    name: 16,
    checked: false,
    listeners: {
        change: 'func_showhideSpreasheetColumn'
    }
},{
    boxLabel: 'Premises Telephone',
    name: 17,
    checked: false,
    listeners: {
        change: 'func_showhideSpreasheetColumn'
    }
},{
    boxLabel: 'Premises Mobile',
    name: 18,
    checked: false,
    listeners: {
        change: 'func_showhideSpreasheetColumn'
    }
},{
    boxLabel: 'Premises Expiry Date',
    name: 19,
    checked: false,
    listeners: {
        change: 'func_showhideSpreasheetColumn'
    }
},{
    boxLabel: 'Consignee',
    name: 20,
    checked: false,
    listeners: {
        change: 'func_showhideSpreasheetColumn'
    }
},{
    boxLabel: 'Consignee Postal Address',
    name: 21,
    checked: false,
    listeners: {
        change: 'func_showhideSpreasheetColumn'
    }
},{
    boxLabel: 'Consignee Physical Address',
    name: 22,
    checked: false,
    listeners: {
        change: 'func_showhideSpreasheetColumn'
    }
},{
    boxLabel: 'Consignee Telephone No',
    name: 23,
    checked: false,
    listeners: {
        change: 'func_showhideSpreasheetColumn'
    }
},{
    boxLabel: 'Consignee Mobile No',
    name: 24,
    checked: false,
    listeners: {
        change: 'func_showhideSpreasheetColumn'
    }
},{
    boxLabel: 'Consignee Email',
    name: 25,
    checked: false,
    listeners: {
        change: 'func_showhideSpreasheetColumn'
    }
},{
    boxLabel: 'Consignee Country',
    name: 26,
    checked: false,
    listeners: {
        change: 'func_showhideSpreasheetColumn'
    }
},{
    boxLabel: 'Consignee Region',
    name: 27,
    checked: false,
    listeners: {
        change: 'func_showhideSpreasheetColumn'
    }
},{
    boxLabel: 'Consignee Options',
    name: 28,
    checked: false,
    listeners: {
        change: 'func_showhideSpreasheetColumn'
    }
},{
    boxLabel: 'Sender/Received',
    name: 29,
    checked: false,
    listeners: {
        change: 'func_showhideSpreasheetColumn'
    }
},{
    boxLabel: 'Sender/Received Postal Address',
    name: 30,
    checked: false,
    listeners: {
        change: 'func_showhideSpreasheetColumn'
    }
},{
    boxLabel: 'Sender/Received Physical Address',
    name: 31,
    checked: false,
    listeners: {
        change: 'func_showhideSpreasheetColumn'
    }
},{
    boxLabel: 'Sender/Received Telephone No',
    name: 32,
    checked: false,
    listeners: {
        change: 'func_showhideSpreasheetColumn'
    }
},{
    boxLabel: 'Sender/Received Mobile No',
    name: 33,
    checked: false,
    listeners: {
        change: 'func_showhideSpreasheetColumn'
    }
},{
    boxLabel: 'Sender/Received Email',
    name: 34,
    checked: false,
    listeners: {
        change: 'func_showhideSpreasheetColumn'
    }
},{
    boxLabel: 'Sender/Received Country',
    name: 35,
    checked: false,
    listeners: {
        change: 'func_showhideSpreasheetColumn'
    }
},{
    boxLabel: 'Sender/Received Region',
    name: 36,
    checked: false,
    listeners: {
        change: 'func_showhideSpreasheetColumn'
    }
}, {
    boxLabel: 'Application Category',
    name: 37,
    checked: false,
    listeners: {
        change: 'func_showhideSpreasheetColumn'
    }
},{
    boxLabel: 'Application Type Category',
    name: 38,
    checked: false,
    listeners: {
        change: 'func_showhideSpreasheetColumn'
    }
},{
    boxLabel: 'Port of Entry/Exit',
    name:39,
    checked: false,
    listeners: {
        change: 'func_showhideSpreasheetColumn'
    }
},{
    boxLabel: 'Modes of Transport',
    name:40,
    checked: false,
    listeners: {
        change: 'func_showhideSpreasheetColumn'
    }
},{
    boxLabel: 'Certificate/Permit Issue Place',
    name: 41,
    checked: false,
    listeners: {
        change: 'func_showhideSpreasheetColumn'
    }
},{
    boxLabel: 'Proforma Invoice No',
    name: 42,
    checked: false,
    listeners: {
        change: 'func_showhideSpreasheetColumn'
    }
},{
    boxLabel: 'Proforma Invoice Dates',
    name: 43,
    checked: false,
    listeners: {
        change: 'func_showhideSpreasheetColumn'
    }
},{
    boxLabel: 'Received From',
    name: 44,
    checked: false,
    listeners: {
        change: 'func_showhideSpreasheetColumn'
    }
},{
    boxLabel: 'Permit Issue Date',
    name: 45,
    checked: false,
    listeners: {
        change: 'func_showhideSpreasheetColumn'
    }
},{
    boxLabel: 'Permit Expiry Date',
    name: 46,
    checked: false,
    listeners: {
        change: 'func_showhideSpreasheetColumn'
    }
},{
    boxLabel: 'Receipt No',
    name: 47,
    checked: false,
    listeners: {
        change: 'func_showhideSpreasheetColumn'
    }
},{
    boxLabel: 'Invoice No',
    name: 48,
    checked: false,
    listeners: {
        change: 'func_showhideSpreasheetColumn'
    }
},{
    boxLabel: 'Invoice Amount',
    name: 49,
    checked: false,
    listeners: {
        change: 'func_showhideSpreasheetColumn'
    }
},{
    boxLabel: 'Paid Amount',
    name: 50,
    checked: false,
    listeners: {
        change: 'func_showhideSpreasheetColumn'
    }
},{
    boxLabel: 'Pay Currency',
    name: 51,
    checked: false,
    listeners: {
        change: 'func_showhideSpreasheetColumn'
    }
}]
});