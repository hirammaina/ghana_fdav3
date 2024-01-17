/**
 * @Author: Job.Murumba
 * @Date:   2024-01-10 10:38:23
 * @Last Modified by:   Job.Murumba
 * @Last Modified time: 2024-01-10 14:32:47
 */
/**
 * This class provides the modal Ext.Window support for all Authentication forms.
 * It's layout is structured to center any Authentication dialog within it's center,
 * and provides a backGround image during such operations.
 */
Ext.define('Admin.view.authentication.LockingWindow', {
    extend: 'Ext.window.Window',
    xtype: 'lockingwindow',
    requires: [
        'Admin.view.authentication.AuthenticationController',
        'Ext.layout.container.VBox'
    ],

    closable: false,
    resizable: false,
    autoShow: true,
    titleAlign: 'center',
    maximized: true,
    modal: true,

    layout: {
        type: 'vbox',
        align: 'right',
        pack: 'center'
    },

    controller: 'authentication'
});
