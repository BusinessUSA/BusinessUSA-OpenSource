<style>
	body.page-node .region-content {
		padding: 0%;
	}
	
	.calendar-icons{
		margin: 10px 0;
	}

	.calendar-icon-container a{
		color: #fff;
		font-family: "latolight";
		font-size: 10px;
		padding: 8px 10px;
		float: left;
	}

	.calendar-icon-container a:hover{
		text-decoration: none;
	}

	.calendar-outlook a {
	   background-color: #f0d124;
	}

	.calendar-gcal a {
	   background-color: #44b3e7;
	   margin: 0 10px;
	}

	.calendar-ycal a {
	    background-color: #8c30bb;
	}
</style>

<script type="text/javascript">

	jQuery("document").ready(function() {

    	//Enable pop over
    	$('[data-toggle="popover"]').popover();

	});

</script>

<div id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?>">

<div class="eventslanding-columns">
<div class="node-title">
	<h2><?php print $node->title; ?></h2>
</div>

<div class="eventslanding-column-left">
	<div class="when">
		<!-- Example Output: "Sep 20, 2012 | 7am-9am" -->
		<h3>When</h3>
		<?php
		global $dateStartUnix, $dateEndUnix;

		if (!empty($node->field_event_date['und'][0]['value'])) {

			// Get start/end dates from the node/database
			$dateStart = $node->field_event_date['und'][0]['value']; // Value stored in format of 2012-11-13 05:00:00
			$dateEnd = $node->field_event_date['und'][0]['value2']; // Value stored in format of 2012-11-13 05:00:00
			if(!$dateEnd)  $dateEnd = $dateStart;


			// Get the current GMT Hour-Offset (changes with daylight savings time)
			$gmtHourOffset = explode(':', date('P'));
			$gmtHourOffset = abs(intval($gmtHourOffset[0]));

			// Convert the pulled information to Unix epoc/time
			$dateStart = str_replace(' ', '-', $dateStart);
			$dateStart = str_replace(':', '-', $dateStart);
			$dateStart = explode('-', $dateStart);
			$dateStartUnix = mktime($dateStart[3] - $gmtHourOffset, $dateStart[4], $dateStart[5], $dateStart[1], $dateStart[2], $dateStart[0]);
			$dateEnd = str_replace(' ', '-', $dateEnd);
			$dateEnd = str_replace(':', '-', $dateEnd);
			$dateEnd = explode('-', $dateEnd);
			$dateEndUnix = mktime($dateEnd[3] - $gmtHourOffset, $dateEnd[4], $dateEnd[5], $dateEnd[1], $dateEnd[2], $dateEnd[0]);
			//dsm( array($n, $dateStart, $dateEnd, $dateStartUnix, $dateEndUnix) );


			// Print output formatted - either a date range or a single day
			if (date('D', $dateStartUnix) !== date('D', $dateEndUnix)) {
				// Print date range
				print date('M dS-', $dateStartUnix) . date('dS', $dateEndUnix) . " | " . date('g:ia-', $dateStartUnix) . date('ga ', $dateEndUnix) . date('Y', $dateEndUnix);
			}
			else {
				// Print date
				print date('M d, Y | g:ia-', $dateStartUnix) . date('g:ia', $dateEndUnix);
			}
			//print print_r($dateStart, true) . " - $dateStartUnix - " . print_r($dateEnd, true) . " - $dateEndUnix ";
		}
		?>
	</div>

	<!-- Calendar export icons -->
	<?php
		global $gcal_url, $ycal_url;

		$city = $state = '';
		//base calendar url
		$gcal_url = "https://www.google.com/calendar/render?action=TEMPLATE";
		$ycal_url = "http://calendar.yahoo.com/?v=60";

		//title parameter
		$gcal_url .= "&text=" . str_replace("\"", "", $node->title);
		$ycal_url .= "&TITLE=" . str_replace("\"", "", $node->title);

		//date parameter
		$sdate = $node->field_event_date['und'][0]['value'];
		$edate = $node->field_event_date['und'][0]['value2'];

		$gcal_url .= "&dates=" . date('Ymd\THis\Z', strtotime($sdate)) . '/' . date('Ymd\THis\Z', strtotime($edate));
		$ycal_url .= "&ST=" . date('Ymd\THis', $dateStartUnix) . "&ET=" . date('Ymd\THis', $dateEndUnix);

		//website url
		$url = $GLOBALS['base_url'] . '/' . drupal_lookup_path('alias', "node/" . $node->nid);
		$gcal_url .= "&sprop=website:" . $url;
		$ycal_url .= "&URL=" . $url;

		//location
		if (!empty($node->field_event_address_1['und'][0]['value'])):
			$location = $node->field_event_address_1['und'][0]['value'];
		endif;

		if (!empty($node->field_event_address_2['und'][0]['value'])):
			$location .= ", " . $node->field_event_address_2['und'][0]['value'];
		endif;

		if (!empty($node->field_event_city['und'][0]['value'])):
			$city = $node->field_event_city['und'][0]['value'];
			$location .= ", " . $node->field_event_city['und'][0]['value'];
		endif;

		if (!empty($node->field_event_state['und'][0]['value'])):
			$state = $node->field_event_state['und'][0]['value'];
			$location .= ", " . $node->field_event_state['und'][0]['value'];
		endif;

		if (!empty($node->field_event_zip['und'][0]['value'])):
			$location .= " - " . $node->field_event_zip['und'][0]['value'];
		endif;

		if (!empty($location)):
			$gcal_url .= "&location=" . trim($location);
			$ycal_url .= "&in_loc=" . trim($location);
		endif;

		//detail description
		if (!empty($node->field_event_detail_desc['und'][0]['value'])):
			$desc = strip_tags(trim($node->field_event_detail_desc['und'][0]['value']));
			$pos = strpos($desc, ' ', 450);
			if ($pos !== false) {
				$desc = substr($desc, 0, $pos) . '...';
			}

			$gcal_url .= "&details=" . $desc;
			$ycal_url .= "&DESC=" . $desc;
		endif;

	?>
	
	<div class="calendar-icons clearfix">
		<button type="button" class="btn btn-default btn-sm" data-template='<div class="popover" role="tooltip"><div class="arrow"></div><div class="popover-content clearfix"></div></div>' data-html="true" data-toggle="popover" data-trigger="focus" data-placement="auto left" 
			data-content='
			<p>
				Please select a service<br>below to add this event<br>to your calendar
			</p>
			
			<div class="calendar-icon-container calendar-outlook">
				<a href="/ical/<?php print $node->nid; ?>/calendar.ics"
					 title="Export Event to iCal format">O
				</a>
			</div>

			<div class="calendar-icon-container calendar-gcal">
				<a href="<?php print $GLOBALS['gcal_url']; ?>"
					 title="Export Event to Google Calendar.">G
				</a>
			</div>

			<div class="calendar-icon-container calendar-ycal">
				<a href="<?php print $GLOBALS['ycal_url']; ?>"
					 title="Export Event to Yahoo Calendar.">Y
				</a>
			</div>

			'>
			Add to Calendar
		</button>
	</div>
								

	<!-- "Register" / Link -->

	<?php
	if (!empty($node->field_event_register_link) && !empty($node->field_event_register_link['und'][0]['value'])) {
		$link = $node->field_event_register_link['und'][0]['value'];
		print "<a class='btn-register' href=\"$link\">Register</a>";
	}
	?>


