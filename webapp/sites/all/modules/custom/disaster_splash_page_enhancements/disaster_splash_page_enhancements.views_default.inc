<?php
/**
 * @file
 * disaster_splash_page_enhancements.views_default.inc
 */

/**
 * Implements hook_views_default_views().
 */
function disaster_splash_page_enhancements_views_default_views() {
  $export = array();

  $view = new view();
  $view->name = 'disaster_splash_featured_info';
  $view->description = 'Populates the links on the disaster splash page.';
  $view->tag = 'default';
  $view->base_table = 'node';
  $view->human_name = 'Disaster Splash Links';
  $view->core = 7;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: Master */
  $handler = $view->new_display('default', 'Master', 'default');
  $handler->display->display_options['title'] = 'Disaster Splash Featured Information';
  $handler->display->display_options['use_more_always'] = FALSE;
  $handler->display->display_options['access']['type'] = 'perm';
  $handler->display->display_options['cache']['type'] = 'none';
  $handler->display->display_options['query']['type'] = 'views_query';
  $handler->display->display_options['exposed_form']['type'] = 'basic';
  $handler->display->display_options['pager']['type'] = 'some';
  $handler->display->display_options['pager']['options']['items_per_page'] = '10';
  $handler->display->display_options['pager']['options']['offset'] = '0';
  $handler->display->display_options['style_plugin'] = 'default';
  $handler->display->display_options['row_plugin'] = 'fields';
  /* Field: Content: Title */
  $handler->display->display_options['fields']['title']['id'] = 'title';
  $handler->display->display_options['fields']['title']['table'] = 'node';
  $handler->display->display_options['fields']['title']['field'] = 'title';
  $handler->display->display_options['fields']['title']['label'] = '';
  $handler->display->display_options['fields']['title']['exclude'] = TRUE;
  $handler->display->display_options['fields']['title']['alter']['word_boundary'] = FALSE;
  $handler->display->display_options['fields']['title']['alter']['ellipsis'] = FALSE;
  $handler->display->display_options['fields']['title']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['title']['link_to_node'] = FALSE;
  /* Field: Content: Disaster Splash Link */
  $handler->display->display_options['fields']['field_disaster_splash_link']['id'] = 'field_disaster_splash_link';
  $handler->display->display_options['fields']['field_disaster_splash_link']['table'] = 'field_data_field_disaster_splash_link';
  $handler->display->display_options['fields']['field_disaster_splash_link']['field'] = 'field_disaster_splash_link';
  $handler->display->display_options['fields']['field_disaster_splash_link']['label'] = '';
  $handler->display->display_options['fields']['field_disaster_splash_link']['exclude'] = TRUE;
  $handler->display->display_options['fields']['field_disaster_splash_link']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['field_disaster_splash_link']['click_sort_column'] = 'url';
  $handler->display->display_options['fields']['field_disaster_splash_link']['type'] = 'link_plain';
  /* Field: Global: Custom text */
  $handler->display->display_options['fields']['nothing']['id'] = 'nothing';
  $handler->display->display_options['fields']['nothing']['table'] = 'views';
  $handler->display->display_options['fields']['nothing']['field'] = 'nothing';
  $handler->display->display_options['fields']['nothing']['label'] = '';
  $handler->display->display_options['fields']['nothing']['alter']['text'] = '<a href="[field_disaster_splash_link]" class="disaster-splash-featured-link">[title]</a>';
  $handler->display->display_options['fields']['nothing']['element_label_colon'] = FALSE;
  /* Sort criterion: Content: Title */
  $handler->display->display_options['sorts']['title']['id'] = 'title';
  $handler->display->display_options['sorts']['title']['table'] = 'node';
  $handler->display->display_options['sorts']['title']['field'] = 'title';
  $handler->display->display_options['sorts']['title']['order'] = 'DESC';
  /* Filter criterion: Content: Published */
  $handler->display->display_options['filters']['status']['id'] = 'status';
  $handler->display->display_options['filters']['status']['table'] = 'node';
  $handler->display->display_options['filters']['status']['field'] = 'status';
  $handler->display->display_options['filters']['status']['value'] = 1;
  $handler->display->display_options['filters']['status']['group'] = 1;
  $handler->display->display_options['filters']['status']['expose']['operator'] = FALSE;
  /* Filter criterion: Content: Type */
  $handler->display->display_options['filters']['type']['id'] = 'type';
  $handler->display->display_options['filters']['type']['table'] = 'node';
  $handler->display->display_options['filters']['type']['field'] = 'type';
  $handler->display->display_options['filters']['type']['value'] = array(
    'disaster_splash_link' => 'disaster_splash_link',
  );
  /* Filter criterion: Content: Disaster Splash Link Region (field_disaster_splash_link_regio) */
  $handler->display->display_options['filters']['field_disaster_splash_link_regio_tid']['id'] = 'field_disaster_splash_link_regio_tid';
  $handler->display->display_options['filters']['field_disaster_splash_link_regio_tid']['table'] = 'field_data_field_disaster_splash_link_regio';
  $handler->display->display_options['filters']['field_disaster_splash_link_regio_tid']['field'] = 'field_disaster_splash_link_regio_tid';
  $handler->display->display_options['filters']['field_disaster_splash_link_regio_tid']['operator'] = 'and';
  $handler->display->display_options['filters']['field_disaster_splash_link_regio_tid']['value'] = array(
    0 => '205',
  );
  $handler->display->display_options['filters']['field_disaster_splash_link_regio_tid']['vocabulary'] = 'disaster_splash_link_types';

  /* Display: Featured Information */
  $handler = $view->new_display('block', 'Featured Information', 'featured_info');
  $handler->display->display_options['display_description'] = 'Populates the links in the featured information section of the disaster splash page.';

  /* Display: Information Maps */
  $handler = $view->new_display('block', 'Information Maps', 'info_maps');
  $handler->display->display_options['display_description'] = 'Populates the links in the information maps section of the disaster splash page.';
  $handler->display->display_options['defaults']['filter_groups'] = FALSE;
  $handler->display->display_options['defaults']['filters'] = FALSE;
  /* Filter criterion: Content: Published */
  $handler->display->display_options['filters']['status']['id'] = 'status';
  $handler->display->display_options['filters']['status']['table'] = 'node';
  $handler->display->display_options['filters']['status']['field'] = 'status';
  $handler->display->display_options['filters']['status']['value'] = 1;
  $handler->display->display_options['filters']['status']['group'] = 1;
  $handler->display->display_options['filters']['status']['expose']['operator'] = FALSE;
  /* Filter criterion: Content: Type */
  $handler->display->display_options['filters']['type']['id'] = 'type';
  $handler->display->display_options['filters']['type']['table'] = 'node';
  $handler->display->display_options['filters']['type']['field'] = 'type';
  $handler->display->display_options['filters']['type']['value'] = array(
    'disaster_splash_link' => 'disaster_splash_link',
  );
  /* Filter criterion: Content: Disaster Splash Link Region (field_disaster_splash_link_regio) */
  $handler->display->display_options['filters']['field_disaster_splash_link_regio_tid']['id'] = 'field_disaster_splash_link_regio_tid';
  $handler->display->display_options['filters']['field_disaster_splash_link_regio_tid']['table'] = 'field_data_field_disaster_splash_link_regio';
  $handler->display->display_options['filters']['field_disaster_splash_link_regio_tid']['field'] = 'field_disaster_splash_link_regio_tid';
  $handler->display->display_options['filters']['field_disaster_splash_link_regio_tid']['operator'] = 'and';
  $handler->display->display_options['filters']['field_disaster_splash_link_regio_tid']['value'] = array(
    0 => '206',
  );
  $handler->display->display_options['filters']['field_disaster_splash_link_regio_tid']['vocabulary'] = 'disaster_splash_link_types';
  $export['disaster_splash_featured_info'] = $view;

  $view = new view();
  $view->name = 'fema_disaster_declarations';
  $view->description = '';
  $view->tag = 'default';
  $view->base_table = 'node';
  $view->human_name = 'FEMA Disaster Declarations';
  $view->core = 7;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: Master */
  $handler = $view->new_display('default', 'Master', 'default');
  $handler->display->display_options['title'] = 'FEMA Disaster Declarations';
  $handler->display->display_options['use_ajax'] = TRUE;
  $handler->display->display_options['use_more_always'] = FALSE;
  $handler->display->display_options['access']['type'] = 'perm';
  $handler->display->display_options['cache']['type'] = 'none';
  $handler->display->display_options['query']['type'] = 'views_query';
  $handler->display->display_options['exposed_form']['type'] = 'basic';
  $handler->display->display_options['pager']['type'] = 'full';
  $handler->display->display_options['pager']['options']['items_per_page'] = '5';
  $handler->display->display_options['pager']['options']['offset'] = '0';
  $handler->display->display_options['pager']['options']['id'] = '0';
  $handler->display->display_options['pager']['options']['quantity'] = '9';
  $handler->display->display_options['style_plugin'] = 'default';
  $handler->display->display_options['row_plugin'] = 'fields';
  /* Field: Content: Title */
  $handler->display->display_options['fields']['title']['id'] = 'title';
  $handler->display->display_options['fields']['title']['table'] = 'node';
  $handler->display->display_options['fields']['title']['field'] = 'title';
  $handler->display->display_options['fields']['title']['label'] = '';
  $handler->display->display_options['fields']['title']['exclude'] = TRUE;
  $handler->display->display_options['fields']['title']['alter']['word_boundary'] = FALSE;
  $handler->display->display_options['fields']['title']['alter']['ellipsis'] = FALSE;
  $handler->display->display_options['fields']['title']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['title']['link_to_node'] = FALSE;
  /* Field: Content: FEMA Disaster Link */
  $handler->display->display_options['fields']['field_fema_disaster_link']['id'] = 'field_fema_disaster_link';
  $handler->display->display_options['fields']['field_fema_disaster_link']['table'] = 'field_data_field_fema_disaster_link';
  $handler->display->display_options['fields']['field_fema_disaster_link']['field'] = 'field_fema_disaster_link';
  $handler->display->display_options['fields']['field_fema_disaster_link']['label'] = '';
  $handler->display->display_options['fields']['field_fema_disaster_link']['exclude'] = TRUE;
  $handler->display->display_options['fields']['field_fema_disaster_link']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['field_fema_disaster_link']['click_sort_column'] = 'url';
  $handler->display->display_options['fields']['field_fema_disaster_link']['type'] = 'link_plain';
  /* Field: Global: Custom text */
  $handler->display->display_options['fields']['nothing']['id'] = 'nothing';
  $handler->display->display_options['fields']['nothing']['table'] = 'views';
  $handler->display->display_options['fields']['nothing']['field'] = 'nothing';
  $handler->display->display_options['fields']['nothing']['label'] = '';
  $handler->display->display_options['fields']['nothing']['alter']['text'] = '<a href="[field_fema_disaster_link]" class="fema-disaster-link">[title]</a>';
  $handler->display->display_options['fields']['nothing']['element_label_colon'] = FALSE;
  /* Sort criterion: Content: FEMA Disaster Date Published (field_fema_disaster_date_publish) */
  $handler->display->display_options['sorts']['field_fema_disaster_date_publish_value']['id'] = 'field_fema_disaster_date_publish_value';
  $handler->display->display_options['sorts']['field_fema_disaster_date_publish_value']['table'] = 'field_data_field_fema_disaster_date_publish';
  $handler->display->display_options['sorts']['field_fema_disaster_date_publish_value']['field'] = 'field_fema_disaster_date_publish_value';
  $handler->display->display_options['sorts']['field_fema_disaster_date_publish_value']['order'] = 'DESC';
  /* Sort criterion: Content: Post date */
  $handler->display->display_options['sorts']['created']['id'] = 'created';
  $handler->display->display_options['sorts']['created']['table'] = 'node';
  $handler->display->display_options['sorts']['created']['field'] = 'created';
  $handler->display->display_options['sorts']['created']['order'] = 'DESC';
  /* Filter criterion: Content: Published */
  $handler->display->display_options['filters']['status']['id'] = 'status';
  $handler->display->display_options['filters']['status']['table'] = 'node';
  $handler->display->display_options['filters']['status']['field'] = 'status';
  $handler->display->display_options['filters']['status']['value'] = 1;
  $handler->display->display_options['filters']['status']['group'] = 1;
  $handler->display->display_options['filters']['status']['expose']['operator'] = FALSE;
  /* Filter criterion: Content: Type */
  $handler->display->display_options['filters']['type']['id'] = 'type';
  $handler->display->display_options['filters']['type']['table'] = 'node';
  $handler->display->display_options['filters']['type']['field'] = 'type';
  $handler->display->display_options['filters']['type']['value'] = array(
    'fema_disaster' => 'fema_disaster',
  );

  /* Display: Block */
  $handler = $view->new_display('block', 'Block', 'block');
  $export['fema_disaster_declarations'] = $view;

  return $export;
}
