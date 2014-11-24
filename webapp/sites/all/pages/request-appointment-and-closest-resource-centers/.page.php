

<!-- START: Filters for the "Request an Appointment" page -->

<div class="row">
	<div class="resourcecenters-filter-container col-sm-4 adjust-padding-right">

		<div class="zipRadius">
			<form class="ziprangeform row">

				<div class="resourcecenters-filter-ziparea col-xs-6" style="padding-right:0;">

					<label for="resourcecenters-filter-ziparea-input">
						<small>Enter Zip Code</small>
					</label>
					<div class="input-group">
						<?php if (($_GET['zip'] == '20171') or  ($_GET['zip'] == '')){?>
							<input id="resourcecenters-filter-ziparea-input" class="form-control" name="zip" type="text" value="<?php print $_GET['zip'] ?>" class="auto-fill-zip-code"/>
						<?php } else{ ?>
							<input id="resourcecenters-filter-ziparea-input" class="form-control" name="zip" type="text" value= '<?php print $_GET['zip'] ?>' class="auto-fill-zip-code"/>
						<?php } ?>
						<span class="input-group-btn">
							<input type="submit" class="btn btn-primary" value="GO" />
						</span>
					</div>

				</div>

				<div class="resourcecenters-filter-rangearea col-xs-6">

					<label for="resourcecenters-filter-rangearea-select">
						<small>Maximum distance</small>
					</label>
					<select name="range" id="resourcecenters-filter-rangearea-select">
						<option>5</option>
						<option>10</option>
						<option>25</option>
						<option>50</option>
						<option>100</option>
					</select>
					<input name="wiz" type="hidden" value="anything" class="facet-forward"/>
					<script>
						jQuery('#resourcecenters-filter-rangearea-select').val(<?php print $range; ?>);
						jQuery('#resourcecenters-filter-rangearea-select').bind('change', function () {
							jQuery('.ziprangeform').submit();
						});
						jQuery('.ziprangeform').bind('submit', function(event){
							var wizItems = [];
							var nonWizItems = [];

							jQuery('.resourcecenters-filter-filters input[type="checkbox"]:checked').each(function (element_key, element) {
								var element_id = jQuery(element).attr('id');
								if (element_id in Drupal.settings.resource_center.facet_checkboxes.wiz) {
									wizItems.push(Drupal.settings.resource_center.facet_checkboxes.wiz[element_id]);
								} else if (element_id in Drupal.settings.resource_center.facet_checkboxes) {
									nonWizItems.push(element_id);
								}
							});

							jQuery('.facet-forward').val(wizItems.join(','));
							jQuery(".facet-forward-non-wiz").remove();
							jQuery(nonWizItems).each(function (element_key, element_id) {
								newElem = jQuery('<input>').attr({
									id: element_id+element_key,
									name: element_id,
									value: element_id,
									type: 'hidden',
									class: "facet-forward-non-wiz"
								});
								console.log(jQuery(event.target).append(newElem));
							});
						});
					</script>

				</div>

			</form>
		</div>




		<div class="filters">
			<br>
			<div class="panel panel-block">
			
				<div class="panel-heading">
					<h3 class="panel-title">Filter by resource type</h3>
				</div>
				<div class="panel-body">

					<?php

						$officeTypeField = field_info_field('field_appoffice_type');
						$officeTypes = list_allowed_values($officeTypeField);
						$officeTypesTooltipDesc = array(
						"SCORE Office"=>"SCORE is a nonprofit association comprised of over 13,000 volunteer business counselors, advisors, and mentors who offer free counseling and mentoring throughout the U.S. and its territories.",
						"Small Business Development Center"=> "Small Business Development Centers (SBDCs) are partnerships primarily between the government and colleges/universities aimed at providing educational services to small business owners.",
						"SBA District Office" => "SBA District Offices offer counseling, training and business development specialists to help you start and grow your business.",
						"SBA Regional Office" => "SBA Regional Offices offer counseling, training and business development specialists to help you start and grow your business.",
						"Disaster Field Office" => "SBA Disaster Field Offices offer counseling and financial assistance to those who are rebuilding their homes and businesses after natural disasters.",
						"U.S. Export Assistance Center" => "U.S. Export Assistance Centers (USEACs) are designed to provide export assistance and make worldwide commerce achievable for your small- or medium-sized business.",
						"Private Lender" => "The SBA extends financial assistance through private sector lenders, such as banks and other financial institutions, who make the loans with an SBA guaranty. A participating lender may make one of three decisions: to approve the loan itself, make it with SBA's guaranty, or decline it altogether.",
						"Women's Business Center" => "Women's Business Centers (WBCs) represent a national network of educational centers designed to assist women to start and grow small businesses.",
						"Veteran's Business Outreach Center" => "Veterans Business Outreach Centers (VBOCs) are designed to provide entrepreneurial development services and referrals for eligible veterans owning or considering starting a small business.",
						"Small Business Investment Company" => "A Small Business Investment Company (SBIC) is a private lending company which is licensed and regulated by the Small Business Administration (SBA). SBIC's offer venture capital financing to higher-risk small businesses, and SBIC loans are guaranteed by the SBA.",
						"Procurement & Technical Assistance Center" => "Procurement Technical Assistance Centers (PTACs) provide technical assistance to businesses that want to sell products and services to federal, state, and/or local governments.",
						"Microloan Program Intermediary Lender" => "The U.S. Small Business Administration (SBA) offers a Microloan Program that provides microloans (loans of $50,000 or less) to small businesses by way of a network of private non-profit community and faith based lenders (subject to availability of funds). The SBA makes loans to these Intermediary Lenders, enabling the lenders in turn to make loans to small business borrowers.",
						"Certified Development Company" => "Certified Development Companies (CDCs) are nonprofit corporations certified and regulated by the SBA which work with participating lenders to provide financing to small businesses.",
						"U.S. Commercial Service Office" => "The U.S. Commercial Service is the trade promotion arm of the U.S. Department of Commerce’s International Trade Administration. U.S. Commercial Service trade professionals in over 100 U.S. cities and in more than 75 countries help U.S. companies get started in exporting or increase sales to new global markets.",
						"Rural Development Office" => "USDA Office of Rural Development (RD) is an agency with the United States Department of Agriculture which runs programs intended to improve the economy and quality of life in rural America.",
						"NIST (MEP - Manufacturing Extension Partnership)" => "The Manufacturing Extension Partnership (MEP) is a catalyst for strengthening American manufacturing – accelerating its ongoing transformation into a more efficient and powerful engine of innovation driving economic growth and job creation.",
						"BIS Export Enforcement Office" => "BIS Export Enforcement Office works cooperatively with the exporting community to prevent violations, and conducts investigations to gather evidence to support criminal and administrative sanctions.",
						"BIS Outreach and Education Services Division Office"=>"The BIS Outreach and Educational Services Division is responsible for responding to inquiries from the exporting community regarding the Export Administration Regulations (EAR), export control policy, and licensing procedures. It also plans, conducts and participates in seminars and other outreach efforts to help exporters understand and comply with the EAR.", "MBDA Business Center" => "Minority-owned firms seeking to penetrate new markets — domestic & global — and growing in size and scale, can access business experts at a MBDA Business Center. Whether it’s securing capital, competing for a contract, identifying a strategic partner or becoming export-ready, your success is our priority."
						);
						?>
						<ul class="resourcecenters-filter-filters">
							<?php foreach ( $officeTypes as $officeKey => $officeType ) { ?>
								<?php $officeId = cssFriendlyString($officeKey); ?>
								<?php
									if($officeId === 'sbaregionaloffice') continue;
								?>

								<li class="resourcecenters-filter">
									<?php

									global $officeParams;
									if (strpos($_GET['wiz'],',') !== false){
										$officeParams = explode(',', $_GET['wiz']);
									}
												// Export checkboxes as settings.
									$wiz_facet_checkboxes = array(
										'scoreoffice' => 'score',
										'mbdabusinesscenter' => 'min',
										'veteransbusinessoutreachcenter' => 'vet',
										'womensbusinesscenter' => 'woman',
										'smallbusinessdevelopmentcenter' => 'sbdc',
										'usexportassistancecenter' => 'useac',
										'sbadistrictoffice' => 'sbadistoff',
										);

									$facet_checkboxes = array(
										'wiz' => $wiz_facet_checkboxes,
										'disasterfieldoffice' => 'disasterfieldoffice',
										'privatelender' => 'privatelender',
										'smallbusinessinvestmentcompany' => 'smallbusinessinvestmentcompany',
										'procurementtechnicalassistancecenter' => 'procurementtechnicalassistancecenter',
										'microloanprogramintermediarylender' => 'microloanprogramintermediarylender',
										'certifieddevelopmentcompany' => 'certifieddevelopmentcompany',
										'uscommercialserviceoffice' => 'uscommercialserviceoffice',
										'ruraldevelopmentoffice' => 'ruraldevelopmentoffice',
										'nistmepmanufacturingextensionpartnership' => 'nistmepmanufacturingextensionpartnership',
										'bisexportenforcementoffice' => 'bisexportenforcementoffice',
										'bisoutreachandeducationservicesdivisionoffice' => 'bisoutreachandeducationservicesdivisionoffice',
										);

									drupal_add_js(array('resource_center' => array('facet_checkboxes' => $facet_checkboxes)),'setting');
									
									?>
										<!-- Custom code added for users coming from
										Start a business wizard to check score or small business checkbox by default -->

										<?php  if ( $officeId == 'scoreoffice') { ?>
											<?php if ((($_GET['wiz']) == 'score') or (($_GET['wiz']) == 'native') or (($_GET['wiz']) == 'businesssunday') or (in_array('score',$officeParams))) { ?>
												<input data-label="<?php print $officeType; ?>" id="<?php print $officeId; ?>" type="checkbox" checked="checked">
											<?php } else {?>
												<input data-label="<?php print $officeType; ?>" id="<?php print $officeId; ?>" type="checkbox">
											<?php } ?>

										<?php } else if ( $officeId == 'mbdabusinesscenter') { ?>
											<?php if ((($_GET['wiz']) == 'min') or (($_GET['wiz']) == 'native') or (in_array('mbda',$officeParams)) or in_array('min',$officeParams)) { ?>
												<input data-label="<?php print $officeType; ?>" id="<?php print $officeId; ?>" type="checkbox" checked="checked">
											<?php } else {?>
												<input data-label="<?php print $officeType; ?>" id="<?php print $officeId; ?>" type="checkbox">
											<?php } ?>

										<?php } else if ( ($officeId == 'veteransbusinessoutreachcenter') ) { ?>
											<?php if ((($_GET['wiz']) == 'vet') or (in_array('veteran',$officeParams) or in_array('vet',$officeParams))) { ?>
												<input data-label="<?php print $officeType; ?>" id="<?php print $officeId; ?>" type="checkbox" checked="checked">
											<?php } else {?>
												<input data-label="<?php print $officeType; ?>" id="<?php print $officeId; ?>" type="checkbox">
											<?php } ?>

										<?php } else if ( $officeId == 'womensbusinesscenter') { ?>
											<?php if ((($_GET['wiz']) == 'woman') or (in_array('woman',$officeParams))) { ?>
												<input data-label="<?php print $officeType; ?>" id="<?php print $officeId; ?>" type="checkbox" checked="checked">
											<?php } else {?>
												<input data-label="<?php print $officeType; ?>" id="<?php print $officeId; ?>" type="checkbox">
										<?php } ?>

										<?php } else if ( $officeId == 'smallbusinessdevelopmentcenter') { ?>
											<?php if ((($_GET['wiz']) == 'sbdc') or (($_GET['wiz']) == 'native') or (($_GET['wiz']) == 'businesssunday') or (in_array('sbdc',$officeParams))) { ?>
												<input data-label="<?php print $officeType; ?>" id="<?php print $officeId; ?>" type="checkbox" checked="checked">
											<?php } else {?>
												<input data-label="<?php print $officeType; ?>" id="<?php print $officeId; ?>" type="checkbox">
										<?php } ?>
										
										<?php } else if ( $officeId == 'sbadistrictoffice') { ?>
											<?php if ((($_GET['wiz']) == 'businesssunday') or (in_array('sbadistoff',$officeParams))) { ?>
												<input data-label="<?php print $officeType; ?>" id="<?php print $officeId; ?>" type="checkbox" checked="checked">
											<?php } else {?>
												<input data-label="<?php print $officeType; ?>" id="<?php print $officeId; ?>" type="checkbox">
										<?php } ?>
										
										<?php } else if ( $officeId == 'usexportassistancecenter') { ?>
											<?php if (($_GET['wiz'] == 'useac') or in_array('useac',$officeParams))  { ?>
												<input data-label="<?php print $officeType; ?>" id="<?php print $officeId; ?>" type="checkbox" checked="checked">
											<?php } else {?>
												<input data-label="<?php print $officeType; ?>" id="<?php print $officeId; ?>" type="checkbox">
										<?php } ?>

										<?php } else { ?>

										<input data-label="<?php print $officeType; ?>" id="<?php print $officeId; ?>" type="checkbox" <?php if (!empty($_GET[$officeId])) print('checked="checked"'); ?> >

										<?php } ?>

										<?php if($officeTypesTooltipDesc[$officeKey]!=""): ?>
											<a class="pull-right" data-trigger="hover" data-template='<div class="popover" role="tooltip"><div class="popover-content"></div></div>' data-placement="auto" data-toggle="popover" data-content="<?php print $officeTypesTooltipDesc[$officeKey];?>">
												<img alt="Information Icon" title="Information icon" src="/sites/all/themes/bizusa/images/icons/informationicon.png"/>
											</a>
										<?php endif;?>
								</li>
							<?php } ?>
						</ul>
						
						<input class="resourcecenters-filter-submit btn btn-primary" type="button" value="Apply" />
					
				</div>
			
			</div>

		</div>

	</div>

	<!-- END: Filters for the "Request an Appointment" page -->


	<!-- START: GoogleMap area -->
	<div class="resourcecenters-googlemap-container col-sm-8 adjust-padding-left">

		<!-- Include the GoogleMap-JavaScript-API -->
		<script src="<?php print ( !empty($_SERVER['HTTPS']) ? 'https' : 'http' ); ?>://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
		<script>
			map = null;
			google.maps.event.addDomListener(window, 'load', function () {
				var mapOptions = {
					zoom: 9,
					center: new google.maps.LatLng( <?php print $locInfo['lat'] ?>, <?php print $locInfo['lng'] ?>),
					mapTypeId: google.maps.MapTypeId.ROADMAP
				};
				map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
				drawMarkersFromView();
			});
		</script>

		<!-- JavaScript above injects a google map into the following div -->
		<div id="map-canvas" class="resevents-googlemap-mapcanvas"></div>

	</div>
	<!-- END: GoogleMap area -->

