<style>

@media print{
    #parature-wrapper { display: none!important; }
}
.starting-a-business #parature-wrapper, .managing-a-business #parature-wrapper{
    display: block;
}

#parature-wrapper{
    display: block;
    position: fixed;
    float: right;
    right: -40px;
    top:28px;
    z-index: 1000;
    background: #fff;
    width: 95px;
    height: 554px;
}

/* Parature bar with icons */

.parature-img-style{
    display:block;
    margin-top: 15px;
    margin-left: 15px;
}

.parature-div {
    height: 50px;
    width: 50px;
    background: #F3F3F3;
}

#parature-chat {
    position: fixed;
    top: 41px;
    right: 0px;
    z-index: 1000000;
}

#parature-ticket {
    position: fixed;
    top: 92px;
    right: 0px;
    z-index: 1000000;
}

#parature-feedback {
    position: fixed;
    top: 143px;
    right: 0px;
    z-index: 1000000;
}

#parature-fed {
    position: fixed;
    top: 194px;
    right: 0px;
    z-index: 1000000;
}

#parature-ss {
    position: fixed;
    top: 245px;
    right: 0px;
    z-index: 1000000;
}

#parature-chat span, #parature-ticket span, #parature-ss span, #parature-feedback span,
#parature-biz-usa-1 span,
#parature-biz-usa-2 span,
#parature-biz-usa-3 span,
#parature-biz-usa-4 span {
    color: #FFF;
    font: normal 14px 'Bitter-Regular', Times, serif;
    display: block;
    padding: 15px 0 0px 50px;
    letter-spacing: 0;
    white-space: nowrap;
    cursor: pointer;
}

#parature-more span{
    color: #165778;
    font: normal 14px 'Bitter-Regular', Times, serif;
    display: block;
    padding: 16px 0px 0px 50px;
    letter-spacing: 0;
    white-space: nowrap;
    cursor: pointer;
}

#parature-fed span {
    color: #FFF;
    font: normal 14px 'Bitter-Regular', Times, serif;
    display: block;
    padding: 15px 0 0px 50px;
    letter-spacing: 0;
    white-space: nowrap;
}

#parature-wrapper a:hover{
    text-decoration: none;
}
#parature_chat_min, #parature_ticket_min, #parature_ss_min, #parature_fed_min, #parature_feedback_min  {
    float: left;
}

div.confirm_parature ui-dialog-titlebar {
    background-color: blue;
}
.confirm_parature .ui-helper-clearfix {
    background: url('http://business.usa.gov/sites/all/themes/bizusa/images/nav_bg_new.png') repeat-x;
    color: white;
    margin: -3px;
    font-weight: normal;
    font-family: Georgia,serif;
}
.confirm_parature .ui-dialog-buttonset span.ui-button-text {
    background: #1b8bb3;
    text-transform: uppercase;
    color: white;
    border: none;
}

#parature-wrapper h3#parature-help{
    display: block;
    position: fixed;
    top: 60px;
    right: 5px;
    z-index: 1000000;
    *font-size: 12px;
    *top: 70px;
    *right:9px;
}

#parature-wrapper h3#parature-tools{
    display: block;
    position: fixed;
    top: 288px;
    right: 0px;
    z-index: 1000000;
    *font-size: 12px;
    *top:301px;
    *right:6px;
}

#biz-usa-logo{
    display: block;
    position: fixed;
    top: 38px;
    right: 0px;
    z-index: 1000000;
}

#parature-biz-usa-1{
    display: block;
    position: fixed;
    top: 321px;
    right: 0px;
    z-index: 1000000;
}

#parature-biz-usa-2{
    display: block;
    position: fixed;
    top: 372px;
    right: 0px;
    z-index: 1000000;
}

#parature-biz-usa-3{
    display: block;
    position: fixed;
    top: 423px;
    right: 0px;
    z-index: 1000000;
}

#parature-biz-usa-4{
    display: block;
    position: fixed;
    top: 474px;
    right: 0px;
    z-index: 1000000;
}

#parature-more{
    display: block;
    position: fixed;
    top: 527px;
    right: 0px;
    z-index: 1000000;
}

