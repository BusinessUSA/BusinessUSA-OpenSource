/**
 * Created by sanjay.gupta on 7/23/14.
 */

var downloadlink = '';
(function ($) {


    $(document).ready(function(){
        downloadlink = '';
        var disclaimer_div = $('#dv_disclaimer');
        disclaimer_div.attr('style', 'display:none');
    });

})(jQuery);


function redirect()
{
    parent.$.colorbox.close();
    window.location = downloadlink;
    return true;

}

function showDisclaimer(param)
{
    if (param == 'browse')
    {
        downloadlink = 'http://www.browsealoud.com/us/support/getbrowsealoud/';
    }
    else if (param == 'technical')
    {
        downloadlink = 'http://www.browsealoud.com/us/support/';
    }
    else
    {
        downloadlink = 'http://business.usa.gov';
    }

    jQuery.colorbox({
        'html':jQuery('#dv_disclaimer').html()

    });


}

