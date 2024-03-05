Ext.define('Admin.view.Enforcement.views.forms.MonitoringCompliance.WorkPlanRegionsFrm.js', {
    extend: 'Ext.form.Panel',
    xtype: 'workPlanRegionsFrm',
    controller: 'enforcementvctr',
    layout: 'column',
    controller:'enforcementvctr',
    bodyPadding: 5,
    autoScroll: true,
    height: Ext.Element.getViewportHeight() - 118,
    defaults: {
        columnWidth: 1,
        margin: 2,
        labelAlign: 'top',
        allowBlank: false
    },
 
    items: [

        {
            xtype: 'hiddenfield',
            name: 'id'
        },
        {
            xtype: 'hiddenfield',
            name: '_token',
            value: token
        },
        {
            xtype: 'hiddenfield',
            name: 'monitoring_plan_id',
        },
        {
            xtype: 'tagfield',
            fieldLabel: 'Select Region',
            margin: '0 20 20 0',
            // store: 'regionsstr',
            name: 'region_id',
            allowBlank: false,
            forceSelection: true,
            filterPickList: true,
            encodeSubmitValue: true,
            emptyText: 'Select Regions',
            growMax: 100,
            queryMode: 'local',
            valueField: 'id',
            displayField: 'name',
            listeners: {
                beforerender: {
                    fn: 'setCompStore',
                    config: {
                        pageSize: 10000,
                        proxy: {
                            
                            extraParams: {
                                table_name: 'par_regions',
                                filters: JSON.stringify({is_local:1})
                            }
                        }
                    },
                    isLoad: true
                },
            }
        },
        {
            xtype: 'tagfield',
            fieldLabel: 'Facilities',
            name: 'facility_id',
            valueField: 'id',
            columnWidth: 1,
            displayField: 'name',
            forceSelection: true,
            filterPickList: true,
            encodeSubmitValue: true,
            hidden:false,
            queryMode: 'local',
            growMax: 10,
            listeners: {
                beforerender: {
                    fn: 'setCompStore',
                    config: {
                        proxy: {
                            url:'enforcement/getApprovedPremises'
                        }
                    },
                    isLoad: true
                }, 
            }
            
        },
        {
            xtype: 'tagfield',
            fieldLabel: 'Inspectors',
            name: 'inspector_id',
            valueField: 'id',
            columnWidth: 1,
            displayField: 'name',
            forceSelection: true,
            filterPickList: true,
            encodeSubmitValue: true,
            hidden:false,
            queryMode: 'local',
            growMax: 10,
            listeners: {
                beforerender: {
                    fn: 'setCompStore',
                    config: {
                        proxy: {
                            url:'enforcement/getusers'
                        }
                    },
                    isLoad: true
                }, 
            }
            
        }, 
    ],
    buttons: [
        {
            text: 'Save Details',
            iconCls: 'x-fa fa-save',
            ui: 'soft-purple',
            handler: 'doCreateCommonParamWin',
            action_url: 'enforcement/saveMonitoringPlanRegions',
            table_name: 'mon_work_plan_regions',
            storeID: 'annualworkplanstr',
        }
    ]    
});