.parature-biz-usa-style {
    display: block;
    margin-left: 0px;
    margin-top: 0px;
    float: left;
}

/* end Parature bar with icons */

</style>
<div id="parature-wrapper">
    <style type="text/css">
        @media print {
            #parature-wrapper {display:none;}
        }
    </style>
    <div id="parature-biz-usa-logo">
        <a id="opener-logo" href="javascript:void(0);" title="Business USA">
            <img id="biz-usa-logo" src="http://business.usa.gov/sites/all/themes/bizusa/images/parature_business_usa.png" alt="Business USA" />
        </a>
    </div>
    <h3 id="parature-help">HELP</h3>
    <div id="parature-ticket" class="parature-div">
        <a id="opener-1" href="javascript:void(0);" title="Ask a Question">
            <img id="parature_ticket_min" src="http://business.usa.gov/sites/all/themes/bizusa/images/parature_icon_question.png" class="parature-img-style" alt="Ask a question">
            <span class="icon-text">Ask a Question</span>
        </a>
    </div>
    <div id="parature-feedback" class="parature-div">
        <a id="opener-2" href="javascript:void(0);" title="Give Feedback" alt="Give Feedback">
            <img id="parature_feedback_min" src="http://business.usa.gov/sites/all/themes/bizusa/images/parature_icon_feedback.png" class="parature-img-style" alt="Give feedback">
            <span class="icon-text">Give Feedback</span>
        </a>
    </div>
    <div id="parature-fed" class="parature-div">
        <a id="opener-3" href="javascript:void(0);" title="1-800-333-4636" alt="1-800-333-4636" style="cursor:text;">
            <img id="parature_fed_min" src="http://business.usa.gov/sites/all/themes/bizusa/images/parature_icon_contact.png" class="parature-img-style" alt="1-800-333-4636">
            <span class="icon-text">1-800-FED-INFO</span>
        </a>
    </div>
    <div id="parature-ss" class="parature-div">
        <a id="opener-4" href="javascript:void(0);" title="Browse Knowledgebase">
            <img id="parature_ss_min" src="http://business.usa.gov/sites/all/themes/bizusa/images/parature_icon_information.png" class="parature-img-style" alt="Browse knowledgebase">
            <span class="icon-text">Browse Knowledgebase</span>
        </a>
    </div>
    <h3 id="parature-tools">TOOLS</h3>
    <div id="parature-biz-usa-1" class="parature-div">
        <a id="opener-5" href="javascript:void(0);" title="Start a Business">
            <img id="parature_icon_blue" src="http://business.usa.gov/sites/all/themes/bizusa/images/parature_icon_blue_start_bus.jpg" class="parature-biz-usa-style" alt="Browse knowledgebase">
            <span class="icon-text">Start a Business</span>
        </a>
    </div>
    <div id="parature-biz-usa-2" class="parature-div">
        <a id="opener-6" href="javascript:void(0);" title="Learn About New Health Care Changes">
            <img id="parature_icon_blue" src="http://business.usa.gov/sites/all/themes/bizusa/images/parature_icon_green_healthcare.jpg" class="parature-biz-usa-style" alt="Browse knowledgebase">
            <span class="icon-text">Learn About New Health Care Changes</span>
        </a>
    </div>
    <div id="parature-biz-usa-3" class="parature-div">
        <a id="opener-7" href="javascript:void(0);" title="Find Opportunities">
            <img id="parature_icon_blue" src="http://business.usa.gov/sites/all/themes/bizusa/images/parature_icon_pink_opportunities.jpg" class="parature-biz-usa-style" alt="Browse knowledgebase">
            <span class="icon-text">Find Opportunities</span>
        </a>
    </div>
    <div id="parature-biz-usa-4" class="parature-div">
        <a id="opener-8" href="javascript:void(0);" title="Browse Resources for Veterans">
            <img id="parature_icon_blue" src="http://business.usa.gov/sites/all/themes/bizusa/images/parature_icon_purple_veterans.jpg" class="parature-biz-usa-style" alt="Browse knowledgebase">
            <span class="icon-text">Browse resource for Veterans</span>
        </a>
    </div>
    <div id="parature-more" class="parature-div">
        <a id="opener-9" href="/" title="Small Business Administration Tools">
            <img id="parature_icon_blue" src="http://business.usa.gov/sites/all/themes/bizusa/images/more.png" class="parature-biz-usa-style" alt="Small Business Administration Tools">
            <span class="icon-text">SBA Tools</span>
        </a>
    </div>
    <div id="dialog-modal-1" title="Redirecting to BusinessUSA.gov" style="display:none">
        <p>You are being redirected to BusinessUSA.gov – an SBA partner. Please click the OK button below to continue. Your browser will open in a new window.</p>
    </div>
    <div id="dialog-modal-2" title="Redirecting to BusinessUSA.gov" style="display:none">
        <p>You are being redirected to BusinessUSA.gov – an SBA partner. Please click the OK button below to continue. Your browser will open in a new window.</p>
    </div>
    <div id="dialog-modal-4" title="Redirecting to BusinessUSA.gov" style="display:none">
        <p>You are being redirected to BusinessUSA.gov – an SBA partner. Please click the OK button below to continue. Your browser will open in a new window.</p>
    </div>
    <div id="dialog-modal-5" title="Redirecting to BusinessUSA.gov" style="display:none">
        <p>You are being redirected to BusinessUSA.gov – an SBA partner. Please click the OK button below to continue. Your browser will open in a new window.</p>
    </div>
    <div id="dialog-modal-6" title="Redirecting to BusinessUSA.gov" style="display:none">
        <p>You are being redirected to BusinessUSA.gov – an SBA partner. Please click the OK button below to continue. Your browser will open in a new window.</p>
    </div>
    <div id="dialog-modal-7" title="Redirecting to BusinessUSA.gov" style="display:none">
        <p>You are being redirected to BusinessUSA.gov – an SBA partner. Please click the OK button below to continue. Your browser will open in a new window.</p>
    </div>
    <div id="dialog-modal-8" title="Redirecting to BusinessUSA.gov" style="display:none">
        <p>You are being redirected to BusinessUSA.gov – an SBA partner. Please click the OK button below to continue. Your browser will open in a new window.</p>
    </div>
    <div id="dialog-modal-logo" title="Redirecting to BusinessUSA.gov" style="display:none">
        <p>You are being redirected to BusinessUSA.gov – an SBA partner. Please click the OK button below to continue. Your browser will open in a new window.</p>
    </div>
