<?php 
//Returns your solr query with all reserved characters properly escaped
function solrEscapeQuery($query){
   $luceneReservedCharacters = preg_quote('+-&|!(){}[]^"~*?:\\ ');
   $query = preg_replace_callback(
    '/([' . $luceneReservedCharacters . '])/',
    function($matches) {
        return '\\' . $matches[0];
    },
    $query);
    return $query;
}

