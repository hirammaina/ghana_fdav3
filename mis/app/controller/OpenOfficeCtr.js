Ext.define('Admin.controller.OpenOfficeCtr', {
    extend: 'Ext.app.Controller',
    stores: [
        'Admin.store.frontOffice.SpreadSheetApplicationTypesStr',
        'Admin.store.frontOffice.SpreadSheetRegulatedProdTypesStr',
        'Admin.store.frontOffice.product.SpreadSheetProductApplicationColumnsStr',
        'Admin.store.frontOffice.product.SpreadSheetApplicationColumnsStr',
        'Admin.store.frontOffice.product.SpreadSheetManInfoStr',
        'Admin.store.frontOffice.product.SpreadSheetProductNutrientsStr',
        'Admin.store.frontOffice.product.SpreadSheetProductPackagingStr',
        'Admin.store.frontOffice.product.SpreadSheetProductIngridientStr',
        'Admin.store.frontOffice.product.SpreadSheetProductInspectionStr',
        'Admin.store.frontOffice.product.SpreadSheetProductSampleInfoStr',
        'Admin.store.frontOffice.premise.SpreadSheetPremiseApplicationColumnsStr',
        'Admin.store.frontOffice.premise.SpreadSheetPremiseBsnInfoStr',
        'Admin.store.frontOffice.premise.SpreadSheetPremisePersonnelInfoStr',
        'Admin.store.frontOffice.gmp.SpreadSheetGmpApplicationColumnsStr',
        'Admin.store.frontOffice.gmp.SpreadSheetFacilityLocationStr',
        'Admin.store.frontOffice.gmp.SpreadSheetGmpManBlockStr',
        'Admin.store.frontOffice.gmp.SpreadSheetGmpManLineStr',
        'Admin.store.frontOffice.gmp.SpreadSheetGmpManSiteStr',
        'Admin.store.frontOffice.gmp.SpreadSheetGmpBsnTypeDetailsStr',
        'Admin.store.frontOffice.importexport.SpreadSheetIEApplicationColumnsStr',
        'Admin.store.frontOffice.clinicaltrial.SpreadSheetClinicalTrialApplicationColumnsStr',
        'Admin.store.frontOffice.clinicaltrial.ClinicalTrialStudySiteStr',
        'Admin.store.frontOffice.clinicaltrial.ClinicalTrialInvestigatorsStr',
        'Admin.store.frontOffice.clinicaltrial.ClinicalTrialIMPProductsStr', 
        'Admin.store.frontOffice.importexport.SpreadSheetIEProductStr',
        'Admin.store.frontOffice.importexport.SpreadSheetIEApplicationSectionsStr',
        'Admin.store.frontOffice.product.ProductImageViewStr',
        'Admin.store.frontOffice.productnotification.SpreadSheetProductNoteColumnsStr',
        'Admin.store.frontOffice.promadvert.SpreadSheetPromAdvertColumnsStr',
        'Admin.store.frontOffice.promadvert.SpreadSheetPromotionMaterialProductssStr',
        'Admin.store.frontOffice.promadvert.SpreadSheetPromotionMaterialDetailsStr',
        'Admin.store.frontOffice.enquiries.EnquiriesStr',
        'Admin.store.frontOffice.importexport.SpreadSheetIEPermitApplicationColumnsStr',
        'Admin.store.frontOffice.importexport.SpreadSheetIEPoeApplicationStr',
        'Admin.store.frontOffice.disposal.SpreadSheetDisposalApplicationColumnsStr',
        'Admin.store.frontOffice.product.MedicalDeviceSpreadSheetProductApplicationColumnsStr'
    

        
    ]
});