</div>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" />
<script>
// Modal box
$( "#dialog-modal-1" ).dialog({
    height: 220,
    width: 320,
    modal: true,
    autoOpen: false,
    dialogClass: 'confirm_parature',
    buttons: {
        Ok: function() {
            window.open('http://help.business.usa.gov/ics/support/ticketnewwizard.asp?style=classic&deptID=30030&utm_source=parature&utm_medium=internal&utm_campaign=ask&source=SBA', '_blank');
            jQuery(this).dialog("close");
        }
    }
});

$( "#dialog-modal-2" ).dialog({
    height: 220,
    width: 320,
    modal: true,
    autoOpen: false,
    dialogClass: 'confirm_parature',
    buttons: {
        Ok: function() {
            window.open('http://help.businessusa.gov/ics/support/ticketnewwizard.asp?style=classic&feedback=true&utm_source=parature&utm_medium=internal&utm_campaign=feedback&source=SBA', '_blank');
            jQuery(this).dialog("close");
        }
    }
});
$( "#dialog-modal-4" ).dialog({
    height: 220,
    width: 320,
    modal: true,
    autoOpen: false,
    dialogClass: 'confirm_parature',
    buttons: {
        Ok: function() {
            window.open('http://help.business.usa.gov/ics/support/kbsplash.asp?deptID=30030&task=knowledge&utm_source=parature&utm_medium=internal&utm_campaign=knowledgbase&source=SBA', '_blank');
            jQuery(this).dialog("close");
        }
    }
});
$( "#dialog-modal-5" ).dialog({
    height: 220,
    width: 320,
    modal: true,
    autoOpen: false,
    dialogClass: 'confirm_parature',
    buttons: {
        Ok: function() {
            window.open('http://business.usa.gov/start-a-business?style=classic&deptID=30030&utm_source=parature&utm_medium=internal&utm_campaign=start&source=SBA', '_blank');
            jQuery(this).dialog("close");
        }
    }
});
$( "#dialog-modal-6" ).dialog({
    height: 220,
    width: 320,
    modal: true,
    autoOpen: false,
    dialogClass: 'confirm_parature',
    buttons: {
        Ok: function() {
            window.open('http://business.usa.gov/healthcare?style=classic&feedback=true&utm_source=parature&utm_medium=internal&utm_campaign=healthcare&source=SBA', '_blank');
            jQuery(this).dialog("close");
        }
    }
});
$( "#dialog-modal-7" ).dialog({
    height: 220,
    width: 320,
    modal: true,
    autoOpen: false,
    dialogClass: 'confirm_parature',
    buttons: {
        Ok: function() {
            window.open('http://business.usa.gov/find-opportunities?deptID=30030&task=knowledge&utm_source=parature&utm_medium=internal&utm_campaign=opportunities&source=SBA', '_blank');
            jQuery(this).dialog("close");
        }
    }
});
$( "#dialog-modal-8" ).dialog({
    height: 220,
    width: 320,
    modal: true,
    autoOpen: false,
    dialogClass: 'confirm_parature',
    buttons: {
        Ok: function() {
            window.open('http://business.usa.gov/veterans?style=classic&feedback=true&utm_source=parature&utm_medium=internal&utm_campaign=veterans&source=SBA', '_blank');
            jQuery(this).dialog("close");
        }
    }
});
$( "#dialog-modal-logo" ).dialog({
    height: 220,
    width: 320,
    modal: true,
    autoOpen: false,
    dialogClass: 'confirm_parature',
    buttons: {
        Ok: function() {
            window.open('http://business.usa.gov/?deptID=30030&task=knowledge&utm_source=parature&utm_medium=internal&utm_campaign=knowledgbase&source=SBA', '_blank');
            jQuery(this).dialog("close");
        }
    }
});

