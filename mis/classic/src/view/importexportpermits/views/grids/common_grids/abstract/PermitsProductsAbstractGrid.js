/**
 * Created by Softclans on 9/22/2018.
 */
Ext.define('Admin.view.importexportpermits.views.grids.common_grids.abstract.PermitsProductsAbstractGrid', {
    extend: 'Ext.grid.Panel',
    controller: 'importexportpermitsvctr',
    xtype: 'permitsproductsabstractgrid',
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
               // return 'invalid-row';
            }
        }
    },
    plugins: [
        {
            ptype: 'gridexporter'
        }
    ],
    plugins: [{
        ptype: 'gridexporter'
    }, {
        ptype: 'cellediting',
        clicksToEdit: 1,
        editing: true
    },{
        ptype: 'filterfield'
    }],
    features: [{
        ftype: 'searching',
        minChars: 2,
        mode: 'local'
    },{
        ftype: 'summary',
        dock: 'bottom'
    }],
    initComponent: function () {
        var defaultColumns = [{
            xtype:'rownumberer'  
          },{
              xtype: 'gridcolumn',
              dataIndex: 'brand_name',
              text: 'Brand Name/Device Name',
              flex: 1
          }, {
              xtype: 'gridcolumn',
              dataIndex: 'certificate_no',
              text: 'Certificate No',
              flex: 1,
          },{
              xtype: 'gridcolumn',
              dataIndex: 'common_name',
              text: 'Common Name',
              flex: 1,
          },{
              xtype: 'gridcolumn',
              dataIndex: 'product_category',
              hidden: true,
              text: 'Product Category',
              flex: 1,
          }, {
              xtype: 'gridcolumn',
              dataIndex: 'quantity',
              text: 'Quantity',
              flex: 1,
          }, {
              xtype: 'gridcolumn',
              dataIndex: 'packaging_units',
              text: 'Packaging Units',
              flex: 1,
          }, {
              xtype: 'gridcolumn',
              dataIndex: 'pack_size',hidden: true,
              text: 'Unit Pack size',
      
              flex: 1,
          },{
              xtype: 'gridcolumn',
              dataIndex: 'pack_unit',hidden: true,
              text: 'Unit Pack',
      
              flex: 1,
          },{
              xtype: 'gridcolumn',
              dataIndex: 'currency_name',
              text: 'Currency Name',
              flex: 1,
          },{
              
              xtype: 'gridcolumn',
              dataIndex: 'unit_price',
              text: 'Unit Price',
              flex: 1,
            
          },{
              xtype: 'gridcolumn',
              dataIndex: 'total_value',
              text: 'Total Value',
              width: 200,
              summaryType: 'sum',
              renderer: function (val, meta, record) {
                  return Ext.util.Format.number(val, '0,000.00');
              },
              summaryRenderer: function (val) {
                  val = Ext.util.Format.number(val, '0,000.00');
                  return 'Total Fob '+val
              }
          },{
            xtype: 'gridcolumn',
            text: 'Registration Status', 
            dataIndex: 'certificate_no',
            renderer: function (value, metaData) {
                if (value !='') {
                    metaData.tdStyle = 'color:white;background-color:green';
                    return "Registered/Authorised";
                }
    
                metaData.tdStyle = 'color:white;background-color:red';
                return "Not Registered";
            }
    
        },{   
            xtype: 'gridcolumn',
            dataIndex: 'prodregistrationvalidation_recommendation_id',
            tdCls:'wrap-text',
            text: 'Product Registration Validation Recommendation',
            flex: 1,
                renderer: function (val, meta, record, rowIndex, colIndex, store, view) {
                    var textVal = 'Select Recommendation';
                    
                    if (val == 2) {
                        meta.tdStyle = 'color:white;background-color:green';
                        
                    }else if(val == 3){
                        meta.tdStyle = 'color:white;background-color:red';
                    }else{
                        meta.tdStyle = 'color:white;background-color:blue';
                    }
                    
                    return textVal;
                }
          },{   
            xtype: 'gridcolumn',
            dataIndex: 'prodregistrationvalidation_recommendation_remarks',
            tdCls:'wrap-text',
            text: 'Product Registration Validation Recommendation',
            flex: 1,
            renderer: function (val) {
                if (val == '') {
                   
                         var val = 'Recommendation Remarks';
                }
                return val;
            }
          },{   
            xtype: 'gridcolumn',
            dataIndex: 'permitprod_recommendation_id',
            tdCls:'wrap-text',
            text: 'Permits Product Recommendation(Acceptance)',
            flex: 1,
                editor: {
                    xtype: 'combo',
                    store: 'permitprod_recommendationstr',
                    valueField: 'id',
                    displayField: 'name',
                    queryMode: 'local',
                    listeners: {
                       
                    }
                },
                renderer: function (val, meta, record, rowIndex, colIndex, store, view) {
                    var textVal = 'Select Recommendation';
                    if (view.grid.columns[colIndex].getEditor().getStore().getById(val)) {
                        textVal = view.grid.columns[colIndex].getEditor().getStore().getById(val).data.name;
                    }
                    if (val == 2) {
                        meta.tdStyle = 'color:white;background-color:green';
                        
                    }else if(val == 3){
                        meta.tdStyle = 'color:white;background-color:red';
                    }else{
                        meta.tdStyle = 'color:white;background-color:blue';
                    }
                    
                    return textVal;
                }
          },{   
            xtype: 'gridcolumn',
            dataIndex: 'permitprod_recommendation_remarks',
            tdCls:'wrap-text',
            text: 'Recommendation Remarks',
            flex: 1,
            editor: {
                xtype:'textfield'
            },renderer: function (val) {
                if (val == '') {
                   
                         var val = 'Recommendation Remarks';
                }
                return val;
            }
          }];
        this.columns = defaultColumns.concat(this.columns);
        this.callParent(arguments);
    }
});
