var config = new CloudOauthConfig(); // Cloud PKI configuration
config.setHost("https://cloud.govca.rw/cloud-service");
config.setClientId("Rwanda_FDA_Test");
config.setRedirectUri("https://irims.rwandafda.gov.rw/training/digital_sign/callback.html");
config.setScope("read");
//https://irims.rwandafda.gov.rw/training/digital_sign/callback.html
// get signature value in PDF from CloudPKI
function getP7DetachedMessageFromCloud() {
	var cloudoauth = new CloudOauth(config);

	var customCallback = function(result) {
		$("#textarea-p7-detached-message").val(result.CP400.p7SignHex);
		$("#textarea-certificate").val(result.CP400.certHex);
	
	}
	//var plainText = $('#text-pdf-hash').val();
	var plainText = "129F36F1869E6C1365E7A91B3EAEABC1C5144567F6DDCA09084DA48FF10D7B64";
		cloudoauth
		.setApiCodes("CP400,CP300")
		.setPlainText(plainText, true)		
		.call(customCallback);
}


// get VID for registration
function vidCheckAction() {
	var cloudoauth = new CloudOauth(config);
	var customCallback = function(result) {;
		//$("#vid").val(result.CP300.vidRandomValue);
		$("#dn").val(result.dn);
	}
	//var idn = $('#idn').val();	
	var idn = "1191280000207095";
	cloudoauth
		.setApiCodes("CP300")
		.setIdn(idn)
		.call(customCallback);
}


