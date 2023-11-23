var IE_sectionid="";
var IE_sub_module="";
Ext.define('Admin.view.openOffice.controlleddrugs.controller.ControlDrgSpreadSheetIECtr', {
    extend: 'Ext.app.ViewController',
    alias: 'controller.controldrgspreadsheetiectr',

          reloadSheetStore: function(combo,newValue,old,eOpts) {
           var form=combo.up('form'),
                store=form.down('pagingtoolbar').getStore();
                IE_sub_module=newValue;
           store.load();
         },   funcViewApplicationProcess:function (item) {
          var btn = item.up('button'),
              record = btn.getWidgetRecord(),
              application_code=record.get('application_code');
              tracking_no=record.get('tracking_no');
              reference_no=record.get('reference_no');
              application_enquiriesGrid = Ext.widget('application_enquiriesGrid');
              if(reference_no == ''){
                 var reference_no = tracking_no;
              }
              application_enquiriesGrid.down('textfield[name=Reference]').setValue(reference_no);
              store = application_enquiriesGrid.getStore();
application_enquiriesGrid.down('textfield[name=Reference]').setDisabled(true);
store.load();
           funcShowCustomizableWindow('View Process', '80%', application_enquiriesGrid, 'customizablewindow');
           
  },
          loadApplicationColumns: function(sender,record) {
               IE_sectionid=record.data['id'];
                if(IE_sectionid==0){
                IE_sectionid='';
               }
              //load filters to other depedent stores
              var filter = {'t1.regulated_producttype_id':IE_sectionid};
              var   filters = JSON.stringify(filter);
               
               var form = this.lookupReference('iegridpanel'),
               permit_categoryStr=form.down('combo[name=permit_category_id]').getStore(),
               import_typecategoryStr=form.down('combo[name=import_typecategory_id]').getStore();
               
               permit_categoryStr.removeAll();
               import_typecategoryStr.removeAll();
               

               
               permit_categoryStr.load();
               import_typecategoryStr.load();

               //loading grid
               IE_sub_module=this.getView().down('combo[name=sub_module]').getValue();
              //add filters
              var filter = {'t1.regulated_producttype_id':IE_sectionid,'t1.sub_module_id':IE_sub_module};
              var   filters = JSON.stringify(filter);
              Ext.data.StoreManager.lookup('spreadsheetieapplicationcolumnsstr').load({params:{filters:filters}});

            },
          loadadditionalinfo:  function(sender,record) {
              var application_code=record.data['application_code'];
              console.log(record.data);
              var filter = { 't1.application_code':application_code };
              var   filters = JSON.stringify(filter);
              Ext.data.StoreManager.lookup('spreadsheetieproductstr').reload({params:{filters:filters}});
              Ext.data.StoreManager.lookup('spreadsheetiepoeapplicationstr').reload({params:{filters:filters}});

            },
          funcReloadspreadSheetStrs: function() {
                   Ext.data.StoreManager.lookup('spreadsheetieapplicationcolumnsstr').reload();
                 }, 
          func_showhideSpreasheetColumn: function (chk, value) {
                  var  chk_name = chk.name;
                  var grid =this.lookupReference('iegridpanel');
                  grid.columns[chk_name].setVisible(value);
            },
             func_exportimportspreadsheet: function (btn) {

                 var name=btn.name;
                  var grid = this.lookupReference('iegridpanel'),
                      form = grid.up('#spreadsheetpermitcnt'),
                  filterfield = grid.getPlugin('filterfield');
                 // console.log(grid);
                  //filters
                      var filter_array =Ext.pluck( filterfield.getgridFilters(grid), 'config');
                      var  application_type_id= zone_id=grid.down('combo[name=zone_id]').getValue(),
                      permit_category_id=grid.down('combo[name=permit_category_id]').getValue(),
                      permit_reason_id=grid.down('combo[name=permit_reason_id]').getValue(),
                      port_id=grid.down('combo[name=port_id]').getValue(),
                    //  validity_status=grid.down('combo[name=validity_status]').getValue(),
                     // currency_id=grid.down('combo[name=currency_id]').getValue(),
                      consignee_options_id=grid.down('combo[name=consignee_options_id]').getValue(),
                      import_typecategory_id=grid.down('combo[name=import_typecategory_id]').getValue();
                      mode_oftransport_id=form.down('combo[name=mode_oftransport_id]').getValue();
                      received_from_date=form.down('datefield[name=received_from_date]').getValue(),
                      received_to_date=form.down('datefield[name=received_to_date]').getValue(),
                      
                      approved_from_date=form.down('datefield[name=approved_from_date]').getValue(),
                      approved_to_date=form.down('datefield[name=approved_to_date]').getValue();

                      var Originalfilter = {'t1.regulated_producttype_id':IE_sectionid,'t1.sub_module_id':IE_sub_module};
                      var filters = JSON.stringify(Originalfilter);

                   //headers
                   if(name=='summary'){
                   var header=Ext.pluck(grid.query('gridcolumn:not([hidden])'), 'name');
                   var header2=[];
                   var x=0;
                   for (var i = 1; i <= header.length; i++) {
                    header2[x]= header[i];
                     x++;
                   }
                 }else{
                  var header=Ext.pluck(grid.columns, 'name');
                  var header2=[];
                   var x=0;
                   for (var i = 2; i <= header.length; i++) {
                    header2[x]= header[i];
                     x++;
                   }
                 }
                   
                   var header= Ext.encode(header2);
                   filter_array = Ext.JSON.encode(filter_array);
                   Ext.getBody().mask('Exporting Records Please wait...');
                    Ext.Ajax.request({
                      url: 'openoffice/exportall',
                      method: 'POST',
                      params : {
                                  'header':header,
                                  'filters':filters,
                                  'filter':filter_array,
                                  'application_type': application_type_id,
                                  'permit_category':permit_category_id,
                                  'import_typecategory':import_typecategory_id,
                                  'permit_reason':permit_reason_id,
                                  'port':port_id,
                                  'mode_oftransport_id':mode_oftransport_id,
                                //  'currency':currency_id,
                                 // 'registration_status': registration_status,
                                 // 'validity_status': validity_status,

                                 'approved_to_date':approved_to_date,
                                  'approved_from_date':approved_from_date,
                                  'received_to_date':received_to_date,
                                  'received_from_date':received_from_date,
                                  
                                  'issueplace':zone_id,
                                  'headingText': 'IMPORT/EXPORT APPLICATIONS SPREADSHEET',
                                  'consignee_options':consignee_options_id,
                                  'function':'getIESpreadSheet',
                                  'filename':'ImportEportProdductsSpreadsheet'
                    },
                      
                       success: function (response, textStatus, request) {
                        var t = JSON.parse(response.responseText);
                        var a = document.createElement("a");
                        a.href = t.file; 
                        a.download = t.name;
                        document.body.appendChild(a);

                        a.click();
                     
                        a.remove();
                        Ext.getBody().unmask();
      
                      },
                      failure: function(conn, response, options, eOpts) {
                           Ext.Msg.alert('Error', 'please try again');
                           Ext.getBody().unmask();
                      }});
        

             },
             func_clearfilters: function(btn) {
               grid = this.lookupReference('iegridpanel');
                
                 var t=grid.down('headercontainer').getGridColumns();

                 for (var i = t.length - 1; i >= 2; i--) {
                      column=t[i];
                      var textfield=column.down('textfield');

                      if(textfield!=null){
                         textfield.setValue('');
                      }

                      store=grid.getStore();
                      grid = column.up('grid');
                      grid.getStore().removeFilter(column.filter.property || column.dataIndex);
                      store.reload();
                     // column.setText(column.textEl.dom.firstElementChild.innerText);
                 
                   }
             },
             setPageSize: function(combo, newValue){
               var pagesize=combo.getValue();
               Ext.apply(Ext.getStore('spreadsheetieapplicationcolumnsstr'), {pageSize: pagesize});
             },
             setConfigCombosStore: function (obj, options) {
        this.fireEvent('setConfigCombosStore', obj, options);
    },
    func_viewUploadedDocs: function(btn) {
         var me = btn.up('button'),
              record = me.getWidgetRecord(),
              form = Ext.widget('uploadeddocsperapplicationGrid'),
              application_code = record.get('application_code');
              form.down('hiddenfield[name=application_code]').setValue(application_code);

              funcShowCustomizableWindow('Application Documents', '60%', form, 'customizablewindow');
      
    }, setCommonGridsStore: function (obj, options) {
        this.fireEvent('setCommonGridsStore', obj, options);
    },  
    setConfigGridsStore: function (obj, options) {
      this.fireEvent('setConfigGridsStore', obj, options);
  },  
    

});