</div>



<div class="row">


	<!-- Views on the "Request an Appointment" page -->
	<div id="dataList">

		<div class="col-sm-4 adjust-padding-right">
			<div class="panel panel-block">
				<div class="panel-heading">
					<h3 class="panel-title">Resource Centers</h3>
				</div>
				<div class="panel-body synch-height2" synchheightgroup="1">
					<?php print views_embed_view('closes_resource_center_retheme', 'resource_centers', trim($zip), trim($range));?>
				</div>
			</div>
		</div>

		<div class="col-sm-4 adjust-padding-right adjust-padding-left">
			<div class="panel panel-block">
				<div class="panel-heading">
					<h3 class="panel-title">Other Local Resources</h3>
				</div>
				<div class="panel-body synch-height2" synchheightgroup="1">
					<?php
						$locInfo = getLatLongFromZipCode($zip);
						$locInfo['state'] = acronymToStateName( $locInfo['state'] );

						print "<!-- locInfo['state'] = {$locInfo['state']} -->";
						print views_embed_view('closes_resource_center_retheme', 'other_local_resources', $locInfo['state']);
					?>
				</div>
			</div>
		</div>

		<div class="col-sm-4 adjust-padding-left">

			<div class="panel panel-block">
				<div class="panel-heading">
					<h3 class="panel-title">Explore local events</h3>
				</div>
				<div class="panel-body synch-height2" synchheightgroup="1">
					<?php print views_embed_view('closes_resource_center_retheme', 'events', $zip, $range); ?>
				</div>
			</div>

		</div>

	</div>


</div>


<script type="text/javascript" src="<?php print '/sites/all/themes/bizusa/scripts/pages/request-appointment.js'; ?>"></script>


<!--
# Delete the script from the header in the view. drawAllMarkersOnGoogleMap()
-->
