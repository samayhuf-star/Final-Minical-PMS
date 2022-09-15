<div class="modal fade" id="display-errors">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="display-cc-details" data-backdrop="static" 
   data-keyboard="false" style="z-index: 9999;"
   >
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="companyDateFormat" value="<?php echo isset($this->company_date_format) ? $this->company_date_format : ''; ?>">

<?php
   $whitelabelinfo = $this->session->userdata('white_label_information');
?>

<div class="footer hidden-print col-md-12">
    <?php
   
     $time = time() ;
     $year= date("Y",$time);
     echo isset($whitelabelinfo['name']) && $whitelabelinfo['name'] ? $whitelabelinfo['name']." &copy; " . $year ."  " : "Minical Inc."." &copy; " . $year ."  ";
    ?>

    <?php  //if(check_active_extensions('terms_and_privacy', $this->company_id)){
     if(isset($whitelabelinfo['terms_of_service']) && $whitelabelinfo['terms_of_service']) { ?>
        <a href="<?php echo $whitelabelinfo['terms_of_service']; ?>" target="_blank"><?php echo l('Terms Of Service', true); ?></a>
    <?php } else { ?>
        <a href="<?php echo base_url();?>auth/show_terms_of_service" target="_blank"><?php echo l('Terms Of Service', true); ?></a>
    <?php } ?>
    <?php echo " and " ?>

    <?php if(isset($whitelabelinfo['privacy_policy']) && $whitelabelinfo['privacy_policy']) { ?>
        <a href="<?php echo $whitelabelinfo['privacy_policy']; ?>" target="_blank"><?php echo l('Privacy Policy', true); ?></a>
    <?php } else { ?>
        <a href="<?php echo base_url();?>auth/show_privacy_policy" target="_blank"><?php echo l('Privacy Policy', true); ?></a>
    <?php } //}?>
</div>

<input type="hidden" id="currentCompanyId" value="<?php echo $this->company_id; ?>"/>
<input type="hidden" id="companySubscriptionLevel" value="<?php echo isset($this->company_subscription_level) && $this->company_subscription_level ? $this->company_subscription_level : 0; ?>"/>
<input type="hidden" id="companySubscriptionState" value="<?php echo isset($this->company_subscription_state) && $this->company_subscription_state ? $this->company_subscription_state : 0; ?>">
<input type="hidden" id="companyFeatureLimit" value="<?php echo isset($this->company_feature_limit) && $this->company_feature_limit ? $this->company_feature_limit : ''; ?>">
<input type="hidden" id="RoomSingular" value="<?php echo isset($this->default_room_singular) && $this->default_room_singular ? $this->default_room_singular : ''; ?>">
<input type="hidden" id="RoomPlular" value="<?php echo isset($this->default_room_plural) && $this->default_room_plural ? $this->default_room_plural : ''; ?>">
<input type="hidden" id="RoomType" value="<?php echo isset($this->default_room_type) && $this->default_room_type ? $this->default_room_type : ''; ?>">
<input type="hidden" id="CheckInTime" value="<?php echo isset($this->default_checkin_time) && $this->default_checkin_time ? $this->default_checkin_time : ''; ?>">
<input type="hidden" id="CheckOutTime" value="<?php echo isset($this->default_checkout_time) && $this->default_checkout_time ? $this->default_checkout_time : ''; ?>">


