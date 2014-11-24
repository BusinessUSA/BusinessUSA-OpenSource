<?php
    $dtStart = new DateTime( $event->field_event_date['und'][0]['value'] . ' UTC');
    $dtStart->setTimezone( new DateTimeZone('America/New_York') );
    if(!$event->field_event_date['und'][0]['value2'])  $dtEnd = $dtStart;
    else{
        $dtEnd = new DateTime( $event->field_event_date['und'][0]['value2'] . ' UTC');
        $dtEnd->setTimezone( new DateTimeZone('America/New_York') );
    }
    $gcalDateStart = $dtStart->format("Ymd\\This\\Z");
    $gcalDateEnd = $dtEnd->format("Ymd\\This\\Z");
    $ycalDateStart = $dtStart->format("Ymd\\This");
    $ycalDateEnd = $dtEnd->format("Ymd\\This");
    $busaURL = 'http://business.usa.gov/' . $event->nid;
    $calLocation = '';
    $address = $event->field_event_address_1['und'][0]['safe_value'];
    $address2 = $event->field_event_address_2['und'][0]['safe_value'];
    $city = $event->field_event_city['und'][0]['safe_value'];
    $state = $event->field_event_state['und'][0]['safe_value'];
    $zip = $event->field_event_zip['und'][0]['safe_value'];
    $description = $event->field_event_detail_desc['und'][0]['value'];
    $targetLatitude = (float) $event->field_event_latitude['und'][0]['safe_value'];
    $targetLongitude = (float) $event->field_event_longitude['und'][0]['safe_value'];
    $nid = $event->nid;
    $title = $event->title;
    if (!empty($address))  $calLocation .= $address . ', ';
    $calLocation = urlencode($calLocation);
    $calDetail = '';
    if ( !empty($description) ) {
        $calDetail = substr($description, 0, 100) . '...';
    }
    $calDetail = urlencode($calDetail);


    $gcalLink = "https://www.google.com/calendar/render?" . urlencode("action=TEMPLATE&text=$title&dates=$gcalDateStart/$gcalDateEnd&sprop=website:$busaURL&location=$calLocation&details=$calDetail");
    $ycalLink = "http://calendar.yahoo.com/?" . urlencode("v=60&TITLE=$title&ST=$ycalDateStart&ET=$ycalDateEnd&URL=$busaURL&in_loc=$calLocation&DESC=$calDetail");
?>

