<?php
/**
 * Created by PhpStorm.
 * User: sanjay.gupta
 * Date: 6/30/14
 * Time: 3:00 PM
 */

?>

<div>
    <!-- <div><? print $node->title ?></div> -->

    <div class="twocolumn">
        <div class="row-container">
                <span  class="leftcol">
                    <label id="lblOpportunityTitle" value="Opportunity Title">
                        Opportunity Number:

                    </label>
                </span>
                <span class="rightcol">
                    <?php print $node->nid ?>
                </span>

        </div>

        <div class="row-container">
                <span  class="leftcol">
                    <label id="lblnoticetype" value="Notice Type">
                        Notice Type:

                    </label>
                </span>
                <span class="rightcol">
                    <?php
                        if (trim($node->field_notice_type['und'][0]['value']) != '') {
                        print $node->field_notice_type['und'][0]['value'];
                    }
                    else
                        {
                            print 'N/A';
                        }?>

                </span>
        </div>

    </div>

    <div>
        <div class="row-container">
            <span  class="leftcol">
                <label id="lblsynopsis" value="Synopsis">
                    Synopsis:

                </label>
            </span>
            <span class="rightcol">

                <?php
                if (trim($node->body['und'][0]['value']) != '') {
                    print  $node->body['und'][0]['value'];
                }
                else
                {
                    print 'N/A';
                }?>


            </span>

        </div>
    </div>

    <div>
        <div class="row-container">
            <span  class="leftcol">
                <label id="lblprojDesc" value="Project Description">
                    Project Description:

                </label>
            </span>
            <span class="rightcol">

                <?php
                if (trim($node->field_project_description['und'][0]['value']) != '') {
                    print $node->field_project_description['und'][0]['value'];
                }
                else
                {
                    print 'N/A';
                }?>

            </span>

        </div>
    </div>

    <div>
        <div class="row-container">
            <span  class="leftcol">
                <label id="lblnoticetype" value="Notice Type">
                    Agency Address:

                </label>
            </span>
            <span class="rightcol">


                <?php
                if (trim($node->field_agency_address['und'][0]['value']) != '') {
                    print $node->field_agency_address['und'][0]['value'];
                }
                else
                {
                    print 'N/A';
                }?>
            </span>

        </div>
    </div>



    <div>
        <div class="row-container">
            <span  class="leftcol">
                <label id="lblnoticetype" value="Notice Type">
                    Contracting Agency/Organization Address:

                </label>
            </span>
            <span class="rightcol">


                <?php
                if (trim($node->field_url_of_agency['und'][0]['value']) != '') {
                    print $node->field_url_of_agency['und'][0]['value'];
                }
                else
                {
                    print 'N/A';
                }?>
            </span>

        </div>
    </div>



    <div>
        <div class="row-container">
            <span  class="leftcol">
                <label id="lblplaceofperformance" value="Place of Performance">
                    Place of Performance:

                </label>
            </span>
            <span class="rightcol">



                <?php
                if (trim($node->field_performance_city['und'][0]['value']) != '') {
                    print $node->field_performance_city['und'][0]['value'];
                }
                else
                {
                    print 'N/A';
                }?>

                <?php
                if (trim($node->field_performance_state['und'][0]['value']) != '') {
                    print ", ". $node->field_performance_state['und'][0]['value'];
                }
                else
                {
                    print 'N/A';
                }?>

                <?php
                if (trim($node->field_performance_zip_code['und'][0]['value']) != '') {
                    print " ". $node->field_performance_zip_code['und'][0]['value'];
                }
                else
                {
                    print 'N/A';
                }?>

               <div> United States</div>
            </span>

        </div>
    </div>

    <div>
        <div class="row-container">
            <span  class="leftcol">
                <label id="lblpointofcontact" value="Point of Contact">
                    Point of Contact:

                </label>
            </span>
            <span class="rightcol">

                 <?php
                 if (trim($node->field_contact_person['und'][0]['value']) != '') {
                     ?>
                <div>
                     <?php print $node->field_contact_person['und'][0]['value'];?>
                </div>
                <?php }
                 else
                 {
                     print 'N/A';
                 }?>

                <?php
                if (trim($node->field_contact_phone_number['und'][0]['value']) != '') {
                ?>
                <div>
                    <?php print $node->field_contact_phone_number['und'][0]['value'];?>
                </div>
                <?php
                }
                else
                {
                    print 'N/A';
                }?>

                <?php
                if (trim($node->field_contact_email['und'][0]['value']) != '') {
                ?>
                <div>
                    <?php print $node->field_contact_email['und'][0]['value'];?>
                </div>
                <?php
                }
                else
                {
                    print 'N/A';
                }?>

            </span>

        </div>
    </div>



    <div>
        <div class="row-container">
            <span  class="leftcol">
                <label id="lblnoticetypesidebar" value="Notice Type">
                    Notice Type:

                </label>
            </span>
            <span class="rightcol">

                <?php
                if (trim($node->field_notice_type['und'][0]['value']) != '') {
                    print $node->field_notice_type['und'][0]['value'];
                }
                else
                {
                    print 'N/A';
                }?>

            </span>
        </div>


        <div class="row-container">
            <span  class="leftcol">
                <label id="lblawarddate" value="Award Date">
                    Contract Award Date:

                </label>
            </span>
            <span class="rightcol">


               <?php
               if (trim($node->field_contract_award_date['und'][0]['value']) != '') {
                   print date('F j, Y', strtotime($node->field_contract_award_date['und'][0]['value']));
               }
               else
               {
                   print 'N/A';
               }?>

            </span>
        </div>

        <div class="row-container">
            <span  class="leftcol">
                <label id="lbldeadline" value="Deadline Date">
                    Response Deadline Date:

                </label>
            </span>
            <span class="rightcol">


                <?php
                if (trim($node->field_response_deadline['und'][0]['value']) != '') {
                    print date('F j, Y', strtotime($node->field_response_deadline['und'][0]['value']));
                }
                else
                {
                    print 'N/A';
                }?>

            </span>
        </div>

        <div class="row-container">
            <span  class="leftcol">
                <label id="lblnaics" value="NAICS Code">
                   NAICS Code:

                </label>
            </span>
            <span class="rightcol">
                <?php
                if (trim($node->field_naics_code['und'][0]['value']) != '') {
                    print $node->field_naics_code['und'][0]['value'];
                }
                else
                {
                    print 'N/A';
                }?>
            </span>
        </div>


        <div class="row-container">
            <span  class="leftcol">
                <label id="lblreportreq" value="Reporting Requirement">
                    Reporting Requirement:

                </label>
            </span>
            <span class="rightcol">


                <?php
                if (trim($node->field_reporting_requirements['und'][0]['value']) != '') {
                    print $node->field_reporting_requirements['und'][0]['value'];
                }
                else
                {
                    print 'N/A';
                }?>
            </span>
        </div>

        <div class="row-container">
            <span  class="leftcol">
                <label id="lblspecialcondition" value="Special Site Conditions">
                    Special Site Conditions

                </label>
            </span>
            <span class="rightcol">

                <?php
                if (trim($node->field_special_site_conditions['und'][0]['value']) != '') {
                    print $node->field_special_site_conditions['und'][0]['value'];
                }
                else
                {
                    print 'N/A';
                }?>

            </span>
        </div>


        <div class="row-container">
            <span  class="leftcol">
                <label id="lbltradereq" value="Trades Required">
                    Trades Requirement

                </label>
            </span>
            <span class="rightcol">


                <?php
                if (trim($node->field_trades_required['und'][0]['value']) != '') {
                    print $node->field_trades_required['und'][0]['value'];
                }
                else
                {
                    print 'N/A';
                }?>
            </span>
        </div>

        <div class="row-container">
            <span  class="leftcol">
                <label id="lblsection3" value="Section 3 Certifications">
                    Do Section 3 Requirements Apply?

                </label>
            </span>


              <span class="rightcol">
                  <?php if ($node->field_section_3_requirements['und'][0]['value'] == "1")
                  {?>
                      <div>
                          Yes
                      </div>
                     <?php

                      if (trim($node->field_specify_section_3_certific['und'][0]['value']) != '') {
                          print $node->field_specify_section_3_certific['und'][0]['value'];
                      }
                      else
                      {
                          print 'N/A';
                      }

                  }
                    else
                        {
                            ?>
                        <div>
                            No
                        </div>
                        <?php } ?>
                  </span>

        </div>

        <div class="row-container">
            <span  class="leftcol">
                <label id="lblmwdbe" value="Minority Women requirement">
                    Do Minority, Women and Disadvantaged Business Enterprise (M/W/DBE) Requirements Apply?
                </label>
            </span>


              <span class="rightcol">
                  <?php if ($node->field_m_w_dbe_requirements_apply['und'][0]['value'] == "1")
                  {?>
                      <div>
                          Yes
                      </div>
                      <?php

                      if (trim($node->field_specify_m_w_dbe_certificat['und'][0]['value']) != '') {
                          print $node->field_specify_m_w_dbe_certificat['und'][0]['value'];
                      }
                      else
                      {
                          print 'N/A';
                      }
                  }
                  else
                  {
                      ?>
                      <div>
                          No
                      </div>
                  <?php } ?>
                  </span>

        </div>

     </div>

    <?php if (!empty($content['last_date_modified'])): ?>
        <?php print render($content['last_date_modified']); ?>
    <?php endif; ?>
</div>