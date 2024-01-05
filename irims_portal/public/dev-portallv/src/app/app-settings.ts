var app_connection = 'production';
if(window.location.hostname =='localhost'){
  //var app_connection = 'development';
}
if(app_connection == 'development'){

  var s2bpayjs = 'https://test-s2bpay.sc.com/s2bpaysit/resources/merchant/js/s2bpay.js';

  var base_url = 'http://localhost:84/irims_systems/rwanda_fdamis/rwnda_fdamis/demostration/portal/'
 var  assets_url = 'http://localhost:84/irims_systems/rwanda_fdamis/rwnda_fdamis/demostration/portal/public/resources/';
  var mis_url = 'localhost:84/irims_systems/rwanda_fdamis/rwnda_fdamis/demostration/mis/';
  var siteKey = '6LcoH54UAAAAAOqpAGCXC4cmup6N2c5KseVHmv1c';
}
else if(app_connection == 'fixed_acess'){
    var base_url = ''
    var assets_url = '';
    var mis_url = '';
}
else if(app_connection == 'production'){
  var base_url = window.location.protocol+'//'+window.location.hostname+(window.location.port ? ':'+window.location.port: '')+window.location.pathname;
  var assets_url = window.location.protocol+'//'+window.location.hostname+(window.location.port ? ':'+window.location.port: '')+window.location.pathname+"public/resources/";
  var mis_url = window.location.protocol+'//'+window.location.hostname+(window.location.port ? ':'+window.location.port: '')+'/mis/';
}

export class AppSettings {

  //* on production mode localhost:4200
  public static irimshelpdesk_url = 'https://irims.rwandafda.gov.rw/irimshelpdesk/public/';
  public static base_url = base_url;
  public static assets_url = assets_url;
  public static mis_url = mis_url;
  public static siteKey= "6LdIjbsUAAAAAOhQtlHVuK8kpSdbBXAtX3K5pYQb";
  public static system_title ='Rwanda Food and Drugs Regulatory Information Management System';
  public static system_website = 'rwandafda.gov.rw/';
  public static system_version = 'iRIMS 2.92';
  public static session_timeoutcheck = 4800000;
  public static encryptSecretKey = 'kPJks1MrdXE03n8H';

}