</div>


<div class="eventslanding-column-right">
	<div class="where">
		<h3>Where</h3>
	</div>

	<div class="event-address">
		<?php
		if (!empty($node->field_event_address_1) && !empty($node->field_event_address_1['und'][0]['value'])) {
			print $node->field_event_address_1['und'][0]['value'];
		}
		if (!empty($node->field_event_address_2) && !empty($node->field_event_address_2['und'][0]['value'])) {
			print '<br/>' . $node->field_event_address_2['und'][0]['value'];
		}
		?>

		<?php
		if (!empty($node->field_event_city) && !empty($node->field_event_city['und'][0]['value'])) {
			print $node->field_event_city['und'][0]['value'] . ", ";
		}
		if (!empty($node->field_event_state) && !empty($node->field_event_state['und'][0]['value'])) {
			print $node->field_event_state['und'][0]['value'] . " ";
		}
		if (!empty($node->field_event_zip) && !empty($node->field_event_zip['und'][0]['value'])) {
			print $node->field_event_zip['und'][0]['value'];
		}
		?>
	</div>

	<!-- "Details" / Description -->
	<div class="details">
		<?php
		if (!empty($node->field_event_detail_desc) && !empty($node->field_event_detail_desc['und'][0]['value'])) {
			//$detail = preg_replace('/<p align="center">..<\/p>/', "", $node->field_event_detail_desc['und'][0]['value']);
			//$detail = str_replace("<br>", "", $detail);
			//$detail = str_replace("<br />", "", $detail);

			//$detail = str_replace('align="center"', "", $detail);
				//BUSUSA-3388 - Event description formatting
				$detail = $node->field_event_detail_desc['und'][0]['safe_value'];
				//$detail = str_replace("<br>\n  ","<br>&nbsp;", $node->field_event_detail_desc['und'][0]['safe_value']);
			print $detail;
		}
		?>
	</div>

	<!-- "Learn More" / Link -->
	<?php if (!empty($node->field_event_url) && !empty($node->field_event_url['und'][0]['url'])) { ?>
		<div class="learn-more">
			<?php
			$link = $node->field_event_url['und'][0]['url'];
			print "<span class='eventslanding-label'>Learn More:</span> <a href=\"$link\">$link</a>";
			?>
		</div>
	<?php } ?>

	<!-- "Contact Information" / Cont. Info -->
	<div class="contact-info">
								<span class="eventslanding-label">
										Contact:
								</span>

		<?php
		if (!empty($node->field_event_poc_name) && !empty($node->field_event_poc_name['und'][0]['value'])) {
			print $node->field_event_poc_name['und'][0]['value'];
		}
		?>

		<?php
		if (!empty($node->field_event_poc_phone) && !empty($node->field_event_poc_phone['und'][0]['value'])) {
			print $node->field_event_poc_phone['und'][0]['value'];
		}
		?>

		<?php
		if (!empty($node->field_event_poc_email) && !empty($node->field_event_poc_email['und'][0]['value'])) {
			$link = $node->field_event_poc_email['und'][0]['value'];
			print "<a href=\"mailto:$link\">$link</a>";
		}
		?>

	</div>

</div>
</div>

<!--div>
		<img class="map" src="https://maps.googleapis.com/maps/api/staticmap?size=700x265&sensor=false&
		maptype=RoadMap&zoom=10&markers= <?php print $node->field_event_address_1['und'][0]['value'];
print $node->field_event_city['und'][0]['value'];?>" ></img>
		</div-->

<div class="map-container">
	<?php
	$targetLatitude = (float) $node->field_event_latitude['und'][0]['safe_value'];
	$targetLongitude = (float) $node->field_event_longitude['und'][0]['safe_value'];
	$city_state = (!empty($city) && !empty($city)) ? "$city, $state" : '';
	$variables = array(
		'cityState' => $city_state,
		'targetLatitude' => $targetLatitude,
		'targetLongitude' => $targetLongitude,
		'title' => $node->title
	);
	if (($targetLatitude && $targetLongitude) || $city_state) {
		print theme('google_map', $variables);
	}
	?>
</div>

	<?php if (!empty($content['last_date_modified'])): ?>
			<?php print render($content['last_date_modified']); ?>
	<?php endif; ?>
</div>


<!-- <?php print render($content['comments']); ?>  -->
