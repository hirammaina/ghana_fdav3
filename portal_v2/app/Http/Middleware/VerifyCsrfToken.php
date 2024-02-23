<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * Indicates whether the XSRF-TOKEN cookie should be set on the response.
     *
     * @var bool
     */
    protected $addHttpCookie = true;

    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'utilities/onPermitApplicationSubmit',
        'utilities/onAddUniformApplicantDataset',
        'authentication/onUserLogin',
        'administration/onUserLogOut',
        'tradermanagement/onAccountRegistration',
        'clinicaltrials/saveClinicalTrialApplication',
        'documentmanagement/resumableuploadApplicationDocumentFile',
        'clinicaltrials/saveCtrProgressReportingApplication',
        'clinicaltrials/saveCtrOtherReportingApplication',
        'clinicaltrials/saveCtrSaeReportingApplication',
        'importexportapp/onAddUniformApplicantDataset',
        'clinicaltrials/onSaveClinicalStudySite',
        'productregistration/onSaveProductApplication',
        'productregistration/onSaveProductOtherDetails',
        'productregistration/onSaveProductOtherActiveQiSDetails',
        'productregistration/onSaveProductGeneralQiSDetails',
        'productregistration/onSaveProductManufQiSDetails',
        'productregistration/onSaveProductCharacterQiSDetails',
        'productregistration/onSaveProductControlAPIQiSDetails',
        'productregistration/onSaveProductReferQiSDetails',
        'clinicaltrials/onsaveclinicaltInvestigatorDetails',
        'clinicaltrials/onsaveclinicaltMonitorDetails',
        'clinicaltrials/saveiMPProductDetailsDetails',
        'clinicaltrials/saveComparatorProductDetailsDetails',
        'importexportapp/saveDisposalApplication',
        'importexportapp/saveDisposalPermitProductdetails',
        'documentmanagement/onunfitProductsUpload',
        'tradermanagement/onUpdateTraderAccountDetails',
        'utilities/onCustomerAccountRegistrationSubmission',
        'tradermanagement/onPharmacisAccountUsersRegistration',
        'premisesregistration/onSavePremisesApplication',
        'premisesregistration/onSavePremisesOtherDetails',
        'premisesregistration/onSaveDrugShopApplication',
        'premisesregistration/onSavePremisesStoreLocationDetails',
        'premisesregistration/onSavePremisesDirectors',
        'premisesregistration/onSavePremisesDirectorsDetails',
        'premisesregistration/onDeletePremisesDetails',
        'premisesregistration/onSavePremisesholder',
        'premisesregistration/onSavePremisesPersonnel',
        'importexportapp/onDeletePermitdetails',
        'premisesregistration/onSaveTelephoneDetails',
        'premisesregistration/onSaveRenPremisesApplication',
        'authentication/onFuncChangePassword',
        'utilities/saveManufacturerSiteFulldetails',
        'gmpinspection/onSaveGmpApplication',
        'gmpinspection/onSaveGmpContractDetails',
        'productregistration/onAddManufacturingSite',
        'utilities/onsaveClinicalVariationsrequests',
        'tradermanagement/onAccountUsersRegistration',
        'clinicaltrials/onSaveClinicalPersonnel',
        'clinicaltrials/onsavePatientInformation',
        'clinicaltrials/saveiMPHandlingProductDetailsDetails',
        'gmpinspection/onSavemanufatcuringSiteBlocks',
        'gmpinspection/onSaveGmpProductLinedetails',
        'gmpinspection/onSaveGmpSurgicalProductlineDetails',
        'gmpinspection/onSaveIntededmanufatcuringActivity',
        'gmpinspection/onSavePremisesPersonnel',
        'gmpinspection/onDeletePremisesDetails',
        'clinicaltrials/onSaveClincialTrialDescriptionApplication',
        'clinicaltrials/onSaveClincialTrialEthicsApplication',
        'clinicaltrials/onSaveMonitoringApplication',
        'clinicaltrials/onSaveClincialTrialOthersApplication',
        'clinicaltrials/onSaveClincialTrialParticipantsApplication',
        'clinicaltrials/onsaveSummaryActivity',
        'clinicaltrials/onsaveRegulatorystudyLapse',
        'clinicaltrials/onsaveDeviationReport',
        'clinicaltrials/onsaveInspectionReport',
        'clinicaltrials/onSaveClincialTrialHistoryApplication',
        'clinicaltrials/onSaveClincialTrialSummaryApplication',
        'clinicaltrials/onSaveClincialTrialProgressReportApplication',
        'clinicaltrials/onSaveNonClincialTrialToxicology',
        'clinicaltrials/onsaveToxicityDosage',
        'clinicaltrials/onSaveNonClincialTrialPharmacology',
        'clinicaltrials/onSaveClincialTrialMeasures',
        'premisesregistration/onSaveDrugShopStoreLocationDetails',
        'premisesregistration/onSaveApprovalRecomDetails',
        'clinicaltrials/saveAltClinicalTrialApplication',
        'promotionadverts/savePromotionalAdvertapplication',
        'promotionadverts/OnSavePromotionalProductParticulars',
        'utilities/onsaveProductConfigData',
        'productregistration/onSaveGroupedApplicationdetails',
        "utilities/onGroupedApplicationInvoiceGeneration",
        "gmpinspection/onSaveGmpOtherDetails",
        "utilities/onApplicationInvoiceGeneration",
        "utilities/onSavesampleDetails",
        "utilities/onSaveinitCAPAresponses"
    ];
}
