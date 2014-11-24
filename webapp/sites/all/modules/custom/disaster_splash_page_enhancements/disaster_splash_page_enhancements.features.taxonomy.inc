<?php
/**
 * @file
 * disaster_splash_page_enhancements.features.taxonomy.inc
 */

/**
 * Implements hook_taxonomy_default_vocabularies().
 */
function disaster_splash_page_enhancements_taxonomy_default_vocabularies() {
  return array(
    'disaster_splash_link_types' => array(
      'name' => 'Disaster Splash Link Types',
      'machine_name' => 'disaster_splash_link_types',
      'description' => 'Link types/regions for the disaster splash page.',
      'hierarchy' => 0,
      'module' => 'taxonomy',
      'weight' => 0,
      'rdf_mapping' => array(
        'rdftype' => array(
          0 => 'skos:ConceptScheme',
        ),
        'name' => array(
          'predicates' => array(
            0 => 'dc:title',
          ),
        ),
        'description' => array(
          'predicates' => array(
            0 => 'rdfs:comment',
          ),
        ),
      ),
    ),
  );
}
