var premise_type_id="";
var sub_module="";
Ext.define('Admin.view.openOffice.premise.Controller.SpreadSheetPremiseCtr', {
    extend: 'Ext.app.ViewController',
    alias: 'controller.spreadsheetpremisectr',

    reloadSheetStore: function(combo,newValue,old,eOpts) {
        var form=combo.up('form'),
        store=form.down('pagingtoolbar').getStore();
        sub_module=newValue;
        if(newValue==0){
            sub_module="";
         }

        store.load();
    }, 
      setParamCombosStore: function (obj, options) {
        this.fireEvent('setParamCombosStore', obj, options);
    },
    setConfigCombosSectionfilterStore: function (obj, options) {

        this.fireEvent('setConfigCombosStoreWithSectionFilter', obj, options);
    },
 
    loadApplicationColumns: function(sender,record) {
        sectionid=record.data['id'];
        if(sectionid==0){
                sectionid='';
        }
              //load filters to other depedent stores
        var filter = {'t1.section_id':sectionid};
        var   filters = JSON.stringify(filter);
        var form =this.lookupReference('premisegridpanel'),
        // BsnTypestore=form.down('combo[name=BsnType_id]').getStore();
             
       // BsnTypestore.removeAll();
        // BsnTypestore.load({params:{filters:filters,table_name: 'par_business_types'}});

               //loading grid
        sub_module=this.getView().down('combo[name=sub_module]').getValue();
              //add filters
        var filter = {'t1.section_id':sectionid,'t1.sub_module_id':sub_module};
         var   filters = JSON.stringify(filter);
        Ext.data.StoreManager.lookup('spreadsheetpremiseapplicationcolumnsstr').load({params:{filters:filters}});

        },

    loadadditionalinfo:  function(sender,record) {
        var premiseid=record.data['premise_id'];
        var filter = { 't1.premise_id':premiseid };
        var   filters = JSON.stringify(filter);
              
        Ext.apply(Ext.getStore('spreadsheetpremisebsninfostr').reload({params:{filters:filters}}));
        Ext.apply(Ext.getStore('spreadsheetpremisepersonnelinfostr').reload({params:{filters:filters}}));
    
    },
    funcReloadspreadSheetStrs: function() {

        Ext.apply(Ext.getStore('spreadsheetpremiseapplicationcolumnsstr').reload());
    }, 
    func_showhideSpreasheetColumn: function (chk, value) {
        var  chk_name = chk.name;
        var form=chk.up('form'),
        container = form.up('form'),
        grid = container.down('spreadsheetpremiseview');
        grid.columns[chk_name].setVisible(value);
    },
            
    func_exportpremisespreadsheet: function (btn) {
        var name=btn.name;
        var container=btn.up('form'),
        grid = container.down('spreadsheetpremiseview'),
        premises_type_grid = container.down('spreadsheetpremisetypes'),
        filterfield = grid.getPlugin('filterfield');
        //filters
        var filter_array =Ext.pluck( filterfield.getgridFilters(grid), 'config');
        var  Category_id=grid.down('combo[name=Category_id]').getValue(),
        BsnType_id=grid.down('combo[name=BsnType_id]').getValue(),
        BsnScale_id=grid.down('combo[name=BsnScale_id]').getValue(),
        zone_id=grid.down('combo[name=zone_id]').getValue(),
        registration_status=grid.down('combo[name=registration_status]').getValue(),
        validity_status=grid.down('combo[name=validity_status]').getValue(),
        BsnCategory_id=grid.down('combo[name=BsnCategory_id]').getValue();

        var Originalfilter = {'t1.sub_module_id':sub_module};
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
        Ext.getBody().mask('Exporting Records Please wait...');
        filter_array = Ext.JSON.encode(filter_array);
        Ext.Ajax.request({
                url: 'openoffice/exportall',
                method: 'POST',
                params : {
                          'header':header,
                          'premise_type_id':premise_type_id,
                          'filters':filters,
                          'filter':filter_array,
                          'Category': Category_id,
                          'BsnType': BsnType_id,
                          'BsnCategory': BsnCategory_id,
                          'BsnScale_id':BsnScale_id,
                          'issueplace':zone_id,
                          'heading': 'Premise Applications Spreadsheet',
                          'function':'getPremiseApplicationColumns',
                          'filename':'PremiseProdductsSpreadsheet'
                },
                      
        success: function (response, textStatus, request) {
          Ext.getBody().unmask();
          var t = JSON.parse(response.responseText);
          if (t.success==true ||t.success===true) {
              var a = document.createElement("a");
              a.href = t.file; 
              a.download = t.name;
              document.body.appendChild(a);

              a.click();
                             
              a.remove();

          } else {
              toastr.error(t.message, 'Warning Response');
          }
                  
        },
        failure: function(conn, response, options, eOpts) {
              Ext.getBody().unmask();
              Ext.Msg.alert('Error', 'please try again');
        }
        });
    },

    func_checkFilters: function(btn) {
      var container=btn.up('form'),
      form = container.down('spreadsheetpremisevisiblecolumns'),
      fields=form.getForm().getFields();

      fields.each(function(checkbox){
            checkbox.setValue(btn.re);
      });
      if(btn.re){
        btn.re=false;
        btn.setText('Unselect All');
      }
      else{
        btn.re=true;
        btn.setText('Select All');
       }
    },
    func_clearfilters: function(btn) {
      var container=btn.up('form'),
      grid = container.down('spreadsheetpremiseview');
                
      var t=grid.down('headercontainer').getGridColumns();

      for (var i = t.length - 1; i >= 2; i--) {
          column=t[i];
          var textfield=column.down('textfield');
          var combo=column.down('combobox');

          if(textfield!=null){
            textfield.setValue('');
          }else{
            combo.setValue(0);
          }

      }

    },
    setPageSize: function(combo, newValue){
      var pagesize=combo.getValue();
      Ext.apply(Ext.getStore('spreadsheetpremiseapplicationcolumnsstr'), {pageSize: pagesize});
    },
    setConfigCombosStore: function (obj, options) {
      this.fireEvent('setConfigCombosStore', obj, options);
    },
    setConfigGridsStore: function (me, options) {
      var config = options.config,
      isLoad = options.isLoad,
      toolbar = me.down('pagingtoolbar'),
      store = Ext.create('Admin.store.configurations.ConfigGridAbstractStore', config);
      me.setStore(store);
      toolbar.setStore(store);
      if (isLoad === true || isLoad == true) {
          store.removeAll();
          store.load();
      }
    },
    setPremiseTypeGridStore: function (me, options) {
      var config = options.config,
      isLoad = options.isLoad,
        
      store = Ext.create('Admin.store.configurations.ConfigGridAbstractStore', config);
      me.setStore(store);
       
      if (isLoad === true || isLoad == true) {
          store.removeAll();
          store.load();
          store.on('load', function () {
                var all={name: 'All',id:0};
                store.insert(0, all);
            });
        }
     },
    setWorkflowCombosStore: function (obj, options) {
      this.fireEvent('setWorkflowCombosStore', obj, options);
      },
    func_viewUploadedDocs: function(btn) {
      var me = btn.up('button'),
      record = me.getWidgetRecord(),
      form = Ext.widget('uploadeddocsperapplicationGrid'),
      application_code = record.get('application_code');
      form.down('hiddenfield[name=application_code]').setValue(application_code);

      funcShowCustomizableWindow('Application Documents', '60%', form, 'customizablewindow');
      
    },
    quickNavigation: function (btn) {
        var step = btn.step,
        wizard = btn.wizard,
        wizardPnl = btn.up(wizard),
        panel = wizardPnl.up('panel'),
        progress = wizardPnl.down('#progress_tbar'),
        progressItems = progress.items.items;
                    
        if (step > 0) {
            var thisItem = progressItems[step];
                 
        }
      
            if (step == 0) {
                wizardPnl.getViewModel().set('atBeginning', true);
                wizardPnl.getViewModel().set('atEnd', false);
            } else if (step == 1) {
                wizardPnl.getViewModel().set('atBeginning', false);
                wizardPnl.getViewModel().set('atEnd', false);
            }else if (step == 2) {
                wizardPnl.getViewModel().set('atBeginning', false);
                wizardPnl.getViewModel().set('atEnd', true);
            } else {
                wizardPnl.getViewModel().set('atEnd', true);
            }

       

        wizardPnl.getLayout().setActiveItem(step);
        var layout = wizardPnl.getLayout(),
        item = null,
        i = 0,
        activeItem = layout.getActiveItem();
        for (i = 0; i < progressItems.length; i++) {
            item = progressItems[i];
            if (step === item.step) {
                item.setPressed(true);
            }
            else {
                item.setPressed(false);
            }
            if (Ext.isIE8) {
                item.btnIconEl.syncRepaint();
            }
        }
        activeItem.focus();
    },

     onPrevCardClick: function (btn) {
        var wizardPnl = btn.up('panel');
        wizardPnl.getViewModel().set('atEnd', false);
        this.navigateMoreDetails(btn, wizardPnl, 'prev');

    },

    onNextCardClick: function (btn) {
        var wizardPnl = btn.up('panel');
        wizardPnl.getViewModel().set('atBeginning', false);
        this.navigateMoreDetails(btn, wizardPnl, 'next');
    },

   navigateMoreDetails: function (button, wizardPanel, direction) {
        var layout = wizardPanel.getLayout(),
        progress = this.lookupReference('progress'),
        model = wizardPanel.getViewModel(),
        panel = wizardPanel.up('panel'),
        progressItems = progress.items.items,
        item, i, activeItem, activeIndex;
        layout[direction]();
        activeItem = layout.getActiveItem();
        activeIndex = wizardPanel.items.indexOf(activeItem);
        application_status_id= panel.down('hiddenfield[name=application_status_id]').getValue();
        for (i = 0; i < progressItems.length; i++) {
            item = progressItems[i];

            if (activeIndex === item.step) {
                item.setPressed(true);
            } else {
                item.setPressed(false);
            }
            if (Ext.isIE8) {
                item.btnIconEl.syncRepaint();
            }
        }
        activeItem.focus();

        // beginning disables previous
        if (activeIndex === 0) {
            //wizardPanel.down('button[name=save_btn]').setDisabled(true);
            model.set('atBeginning', true);
        } else {
            //wizardPanel.down('button[name=save_btn]').setDisabled(false);
            model.set('atBeginning', false);
        }
        if (activeIndex === 1) {
            model.set('atEnd', false);
        } else {
            model.set('atEnd', false);
        }
         if (activeIndex === 2) {
            model.set('atEnd', true);
        } else {
            model.set('atEnd', false);
        }

     
    },


     showPfAddPremiseOtherdetailsWinFrm: function (btn) {
        var me = this,
        grid=btn.up('grid');
        if (grid.down('hiddenfield[name=premise_id]')) {
            premise_id = grid.down('hiddenfield[name=premise_id]').getValue();
          
        } else {
            var grid=btn.up('grid'),
            panel=grid.up('panel'),
            mainpnl=panel.up('panel'),
            parentpanel=mainpnl.up('panel'),
            premise_id = parentpanel.down('hiddenfield[name=premise_id]').getValue();
                
        }
        var childXtype = btn.childXtype,
        winTitle = btn.winTitle,
        winWidth = btn.winWidth,
        child = Ext.widget(childXtype),
        storeArray = eval(btn.stores),
        arrayLength = storeArray.length;
        funcShowCustomizableWindow(winTitle, winWidth, child, 'customizablewindow');
        child.down('hiddenfield[name=premise_id]').setValue(premise_id);
       
        if (arrayLength > 0) {
            me.fireEvent('refreshStores', storeArray);
        }
    }, 

    showPfAddPremiseResponsibleWinFrm: function (btn) {
        var me = this,
        grid=btn.up('grid'),
        tabpanel=grid.up('panel'),             
        panel= tabpanel.up('panel'),
        activeTab=panel.up('panel'),   
        applicant_id = 0;
        if(activeTab.down('hiddenfield[name=applicant_id]')){
            applicant_id = activeTab.down('hiddenfield[name=applicant_id]').getValue();

        }
        if (grid.down('hiddenfield[name=premise_id]')) {
            premise_id = grid.down('hiddenfield[name=premise_id]').getValue();
            
        } else {
            var win = btn.up('window'),
            premise_id = win.down('hiddenfield[name=premise_id]').getValue();
                
        }
        var childXtype = btn.childXtype,
        winTitle = btn.winTitle,
        winWidth = btn.winWidth,
        child = Ext.widget(childXtype),
        storeArray = eval(btn.stores),
        arrayLength = storeArray.length;
        funcShowCustomizableWindow(winTitle, winWidth, child, 'customizablewindow');
        child.down('hiddenfield[name=premise_id]').setValue(premise_id);
        child.down('hiddenfield[name=trader_id]').setValue(applicant_id);
       
       
        if (arrayLength > 0) {
            me.fireEvent('refreshStores', storeArray);
        }
    }, 


    showEditPfPersonnelDetail: function (item) {
        var me = this,
        btn = item.up('button'),
        grid = btn.up('grid'),
        record = btn.getWidgetRecord(),
        childXtype = item.childXtype,
        winTitle = item.winTitle,
        winWidth = item.winWidth,
        tabpanel=grid.up('panel'),             
        panel= tabpanel.up('panel'),
        activeTab=panel.up('panel');
        applicant_id = 0;
        if(activeTab.down('hiddenfield[name=applicant_id]')){
            applicant_id = activeTab.down('hiddenfield[name=applicant_id]').getValue();

        }
        form = Ext.widget(childXtype);
        form.loadRecord(record);
        form.down('hiddenfield[name=trader_id]').setValue(applicant_id);
        funcShowCustomizableWindow(winTitle, winWidth, form, 'customizablewindow');

    },

    savePfPremisePermitPersonnelDetails: function (btn) {
        var me = this,
            url = btn.action_url,
            form = btn.up('form'),
            win = form.up('window'),
            basicInfoFrm = win.down('form'),
            storeID = btn.storeID,
            store = Ext.getStore(storeID),
            frm = form.getForm();
        if (frm.isValid()) {
            frm.submit({
                url: url,
                waitMsg: 'Please wait...',
                headers: {
                    'Authorization': 'Bearer ' + access_token
                },
                success: function (fm, action) {
                    var response = Ext.decode(action.response.responseText),
                        success = response.success,
                        message = response.message;
                    if (success == true || success === true) {
                        store.load();
                        toastr.success(message, "Success Response");
                        win.close();
                    } else {
                        toastr.error(message, 'Failure Response');
                    }
                },
                failure: function (fm, action) {
                    var resp = action.result;
                    toastr.error(resp.message, 'Failure Response');
                }
            });
        }
    },


     showEditPersonneldetailWinFrm: function (item) {
        var me = this,
        btn = item.up('button'),
        grid = btn.up('grid'),
        record = btn.getWidgetRecord(),
        trader_id = grid.down('hiddenfield[name=trader_id]').getValue(),
        childXtype = item.childXtype,
        winTitle = item.winTitle,
        winWidth = item.winWidth,
        form = Ext.widget(childXtype);
        form.loadRecord(record);
        form.down('hiddenfield[name=trader_id]').setValue(trader_id);
        form.down('hiddenfield[name=id]').setValue(record.get('personnel_id'));
        funcShowCustomizableWindow(winTitle, winWidth, form, 'customizablewindow');

    },

    showEditBasePersonneldetail: function (item) {
        var me = this,
        btn = item.up('button'),
        grid = btn.up('grid'),
        record = btn.getWidgetRecord(),
        trader_id = grid.down('hiddenfield[name=trader_id]').getValue(),
        childXtype = item.childXtype,
        winTitle = item.winTitle,
        winWidth = item.winWidth,
        form = Ext.widget(childXtype);
        form.loadRecord(record);
        form.down('hiddenfield[name=trader_id]').setValue(trader_id);
        form.down('hiddenfield[name=id]').setValue(record.get('id'));
        funcShowCustomizableWindow(winTitle, winWidth, form, 'customizablewindow');

    },

 showPfAddPremiseOtherDetailsWin: function (btn) {
        var me = this,
            grid = btn.up('grid'),
            win = grid.up('window'),
            premise_id = win.down('hiddenfield[name=premise_id]').getValue(),
            section_id = win.down('hiddenfield[name=section_id]').getValue(),
            application_id = win.down('hiddenfield[name=application_id]').getValue(),
            title = btn.winTitle,
            form = Ext.widget('premiseotherdetailsfrm'),
            storeArray = eval(btn.stores),
            arrayLength = storeArray.length,
            filter = "section_id:" + section_id,
            busTypesStr = form.down('combo[name=business_type_id]').getStore();
        if (!application_id) {
            toastr.warning('Please save application first!!', 'Warning Response');
            return false;
        }
        form.down('hiddenfield[name=premise_id]').setValue(premise_id);
        busTypesStr.removeAll();
        busTypesStr.load({params: {filter: filter}});
        if (arrayLength > 0) {
            me.fireEvent('refreshStores', storeArray);
        }
        funcShowCustomizableWindow(title, '35%', form, 'customizablewindow');
    },

showPfTraderPersonnelSelectionGrid: function (btn) {
        var form = btn.up('form'),
            tabpanel=form.up('panel'),             
            panel= tabpanel.up('panel'),
            activeTab=panel.up('panel'),   
            motherPnl=activeTab.up('panel');
            applicant_id = 0;
            var width = btn.winWidth,
            moreDetails = form.getMoreDetails(),
            datacleanup_request_id = motherPnl.down('hiddenfield[name=datacleanup_request_id]').getValue(),
            personnel_type = form.down('hiddenfield[name=personnel_type]').getValue(),
            childItem = Ext.widget(btn.childXtype);
        childItem.setMoreDetails(moreDetails);
        if(activeTab.down('hiddenfield[name=applicant_id]')){
            applicant_id = activeTab.down('hiddenfield[name=applicant_id]').getValue();

        }
        childItem.down('hiddenfield[name=datacleanup_request_id]').setValue(datacleanup_request_id);
        childItem.down('hiddenfield[name=trader_id]').setValue(applicant_id);
        childItem.down('hiddenfield[name=personnel_type]').setValue(personnel_type);
        childItem.addListener('itemdblclick', "onClnTraderPersonnelItemdblclick", this);
        funcShowCustomizableWindow('Personnel', width, childItem, 'customizablewindow');
    },

    onClnTraderPersonnelItemdblclick: function (view, record) {
        var grid = view.grid,
            win = grid.up('window'),
            moreDetails = grid.getMoreDetails(),
            personnel_type = grid.down('hiddenfield[name=personnel_type]').getValue(),
            activeTab = Ext.ComponentQuery.query('#premisescleanupdetailstabPnl')[0];
            win.close();
        if (personnel_type == 'contact_person') {
            basicFrm = activeTab.down('premisedatacleanupcontactpersonfrm');
            basicFrm.loadRecord(record);
        } else {
            var anotherWin = Ext.WindowManager.getActive();
            if (anotherWin) {
                var form = anotherWin.down('form');
                form.loadRecord(record);
            }
        }
    },

    showPfPersonnelSelectionGrid: function (btn) {
        var form = btn.up('form');          
            activeTab = Ext.ComponentQuery.query('#premisesfrontofficeupdatetabpnl')[0]; 
            wizardPnl = activeTab.up('panel');   
            motherPnl=activeTab.up('panel');
            applicant_id = 0;

            var width = btn.winWidth,
            personnel_type = form.down('hiddenfield[name=personnel_type]').getValue(),
            childItem = Ext.widget(btn.childXtype);
        if(wizardPnl.down('hiddenfield[name=applicant_id]')){
            applicant_id = wizardPnl.down('hiddenfield[name=applicant_id]').getValue();

        }
        childItem.down('hiddenfield[name=trader_id]').setValue(applicant_id);
        childItem.down('hiddenfield[name=personnel_type]').setValue(personnel_type);
        childItem.addListener('itemdblclick', "onPfPersonnelItemdblclick", this);
        funcShowCustomizableWindow('Personnel', width, childItem, 'customizablewindow');
    },

    onPfPersonnelItemdblclick: function (view, record) {
        var grid = view.grid,
            win = grid.up('window');
            win.close();
        
            var anotherWin = Ext.WindowManager.getActive();
            if (anotherWin) {
                var form = anotherWin.down('form');
                //form.loadRecord(record);
                form.down('hiddenfield[name=personnel_id]').setValue(record.get('superintendent_id'));
                form.down('textfield[name=name]').setValue(record.get('contact_name'));
                form.down('textfield[name=postal_address]').setValue(record.get('contact_postal_address'));
                form.down('textfield[name=telephone_no]').setValue(record.get('contact_telephone_no'));
                form.down('textfield[name=email_address]').setValue(record.get('contact_email_address'));
            }
    },
    setOrgConfigCombosStore: function (obj, options) {
        this.fireEvent('setOrgConfigCombosStore', obj, options);
    },
     showFrontOfficeApplicant: function(btn) {
        var grid = Ext.widget(btn.childXtype),
        winTitle = btn.winTitle;
        grid.addListener('itemdblclick', btn.handlerFn, this);
        funcShowCustomizableWindow(winTitle, "70%", grid, 'customizablewindow');

    },
    loadfrontOfficeSelectedPremiseApplicant :function(view, record) {
        var grid = view.up('grid'),
        form=Ext.ComponentQuery.query('#frontofficeapplicantdetailsfrm')[0];
        form.loadRecord(record);
        grid.up('window').close();
    },
    setPremiseRegGridsStore: function (obj, options) {
        this.fireEvent('setPremiseRegGridsStore', obj, options);
    },


    showPfAddTraderPersonnelForm: function (btn) {
        var grid = btn.up('grid'),
        trader_id = grid.down('hiddenfield[name=trader_id]').getValue(),
        width = btn.winWidth,
        childObject = Ext.widget(btn.childXtype);
        childObject.down('hiddenfield[name=trader_id]').setValue(trader_id);
       //childObject.down('hiddenfield[name=datacleanup_request_id]').setValue(datacleanup_request_id);
        funcShowCustomizableWindow('Premise Personnel', width, childObject, 'customizablewindow');
    },


     doCreatePfConfigParamWin: function (btn) {
        var me = this,
            url = btn.action_url,
            table = btn.table_name,
            form_xtype = btn.up('form'),
            win = form_xtype.up('window'),
            storeID = btn.storeID,
            store = Ext.getStore(storeID);
            var frm = form_xtype.getForm();
            
        if (frm.isValid()) {
            frm.submit({
                url: url,
                params: {
                    model: table
                },
                waitMsg: 'Please wait...',
                headers: {
                    'Authorization': 'Bearer ' + access_token
                },
                success: function (form, action) {
                    var response = Ext.decode(action.response.responseText),
                        success = response.success,
                        message = response.message;
                    if (success == true || success === true) {
                        toastr.success(message, "Success Response");
                        store.removeAll();
                        store.load();
                        win.close();
                    } else {
                        toastr.error(message, 'Failure Response');
                    }
                },
                failure: function (form, action) {
                    var resp = action.result;
                    toastr.error(resp.message, 'Failure Response');
                }
            });
        }
    },

    saveBasicPersonnelDetails: function (btn) {
        var me = this,
            url = btn.action_url,
            table = btn.table_name,
            form_xtype = btn.up('form'),
            win = form_xtype.up('window'),
            storeID = btn.storeID,
            store = Ext.getStore(storeID);
            var frm = form_xtype.getForm();
            
        if (frm.isValid()) {
            frm.submit({
                url: url,
                params: {
                    model: table
                },
                waitMsg: 'Please wait...',
                headers: {
                    'Authorization': 'Bearer ' + access_token
                },
                success: function (form, action) {
                    var response = Ext.decode(action.response.responseText),
                        success = response.success,
                        message = response.message;
                    if (success == true || success === true) {
                        toastr.success(message, "Success Response");
                        store.removeAll();
                        store.load();
                        win.close();
                    } else {
                        toastr.error(message, 'Failure Response');
                    }
                },
                failure: function (form, action) {
                    var resp = action.result;
                    toastr.error(resp.message, 'Failure Response');
                }
            });
        }
    },


      showPfEditPremiseOtherdetailWinFrm: function (item) {
        var me = this,
        btn = item.up('button'),
        record = btn.getWidgetRecord(),
        childXtype = item.childXtype,
        winTitle = item.winTitle,
        winWidth = item.winWidth,
        form = Ext.widget(childXtype);
        form.loadRecord(record);
        funcShowCustomizableWindow(winTitle, winWidth, form, 'customizablewindow');

    },


     deletePfPremiseOtherdetails: function (item) {
            var me = this,
            btn = item.up('button'),
            record = btn.getWidgetRecord(),
            id = record.get('id'),
            premise_id = record.get('premise_id'),
            storeID = item.storeID,
            store = Ext.getStore(storeID),
            table_name = item.table_name,
            url = item.action_url;
        Ext.MessageBox.confirm('Delete', 'Are you sure to perform this action ?', function (btn) {
            if (btn === 'yes') {
                Ext.getBody().mask('Deleting record...');

                Ext.Ajax.request({
                    url: url,
                    method: 'POST',
                    params: {
                        table_name: table_name,
                        id: id
                    },
                    headers: {
                        'Authorization': 'Bearer ' + access_token,
                        'X-CSRF-Token': token
                    },
                    success: function (response) {
                        Ext.getBody().unmask();
                           var resp = Ext.JSON.decode(response.responseText),
                            message = resp.message,
                            success = resp.success;
                        if (success == true || success === true) {
                            toastr.success(message, 'Success Response');
                            store.removeAll();
                            store.load({ params: { premise_id: premise_id } });
                        } else {
                            toastr.error(message, 'Failure Response');
                        }
                    },
                    failure: function (response) {
                        Ext.getBody().unmask();
                        var resp = Ext.JSON.decode(response.responseText),
                            message = resp.message;
                        toastr.error(message, 'Failure Response');
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        Ext.getBody().unmask();
                        toastr.error('Error deleting data: ' + errorThrown, 'Error Response');
                    }
                });
            } else {
                //
            }
        });
    },

    deletePfPremisePersonnelBasicdetails: function (item) {
            var me = this,
            btn = item.up('button'),
            record = btn.getWidgetRecord(),
            id = record.get('id'),
            premise_id = record.get('premise_id'),
            storeID = item.storeID,
            store = Ext.getStore(storeID),
            table_name = item.table_name,
            url = item.action_url;
        Ext.MessageBox.confirm('Delete', 'Are you sure to perform this action ?', function (btn) {
            if (btn === 'yes') {
                Ext.getBody().mask('Deleting record...');

                Ext.Ajax.request({
                    url: url,
                    method: 'POST',
                    params: {
                        table_name: table_name,
                        id: id
                    },
                    headers: {
                        'Authorization': 'Bearer ' + access_token,
                        'X-CSRF-Token': token
                    },
                    success: function (response) {
                        Ext.getBody().unmask();
                           var resp = Ext.JSON.decode(response.responseText),
                            message = resp.message,
                            success = resp.success;
                        if (success == true || success === true) {
                            toastr.success(message, 'Success Response');
                            store.removeAll();
                            store.load({ params: { premise_id: premise_id } });
                        } else {
                            toastr.error(message, 'Failure Response');
                        }
                    },
                    failure: function (response) {
                        Ext.getBody().unmask();
                        var resp = Ext.JSON.decode(response.responseText),
                            message = resp.message;
                        toastr.error(message, 'Failure Response');
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        Ext.getBody().unmask();
                        toastr.error('Error deleting data: ' + errorThrown, 'Error Response');
                    }
                });
            } else {
                //
            }
        });
    },

    func_LoadBusinessTypeDetailsCombo: function(combo,newValue,old,eopt) {
     var form = combo.up('form'),
       btdCombo = form.down('combo[name=business_type_detail_id]'); 

      if(newValue!=0){
         var filter = {'business_type_id':newValue};
         var filters = JSON.stringify(filter);
         var store = btdCombo.getStore();
         store.removeAll();
         store.load({params:{filters:filters}});
      }else{
         var store=btdCombo.getStore();
         store.removeAll();
         store.load();
      }
          
   },




   ////
    func_updateDetails: function (item) { var me = this,
            btn = item.up('button'),
            record = btn.getWidgetRecord(),
            grid=btn.up('grid'),
            application_code=record.get('application_code'),
            premise_id=record.get('premise_id'),
            applicant_id=record.get('applicant_id'),
            application_id=record.get('application_id'),
            application_status_id=record.get('application_status_id');
            child = Ext.widget('premisesfrontofficeupdatepnl');
            wizardPnl = child.down('premisesfrontofficeupdatewizzardpnl');
            applicantFrm = child.down('frontofficeapplicantdetailsfrm');
            premiseFrm = child.down('premisupdatefrontofficedetailsfrm');
            contactPersonFrm = child.down('premisecontactpersonfrm');
            // premisepersonneldetailStore = child.down('premisepersonneldetailsgrid').getStore();
            // premiseotherdetailStore = child.down('premiseotherdetailsgrid').getStore();
            approval_frm = child.down('premisefrontofficeapprovaldetailsfrm');
            child.down('hiddenfield[name=application_code]').setValue(application_code);
            child.down('hiddenfield[name=premise_id]').setValue(premise_id);
            wizardPnl.down('hiddenfield[name=active_application_id]').setValue(application_id);
            child.down('hiddenfield[name=application_status_id]').setValue(application_status_id);
           Ext.Ajax.request({
            method: 'GET',
            url: 'premiseregistration/getPremApplicationMoreDetails',
            params: {
                application_id: application_id,
                premise_id: premise_id,
                applicant_id: applicant_id
            },
            headers: {
                'Authorization': 'Bearer ' + access_token
            },
            success: function (response) {
                Ext.getBody().unmask();
                var resp = Ext.JSON.decode(response.responseText),
                    success = resp.success,
                    message = resp.message,
                    applicantDetails = resp.applicant_details,
                    premiseDetails = resp.premise_details,
                    contactDetails = resp.contact_details;
                if (success == true || success === true) {
                    if (applicantDetails) {
                        var model1 = Ext.create('Ext.data.Model', applicantDetails);
                        applicantFrm.loadRecord(model1);
                    }
                    if (premiseDetails) {
                        var model2 = Ext.create('Ext.data.Model', premiseDetails);
                        premiseFrm.loadRecord(model2);
                    }
                    if (contactDetails) {
                        var model3 = Ext.create('Ext.data.Model', contactDetails);
                        contactPersonFrm.loadRecord(model3);
                    }
                    } else {
                        toastr.error(message, 'Failure Response');
                    }
                },
                failure: function (response) {
                    Ext.getBody().unmask();
                    var resp = Ext.JSON.decode(response.responseText),
                    message = resp.message,
                    success = resp.success;
                    toastr.error(message, 'Failure Response');
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    Ext.getBody().unmask();
                    toastr.error('Error: ' + errorThrown, 'Error Response');
                }
            });

            Ext.Ajax.request({
                    url: "openoffice/getApprovalDetails",
                    method: 'GET',
                    params: {
                        application_code: record.get('application_code'),
                        start: 0,
                        limit: 1000
                    },
                    headers: {
                        'Authorization': 'Bearer ' + access_token,
                        'X-CSRF-Token': token
                    },
                    success: function (response) {
                       
                        var resp = Ext.JSON.decode(response.responseText),
                            message = resp.message,
                            success = resp.success;
                        if (success == true || success === true) {
                            var model = Ext.create('Ext.data.Model', resp.results[0]);
                           approval_frm.loadRecord(model);
                        } else {
                            toastr.error(message, 'Failure Response');
                            
                        }
                    },
                    failure: function (response) {
                        
                        var resp = Ext.JSON.decode(response.responseText),
                            message = resp.message;
                        toastr.error(message, 'Failure Response');
                        
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                      
                        toastr.error('Error fetching data: ' + errorThrown, 'Error Response');
                       
                    }
                });
         
            child.setHeight(500);
            funcShowCustomizableWindow('Application Details', '80%', child, 'customizablewindow'); 

            
    },
    funcViewApplicationProcess:function (item) {
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
    getApplicationApprovalDetails: function (item) {
        Ext.getBody().mask('Please wait...');
        var me = this,
            is_update = item.is_update,
            isAlt = item.isAlt,
            btn = item.up('button'),
            record = btn.getWidgetRecord(),
            application_id = record.get('application_id'),
            application_code = record.get('application_code'),
            process_id = record.get('process_id'),
            workflow_stage_id = record.get('workflow_stage_id'),
            table_name = item.table_name,
            form = Ext.widget('manualapprovalrecommendationfrm'),
            storeArray = eval(item.stores),
            arrayLength = storeArray.length;
        form.setController('premiseregistrationvctr');
        
        if (arrayLength > 0) {
            me.fireEvent('refreshStores', storeArray);
        }
        if (is_update > 0) {
            form.down('combo[name=decision_id]').setReadOnly(false);
            form.down('datefield[name=approval_date]').setReadOnly(false);
            form.down('datefield[name=expiry_date]').setReadOnly(false);
            form.down('textarea[name=comment]').setReadOnly(false);
            form.down('button[name=save_recommendation]').setText('Update Recommendation');
        }
        form.down('hiddenfield[name=table_name]').setValue(table_name);
        Ext.Ajax.request({
            method: 'GET',
            url: 'getApplicationApprovalDetails',
            params: {
                application_id: application_id,
                application_code: application_code
            },
            headers: {
                'Authorization': 'Bearer ' + access_token
            },
            success: function (response) {
                Ext.getBody().unmask();
                var resp = Ext.JSON.decode(response.responseText),
                    success = resp.success,
                    message = resp.message,
                    results = resp.results,
                    model = Ext.create('Ext.data.Model', results);
                if (success == true || success === true) {
                    form.loadRecord(model);
                    form.down('hiddenfield[name=application_id]').setValue(application_id);
                    form.down('hiddenfield[name=application_code]').setValue(application_code);
                    form.down('hiddenfield[name=process_id]').setValue(process_id);
                    form.down('hiddenfield[name=workflow_stage_id]').setValue(workflow_stage_id);
                    funcShowCustomizableWindow('Recommendation', '40%', form, 'customizablewindow');
                } else {
                    toastr.error(message, 'Failure Response');
                }
            },
            failure: function (response) {
                Ext.getBody().unmask();
                var resp = Ext.JSON.decode(response.responseText),
                    message = resp.message;
                toastr.error(message, 'Failure Response');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                Ext.getBody().unmask();
                toastr.error('Error: ' + errorThrown, 'Error Response');
            }
        });
    },

    updatePremiseApplicationDetails: function (btn) {
        var me = this,
            wizardPnl = btn.up('panel'),
            process_id = wizardPnl.down('hiddenfield[name=process_id]').getValue(),
            module_id = wizardPnl.down('hiddenfield[name=module_id]').getValue(),
            sub_module_id = wizardPnl.down('hiddenfield[name=sub_module_id]').getValue(),
            section_id = wizardPnl.down('hiddenfield[name=section_id]').getValue(),
            workflow_stage_id = wizardPnl.down('hiddenfield[name=workflow_stage_id]').getValue(),
            application_id = wizardPnl.down('hiddenfield[name=active_application_id]').getValue(),
            applicantDetailsForm = wizardPnl.down('frontofficeapplicantdetailsfrm'),
            applicant_id = applicantDetailsForm.down('hiddenfield[name=applicant_id]').getValue(),
            premiseDetailsForm = wizardPnl.down('premisupdatefrontofficedetailsfrm'),
            premiseDetailsFrm = premiseDetailsForm.getForm(),
            action_url = 'premiseregistration/saveRenewalReceivingBaseDetails';
      
        if (!applicant_id) {
            toastr.warning('Please select applicant!!', 'Warning Response');
            return false;
        }
        if (premiseDetailsFrm.isValid()) {
            premiseDetailsFrm.submit({
                url: action_url,
                waitMsg: 'Please wait...',
                params: {
                    process_id: process_id,
                    workflow_stage_id: workflow_stage_id,
                    application_id: application_id,
                    applicant_id: applicant_id,
                    module_id: module_id,
                    sub_module_id: sub_module_id,
                    section_id: section_id
                },
                headers: {
                    'Authorization': 'Bearer ' + access_token,
                    'X-CSRF-Token': token
                },
                success: function (frm, action) {
                    var resp = action.result,
                        message = resp.message,
                        success = resp.success;
                    if (success == true || success === true) {
                        toastr.success(message, "Success Response");
                    } else {
                        toastr.error(message, "Failure Response");
                    }
                },
                failure: function (frm, action) {
                    var resp = action.result,
                        message = resp.message;
                    toastr.error(message, "Failure Response");
                }
            });
        } else {
            toastr.warning('Please fill all the required fields!!', 'Warning Response');
            return false;
        }
    },
         

});