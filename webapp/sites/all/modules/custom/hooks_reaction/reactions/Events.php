<?php

hooks_reaction_add("node_insert",
    function ($node){
        if($node->type == "event"){
            apachesolr_mark_node($node->nid);
            apachesolr_cron();
        }

    }
);//end hooks reaction add
hooks_reaction_add("node_update",
    function ($node){
        if($node->type == "event"){
            apachesolr_mark_node($node->nid);
            apachesolr_cron();
        }

    }
);//end hooks reaction add

?>