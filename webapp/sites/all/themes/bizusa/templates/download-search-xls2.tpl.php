<?php 

    if ( empty($_POST['params_serialized']) ) {
        print 'Error - There is no params_serialized parameter in POST';
        exit();
    } else {
    
        $path = libraries_get_path('PHPExcel');
        require_once($path.'/PHPExcel.php');
        ini_set('memory_limit', '2G');

        $response = _solrToExcel_QuerySolrServer(); // The function _solrToExcel_QuerySolrServer() is defined in this file below

        /* Now that we have the responce from Solr, build the Excel file */
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setTitle('Search Results');
        $objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,1,'Resource');
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,1,'Title');
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,1,'Description');
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,1,'Organization');
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4,1,'POC Name');
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5,1,'POC Organization');
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6,1,'POC Email');
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7,1,'POC Phone');
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8,1,'Learn More URL');

        // Freeze pane so that the heading line won't scroll
        $objPHPExcel->getActiveSheet()->freezePane('A2');

        $row = 2;
        unset($data);
        $data = json_decode($response->data);
      
        foreach($data->response->docs as $doc) {

            $node = node_load($doc->entity_id);	

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,$row,htmlspecialchars_decode($doc->bundle_name, ENT_QUOTES));   
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,$row,htmlspecialchars_decode($doc->label, ENT_QUOTES)); 
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,$row,htmlspecialchars_decode($node->field_program_org_tht_owns_prog['und'][0]['value'], ENT_QUOTES));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4,$row,htmlspecialchars_decode($node->field_program_public_poc_name['und'][0]['value'], ENT_QUOTES));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5,$row,htmlspecialchars_decode($node->field_program_public_poc_org['und'][0]['value'], ENT_QUOTES));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6,$row,htmlspecialchars_decode($node->field_program_public_poc_email['und'][0]['value'], ENT_QUOTES));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7,$row,htmlspecialchars_decode($node->field_program_public_poc_phone['und'][0]['value'], ENT_QUOTES));

            if($doc->bundle == "state_resource"){
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,$row,htmlspecialchars_decode(trim($node->body['und'][0]['value']), ENT_QUOTES));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8,$row,htmlspecialchars_decode($node->field_state_resource_link['und'][0]['url'], ENT_QUOTES));
            }
            elseif($doc->bundle == "blog"){
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,$row,htmlspecialchars_decode(trim($node->field_blog_text['und'][0]['value']), ENT_QUOTES));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8,$row,htmlspecialchars_decode($node->field_blog_url['und'][0]['url'], ENT_QUOTES));
            }
            elseif($doc->bundle == "program") {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,$row,htmlspecialchars_decode(trim($node->field_program_detail_desc['und'][0]['value']), ENT_QUOTES));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8,$row,htmlspecialchars_decode($node->field_program_ext_url['und'][0]['url'], ENT_QUOTES));
            }
            elseif($doc->bundle == "data") {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,$row,htmlspecialchars_decode(trim($node->field_data_detail_desc['und'][0]['value']), ENT_QUOTES));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8,$row,htmlspecialchars_decode($node->field_data_ext_url['und'][0]['url'], ENT_QUOTES));
            }
            elseif($doc->bundle == "event") {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,$row,htmlspecialchars_decode(trim($node->field_event_detail_desc['und'][0]['value']), ENT_QUOTES));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8,$row,htmlspecialchars_decode($node->field_event_url['und'][0]['url'], ENT_QUOTES));
            }
            elseif($doc->bundle == "news") {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,$row,htmlspecialchars_decode(trim($node->field_news_detailed_text['und'][0]['value']), ENT_QUOTES));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8,$row,htmlspecialchars_decode($node->field_news_url['und'][0]['url'], ENT_QUOTES));
            }
            elseif($doc->bundle == "rules") {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,$row,htmlspecialchars_decode(trim($node->field_rules_abstract['und'][0]['value']), ENT_QUOTES));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8,$row,htmlspecialchars_decode($node->field_rule_url['und'][0]['url'], ENT_QUOTES));
            }
            elseif($doc->bundle == "services") {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,$row,htmlspecialchars_decode(trim($node->field_services_detail_desc['und'][0]['value']), ENT_QUOTES));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8,$row,htmlspecialchars_decode($node->field_services_ext_url['und'][0]['url'], ENT_QUOTES));
            }
            elseif($doc->bundle == "success_story") {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,$row,htmlspecialchars_decode(trim($node->field_ss_detailed_text['und'][0]['value']), ENT_QUOTES));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8,$row,htmlspecialchars_decode($node->field_ss_url['und'][0]['url'], ENT_QUOTES));
            }
            elseif($doc->bundle == "tools") {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,$row,htmlspecialchars_decode(trim($node->field_tools_detail_text['und'][0]['value']), ENT_QUOTES));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8,$row,htmlspecialchars_decode($node->field_tools_url['und'][0]['url'], ENT_QUOTES));
            }
            elseif($doc->bundle == "video") {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,$row,htmlspecialchars_decode(trim($node->field_video_detail_text['und'][0]['value']), ENT_QUOTES));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8,$row,htmlspecialchars_decode($node->field_video_url['und'][0]['url'], ENT_QUOTES));
            }
            elseif($doc->bundle == "article") {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,$row,htmlspecialchars_decode(trim($node->field_article_detail_desc['und'][0]['value']), ENT_QUOTES));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8,$row,htmlspecialchars_decode($node->field_article_url['und'][0]['url'], ENT_QUOTES));
            }
            elseif($doc->bundle == "quick_facts") {
                if ( $node->field_qf_display_hp['und'][0]['value'] == 1) {
                    $text = str_replace('   Yes              ', '', $node->field_qf_desc['und'][0]['value']);
                }
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,$row,htmlspecialchars_decode(trim($text), ENT_QUOTES));
            }
            elseif($doc->bundle == "solicitations") {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,$row,htmlspecialchars_decode(trim($node->field_presol_desc['und'][0]['value']), ENT_QUOTES));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,$row,htmlspecialchars_decode($node->field_grants_agency_solr_sorting['und'][0]['value'], ENT_QUOTES));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8,$row,htmlspecialchars_decode($node->field_presol_link['und'][0]['url'], ENT_QUOTES));
            }
            elseif($doc->bundle == "challenges"){
                //$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,$row,htmlspecialchars_decode(trim($node->field_presol_desc['und'][0]['value']), ENT_QUOTES));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,$row,htmlspecialchars_decode($node->field_grants_agency_solr_sorting['und'][0]['value'], ENT_QUOTES));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8,$row,htmlspecialchars_decode($node->field_challenge_url['und'][0]['url'], ENT_QUOTES));
            }
            elseif($doc->bundle == "grants") {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,$row,htmlspecialchars_decode(trim($node->field_grants_body['und'][0]['value']), ENT_QUOTES));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,$row,htmlspecialchars_decode($node->field_grants_agency_solr_sorting['und'][0]['value'], ENT_QUOTES));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8,$row,htmlspecialchars_decode($node->field_grants_link['und'][0]['url'], ENT_QUOTES));
            }
            elseif($doc->bundle == "patent") {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,$row,htmlspecialchars_decode(trim($node->body['und'][0]['value']), ENT_QUOTES));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,$row,htmlspecialchars_decode($node->field_agency_solr_sorting['und'][0]['value'], ENT_QUOTES));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8,$row,htmlspecialchars_decode($node->field_patent_purl_url['und'][0]['url'], ENT_QUOTES));
            }	

            $row++; 
        }
      
        // Save as an Excel BIFF (xls) file
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $savePath = 'sites/default/files/BusinessUSA Search Results - ' . time() . '.xls';
        $objWriter->save($savePath);

        ob_end_clean();
        ob_end_clean();
        ob_end_clean();
        header('Cache-Control: max-age=0');
        header("Location: /$savePath"); 
        exit();
    }
    
    
