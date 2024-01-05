exports.iremboMakePayment=function(publicKey,invoiceNumber,spinner) {

  IremboPay.initiate({
      publicKey:  publicKey,
      invoiceNumber: invoiceNumber,
      locale: IremboPay.locale.EN,
      isTest: false, //set this value for productionfalse
      callback:(err, resp) => {
        spinner.hide();
          if (!err){
                IremboPay.closeModal();
                spinner.hide();
          }else{
               // Perform actions on error
              var code = err.code,
                  message = err.message;
                  spinner.hide();

          }
      }
  });
}