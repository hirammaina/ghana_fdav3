Ext.define('Admin.view.promotionmaterials.views.maininterfaces.panels.PromotionAndAdvertsManagerReviewPanel', {
    extend:'Admin.view.promotionmaterials.views.maininterfaces.common.PromoAndAdvertWorkflowStagesInterfaceParent', 
    title: 'Pending Applications',
    viewModel: 'promotionmaterialsviewmodel',
    xtype: 'promotionandadvertsmanagerreviewpanel',
    layout: 'fit',
    items: [
        {
            xtype: 'promotionmaterialsmanagerreviewgrid'
        }
    ]
});