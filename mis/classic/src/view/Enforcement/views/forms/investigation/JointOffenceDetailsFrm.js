Ext.define('Admin.view.Enforcement.views.forms.investigation.JointOffenceDetailsFrm', {
    extend: 'Ext.form.Panel',
    xtype: 'jointOffenceDetailsFrm',
    itemId: 'jointOffenceDetailsFrm',
    controller: 'enforcementvctr',
    layout: {
        type: 'column'
    },
    autoScroll: true,
    bodyPadding: 5,
    defaults: {
        columnWidth: 0.5,
        margin: 3,
        labelAlign: 'top',
        allowBlank: false,
       
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
         //PREMISE
         {
            xtype: 'combo', anyMatch: true,
            fieldLabel: 'Is Facility Registered',
            name: 'is_facility_registered',
            store: 'confirmationstr',
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
				var premise_name_txt=combo.up('form').down('textfield[name=premise_name]');
				var	permit_no=combo.up('form').down('textfield[name=permit_no]');
                var	new_premise_name=combo.up('form').down('textfield[name=new_premise_name]');
                var permit_container=combo.up('form').down('fieldcontainer[name=permit_container]');
				var	link_registered_premise_btn=combo.up('form').down('button[name=link_registered_premise]');

                    if(value==1){
						link_registered_premise_btn.enable();
                        permit_no.setVisible(true);
                        permit_container.setVisible(true);
                        permit_no.allowBlank = true;
                        premise_name_txt.setVisible(true);
                        premise_name_txt.allowBlank = false;
                        new_premise_name.setVisible(false);
                        new_premise_name.allowBlank = true;
					}else{
                        new_premise_name.setVisible(true);
                        new_premise_name.allowBlank = false;
                        permit_no.setVisible(false);
                        permit_container.setVisible(false);
                        permit_no.allowBlank = true;
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
            //hidden:true,
            defaults: {
                labelAlign: 'top'
            },
            fieldLabel: 'Permit No',
            items: [
                {
                    xtype: 'textfield',
                    name: 'permit_no',
                    readOnly: true,
                    columnWidth: 0.9
                },
                {
					bind:{disabled:'readOnly'},
                    xtype: 'button',
                    iconCls: 'x-fa fa-link',
                    columnWidth: 0.1,
                    tooltip: 'Link Registred Facility',
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
		},
        {   
			xtype:'textfield',
			name:'new_premise_name',
			fieldLabel:'Premise Name',
            hidden:true, 
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
			xtype:'numberfield',
			name:'no_of_offence',
			fieldLabel:'No. of Offences',
		},
        {   
			xtype:'textareafield',
			name:'action',
			fieldLabel:'Action',
            allowBlank:true
		},
        {   
			xtype:'textareafield',
			name:'remarks',
			fieldLabel:'Remarks',
            allowBlank:true
		},
      
        
    ],
  
});