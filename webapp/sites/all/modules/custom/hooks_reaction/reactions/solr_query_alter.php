<?php
/**
 * Created by PhpStorm.
 * User: sanjay.gupta
 * Date: 7/16/14
 * Time: 2:54 PM
 */



hooks_reaction_add("apachesolr_query_alter",
    function ($query)
    {

        $param = $query->getParams();
        $query->replaceParam(q, strtolower($param['q']));
        //$query->replaceParam(q, strtolower($query->getParams()['q']));
    });


?>