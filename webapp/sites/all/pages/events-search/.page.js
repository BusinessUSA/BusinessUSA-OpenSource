jQuery("document").ready(function() {
    jQuery(".bef-datepicker").datepicker({readonly: "readonly", minDate: 0,
        onSelect: function(){
            var minDate = jQuery("#edit-date-filter-min-date").val();
            var maxDate = jQuery("#edit-date-filter-max-date").val();
            if(minDate) jQuery("#edit-date-filter-max-date").datepicker("option", "minDate", minDate);
            if(maxDate) jQuery("#edit-date-filter-min-date").datepicker("option", "maxDate", maxDate);
        }
    });
    //move the sidebar for mobile
    if (windowWidth < 768)  jQuery('.region-sidebar-first').insertBefore(jQuery('.region-content'));

    //Enable pop over
    $('[data-toggle="popover"]').popover();

});