<div class="view-content clearfix">
     <div class="events-row">
        <div class="eventscolumn-left">
            <div class="event-calendar">
                <div class='month'><?php echo $dtStart->format('M'); ?></div><div class='day'><?php echo $dtStart->format('d'); ?></div>
            </div>
            <div class="event-calendarlinks">

                <button type="button" class="btn btn-default btn-sm center-block" data-template='<div class="popover" role="tooltip"><div class="arrow"></div><div class="popover-content clearfix"></div></div>' data-html="true" data-toggle="popover" data-trigger="focus" data-placement="auto left" 
                    data-content='
                        <p>
                            Please select a service<br>below to add this event<br>to your calendar
                        </p>
                        <div class="calendar-icon-container calendar-outlook">
                            <a href="/ical/<?php print $nid; ?>/calendar.ics">
                                <img class="ical" alt="Outlook Calendar" src="/sites/all/themes/bizusa/images/outlook_cal.png" />
                            </a>
                        </div>
                        <div class="calendar-icon-container calendar-gcal">
                            <a href="<?php print $gcalLink; ?>" target="blank">
                                <img class="gcal" alt="Google Calendar" src="/sites/all/themes/bizusa/images/gcal.png" />
                            </a>
                        </div>
                        <div class="calendar-icon-container calendar-ycal">
                            <a href="<?php print $ycalLink; ?>" target="blank">
                                <img class="ycal" alt="Yahoo Calendar" src="/sites/all/themes/bizusa/images/ycal.png" />
                            </a>
                        </div>
                    '>
                    Add to Calendar
                </button>
                
            </div>
        </div>

        <div class="eventscolumn-right">
            <h2 class="event-title">
                <a href="/node/<?php print $nid; ?>">
                    <?php print $title; ?>
                </a>
            </h2>
            <div class="event-date-time">
                <?php
                if ( $dtStart->format('Ymd') == $dtStart->format('dtEnd') ) { // If this event starts and ends on the same day....
                    print $dtStart->format('M dS, Y'). "<span>|</span>" . $dtStart->format('h:ia - ') . $dtEnd->format('h:ia');
                } else {
                    print $dtStart->format('M dS, Y') . ' to ' . $dtEnd->format('M dS, Y') . "<span>|</span>" . $dtStart->format('h:ia - ') . $dtEnd->format('h:ia');
                }
                ?>
            </div>
            <div class="event-location">
              <?php if (!empty($address)): ?>
                <div class="event-where">
                    Where:
                </div>
                <div class="event-address">
                    <?php print $address; ?>
                </div>
              <?php endif; ?>
                <div class="event-citystatezip">
                    <?php
                    $addr = '';
                    if (!empty($address2)) {
                        if ($addr !== '') $addr .= ', ';
                        $addr .= $address2;
                    }
                    if (!empty($city)) {
                        if ( $addr !== '' ) $addr .= ', ';
                        $addr .= $city;
                    }
                    if (!empty($state)) {
                        if ( $addr !== '' ) $addr .= ', ';
                        $addr .= $state;
                    }
                    if (!empty($zip)) {
                        if ( $addr !== '' ) $addr .= ' ';
                        $addr .= $zip;
                    }
                    print $addr;
                    ?>
                </div>


                <!-- "Contact Information" / Cont. Info -->
                 <div class="contact-info">
                    <?php if (!empty($event->field_event_poc_name['und'][0]['value']) || !empty($event->field_event_poc_phone['und'][0]['value']) || !empty($event->field_event_poc_email['und'][0]['value']) ): ?>
                    <span class="events-contact-info">
                        Contact:
                    </span>

                        <div class="events-contact-poc">
                            <?php
                            if (!empty($event->field_event_poc_name) && !empty($event->field_event_poc_name['und'][0]['value'])) {
                                print $event->field_event_poc_name['und'][0]['value'];
                            }
                            ?>
                        </div>
                        <div class="events-contact-phone">
                            <?php
                            if (!empty($event->field_event_poc_phone) && !empty($event->field_event_poc_phone['und'][0]['value'])) {
                                print $event->field_event_poc_phone['und'][0]['value'];
                            }
                            ?>
                        </div>
                        <div class="events-contact-email">
                            <?php
                            if (!empty($event->field_event_poc_email) && !empty($event->field_event_poc_email['und'][0]['value'])) {
                                $link = $event->field_event_poc_email['und'][0]['value'];
                                print "<a href=\"mailto:$link\">$link</a>";
                            }
                            ?>
                        </div>
                    <?php endif; ?>
                </div>

            </div>

            <div class="event-descr">
                <?php print truncate_utf8(strip_tags(htmlspecialchars_decode($description)), 289, true, true); ?>
                <a href="javascript:void(0)" onclick="expanddesc(this)" class="viewmoreanc">Read more</a>
            </div>

            <div class="event-descr" style="display:none">
                <?php print strip_tags(htmlspecialchars_decode($description)); ?>
                <a href="javascript:void(0)" onclick="expanddesc(this)" class="viewmoreanc">Read less</a>

            </div>
        </div>
        <div class='google-map'>
            <?php
            $variables = array('targetLatitude' => $targetLatitude, 'targetLongitude' => $targetLongitude, 'title' => $title, 'count' => $count, 'hideMap' => TRUE);
            if($targetLatitude && $targetLongitude) {
                print theme('google_map', $variables);
                $count++;
            }
            ?>
        </div>
    </div>
</div>