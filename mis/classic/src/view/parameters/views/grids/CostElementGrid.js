Ext.define('Admin.view.parameters.views.grids.CostElementGrid', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.costelementgrid',
    header: false,
    scroll: true,
    autoHeight: true,
    width: '100%',
    controller: 'parametervctr',
    height: Ext.Element.getViewportHeight() - 116,
    viewConfig: {
        deferEmptyText: false,
        emptyText: "No Records found",
        style:{
            'text-align':'center'
        }
    },
    plugins: [{
        ptype: 'filterfield'
    },{
        ptype: 'gridexporter'
    }],
  //  tbar: [],
 //   bbar: [],
    features: [{
        ftype: 'searching',
        minChars: 2,
        mode: 'local'
    } ,{
        ftype: 'grouping',
        startCollapsed: false
    }],
    columns: [
    {
            xtype: 'rownumberer'
    },{
        xtype: 'gridcolumn',
        dataIndex: 'section_name',
        text: 'Section Name',
        flex:1,
        tdCls: 'wrap'
    },
     {
        xtype: 'gridcolumn',
        dataIndex: 'feetype',
        text: 'Fee Type',
        flex:1,
        tdCls: 'wrap'
    },{
        xtype: 'gridcolumn',
        dataIndex: 'irembo_productname',
        text: 'Irembo Product Name',
        flex:1,
        tdCls: 'wrap',
        renderer: function (value, metaData, record) {
            var irembo_feescode_id = record.get('irembo_feescode_id');
            console.log(irembo_feescode_id);
            if (irembo_feescode_id < 1  || irembo_feescode_id == 'undefined') {
                metaData.tdStyle = 'color:white;background-color:red';
                return "Not Configured";
            }
            else{
                metaData.tdStyle = 'color:white;background-color:green';
                return value;
            }
        }
    },{
        xtype: 'gridcolumn',
        dataIndex: 'irembo_productcode',
        text: 'Irembo Product Code',
        flex:1,
        tdCls: 'wrap',
        renderer: function (value, metaData,record) {
            var irembo_feescode_id = record.get('irembo_feescode_id'); console.log(irembo_feescode_id);
            if (irembo_feescode_id < 1 || irembo_feescode_id == 'undefined') {
                metaData.tdStyle = 'color:white;background-color:red';
                return "Not Configured";
            }
            else{
                metaData.tdStyle = 'color:white;background-color:green';
                return value;
            }
        }
    },{
        xtype: 'gridcolumn',
        dataIndex: 'product_amount',
        text: 'Irembo Product Amount',
        flex:1,
        tdCls: 'wrap'
    },
    {
        xtype: 'gridcolumn',
        dataIndex: 'category',
        text: 'Category',
        name: 'category',
        flex:1,
        tdCls: 'wrap'
    },
    {
        xtype: 'gridcolumn',
        dataIndex: 'sub_category',
        text: 'Sub Category',
        flex:1,
        tdCls: 'wrap'
    },
     
    {
        xtype: 'gridcolumn',
        dataIndex: 'element',
        text: 'Cost Element',
        flex:1,
        tdCls: 'wrap'
    },  
    {
        xtype: 'gridcolumn',
        dataIndex: 'cost_type',
        hidden: true,
        text: 'Application Fee Type',
        flex:1,
        tdCls: 'wrap'
    },
     {
        xtype: 'gridcolumn',
        dataIndex: 'cost',
        text: 'Cost',
        flex:1,
        tdCls: 'wrap'
    },
     {
        xtype: 'gridcolumn',
        dataIndex: 'currency_name',
        text: 'Currency',
        flex:1,
        tdCls: 'wrap'
    },{
        xtype: 'gridcolumn',
        dataIndex: 'formula',
        text: 'Is Formula',
        flex:0.5,
        renderer: function (value, metaData) {
            if (value == 1) {
                metaData.tdStyle = 'color:white;background-color:green';
                return "True";
            }
            else{
                metaData.tdStyle = 'color:white;background-color:red';
                return "False";
            }
        }
    },{
        xtype: 'gridcolumn',
        dataIndex: 'formula_rate',
        text: 'Formular Rate(%)',
        flex:0.5,
        tdCls: 'wrap'
    },{
        text: 'Options',
        xtype: 'widgetcolumn',
        flex:0.5,
        widget: {
            width: 75,
            ui: 'gray',
            iconCls: 'x-fa fa-th-list',
            textAlign: 'left',
            xtype: 'splitbutton',
            menu: {
                xtype: 'menu',
                items: []
            }
        }
    }
    ]

});