<script type="text/javascript">
    var is_current_user_admin = "<?php echo $this->session->userdata('is_current_user_admin'); ?>" ;
    var is_current_user_superadmin = "<?php echo $this->user_id === SUPER_ADMIN_USER_ID; ?>" ;
    var is_current_user_activated = "<?=$this->ci->session->userdata('status');?>";

    var innGrid = innGrid || {};
    innGrid.enableNewCalendar = parseInt('<?=(isset($this->enable_new_calendar) ? $this->enable_new_calendar : 1)?>');
    innGrid.enableHourlyBooking = parseInt('<?=(isset($this->enable_hourly_booking) ? $this->enable_hourly_booking : 0)?>');

    innGrid.featureSettings = innGrid.featureSettings || {};
    innGrid.featureSettings.allow_free_bookings = parseInt('<?=(isset($this->allow_free_bookings) ? $this->allow_free_bookings : 0)?>');
    innGrid.featureSettings.selectedPaymentGateway = '<?=(isset($this->selected_payment_gateway) ? $this->selected_payment_gateway : '')?>';
    innGrid.featureSettings.bookingCancelledWithBalance = parseInt('<?=(isset($this->booking_cancelled_with_balance) ? $this->booking_cancelled_with_balance : 0)?>');
    innGrid.isCCVisualizationEnabled = parseInt('<?=(($this->is_cc_visualization_enabled) ? 1 : 0)?>');
    innGrid.isAsaasPaymentEnabled = parseInt('<?=(($this->selected_payment_gateway == "asaas") ? 1 : 0)?>');
    innGrid.isChannePCIEnabled = parseInt('<?=(($this->is_channex_pci_enabled) ? 1 : 0)?>');
    innGrid.isPCIBookingEnabled = parseInt('<?=(($this->is_pci_booking_enabled) ? 1 : 0)?>');
    innGrid.restrictCvcNotMandatory = parseInt('<?=(($this->restrict_cvc_not_mandatory) ? 1 : 0)?>');
    innGrid.featureSettings.cuurentLanguage = "<?=$this->session->userdata('language') ? $this->session->userdata('language') : ''?>";
    innGrid.imageUrl = "<?= $this->image_url; ?>";
    innGrid.companyAPIKey = "<?=(isset($this->company_api_key) ? $this->company_api_key : '')?>";
    innGrid.companyID = "<?=(isset($this->company_id) ? $this->company_id : '')?>";

    innGrid.featureSettings.defaultRoomSingular = '<?=(isset($this->default_room_singular) ? $this->default_room_singular : '')?>';
    innGrid.featureSettings.defaultRoomPlural = '<?=(isset($this->default_room_plural) ? $this->default_room_plural : '')?>';
    innGrid.featureSettings.defaultRoomType = '<?=(isset($this->default_room_type) ? $this->default_room_type : '')?>';

    // subscription plans
    var STARTER = "<?php echo STARTER; ?>";
    var BASIC   = "<?php echo BASIC; ?>";
    var PREMIUM = "<?php echo PREMIUM; ?>";
    var ELITE   = "<?php echo ELITE; ?>";
</script>
<script>
    var language = JSON.parse('<?=addslashes(json_encode($this->lang->language));?>');
    
    function lang(key){
        return language[key] || (language[key.toString().toLowerCase()] || '');
    }
</script>

<script>
    <!-- Below script used for language translation  -->
    <?php
        // $l = addslashes(json_encode($this->session->userdata('translation_data')));
        $l = addslashes(json_encode(isset($this->all_translations_data) ? $this->all_translations_data : array()));
        //$l = addslashes(json_encode($this->all_translations));
    ?>
    <!-- Create global variable for language phrases array -->
    var language_phrases = JSON.parse('<?php echo $l ? $l : "[]"; ?>');
    var nonTranslatedKeys = new Array();
    <!-- Below function return a value of phrase key -->


    function l(phrase_key)
    {
        <?php if($this->user_id === SUPER_ADMIN_USER_ID) { ?>
        if (language_phrases[phrase_key] === undefined) {
            nonTranslatedKeys.push(phrase_key);
        }
        <?php } ?>

        return language_phrases[phrase_key] || (language_phrases[phrase_key.toString().toLowerCase()] || phrase_key);
    }

    // add non-translated-keys to DB 
    <?php if($this->user_id === SUPER_ADMIN_USER_ID) { ?>
    setInterval(function () {
        // console.log('nonTranslatedKeys', nonTranslatedKeys);
        if (nonTranslatedKeys.length > 0){

            $.ajax({
                type: "POST",
                url: getBaseURL() + 'language_translation/insert_non_translated_keys',
                data: { non_translated_keys: nonTranslatedKeys},
                dataType: "json",
                success: function( data ) {
                    console.log('data', data);
                }
            });

            nonTranslatedKeys = [];
        }
    }, 10000);
    <?php } ?>

</script>

