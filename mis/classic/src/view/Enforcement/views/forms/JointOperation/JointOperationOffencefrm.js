Ext.define('Admin.view.Enforcement.views.forms.JointOperation.JointOperationOffencefrm', {
    extend: 'Ext.form.Panel',
    xtype: 'jointOperationOffencefrm',
    itemId: 'jointOperationOffencefrm',
    controller: 'enforcementvctr',
    layout: {
        type: 'column'
    },
    //height: Ext.Element.getViewportHeight() - 118,
    autoScroll: true,
    bodyPadding: 5,
    defaults: {
        columnWidth: 0.33,
        margin: 3,
        labelAlign: 'top',
       allowBlank: true,
       
    },
  
    items: [
        {
            xtype: 'hiddenfield',
            name: 'id'
        },
        {
            xtype: 'hiddenfield',
            name: 'isReadOnly'
        },
        {
            xtype: 'hiddenfield',
            name: '_token',
            value: token
        },
        {
            xtype: 'combo', anyMatch: true,
            fieldLabel: 'is facility based',
            name: 'is_facility_based',
            store: 'confirmationstr',
            allowBlank:true,
            //hidden:true,
            valueField: 'id',
            displayField: 'name',
            queryMode: 'local',
            forceSelection: true,
            listeners:{
                afterrender: function(){
                    var store=this.getStore();
                    store.removeAll();
                    store.load();
                },
			 change:function(combo,value)
			 {
				var is_facility_registered=combo.up('form').down('combo[name=is_facility_registered]');
                var permit_container=combo.up('form').down('fieldcontainer[name=permit_container]');
                var individual_name=combo.up('form').down('textfield[name=individual_name]');
				var	individual_physical_address=combo.up('form').down('textfield[name=individual_physical_address]');
                var	individual_designation=combo.up('form').down('textfield[name=individual_designation]');
                var	individual_telephone=combo.up('form').down('numberfield[name=individual_telephone]');
                var premise_name_txt=combo.up('form').down('textfield[name=premise_name]');
				var	permit_no=combo.up('form').down('textfield[name=permit_no]');
                var	physical_address=combo.up('form').down('textfield[name=physical_address]');
                var	telephone=combo.up('form').down('numberfield[name=telephone]');
                var	new_premise_name=combo.up('form').down('textfield[name=new_premise_name]');
                var	new_physical_address=combo.up('form').down('textfield[name=new_physical_address]');
                var	new_telephone=combo.up('form').down('numberfield[name=new_telephone]');
				var	link_registered_premise_btn=combo.up('form').down('button[name=link_registered_premise]');

                    if(value==1){
                         is_facility_registered.setVisible(true);
                         permit_container.setVisible(true);
                         individual_name.setVisible(false);
                         individual_name.allowBlank = true;
                         individual_physical_address.setVisible(false);
                         individual_physical_address.allowBlank = true;
                         individual_designation.setVisible(false);
                         individual_designation.allowBlank = true;
                         individual_telephone.setVisible(false);
                         individual_telephone.allowBlank = true;
					}else{
                        individual_name.setVisible(true);
                         individual_name.allowBlank = false;
                         individual_physical_address.setVisible(true);
                         individual_physical_address.allowBlank = false;
                         individual_designation.setVisible(true);
                         individual_designation.allowBlank = false;
                         individual_telephone.setVisible(true);
                         individual_telephone.allowBlank = false;
                         is_facility_registered.setVisible(false);
                         is_facility_registered.allowBlank = true;
                         permit_container.setVisible(false);
                         permit_container.allowBlank = true;
                         premise_name_txt.setVisible(false);
                         premise_name_txt.allowBlank = true;
                         permit_no.setVisible(false);
                         permit_no.allowBlank = true;
                         physical_address.setVisible(false);
                         physical_address.allowBlank = true;
                         telephone.setVisible(false);
                         telephone.allowBlank = true;
                         new_premise_name.setVisible(false);
                         new_premise_name.allowBlank = true;
                         new_physical_address.setVisible(false);
                         new_physical_address.allowBlank = true;
                         new_telephone.setVisible(false);
                         new_telephone.allowBlank = true;
					}
			 }
            }
        },
         //PREMISE
         {
            xtype: 'combo', anyMatch: true,
            fieldLabel: 'Is Facility Licensed',
            name: 'is_facility_registered',
            store: 'confirmationstr',
            hidden:true,
            allowBlank:true,
            valueField: 'id',
            displayField: 'name',
            queryMode: 'local',
            forceSelection: true,
            listeners:{
                afterrender: function(){
                    var store=this.getStore();
                    store.removeAll();
                    store.load();
                },
			 change:function(combo,value)
			 {
				var premise_name_txt=combo.up('form').down('textfield[name=premise_name]');
				var	permit_no=combo.up('form').down('textfield[name=permit_no]');
                var	physical_address=combo.up('form').down('textfield[name=physical_address]');
                var	telephone=combo.up('form').down('numberfield[name=telephone]');
                var	new_premise_name=combo.up('form').down('textfield[name=new_premise_name]');
                var	new_physical_address=combo.up('form').down('textfield[name=new_physical_address]');
                var	new_telephone=combo.up('form').down('numberfield[name=new_telephone]');
                var permit_container=combo.up('form').down('fieldcontainer[name=permit_container]');
				var	link_registered_premise_btn=combo.up('form').down('button[name=link_registered_premise]');

                    if(value==1){
						link_registered_premise_btn.enable();
                        permit_no.setVisible(true);
                        permit_container.setVisible(true);
                        physical_address.setVisible(true);
                        telephone.setVisible(true);
                        permit_no.allowBlank = true;
                        physical_address.allowBlank = true;
                        telephone.allowBlank = true;
                        premise_name_txt.setVisible(true);
                        premise_name_txt.allowBlank = false;
                        new_premise_name.setVisible(false);
                        new_premise_name.allowBlank = true;
                        new_physical_address.setVisible(false);
                        new_physical_address.allowBlank = true;
                        new_telephone.setVisible(false);
                        new_telephone.allowBlank = true;
					}else{
                        new_premise_name.setVisible(true);
                        new_premise_name.allowBlank = false;
                        new_physical_address.setVisible(true);
                        new_physical_address.allowBlank = false;
                        new_telephone.setVisible(true);
                        new_telephone.allowBlank = false;
                        permit_no.setVisible(false);
                        permit_container.setVisible(false);
                        physical_address.setVisible(false);
                        telephone.setVisible(false);
                        permit_no.allowBlank = true;
                        physical_address.allowBlank = true;
                        telephone.allowBlank = true;
						link_registered_premise_btn.disable();
                        premise_name_txt.setVisible(false);
                        premise_name_txt.allowBlank = true;

					}
			 }
            }
        },
        {
            xtype: 'fieldcontainer',
			readOnly:true,
            layout: 'column',
            name:'permit_container',
           // hidden:true,
           allowBlank:true,
            defaults: {
                labelAlign: 'top'
            },
            fieldLabel: 'License No',
            items: [
                {
                    xtype: 'textfield',
                    name: 'permit_no',
                    readOnly: true,
                    allowBlank:true,
                    columnWidth: 0.9
                },
                {
					bind:{disabled:'readOnly'},
                    xtype: 'button',
                    iconCls: 'x-fa fa-link',
                    columnWidth: 0.1,
                    tooltip: 'Link Licensed Facility',
                    name: 'link_registered_premise',
				    disabled:true,
				    handler: 'showRegistererdFacilitySelectionList',
					
                }
            ]
        },
        {   
			xtype:'textfield',
			name:'premise_name',
			fieldLabel:'Premise Name',
            hidden:true, 
            allowBlank:true,
		},
        {   
			xtype:'textfield',
			name:'physical_address',
			fieldLabel:'Physical Address',
            hidden:true, 
            allowBlank:true,
		},
        
        {   
			xtype:'numberfield',
			name:'telephone',
			fieldLabel:'Telephone',
            hidden:true, 
            allowBlank:true,
		},
       // non-licensed facilities
        {   
			xtype:'textfield',
			name:'new_premise_name',
			fieldLabel:'Premise Name',
            allowBlank:true,
            hidden:true, 
		},
        {   
			xtype:'textfield',
			name:'new_physical_address',
			fieldLabel:'Physical Address',
            hidden:true, 
            allowBlank:true,
		},
        {   
			xtype:'textfield',
			name:'new_postal_address',
			fieldLabel:'Postal Address',
            hidden:true, 
            allowBlank:true,
		},
        {   
			xtype:'numberfield',
			name:'new_telephone',
			fieldLabel:'Telephone',
            hidden:true, 
            allowBlank:true,
		},
       // Individual based offence details
       {   
        xtype:'textfield',
        name:'individual_name',
        fieldLabel:'Individual Name',
        hidden:true, 
        allowBlank:true,
    },
    {   
        xtype:'textfield',
        name:'individual_physical_address',
        fieldLabel:'Physical Address',
        hidden:true, 
        allowBlank:true,
    },
    {   
        xtype:'textfield',
        name:'individual_designation',
        fieldLabel:'Designation',
        hidden:true, 
        allowBlank:true,
    },
    
    {   
        xtype:'numberfield',
        name:'individual_telephone',
        fieldLabel:'Telephone',
        hidden:true, 
        allowBlank:true,
    },
     
        {
            xtype: 'combo', anyMatch: true,
            fieldLabel: 'Offence Type',
            identity:'Offence_type',
            name: 'offence_type_id',
            allowBlank: true,
            valueField: 'id',
            displayField: 'name',
            forceSelection: true,
            fieldStyle: {
                'color': 'green',
                'font-weight': 'bold'
            },
            queryMode: 'local',
            listeners: {
                beforerender: {
                    fn: 'setCompStore',
                    config: {
                        proxy: {
                            extraParams: {
                                table_name: 'par_offence_types'
                            }
                        }
                    },
                    isLoad: true
                }, 
            }
            
        },
        {   
			xtype:'htmleditor',
			name:'action',
			fieldLabel:'Action',
            allowBlank:true
		},
        {   
			xtype:'htmleditor',
			name:'remarks',
			fieldLabel:'Remarks',
            allowBlank:true
		},
      
        
    ],
    buttons: [
        {
            xtype: 'button',
            text: 'Save Details',
            ui: 'soft-purple',
            iconCls: 'x-fa fa-save',
            formBind: true,
            action_url: 'enforcement/saveJointDetectedOffence',
            table_name: 'par_joint_detected_offences',
            storeID: 'jointOperationOffenceGridStr',
            action: 'save_joint_offence_details'
        },{
            xtype: 'button',
            text: 'Clear',
            ui: 'soft-purple',
            iconCls: 'x-fa fa-close',
            handler: function () {
                this.up('form').getForm().reset();
            }
        }
    ],
});