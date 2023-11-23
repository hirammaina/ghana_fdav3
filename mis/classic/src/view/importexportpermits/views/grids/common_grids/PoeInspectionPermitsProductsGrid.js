/**
 * Created by Kip on 9/22/2018.
 */
Ext.define('Admin.view.importexportpermits.views.grids.common_grids.PoeInspectionPermitsProductsGrid', {
    extend: 'Ext.grid.Panel',
    controller: 'importexportpermitsvctr',
    xtype: 'poeinspectionpermitsproductsgrid',
    itemId: 'poeinspectionpermitsproductsgrid',
    
    cls: 'dashboard-todo-list',
    autoScroll: true,
    autoHeight: true,
    width: '100%',
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
        xtype: 'button',
        text: 'Product(Consignment) Inspection Recommendation',
        iconCls: 'x-fa fa-plus',
        name: 'update_allproducts',
        ui: 'soft-purple',
        hidden:true,
        childXtype: 'importexportpermitsproductspnl',
        winTitle: 'Update Permit Products Details',
        winWidth: '40%',
    },{
        text: 'Product(Consignment) Inspection Recommendation',
       viewXtype: 'inspectionproductsrecommendationfrm',
       winTitle: 'Inspection Permits Recommendation',
       winWidth: '40%',
        handler: 'funcPermitsProductRecommendationWin',
        iconCls: 'x-fa fa-plus',
        ui: 'soft-red',
    },'->',{
        xtype: 'button',
        text: 'Update Inspection Details',
        iconCls: 'x-fa fa-plus',
        ui: 'soft-purple',
       // hidden: true,
        winWidth: '40%',hidden:true,
        name: 'update_products',
        stores: '[]',
      
    }, {
        xtype: 'exportbtn'
    }, {
        xtype: 'hiddenfield',
        name: 'isReadOnly',
        bind: {
            value: '{isReadOnly}'  // negated
        }
    }],

    plugins: [{
        ptype: 'gridexporter'
    }, {
        ptype: 'cellediting',
        clicksToEdit: 1
    }],
    export_title: 'Import/Export Permits Products',
    bbar: [{
        xtype: 'pagingtoolbar',
        width: '70%',
        displayInfo: true,
        displayMsg: 'Showing {0} - {1} of {2} total records',
        emptyMsg: 'No Records',
        beforeLoad: function () {
            this.up('poeinspectionpermitsproductsgrid').fireEvent('refresh', this);//
        }
    }],
    features: [{
        ftype: 'searching',
        minChars: 2,
        position:'bottom',
        mode: 'local'
    },{
        ftype: 'summary',
        dock: 'bottom'
    }],
    listeners: {
        afterrender: {
            fn: 'setProductRegGridsStore',
            config: {
                pageSize: 100000,
                remoteFilter: true,
                storeId: 'poeinspectionpermitsproductsstr',
                    proxy: {
                        url: 'importexportpermits/getPoeInspectionPermitsProducts',
                        
                    }
            },
            isLoad: true
        }
    },selModel: {
        selType: 'checkboxmodel',
        mode: 'MULTI',
        allowDeselect: true
    },
    columns: [{
        xtype:'rownumberer'  
      },{
          xtype: 'gridcolumn',
          dataIndex: 'brand_name',
          tdCls: 'wrap-text',
          text: 'Brand Name/Device Name',
          flex: 1
      }, {
          xtype: 'gridcolumn',
          dataIndex: 'certificate_no',
          tdCls: 'wrap-text',
          text: 'Certificate No',
          flex: 1,
      },{
          xtype: 'gridcolumn',
          dataIndex: 'common_name',
          tdCls: 'wrap-text',
          text: 'Common Name',
          flex: 1,
      },{
        xtype: 'gridcolumn',
        dataIndex: 'manufacturer_name',
        
        tdCls: 'wrap-text',
        text: 'Manufacturer Name',
        flex: 1,
    },{
        xtype: 'gridcolumn',
        dataIndex: 'country_oforigin',
        
        tdCls: 'wrap-text',
        text: 'Country of Origin',
        flex: 1,
    },{
        xtype: 'gridcolumn',
        dataIndex: 'product_batch_no',
        text: 'Batch Number(Comma Seperator)',
        flex: 1.5,
        tdCls:'wrap-text'
    },{
        xtype: 'gridcolumn',
        dataIndex: 'product_manufacturing_date',
        text: 'Manufacturing Date',
        flex: 1.5,
        tdCls:'wrap-text'
    },{
        xtype: 'gridcolumn',
        dataIndex: 'product_expiry_date',
        text: 'Expiry Date',
        flex: 1.5,
        tdCls:'wrap-text'
    }, {
          xtype: 'gridcolumn',
          dataIndex: 'quantity',
          tdCls: 'wrap-text',
          text: 'Quantity',
          flex: 1,
      }, {
          xtype: 'gridcolumn',
          dataIndex: 'packaging_units',
          tdCls: 'wrap-text',
          text: 'Packaging Units',
          flex: 1,
      }, {
          xtype: 'gridcolumn',
          dataIndex: 'pack_size',hidden: true,
          text: 'Unit Pack size',
  
          flex: 1,
      },{
          xtype: 'gridcolumn',
          dataIndex: 'currency_name',
          tdCls: 'wrap-text',
          text: 'Currency Name',
          flex: 1
      },{
          
          xtype: 'gridcolumn',
          dataIndex: 'unit_price',
          tdCls: 'wrap-text',
          text: 'Unit Price',
          flex: 1,
        
      }, {
        xtype: 'gridcolumn',
        dataIndex: 'remarks',
        text: 'Remarks',
        flex: 1,
        editor: {
            xtype: 'textarea'
        }
    },{   
        xtype: 'gridcolumn',
        dataIndex: 'prodinspection_recommendation',
        tdCls:'wrap-text',
        text: 'Product Inspection Recommendation',
        flex: 1,
            renderer: function (val, meta, record, rowIndex, colIndex, store, view) {
                var textVal = '';
               
                if (val == 1) {
                    textVal = "Product released(Accepted)";
                    meta.tdStyle = 'color:white;background-color:green';
                    
                }else if(val == 2){
                    meta.tdStyle = 'color:white;background-color:red';
                    textVal = "Rejected (Recommended for Disposal)";
                }else if(val == 3){
                    meta.tdStyle = 'color:white;background-color:yellow';
                    textVal = "Released Under Seal";
                    
                   // meta.tdStyle = 'color:white;background-color:blue';
                }else{
                    meta.tdStyle = 'color:white;background-color:#ccc';
                    textVal = "No Recommendation";

                }
                return textVal;
            }
      }]
});
