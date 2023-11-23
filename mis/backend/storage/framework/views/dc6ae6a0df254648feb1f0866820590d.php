<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=10, user-scalable=yes">
    <meta charset="UTF-8">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>"/>

    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('resources/css/toastr.css')); ?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('resources/css/style.css')); ?>"/>
    <link href="node_modules/froala-editor/css/froala_editor.pkgd.min.css" rel="stylesheet" type="text/css" />

    <title>iRIMS System Ver 2.0</title>
</head>
<body>
<div id="loading-mask" >
       
       <img class="center-loader" alt="Loading..."
         src="<?php echo $base_url; ?>/resources/images/loader1.gif"/>
         <hr>
        
        
</div>
<!--<div id="loading-parent">
    <div id="loading-child" class="loading-indicator">
      <center>
            <img height="150" class="center-img" alt="Spinner"
                 src="<?php echo $base_url; ?>/resources/images/loader.gif"/>
      </center>
    </div>
</div>-->
<script type="text/javascript">

    var token = document.querySelector('meta[name="csrf-token"]').content;
    var backendVersion = '<?php echo e(App::VERSION()); ?>';
    var is_logged_in = '<?php echo $is_logged_in; ?>';
    var is_reset_pwd = '<?php echo $is_reset_pwd; ?>';
    var guid = '<?php echo $guid; ?>';
    var user_id = '<?php echo $user_id; ?>';
    var title_id = '<?php echo $title_id; ?>';
    var gender_id = '<?php echo $gender_id; ?>';
    var profile_pic_url = '<?php echo $profile_pic_url; ?>';
    var first_name = '<?php echo $first_name; ?>';
    var last_name = '<?php echo $last_name; ?>';
    var fullnames = '<?php echo $title . ' ' . $first_name . ' ' . $last_name; ?>';
    var base_url = '<?php echo $base_url; ?>';
    var upload_directory =  '<?php echo $upload_directory; ?>';
    var user_role_description = '<?php echo $access_point . ' - ' . $role; ?>';
    var email_address = '<?php echo $email; ?>';
    var phone_number = '<?php echo $phone; ?>';
    var mobile_number = '<?php echo $mobile; ?>';
    var dms_url = '<?php echo $base_url . '/mis_dms/'; ?>';

    var system_name = '<?php echo $system_name; ?>';
    var organisation_name = '<?php echo $organisation_name; ?>';
    var org_name = '<?php echo $org_name; ?>';
    var iso_cert = '<?php echo $iso_cert; ?>';
    var ministry_name = '<?php echo $ministry_name; ?>';
    var system_version = '<?php echo $system_version; ?>';
    var approval_lag_days = '<?php echo $approval_lag_days; ?>';
    var access_token = '<?php echo $access_token; ?>';
    var system_dashboard = '<?php echo $system_dashboard; ?>';
    var scheduledtcmeeting_counter = '<?php echo  $scheduledtcmeeting_counter; ?>';
    var notifications_duecounter = '<?php echo $notifications_duecounter; ?>';

    var notifications_mytaskscounter = '<?php echo $notifications_mytaskscounter; ?>';
     
    var notifications_mycompletedtaskscounter = '<?php echo 100; ?>';
    var lims_baseurl =  '<?php echo 'http://imis.tmda.go.tz/lims_test/index.php/'; ?>'; 

    
    var nonMenusArray = JSON.parse('<?php echo json_encode($nonMenusArray); ?>');

    var Ext = Ext || {}; // Ext namespace won't be defined yet...

    Ext.beforeLoad = function (tags) {
        var s = location.search,  // the query string (ex "?foo=1&bar")
            profile;

        // For testing look for "?classic" or "?modern" in the URL to override
        // device detection default.
        //
        if (s.match(/\bclassic\b/)) {
            
        } else if (s.match(/\bmodern\b/)) {
            profile = 'modern';
        }
        
        else {
            profile = tags.phone ? 'modern' : 'classic';
        }
profile = 'classic';
        Ext.manifest = profile; // this name must match a build profile name

        
    };
</script>

<script type="text/javascript" src="<?php echo e(asset('resources/js/jquery-3.1.1.js')); ?>"></script>

<script type="text/javascript" src="<?php echo e(asset('resources/js/resumable.min.js')); ?>"></script>

<script type="text/javascript" src="<?php echo e(asset('resources/js/toastr.js')); ?>"></script>
<!-- The line below must be kept intact for Sencha Cmd to build your application -->

<script type="text/javascript" src="<?php echo e(asset('resources/statics/vendor/jquery-3.4.1/jquery.min.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('resources/statics/vendor/bootstrap-4.4.1/js/bootstrap.min.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('resources/statics/vendor/bootstrap-toggle/js/bootstrap-toggle.min.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('resources/statics/oauth/cloudpki.api.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('resources/statics/oauth/cloudpki.ui.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('resources/statics/js/ui-events.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('resources/statics/assets/form-to-json.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('resources/statics/assets/script.js')); ?>"></script>

<script id="microloader" type="text/javascript" src="<?php echo e(asset('bootstrap.js')); ?>"></script>
<!--floara-->
<script type="text/javascript" src="node_modules/froala-editor/js/froala_editor.pkgd.min.js"></script>
</body>
</html>
<?php /**PATH C:\xampp8.1\htdocs\fda\Admin\backend\resources\views/init.blade.php ENDPATH**/ ?>