<script>
    
    // js file versions, loading from js files
    var js_version = {};
    js_version['defaults'] = '<?= base_url() . auto_version('js/calendar/defaults.js');?>';
    js_version['main'] = '<?= base_url() . auto_version('js/calendar/main.js');?>';
    js_version['Calendar'] = '<?= base_url() . auto_version('js/calendar/Calendar.js');?>';
    js_version['Header'] = '<?= base_url() . auto_version('js/calendar/Header.js');?>';
    js_version['EventManager'] = '<?= base_url() . auto_version('js/calendar/EventManager.js');?>';
    js_version['RelativeView'] = '<?= base_url() . auto_version('js/calendar/basic/RelativeView.js');?>';
    js_version['BasicView'] = '<?= base_url() . auto_version('js/calendar/basic/BasicView.js');?>';
    js_version['MonthView'] = '<?= base_url() . auto_version('js/calendar/basic/MonthView.js');?>';
    js_version['CustomView'] = '<?= base_url() . auto_version('js/calendar/basic/CustomView.js');?>';
    js_version['BasicEventRenderer'] = '<?= base_url() . auto_version('js/calendar/basic/BasicEventRenderer.js');?>';
    js_version['View'] = '<?= base_url() . auto_version('js/calendar/common/View.js');?>';
    js_version['DayEventRenderer'] = '<?= base_url() . auto_version('js/calendar/common/DayEventRenderer.js');?>';
    js_version['SelectionManager'] = '<?= base_url() . auto_version('js/calendar/common/SelectionManager.js');?>';
    js_version['OverlayManager'] = '<?= base_url() . auto_version('js/calendar/common/OverlayManager.js');?>';
    js_version['CoordinateGrid'] = '<?= base_url() . auto_version('js/calendar/common/CoordinateGrid.js');?>';
    js_version['date'] = '<?= base_url() . auto_version('js/calendar/common/date.js');?>';
    js_version['HoverListener'] = '<?= base_url() . auto_version('js/calendar/common/HoverListener.js');?>';
    js_version['HorizontalPositionCache'] = '<?= base_url() . auto_version('js/calendar/common/HorizontalPositionCache.js');?>';
    js_version['util'] = '<?= base_url() . auto_version('js/calendar/common/util.js');?>';
    
    
    var css_version = {};
    css_version['main'] = '<?= base_url() . auto_version('js/calendar/main.css');?>';
    css_version['common'] = '<?= base_url() . auto_version('js/calendar/common/common.css');?>';
    css_version['basic'] = '<?= base_url() . auto_version('js/calendar/basic/basic.css');?>';
    css_version['custom'] = '<?= base_url() . auto_version('js/calendar/custom.css');?>';

    <!-- Below script used for store current session value  -->
    <?php $user_role = $this->session->userdata('user_role');
	$force_room_selection = isset($this->company_force_room_selection) ? $this->company_force_room_selection : 0; 
	?>
    <!-- Create global variable for user role -->
        var user_role = '<?php echo $user_role; ?>';
		
    var isShowUnassignedRooms = parseInt('<?=(isset($force_room_selection) ? $force_room_selection : 0)?>');

</script>
<!-- start Mixpanel --><script type="text/javascript">(function(e,b){if(!b.__SV){var a,f,i,g;window.mixpanel=b;b._i=[];b.init=function(a,e,d){function f(b,h){var a=h.split(".");2==a.length&&(b=b[a[0]],h=a[1]);b[h]=function(){b.push([h].concat(Array.prototype.slice.call(arguments,0)))}}var c=b;"undefined"!==typeof d?c=b[d]=[]:d="mixpanel";c.people=c.people||[];c.toString=function(b){var a="mixpanel";"mixpanel"!==d&&(a+="."+d);b||(a+=" (stub)");return a};c.people.toString=function(){return c.toString(1)+".people (stub)"};i="disable time_event track track_pageview track_links track_forms register register_once alias unregister identify name_tag set_config reset people.set people.set_once people.increment people.append people.union people.track_charge people.clear_charges people.delete_user".split(" ");
for(g=0;g<i.length;g++)f(c,i[g]);b._i.push([a,e,d])};b.__SV=1.2;a=e.createElement("script");a.type="text/javascript";a.async=!0;a.src="undefined"!==typeof MIXPANEL_CUSTOM_LIB_URL?MIXPANEL_CUSTOM_LIB_URL:"file:"===e.location.protocol&&"//cdn.mxpnl.com/libs/mixpanel-2-latest.min.js".match(/^\/\//)?"https://cdn.mxpnl.com/libs/mixpanel-2-latest.min.js":"//cdn.mxpnl.com/libs/mixpanel-2-latest.min.js";f=e.getElementsByTagName("script")[0];f.parentNode.insertBefore(a,f)}})(document,window.mixpanel||[]);
mixpanel.init("3bc910ef237696a92d7ca663bafa883c");</script><!-- end Mixpanel -->


<script type="text/javascript" src="<?php echo base_url();?>js/jquery-1.11.2.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/bootstrap.min.js"></script>
<?php if(end($this->uri->segments) == 'room_types'){ ?>
    <script type="text/javascript" src="<?php echo base_url();?>js/jquery-ui-1.12.1/jquery-ui.min.js"></script>
<?php } else {?>
    <script type="text/javascript" src="<?php echo base_url();?>js/jquery-ui.min.js"></script>
<?php } ?>
<script type="text/javascript" src="<?php echo base_url() . auto_version('js/helpers.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url() . auto_version('js/underscore-min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url() . auto_version('js/wizard.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url() . auto_version('js/language_translation.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url() . ('js/main.js?v=1');?>"></script>

<?php if (isset($js_files)) : foreach ($js_files as $path) : ?>
	<script type="text/javascript" src="<?php echo $path; ?>"></script>
<?php endforeach; ?>
<?php endif; ?>


<script type="text/javascript">
$(document).ready(function(){
    $('.multi-properties').on('click', function(){
        $('.main-sidebar').css({'z-index':'1'});
    });
});
</script>

<?php 
	echo "<script>
    COUNTRIES_OBJ = JSON.parse('".(COUNTRIES)."');
        </script>";
?>