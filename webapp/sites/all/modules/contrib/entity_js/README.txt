Entity JS
--------------------------
This module provides JavaScript access
to some common Entity API functions.

//Create a new Entity using entity type and values
entity_create(entity_type, values);

//Update an existing Entity using entity type, unique
//Entity ID and an array of updated values
entity_update(entity_type, entity_id, values);

//Delete an existing Entity by providing type and ID
entity_delete(entity_type, entity_id);

//Return a fully rendered entity using a view mode
entity_render_view(entity_type, entity_id, view_mode);

//Return entity object as JSON object
entity_load_json(entity_type, entity_id);

//Return results of an EntityFieldQuery as JSON object
entity_field_query_json($conditions = array());

//Return results of an EntityFieldQuery using extra
//argument entity_load to return full entity objects

Note: This module requires advanced permissions.
-- Core bundle permissions are not used by Entity JS.

/////////-/////////
USE WITH CAUTION!!!
\\\\\\\\\-\\\\\\\\\
