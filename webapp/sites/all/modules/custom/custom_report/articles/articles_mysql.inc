<?php

/**
 * progrmas mysql queries
 */
 function _articles_mysql() {
  $query = db_select('node', 'n');
  $query->leftjoin('field_data_field_article_topic', 't', 'n.nid = t.entity_id');
  $query->leftjoin('field_data_field_article_url', 'at', 'n.nid = at.entity_id');
  $query->leftjoin('field_data_field_article_detail_desc', 'd', 'n.nid = d.entity_id');
  $query->fields('n', array('nid', 'title'))
        ->fields('t', array('field_article_topic_value'))
        ->fields('at', array('field_article_url_url'))
        ->fields('d', array('field_article_detail_desc_value'))
        ->condition('n.type', 'article', '=')
        ->condition(('n.status'), '1', '=')
        ->range();
  $results = $query->execute()->fetchAll();
  return $results;
 }
 