// Changed $ sign to jQuery to avoid conflicts from jQuery.
$( "#opener-logo" ).click(function() {
    jQuery( "#dialog-modal-logo" ).dialog( "open" ).show();
});
$( "#opener-1" ).click(function() {
    jQuery( "#dialog-modal-1" ).dialog( "open" ).show();
});
$( "#opener-2" ).click(function() {
    jQuery( "#dialog-modal-2" ).dialog( "open" ).show();
});
$( "#opener-4" ).click(function() {
    jQuery( "#dialog-modal-4" ).dialog( "open" ).show();
});
$( "#opener-5" ).click(function() {
    jQuery( "#dialog-modal-5" ).dialog( "open" ).show();
});
$( "#opener-6" ).click(function() {
    jQuery( "#dialog-modal-6" ).dialog( "open" ).show();
});
$( "#opener-7" ).click(function() {
    jQuery( "#dialog-modal-7" ).dialog( "open" ).show();
});
$( "#opener-8" ).click(function() {
    jQuery( "#dialog-modal-8" ).dialog( "open" ).show();
});
$( "#opener-logo" ).click(function() {
    jQuery( "#dialog-modal-logo" ).dialog( "open" ).show();
});
//chat window resize
function launchChatWindow(url) {
    var left=window.screen.width-430;
    var windowStyle='top=100,left='+left+',width=450,height=650,menubar=no,location=no,resizable=yes,toolbar=no,scrollbars=yes,status=no,';
    window.open(url,'',windowStyle);
}

//check for agent availability
var time = new Date().getMilliseconds();

function loadScript(url) {
    var script = document.createElement('script');
    script.setAttribute('src', url);
    document.getElementsByTagName('head')[0].appendChild(script);
}

