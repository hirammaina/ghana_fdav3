/**
 * Created by Softclans
 */
Ext.define('Admin.view.Enforcement.views.grids.MonitoringCompliance.DebriefMeetingParticipantsGrids', {
    extend: 'Ext.grid.Panel',
    xtype: 'debriefmeetingparticipantsgrid',
    controller: 'commoninterfacesVctr',
    selModel: {
        selType: 'checkboxmodel',
        mode: 'MULTI',
        allowDeselect: true
    },
    frame: true,
    height: 500,
    listeners: {
        beforerender: {
            fn: 'setGridStore',
            config: {
                pageSize: 10000,
                storeId: 'parmeetingparticipantsstr',
                proxy: {
                    url: 'usermanagement/getActiveSystemUsers'
                   /* extraParams: {
                        model_name: 'MeetingParticipant'
                    }*/
                }
            },
            isLoad: true
        },
        select: function (sel, record, index, eOpts) {
            var grid = sel.view.grid,
                selCount = grid.getSelectionModel().getCount();
            if (selCount > 0) {
                grid.down('button[name=save_selected]').setDisabled(false);
            }
        },
        deselect: function (sel, record, index, eOpts) {
            var grid = sel.view.grid,
                selCount = grid.getSelectionModel().getCount();
            if (selCount < 1) {
                grid.down('button[name=save_selected]').setDisabled(true);
            }
        }
    },
    tbar: [
        {
            xtype: 'button',
            text: 'External Participant',
            ui: 'soft-blue',
            iconCls: 'x-fa fa-plus',
            handler: 'showAddTcMeetingExternalParticipant',
            childXtype: 'debriefmeetingparticipantsfrm',
            winTitle: 'Meeting Participant',
            winWidth: '50%',
            stores: '[]'
        },
        {
            xtype: 'button',
            text: 'Save Selected',
            ui: 'soft-blue',
            iconCls: 'x-fa fa-save',
            disabled: true,
            name: 'save_selected'
        },
        {
            xtype: 'hiddenfield',
            name: 'meeting_id'
        }
    ],
    bbar: [
        {
            xtype: 'pagingtoolbar',
            width: '100%',
            displayInfo: true
        }
    ],
	features: [{
        ftype: 'searching',
        minChars: 2,
        mode: 'local'
    }],
    columns: [
        {
            text: 'Participant Name',
            dataIndex: 'fullnames',
            flex: 1
        },
        {
            text: 'Participant Contact',
            dataIndex: 'phone',
            flex: 1
        },
        {
            text: 'Attendance',
            dataIndex: 'has_attended',
            // hidden: true,
            width: 100,
            editor: {
                xtype: 'checkbox',
                // checked: true,
                inputValue: '1',
                name: 'has_attended'

            },
            renderer: function (value, metaData) {
                if (value == 1) {
                    metaData.tdStyle = 'color:white;background-color:green';
                    return "Attended";
                }else {
                    metaData.tdStyle = 'color:white;background-color:grey';
                    return "Missed";
                }
            }
        },
        {
            text: 'Options',
            xtype: 'widgetcolumn',
            width: 90,
            widget: {
                width: 75,
                textAlign: 'left',
                xtype: 'splitbutton',
                iconCls: 'x-fa fa-th-list',
                ui: 'gray',
                menu: {
                    xtype: 'menu',
                    items: [{
                            text: 'Delete',
                            iconCls: 'x-fa fa-trash',
                            tooltip: 'Delete Record',
                            table_name: 'tc_meeting_participants',
                            // bind: {
                            //     hidden: '{isReadOnly}'
                            // },
                            storeID: 'tcmeetingparticipantsstr',
                            action_url: 'configurations/deleteConfigRecord',
                            action: 'actual_delete',
                            handler: 'doDeleteCommonParamWidgetParam',
                            // hidden: Admin.global.GlobalVars.checkForProcessVisibility('actual_delete')
                        }
                    ]
                }
            }, onWidgetAttach: function (col, widget, rec) {
                var grid =widget.up('grid'),
                    is_meeting = grid.is_meeting;
                if (is_meeting === 1 || is_meeting == 1) {
                    widget.setVisible(false);;
                } 
                else {
                    widget.setVisible(true);
                }
            }
        }
    ]
});