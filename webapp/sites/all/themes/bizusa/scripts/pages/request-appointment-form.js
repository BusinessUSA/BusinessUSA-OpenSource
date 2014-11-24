/**
 * Created by sanjay.gupta on 7/31/14.
 */




(function ($) {
    $(document).ready(function(){
      jQuery('#dateofvisit').click(function() {
          jQuery('#ui-datepicker-div').css('top', '-=71px');
      });

$('#reqaptemail').bind('focusout', function()
    {
        var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        if( !emailReg.test( $('#reqaptemail').val() ) ) {
            alert('Please provide valid email address');
            $('#reqaptemail').focus();}

    }

);

        $('#phone').bind('focusout', function()
        {
            //Phone number validation

            if ($('#phone').val().length > 0)
            {
                if ($('#phone').val().length == 10)
                {
                    var phonenum = $('#phone').val();
                    if  (!($.isNumeric(phonenum)))
                    {
                        alert('Please provide valid numeric phone number');
                        $('#phone').focus();

                    }
                }
                else if ($('#phone').val().length < 10)
                {
                    alert('Please provide phone number with area code');
                    $('#phone').focus();

                }
                else
                {
                    alert('Please provide valid phone number');
                    $('#phone').focus();

                }
            }
        });

});
})(jQuery);