/**  ?? _solrToExcel_QuerySolrServer()
  *  This function will look at $_GET (and $_POST data (via $_REQUEST)) and build the nessesary
  *  Solr-query to hit the Solr-search-server with, and return Solr's responce.
  */
function _solrToExcel_QuerySolrServer(){
        
        $solr = apachesolr_get_solr();
        $solrsort = '';
        
        $params = unserialize( $_POST['params_serialized'] );
        $selectedOption = $_POST['dc'];

        if($selectedOption == 'range'){
            $pageFrom = intval($_POST['rangefrom']);
            $pageTo = intval($_POST['rangeto']);

            $params["start"] = (10 * ($pageFrom - 1));
            $params["rows"] = (10 * (($pageTo - $pageFrom) + 1));
        }else if(strpos($selectedOption, '_')){
            $pageNumber = intval(str_replace('current_', '', $selectedOption));

            $params["start"] = ($pageNumber >= 1)? ($pageNumber*10) : 0;
            $params["rows"] = 10;
        }else{
            $params["start"] = 0;
            $params["rows"] = intval($selectedOption);
        }
        $query1 = apachesolr_drupal_query('apachesolr', $params);
        apachesolr_search_add_boost_params($query1);
     
        $response = $query1->search();
        
        return $response;

}

    
?>