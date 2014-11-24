<?php


/**
 * Implements hook_views_query_alter().
 */
hooks_reaction_add("views_query_alter",
    function (&$view, &$query) {

        // Only do this for the state_resource_view View
        if ( $view->name === 'state_resource_view' ) {

            // Only do this for the state_resource_wizardresults_program Display in this View
            if ( $view->current_display === 'state_resource_wizardresults_program' ) {
                // dsm($view);
                //  dsm($query);

                // Only do this is an argument was passed
                if ( !empty($view->args[0]) ) {

                    //  dsm(“%” . $view->args[0] . “%”);
                  //  dsm($view);

                    $tax = ucfirst($view->args[1]);
                    $tax1= ucfirst($view->args[2]);
                    $vocabulary = taxonomy_vocabulary_machine_name_load('user_submittted_tags');
                    $tree = taxonomy_get_tree($vocabulary->vid);

                    $termid = array();

                    foreach ($tree as $term) {
                        if ($term->name ==$tax) {
                            array_push($termid, $term->tid);

                        }
                        else if($term->name ==$tax1) {
                            //$termid = $term->tid;
                            array_push($termid, $term->tid);
                        }
                    }

                    $query-> where[2]['conditions'][0]['value'] =  $view->args[0];
                    $query-> where[2]['conditions'][1]['value'] = array($view->args[1]);
                    $query-> where[2]['conditions'][2]['value'] = array($termid);


                    $query->add_field('field_data_field_program_owner_share', 'field_program_owner_share_value');
                    $query->table_queue['field_data_field_program_owner_share']['join']->type = 'LEFT';

                    $query->add_field('field_data_field_tagged_terms', 'field_tagged_terms_tid');
                    $query->table_queue['field_data_field_tagged_terms']['join']->type = 'LEFT';

                    /* code here */

                }

            }
            // Only do this for the state_resource_service Display in this View
            else if ( $view->current_display === 'state_resource_service' ) {
                // dsm($view);
                //  dsm($query);

                // Only do this is an argument was passed
                if ( !empty($view->args[0]) ) {

                    //  dsm(“%” . $view->args[0] . “%”);


                    $tax = ucfirst($view->args[3]);
                    $tax1 = ucfirst($view->args[4]);
                    $tax2 = ucfirst($view->args[5]);

                    $vocabulary = taxonomy_vocabulary_machine_name_load('user_submittted_tags');
                    $tree = taxonomy_get_tree($vocabulary->vid);

                    $termid = array();

                    foreach ($tree as $term) {
                        if ($term->name ==$tax) {
                            //$termid = $term->tid;
                            array_push($termid, $term->tid);
                        }
                        else if($term->name ==$tax1) {
                            //$termid = $term->tid;
                            array_push($termid, $term->tid);
                        }
                        else if($term->name ==$tax2) {
                            //$termid = $term->tid;
                            array_push($termid, $term->tid);
                        }

                    }


                    $query-> where[2]['conditions'][0]['value'] =  $view->args[0];
                    $query-> where[2]['conditions'][1]['value'] = array($view->args[1]);
                    $query-> where[2]['conditions'][2]['value'] = array($view->args[2]);
                    $query-> where[2]['conditions'][3]['value'] = array($termid);

                    $query->add_field('field_data_field_program_owner_share', 'field_program_owner_share_value');
                    $query->table_queue['field_data_field_program_owner_share']['join']->type = 'LEFT';


                    $query->add_field('field_data_field_tagged_terms', 'field_tagged_terms_tid');
                    $query->table_queue['field_data_field_tagged_terms']['join']->type = 'LEFT';

                    /* code here */

                }

            }
            // Only do this for the state_resource_ownership_tools Display in this View
            else if ( $view->current_display === 'state_resource_ownership_tools' ) {
                // dsm($view);
                //  dsm($query);

                // Only do this is an argument was passed
                if ( !empty($view->args[0]) ) {

                    //  dsm(“%” . $view->args[0] . “%”);


                    $tax = ucfirst($view->args[2]);
                    $tax1 = ucfirst($view->args[3]);
                    $tax2 = ucfirst($view->args[4]);

                    $vocabulary = taxonomy_vocabulary_machine_name_load('user_submittted_tags');
                    $tree = taxonomy_get_tree($vocabulary->vid);

                    $termid = array();
                    if ( !empty($view->args[4]) ) {

                    foreach ($tree as $term) {
                        if ($term->name ==$tax) {
                            //$termid = $term->tid;
                            array_push($termid, $term->tid);
                        }
                        else if($term->name ==$tax1) {
                            //$termid = $term->tid;
                            array_push($termid, $term->tid);
                        }
                        else if($term->name ==$tax2) {
                            //$termid = $term->tid;
                            array_push($termid, $term->tid);
                        }

                    }
                    }
                    else
                    {
                        foreach ($tree as $term) {
                            if ($term->name ==$tax) {
                                // $termid = $term->tid;
                                array_push($termid, $term->tid);

                            }
                            else if($term->name ==$tax1) {
                                //$termid = $term->tid;
                                array_push($termid, $term->tid);
                            }
                        }
                    }



                    $query-> where[2]['conditions'][0]['value'] =  $view->args[0];
                    $query-> where[2]['conditions'][1]['value'] = array($view->args[1]);
                    $query-> where[2]['conditions'][2]['value'] = array($termid);


                    $query->add_field('field_data_field_program_owner_share', 'field_program_owner_share_value');
                    $query->table_queue['field_data_field_program_owner_share']['join']->type = 'LEFT';

                    $query->add_field('field_data_field_tagged_terms', 'field_tagged_terms_tid');
                    $query->table_queue['field_data_field_tagged_terms']['join']->type = 'LEFT';

                    dsm($query);

                    /* code here */

                }

            }

            // Only do this for the state_resource_wizardresults_service Display in this View
            else if ( $view->current_display === 'state_resource_wizardresults_service' ) {
               // dsm('hello');
                if ( !empty($view->args[0]) ) {

                   //dsm(“%” . $view->args[0] . “%”);

                    $tax = ucfirst($view->args[1]);
                    $tax1= ucfirst($view->args[2]);
                    $vocabulary = taxonomy_vocabulary_machine_name_load('user_submittted_tags');
                    $tree = taxonomy_get_tree($vocabulary->vid);

                    $termid = array();

                    foreach ($tree as $term) {
                        if ($term->name ==$tax) {
                           // $termid = $term->tid;
                            array_push($termid, $term->tid);

                        }
                        else if($term->name ==$tax1) {
                            //$termid = $term->tid;
                            array_push($termid, $term->tid);
                        }
                    }

                    $query-> where[2]['conditions'][0]['value'] =  $view->args[0];
                    $query-> where[2]['conditions'][1]['value'] = array( $view->args[1] );
                    $query-> where[2]['conditions'][2]['value'] = array($termid);

                    $query->add_field('field_data_field_program_owner_share', 'field_program_owner_share_value');
                    $query->table_queue['field_data_field_program_owner_share']['join']->type = 'LEFT';

                    $query->add_field('field_data_field_tagged_terms', 'field_tagged_terms_tid');
                    $query->table_queue['field_data_field_tagged_terms']['join']->type = 'LEFT';


                    /* code here */

                }
            }
            // Only do this for the state_resource_program Display in this View
            else   if ( $view->current_display === 'state_resource_program' ) {
                if ( !empty($view->args[0]) ) {
                    // dsm(“%” . $view->args[0] . “%”);
                    //dsm($view);
                    //dsm($query);
                    $tax = ucfirst($view->args[3]);
                    $tax1 = ucfirst($view->args[4]);
                    $tax2 = ucfirst($view->args[5]);
                    $tax3 = ucfirst($view->args[6]);
                    $vocabulary = taxonomy_vocabulary_machine_name_load('user_submittted_tags');
                    $tree = taxonomy_get_tree($vocabulary->vid);

                    $termid = array();

                    foreach ($tree as $term) {
                        if ($term->name ==$tax) {
                            //$termid = $term->tid;
                            array_push($termid, $term->tid);
                        }
                        else if($term->name ==$tax1) {
                            //$termid = $term->tid;
                            array_push($termid, $term->tid);
                        }
                        else if($term->name ==$tax2) {
                            //$termid = $term->tid;
                            array_push($termid, $term->tid);
                        }
                        else if($term->name ==$tax3) {
                            //$termid = $term->tid;
                            array_push($termid, $term->tid);
                        }
                    }

                    $query-> where[2]['conditions'][0]['value'] =$view->args[0];
                    $query-> where[2]['conditions'][1]['value'] =$view->args[1];
                    $query-> where[2]['conditions'][2]['value'] =array($view->args[2]);
                    $query-> where[2]['conditions'][3]['value'] = array($termid);

                    $query->add_field('field_data_field_program_owner_share', 'field_program_owner_share_value');
                    $query->table_queue['field_data_field_program_owner_share']['join']->type = 'LEFT';

                    $query->add_field('field_data_field_tagged_terms', 'field_tagged_terms_tid');
                    $query->table_queue['field_data_field_tagged_terms']['join']->type = 'LEFT';


                    /* code here */
                }
            }
            // Only do this for the ownership_resource_center Display in this View
            else if ( $view->current_display === 'ownership_resource_center' ) {
                if ( !empty($view->args[0]) ) {
                    // dsm(“%” . $view->args[0] . “%”);
                   //dsm($view);

                    $query-> where[1]['conditions'][2]['value'] = array($view->args[0]);
                    $query-> where[1]['conditions'][3]['value'] = array($view->args[1]);
                     if(!empty($view->args[3]))
                    {
                        $query-> where[1]['conditions'][2]['value'] = array($view->args[0], $view->args[1], $view->args[2]);
                        $query-> where[1]['conditions'][3]['value'] = array($view->args[3]);

                    }

                    else if(!empty($view->args[2]))
                    {
                        $query-> where[1]['conditions'][2]['value'] = array($view->args[0], $view->args[1]);
                        $query-> where[1]['conditions'][3]['value'] = array($view->args[2]);

                    }




                    /* code here */
                }
            }

            // Only do this for the state_resource_all_content Display in this View
            else if ( $view->current_display === 'state_resource_all_content' ) {
                if ( !empty($view->args[0]) ) {



                    $tax = ucfirst($view->args[1]);
                    $vocabulary = taxonomy_vocabulary_machine_name_load('user_submittted_tags');
                    $tree = taxonomy_get_tree($vocabulary->vid);

                    $termid = '';

                    foreach ($tree as $term) {
                        if ($term->name ==$tax) {
                            $termid = $term->tid;

                        }
                    }


                    $query-> where[2]['conditions'][0]['value'] =  $view->args[0];
                    //$query-> where[2]['conditions'][1]['value'] = array( $view->args[1] );
                    $query-> where[2]['conditions'][1]['value'] = array($termid);
                    $query-> where[2]['conditions'][2]['value'] = array($view->args[2]);
                    $query -> where[2]['conditions'][3]['value'] = array($view->args[2]);
                    $query -> where[2]['conditions'][4]['value'] = array($view->args[1]);

                    //$query-> where[1]['conditions'][2]['value'] = array($view->args[0]);
                    //$query-> where[1]['conditions'][3]['value'] = array($view->args[1]);

                    $query->add_field('field_data_field_tagged_terms', 'field_tagged_terms_tid');
                    $query->table_queue['field_data_field_tagged_terms']['join']->type = 'LEFT';


                    $query->add_field('field_data_field_program_loc', 'field_program_loc_value');
                    $query->table_queue['field_data_field_program_loc']['join']->type = 'LEFT';

                    $query->add_field('field_data_field_tools_state', 'field_tools_state_value');
                    $query->table_queue['field_data_field_tools_state']['join']->type = 'LEFT';

                    $query->add_field('field_data_field_state_resource_state', 'field_state_resource_state_value');
                    $query->table_queue['field_data_field_state_resource_state']['join']->type = 'LEFT';






                    /* code here */
                }
            }
              // Only do this for the state_resource_program_tag Display in this View
            else  if ( $view->current_display === 'state_resource_program_tag' ) {
                    //  dsm($view);
                    //   dsm($query);

                    // Only do this is an argument was passed
                    if ( !empty($view->args[0]) ) {

                        //  dsm(“%” . $view->args[0] . “%”);
                        $tax = ucfirst($view->args[0]);
                        $vocabulary = taxonomy_vocabulary_machine_name_load('user_submittted_tags');
                        $tree = taxonomy_get_tree($vocabulary->vid);

                        $termid = '';

                        foreach ($tree as $term) {
                            if ($term->name ==$tax) {
                                $termid = $term->tid;

                            }
                        }
                        $query->where[2]['conditions'][0]['value'] =  $termid;
                        $query->where[2]['conditions'][1]['value']= array( $view->args[1] );

                    }

                }
            // Only do this for the state_resource_service_tag Display in this View
            else  if ( $view->current_display === 'state_resource_service_tag' ) {
                //  dsm($view);
                //   dsm($query);

                // Only do this is an argument was passed
                if ( !empty($view->args[0]) ) {

                    //  dsm(“%” . $view->args[0] . “%”);
                    $tax = ucfirst($view->args[0]);
                    $vocabulary = taxonomy_vocabulary_machine_name_load('user_submittted_tags');
                    $tree = taxonomy_get_tree($vocabulary->vid);

                    $termid = '';

                    foreach ($tree as $term) {
                        if ($term->name ==$tax) {
                            $termid = $term->tid;

                        }
                    }
                    $query->where[2]['conditions'][0]['value'] =  $termid;
                    $query->where[2]['conditions'][1]['value']= array( $view->args[1] );

                }

            }
            // Only do this for the state_resource_tools_tag Display in this View
            else  if ( $view->current_display === 'state_resource_tools_tag' ) {
                //  dsm($view);
                //   dsm($query);

                // Only do this is an argument was passed
                if ( !empty($view->args[0]) ) {

                    //  dsm(“%” . $view->args[0] . “%”);
                    $tax = ucfirst($view->args[0]);
                    $vocabulary = taxonomy_vocabulary_machine_name_load('user_submittted_tags');
                    $tree = taxonomy_get_tree($vocabulary->vid);

                    $termid = '';

                    foreach ($tree as $term) {
                        if ($term->name ==$tax) {
                            $termid = $term->tid;

                        }
                    }
                    $query->where[2]['conditions'][0]['value'] =  $termid;
                    $query->where[2]['conditions'][1]['value']= array( $view->args[1] );

                }

            }
            else if ( $view->current_display === 'ownership_resource_all_content' ) {
                if ( !empty($view->args[0]) ) {



                    $tax = ucfirst($view->args[3]);
                    $tax1 = ucfirst($view->args[4]);


                    $vocabulary = taxonomy_vocabulary_machine_name_load('user_submittted_tags');
                    $tree = taxonomy_get_tree($vocabulary->vid);

                    $termid = array();

                    if (!empty($view->args[5]) ) {

                        $tax2 = ucfirst($view->args[5]);
                        $tax3 = ucfirst($view->args[6]);

                        foreach ($tree as $term) {
                            if ($term->name ==$tax) {
                                //$termid = $term->tid;
                                array_push($termid, $term->tid);
                            }
                            else if($term->name ==$tax1) {
                                //$termid = $term->tid;
                                array_push($termid, $term->tid);
                            }
                            else if($term->name ==$tax2) {
                                //$termid = $term->tid;
                                array_push($termid, $term->tid);
                            }
                            else if($term->name ==$tax3) {
                                //$termid = $term->tid;
                                array_push($termid, $term->tid);
                            }

                        }

                    }
                    else
                    {
                        foreach ($tree as $term) {
                            if ($term->name ==$tax) {
                                //$termid = $term->tid;
                                array_push($termid, $term->tid);
                            }
                            else if($term->name ==$tax1) {
                                //$termid = $term->tid;
                                array_push($termid, $term->tid);
                            }

                        }
                    }


                        $query-> where[2]['conditions'][0]['value'] =  $view->args[0];
                        $query-> where[2]['conditions'][1]['value'] = $view->args[1] ;
                        $query-> where[2]['conditions'][2]['value'] = array($view->args[2]);
                        $query-> where[2]['conditions'][3]['value'] = array($termid);

                        dsm($query);
                        dsm($view);
                        $query->add_field('field_data_field_tagged_terms', 'field_tagged_terms_tid');
                        $query->table_queue['field_data_field_tagged_terms']['join']->type = 'LEFT';


                        $query->add_field('field_data_field_program_owner_share', 'field_program_owner_share_value');
                        $query->table_queue['field_data_field_program_owner_share']['join']->type = 'LEFT';








                        /* code here */
                    }
                }
        }
    }
);

?>