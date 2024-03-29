Ext.define('Admin.view.summaryreport.revenue.view.panel.RevenueSummaryModulePnl', {
	extend: 'Ext.panel.Panel',
	xtype: 'revenueSummaryModulePnl',
	margin: 2,
	layout: 'fit',
   
    dockedItems: [
        {
            xtype: 'toolbar',
            dock: 'top',layout:'fit',
            items:[{
                xtype: 'revenueFilterFrm',

            }],
        }
    ],
   items: [{
                xtype: 'moduleReportRepresentationViewFrm'
              }
            ],
   bbar: [{
          xtype: 'toolbar',
          width: '100%',
          ui: 'footer',
          items: [
           {
              xtype:'externalExportBtn',
              text: 'Export(Grid Summary)',
              containerName: 'form',
              gridName: 'gridpanel' 
              
          },{
            text: 'Print Revenue Summary Report',
            handler: 'funcPrintRevenueSummaryReport'
        },
          '->',
          {
                      xtype: 'combo',
                      emptyText: 'Revenue Types',
                      width: 250,
                      forceSelection: true,
                      queryMode: 'local',
                      valueField: 'id',
                      labelAlign : 'top',
                      displayField: 'name',
                      name: 'revenue_types',
                      allowBlank: false,
                      fieldStyle: {
                          'color': 'green',
                          'font-weight': 'bold'
                      },
                     listeners: {
                          beforerender: {
                              fn: 'setOrgConfigCombosStore',
                              config: {
                                  pageSize: 100,
                                  proxy: {
                                  url: 'configurations/getConfigParamFromTable',
                                  extraParams: {
                                      table_name: 'par_payment_types'
                                  }
                                 }
                              },
                              isLoad: true
                          }
                      }
            },
          {
              text: 'Print Detailed Report',
              handler: 'func_ExpWinShow'
              
          },{
              text: 'Export Detailed Reports',
              handler: 'func_exportRevenueReport',
              xFileName: 'ProductRevenueSummaryReport',
              xPrintFunc: 'getProductsRevenue'
          }
      ]
       }],

      });

