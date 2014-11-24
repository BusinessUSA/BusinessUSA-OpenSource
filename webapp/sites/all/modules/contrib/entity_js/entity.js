/**
 * Function creates a new entity.
 */
function entity_create(entity_type, entity_values) {
  return (function ($) {
    return $.post(Drupal.settings.basePath + 'entity_js_create/' + entity_type, { values: entity_values });
  })(jQuery);
}

/**
 * Function returns a rendered entity as html.
 */
function entity_render_view(entity_type, entity_id, view_mode) {
  return (function ($) {
    return $.get(Drupal.settings.basePath + 'entity_js_drupal_render_entity_view/' + entity_type + '/' + entity_id + '/' + view_mode);
  })(jQuery);
}

/**
 * Function returns a loaded entity JSON.
 */
function entity_load_json(entity_type, entity_id) {
  return (function ($) {
    return $.get(Drupal.settings.basePath + 'entity_js_load_single_json/' + entity_type + '/' + entity_id);
  })(jQuery);
}

/**
 * Function updates an existing entity.
 */
function entity_update(entity_type, entity_id, entity_values) {
  return (function ($) {
    return $.post(Drupal.settings.basePath + 'entity_js_update/' + entity_type + '/' + entity_id, { values: entity_values });
  })(jQuery);
}

/**
 * Function deletes an existing entity.
 */
function entity_delete(entity_type, entity_id) {
  return (function ($) {
    return $.get(Drupal.settings.basePath + 'entity_js_delete/' + entity_type + '/' + entity_id);
  })(jQuery);
}

/**
 * Function returns an EntityFieldQuery.
 */
function entity_field_query_json(conditions, entity_load) {
  return (function ($) {
    if (entity_load === undefined) entity_load = false;
    return $.post(Drupal.settings.basePath + 'entity_js_efq_json', { efq: conditions, load: entity_load });
  })(jQuery);
}