function updateImage(image, id) {

    if( $(image).hasClass('available') ) {

        if(scroll_chat == 215 ) {
            $("#parature_chat_min").attr('src', 'http://business.usa.gov/sites/all/themes/bizusa/images/parature_chat_hover.png');
            jQuery("#parature-chat").css('background', '#46C0B2');
        }
        else {
            $("#parature_chat_min").attr('src', 'http://business.usa.gov/sites/all/themes/bizusa/images/parature_chat.png');
            jQuery("#parature-chat").css('background', '#f3f3f3');
        }
        jQuery("#parature-chat .icon-text").text("Chat with a Specialist");
        jQuery("#parature-chat span").css('color', '#fff');
        jQuery('#e0221f2a-9807-4b4b-9fac-9937209ee8b7').attr('title', 'Chat with a Specialist');

    }
    else{

        $("#parature_chat_min").attr('src', 'http://business.usa.gov/sites/all/themes/bizusa/images/parature_chat_offline.png');
        jQuery("#parature-chat").css('background', '#f3f3f3');
        jQuery("#parature-chat span").css('color', '#4d4d4d');
        jQuery("#parature-chat .icon-text").text("Chat Unavailable");
        jQuery("#e0221f2a-9807-4b4b-9fac-9937209ee8b7").attr('title', 'Chat Unavailable');
    }
}

//Time ensures that we don?t cache the script
loadScript(document.location.protocol + '//help.business.usa.gov/ics/csrchat/ChatButtonHttpModule.aspx?buttonId=e0221f2a-9807-4b4b-9fac-9937209ee8b7&clientId=30027&deptId=30030&time=' + time);

