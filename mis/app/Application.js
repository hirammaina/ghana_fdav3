/**
 * The main application class. An instance of this class is created by app.js when it
 * calls Ext.application(). This is the ideal place to handle application launch and
 * initialization details.
 */
Ext.define("Admin.Application", {
  extend: "Ext.app.Application",

  name: "Admin",

  requires: ["Admin.view.plugins.Badge", "Admin.view.plugins.CKeditor"],
  stores: ["ConfirmationStr"],

  controllers: [
    "SharedUtilitiesCtr",
    "AdministrationCtr",
    "DashboardCtr",
    "UserManagementCtr",
    "ParametersCtr",
    "OrganisationConfigCtr",
    "WorkflowManagementCtr",
    "PremiseRegistrationCtr",
    "ProductRegistrationCtr",
    "ProductRecallAlertCtr",
    "ConfigurationsCtr",
    // 'premiseregistration.DrugsPremiseCtr',
    // 'premiseregistration.FoodPremiseCtr',
    // 'premiseregistration.CosmeticsPremiseCtr',
    // 'premiseregistration.MedDevicesPremiseCtr',
    "GmpApplicationsCtr",
    "ClinicalTrialCtr",
    "ReportsCtr",
    "SurveillanceCtr",
    "ImportExportpermitsCtr",
    "ProductNotificationsCtr",
    "PromoAndAdvertMaterialsController",
    "ProfileCtr",
    "OpenOfficeCtr",
    "OnlineServicesCtr",
    "RevenueManagementCtr",
    "SystemAdministrationProcessCtr",
    "DocumentContolManCtr",
    "PvCtr",
    "EnforcementCtr",
  ],
  defaultToken: "dashboard",

  // The name of the initial view to create. This class will gain a "viewport" plugin
  // if it does not extend Ext.Viewport.
  //
  //mainView: 'Admin.view.main.Main',

  launch: function () {
    if (Ext.manifest) {
      var environment = Ext.manifest.profile;

      if (environment === "production") {
        //If user is logged in open 'app-main' else open 'login'
        Ext.create({
          xtype: is_logged_in
            ? "main-app"
            : is_reset_pwd
            ? "resetpwdscreen"
            : "login",
        });
        if (is_logged_in) {
          checkUserSessionValidity(800000);
          setupTimers();
          var usersstr = Ext.getStore("usersstr"),
            gmpproductlinestatusstr = Ext.getStore("gmpproductlinestatusstr"),
            confirmationstr = Ext.getStore("confirmationstr"),
            navigationstr = Ext.getStore("navigationstr");

          usersstr.load();
          gmpproductlinestatusstr.load();
          navigationstr.load();
          confirmationstr.load();
        }
      } else {
        Ext.create({
          xtype: is_logged_in
            ? "main-app"
            : is_reset_pwd
            ? "resetpwdscreen"
            : "login",
        });
        if (is_logged_in) {
          // checkUserSessionValidity(800000);
          // setupTimers();
          var usersstr = Ext.getStore("usersstr"),
            gmpproductlinestatusstr = Ext.getStore("gmpproductlinestatusstr"),
            confirmationstr = Ext.getStore("confirmationstr"),
            navigationstr = Ext.getStore("navigationstr");

          usersstr.load();
          gmpproductlinestatusstr.load();
          navigationstr.load();
          confirmationstr.load();
        }
      }
    } else {
      // Fallback in case manifest is not available
      console.warn(
        "Ext.manifest is not available. Unable to determine environment."
      );
    }
  },

  onAppUpdate: function () {
    Ext.Msg.confirm(
      "Application Update",
      "This application has an update, reload?",
      function (choice) {
        if (choice === "yes") {
          window.location.reload();
        }
      }
    );
  },
});
