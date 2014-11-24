$(document).ready(function () {
    
    $('#menu').multilevelpushmenu({
        containersToPush: [$('.navbar.navbar-default.navbar-fixed-top.navbar-inverse'), $('.helpArea'), $('body > .wrapper'), $('.footer.navbar')],
        fullCollapse: true,
        collapsed: true,
        mode: 'cover',
        menuWidth: '80%',
        preventItemClick: false,

        onExpandMenuEnd : function() {
            setTimeout(function() { checks(); }, 5);
        },
        onCollapseMenuEnd : function() {
            setTimeout(function() { checks(); }, 5);
        }


    });
    
    $( '#toggle-mobile-menu' ).click(function(){

        // Adjust menu height
        $('#menu').multilevelpushmenu('option', 'menuHeight', $('body > .wrapper').height() + $('.footer.navbar').height() );
        $('#menu').multilevelpushmenu('redraw');
        
        var activeMenuItem = $('#menu').multilevelpushmenu('activemenu');

        if($('#menu').multilevelpushmenu('menuexpanded',activeMenuItem)){
            $( '#menu' ).multilevelpushmenu( 'collapse' );
        }else{
            $( '#menu' ).multilevelpushmenu( 'expand' );
        }
        
    });
    
});


// Scroll mobile issue
function checks() {
    $('.levelHolderClass ul').each(function() {
        var sub = 0;
        $(this).parent().children().each(function() {
            if ($(this).prop('tagName').toLowerCase() !== 'ul') {
                sub += $(this).outerHeight(true);
            }
        });
        var height = $(window).height() - sub;
        $(this).css({
            height: height,
            'overflow-y' : 'auto',
            'min-height': ''
        });
    });
    $('#menu').children().css({
        height : $(window).height() + 'px',
        'min-height' : $(window).height() + 'px'
    });
     $('#menu').css({
        height : $(window).height() + 'px',
        'min-height' : $(window).height() + 'px'
    });
};


$(window).resize(function () {

    // if menu is open close it while resizing
    $( '#menu' ).multilevelpushmenu( 'collapse' );

    // reset margin
    $('body > .wrapper, .footer.navbar, .helpArea').removeAttr( "style" );

    $('#menu').multilevelpushmenu('option', 'menuHeight', $('body > .wrapper').height() + $('.footer.navbar').height() );
    $('#menu').multilevelpushmenu('redraw');

});




$(document).on('click touchstart', function (event) {
    if(!$(event.target).closest('#menu').length) {
        if($('#menu').multilevelpushmenu( 'menuexpanded' , $('#menu').multilevelpushmenu('activemenu') ) ){
            $( '#menu' ).multilevelpushmenu( 'collapse' );
        }
    }
});

// $(document).click(function(event) {
//     if(!$(event.target).closest('#menu').length) {
//         if($('#menu').multilevelpushmenu( 'menuexpanded' , $('#menu').multilevelpushmenu('activemenu') ) ){
//             $( '#menu' ).multilevelpushmenu( 'collapse' );
//         }
//     }        
// });