jQuery(document).ready(function() {

    $('.parature-div a').click(function () {
        window.location = $(this).attr('href');
    });

    //parature icons
    /*
     * Used jQuery instead of $ to avoid conflicts related to jQuery.
     */
    parature_chat = jQuery("#parature-chat");
    parature_ticket = jQuery("#parature-ticket");
    parature_ss = jQuery("#parature-ss");
    parature_feedback = jQuery("#parature-feedback");
    parature_fed = jQuery("#parature-fed");
    parature_biz_usa_1 = jQuery("#parature-biz-usa-1");
    parature_biz_usa_2 = jQuery("#parature-biz-usa-2");
    parature_biz_usa_3 = jQuery("#parature-biz-usa-3");
    parature_biz_usa_4 = jQuery("#parature-biz-usa-4");
    parature_more = jQuery("#parature-more");

    function scrollParatureIcon(scrollOut) {

        jQuery("#parature_ticket_min").attr('src', 'http://business.usa.gov/sites/all/themes/bizusa/images/parature_icon_question.png');
        jQuery("#parature_feedback_min").attr('src', 'http://business.usa.gov/sites/all/themes/bizusa/images/parature_icon_feedback.png');
        jQuery("#parature_fed_min").attr('src', 'http://business.usa.gov/sites/all/themes/bizusa/images/parature_icon_contact.png');
        jQuery("#parature_ss_min").attr('src', 'http://business.usa.gov/sites/all/themes/bizusa/images/parature_icon_information.png');
        jQuery("#parature-biz-usa-1").attr('src', 'http://business.usa.gov/sites/all/themes/bizusa/images/parature_icon_blue_start_bus.jpg');
        jQuery("#parature-biz-usa-2").attr('src', 'http://business.usa.gov/sites/all/themes/bizusa/images/parature_icon_green_healthcare.jpg');
        jQuery("#parature-biz-usa-3").attr('src', 'http://business.usa.gov/sites/all/themes/bizusa/images/parature_icon_pink_opportunities.jpg');
        jQuery("#parature-biz-usa-4").attr('src', 'http://business.usa.gov/sites/all/themes/bizusa/images/parature_icon_purple_veterans.jpg');
        jQuery("#parature-more").attr('src', 'http://business.usa.gov/sites/all/themes/bizusa/images/more.png');

        scroll_chat = 50;
        var scroll_ticket = 50;
        var scroll_ss = 50;
        var scroll_feedback = 50;
        var scroll_fed = 50;
        var scroll_biz_usa_1 = 50;
        var scroll_biz_usa_2 = 50;
        var scroll_biz_usa_3 = 50;
        var scroll_biz_usa_4 = 50;
        var scroll_more = 50;

        switch (scrollOut) {
            case 'parature_chat':
                scroll_chat = 150;
                jQuery("#parature_chat_min").addClass('parature-img-style');
                jQuery("#parature-chat .icon-text").show();
                loadScript(document.location.protocol + '//help.business.usa.gov/ics/csrchat/ChatButtonHttpModule.aspx?buttonId=e0221f2a-9807-4b4b-9fac-9937209ee8b7&clientId=30027&deptId=30030&time=' + time);
                break;
            case 'parature_ticket':
                scroll_ticket = 150;
                jQuery("#parature_ticket_min").attr('src', 'http://business.usa.gov/sites/all/themes/bizusa/images/parature_ticket_hover.png');
                jQuery("#parature_ticket_min").addClass('parature-img-style');
                jQuery("#parature-ticket").css('background', '#165778 ');
                jQuery("#parature-ticket .icon-text").show();
                break;
            case 'parature_ss':
                scroll_ss = 200;
                jQuery("#parature_ss_min").attr('src', 'http://business.usa.gov/sites/all/themes/bizusa/images/parature_ss_hover.png');
                jQuery("#parature_ss_min").addClass('parature-img-style');
                jQuery("#parature-ss").css('background', '#165778 ');
                jQuery("#parature-ss .icon-text").show();
                break;
            case 'parature_feedback':
                scroll_feedback = 150;
                jQuery("#parature_feedback_min").attr('src', 'http://business.usa.gov/sites/all/themes/bizusa/images/parature_feedback_hover.png');
                jQuery("#parature_feedback_min").addClass('parature-img-style');
                jQuery("#parature-feedback").css('background', '#165778 ');
                jQuery("#parature-feedback .icon-text").show();
                break;
            case 'parature_fed':
                scroll_fed = 170;
                jQuery("#parature_fed_min").attr('src', 'http://business.usa.gov/sites/all/themes/bizusa/images/parature_fedinfo_hover.png');
                jQuery("#parature_fed_min").addClass('parature-img-style');
                jQuery("#parature-fed").css('background', '#165778 ');
                jQuery("#parature-fed .icon-text").show();
                break;
            case 'parature_biz_usa_1':
                scroll_biz_usa_1 = 170;
                jQuery("#parature-biz-usa-1").attr('src', 'http://business.usa.gov/sites/all/themes/bizusa/images/parature_fedinfo_hover.png');
                jQuery("#parature-biz-usa-1").addClass('parature-biz-usa-style');
                jQuery("#parature-biz-usa-1").css('background', '#359bb7');
                jQuery("#parature-biz-usa-1 .icon-text").show();
                break;
            case 'parature_biz_usa_2':
                scroll_biz_usa_2 = 290;
                jQuery("#parature-biz-usa-2").attr('src', 'http://business.usa.gov/sites/all/themes/bizusa/images/parature_fedinfo_hover.png');
                jQuery("#parature-biz-usa-2").addClass('parature-biz-usa-style');
                jQuery("#parature-biz-usa-2").css('background', '#1fa385');
                jQuery("#parature-biz-usa-2 .icon-text").show();
                break;
            case 'parature_biz_usa_3':
                scroll_biz_usa_3 = 170;
                jQuery("#parature-biz-usa-3").attr('src', 'http://business.usa.gov/sites/all/themes/bizusa/images/parature_fedinfo_hover.png');
                jQuery("#parature-biz-usa-3").addClass('parature-biz-usa-style');
                jQuery("#parature-biz-usa-3").css('background', '#ff4863');
                jQuery("#parature-biz-usa-3 .icon-text").show();
                break;
            case 'parature_biz_usa_4':
                scroll_biz_usa_4 = 235;
                jQuery("#parature-biz-usa-4").attr('src', 'http://business.usa.gov/sites/all/themes/bizusa/images/parature_fedinfo_hover.png');
                jQuery("#parature-biz-usa-4").addClass('parature-biz-usa-style');
                jQuery("#parature-biz-usa-4").css('background', '#8859a5');
                jQuery("#parature-biz-usa-4 .icon-text").show();
                break;
            case 'parature_more':
                scroll_more = 120;
                jQuery("#parature-more").attr('src', 'http://business.usa.gov/sites/all/themes/bizusa/images/parature_fedinfo_hover.png');
                jQuery("#parature-more").addClass('parature-biz-usa-style');
                jQuery("#parature-more").css('background', '#f3f3f3');
                jQuery("#parature-more .icon-text").show();
                break;
            default:
                jQuery(".parature-div").css('background', '#f3f3f3');
                jQuery("#parature-biz-usa-1").css('background', '#359bb7');
                jQuery("#parature-biz-usa-2").css('background', '#1fa385');
                jQuery("#parature-biz-usa-3").css('background', '#ff4863');
                jQuery("#parature-biz-usa-4").css('background', '#8859a5');
                jQuery("#parature-more").css('background', '#f3f3f3');
                jQuery("#icon-text").hide();
                loadScript(document.location.protocol + '//help.business.usa.gov/ics/csrchat/ChatButtonHttpModule.aspx?buttonId=e0221f2a-9807-4b4b-9fac-9937209ee8b7&clientId=30027&deptId=30030&time=' + time);
                break;
        }

        parature_chat.stop();
        parature_ticket.stop();
        parature_ss.stop();
        parature_feedback.stop();
        parature_fed.stop();
        parature_biz_usa_1.stop();
        parature_biz_usa_2.stop();
        parature_biz_usa_3.stop();
        parature_biz_usa_4.stop();
        parature_more.stop();

        parature_chat.animate({
            width: scroll_chat
        },400,function(){ });

        parature_ticket.animate({
            width: scroll_ticket
        },400,function(){ });

        parature_ss.animate({
            width: scroll_ss
        },400,function(){ });

        parature_feedback.animate({
            width: scroll_feedback
        },400,function(){ });

        parature_fed.animate({
            width: scroll_fed
        },400,function(){ });

        parature_biz_usa_1.animate({
            width: scroll_biz_usa_1
        },400,function(){ });

        parature_biz_usa_2.animate({
            width: scroll_biz_usa_2
        },400,function(){ });

        parature_biz_usa_3.animate({
            width: scroll_biz_usa_3
        },400,function(){ });

        parature_biz_usa_4.animate({
            width: scroll_biz_usa_4
        },400,function(){ });

        parature_more.animate({
            width: scroll_more
        },400,function(){ });
    }

    parature_ticket.mouseenter(function () {
        scrollParatureIcon('parature_ticket');
    });

    parature_ticket.mouseleave(function () {
        scrollParatureIcon('');
    });

    parature_chat.mouseenter(function () {
        scrollParatureIcon('parature_chat');
    });

    parature_chat.mouseleave(function () {
        scrollParatureIcon('');
    });

    parature_ss.mouseenter(function () {
        scrollParatureIcon('parature_ss');
    });

    parature_ss.mouseleave(function () {
        scrollParatureIcon('');
    });

    parature_feedback.mouseenter(function () {
        scrollParatureIcon('parature_feedback');
    });

    parature_feedback.mouseleave(function () {
        scrollParatureIcon('');
    });

    parature_fed.mouseenter(function () {
        scrollParatureIcon('parature_fed');
    });

    parature_fed.mouseleave(function () {
        scrollParatureIcon('');
    });

    parature_biz_usa_1.mouseenter(function () {
        scrollParatureIcon('parature_biz_usa_1');
    });

    parature_biz_usa_1.mouseleave(function () {
        scrollParatureIcon('');
    });

    parature_biz_usa_2.mouseenter(function () {
        scrollParatureIcon('parature_biz_usa_2');
    });

    parature_biz_usa_2.mouseleave(function () {
        scrollParatureIcon('');
    });

    parature_biz_usa_3.mouseenter(function () {
        scrollParatureIcon('parature_biz_usa_3');
    });

    parature_biz_usa_3.mouseleave(function () {
        scrollParatureIcon('');
    });

    parature_biz_usa_4.mouseenter(function () {
        scrollParatureIcon('parature_biz_usa_4');
    });

    parature_biz_usa_4.mouseleave(function () {
        scrollParatureIcon('');
    });

    parature_more.mouseenter(function () {
        scrollParatureIcon('parature_more');
    });

    parature_more.mouseleave(function () {
        scrollParatureIcon('');
    });
    $('a').click(function () {
        window.location = $(this).attr('href');
    });

    var badBox = jQuery('#block-boxes-parature-ticket--2');
    badBox.hide();
    badBox.attr('note', 'Hidden by jQuery from parature-ticket-box');
});
// removing jQuery conflict
$.noConflict();
</script>
