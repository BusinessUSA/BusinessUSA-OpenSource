<?php
global $user;

if ( empty($user) ) {
print "<b>You must be signed in as an administrator in order to run this functionality</b>";
exit;
} else {
$arrUser = (array) $user;
if ( empty($arrUser['name']) || $arrUser['name'] !== 'reisysbizuser' ) {
print "<b>You must be signed in as an administrator in order to run this functionality</b>";
exit;
}
}
?>

<head>
    <script type="text/javascript" src="../sites/all/themes/bizusa/scripts/paging.js"></script>
    <style type="text/css">
        .pg-normal {
            color: black;
            font-weight: normal;
            text-decoration: none;
            cursor: pointer;
        }
        .pg-selected {
            color: black;
            font-weight: bold;
            text-decoration: underline;
            cursor: pointer;
        }
    </style>
</head>

<?php




if ( !empty($_GET['nid']) ) {


    if ( empty($_GET['tid']) ) {
        print 'You forgot to supply a tid (Term ID)';
        return;
    }

    if ( empty($_GET['operation']) ) {
        print 'You forgot to supply an operation (set this value to either accept or reject)';
        return;
    }

    $targNode = node_load( intval($_GET['nid']) );
    dsm($targNode);

    switch ( $_GET['operation'] ) {
        case 'accept':

            // loop through the $targNode->field_pending_tags['und'] to find the target tid
            foreach ( $targNode->field_pending_tags['und'] as $index => $val ) {
                //dsm( $val );
                if ( intval($val['tid']) === intval($_GET['tid']) ) {
                    unset( $targNode->field_pending_tags['und'][$index] ); // Foudn the tid, remove it
                    drupal_set_message('updated');
                    $field_tagged_terms_cnt = count($targNode->field_tagged_terms['und']);
                    $targNode->field_tagged_terms['und'][$field_tagged_terms_cnt]['tid'] = $val['tid'];

                }
            }
                node_save($targNode);
            // add this value to $targNode->field_tagged_terms['und']

            // call node_save($targNode);
            $url  = ($is_https ? 'https://' : 'http://'). $_SERVER['SERVER_NAME'] . "/node/". $targNode->nid . "?status=accept";
            header("Location: $url");

            return;
        case 'reject':


            // loop through the $targNode->field_pending_tags['und'] to find the target tid

            // add this value to $targNode->field_tagged_terms['und']

            // call node_save($targNode);


            foreach ( $targNode->field_pending_tags['und'] as $index => $val ) {
                //dsm( $val );
                if ( intval($val['tid']) === intval($_GET['tid']) ) {
                     unset( $targNode->field_pending_tags['und'][$index] ); // Foudn the tid, remove it
                    $targNode->field_deleted_tags['und'] ; // Foudn the tid, remove it
                    $field_deleted_tagged_terms_cnt = count($targNode->field_deleted_tags['und']);
                    $targNode->field_deleted_tags['und'][$field_deleted_tagged_terms_cnt]['tid'] = $val['tid'];
                }
            }
            node_save($targNode);
            $url  = ($is_https ? 'https://' : 'http://'). $_SERVER['SERVER_NAME'] . "/node/". $targNode->nid . " ?status=reject";
            header("Location: $url");
            return;
        default:

            return;
    }

    return;
}


$tblRows = array();

 If ($_GET['status'] == 'pending' or $_GET['status'] == null)
 {
    $results = db_query("
            SELECT entity_id, field_pending_tags_tid
            FROM field_data_field_pending_tags
            GROUP BY entity_id
        ");



     foreach ( $results as $record ) {
         $node = node_load( $record->entity_id );
         foreach ( $node->field_pending_tags['und'] as $term ) {
             $termInfo = taxonomy_term_load($term['tid']);

             $tblRows[] = array(
                 'nid' => $node->nid,
                 'nodeTitle' => $node->title,
                 'pendingTermId' => $term['tid'],
                 'pendingTermText' => $termInfo->name
             );
         }
     }

 }
else if ($_GET['status'] == 'approved')
{

    $results = db_query("
            SELECT entity_id, field_tagged_terms_tid
            FROM field_data_field_tagged_terms
            GROUP BY entity_id
        ");

    foreach ( $results as $record ) {
        $node = node_load( $record->entity_id );
        foreach ( $node->field_tagged_terms['und'] as $term ) {
            $termInfo = taxonomy_term_load($term['tid']);

            $tblRows[] = array(
                'nid' => $node->nid,
                'nodeTitle' => $node->title,
                'pendingTermId' => $term['tid'],
                'pendingTermText' => $termInfo->name
            );
        }
    }


}
else if ($_GET['status'] == 'reject')
{
    $results = db_query("
            SELECT entity_id, field_deleted_tags_tid
            FROM field_data_field_deleted_tags
            GROUP BY entity_id
        ");

    foreach ( $results as $record ) {
        $node = node_load( $record->entity_id );
        foreach ( $node->field_deleted_tags['und'] as $term ) {
            $termInfo = taxonomy_term_load($term['tid']);

            $tblRows[] = array(
                'nid' => $node->nid,
                'nodeTitle' => $node->title,
                'pendingTermId' => $term['tid'],
                'pendingTermText' => $termInfo->name
            );
        }
    }
}



?>

<div class="reverse-information-flow-mastercontainer">
	<div class="tabs-container">
		<div class="tabstrip">
					<a href="?status=pending"><div id="tab-pending" class="tabs">Pending</div></a>
					<a href="?status=approved"><div id="tab-approved" class="tabs">Approved</div></a>
					<a href="?status=reject"><div id="tab-reject" class="tabs">Rejected</div></a>
				</div>
		<table id="results">
		<thead>
			<td><b>Title</b></td>
			<td><b>Tag</b></td>
			<td><b>Operation</b></td>
		</thead>
		<tbody>
		<?php if (count($tblRows) == 0) { ?> <tr> <td> <?php echo 'N/A'; ?> </td><td> <?php echo 'N/A'; ?> </td><td> <?php echo 'N/A'; ?> </td></tr><?php } else { ?>
		<?php foreach ( $tblRows as $tblRow ): ?>
		<tr>
			<td>
			  <a href="<?php print 'node/'.$tblRow['nid'] ?>"><?php  print $tblRow['nodeTitle']; ?></a>
			</td>
			<td>
				<?php print $tblRow['pendingTermText']; ?>
			</td>
			<?php if($_GET['status'] == 'reject' or $_GET['status'] == 'approved') { ?> <td> <?php echo 'N/A';?> </td> <?php }else{?>
				<td>
					<a href="javascript: if ( confirm('Are you sure you want to approve and add the tag into the CMS?') ) { document.location = document.location.protocol + '//' + document.location.host + document.location.pathname + '?nid=<?php print $tblRow['nid']; ?>&tid=<?php print $tblRow['pendingTermId']; ?>&operation=accept'; }">
						Approve
					</a>
					<a href="javascript: if ( confirm('Are you sure you want to reject this tag?') ) { document.location = document.location.protocol + '//' + document.location.host + document.location.pathname + '?nid=<?php print $tblRow['nid']; ?>&tid=<?php print $tblRow['pendingTermId']; ?>&operation=reject'; }">
						Reject
					</a>
				</td>
			<?php }?>

		</tr>

		<?php endforeach; ?>
		<?php } ?>
		</tbody>
	</table>
	</div>
	<div id="pageNavPosition"></div>
</div>

<script type="text/javascript">
    var pager = new Pager('results', 25);

    pager.init();
    pager.showPageNav('pager', 'pageNavPosition');
    pager.showPage(1);
</script>

<!--<script type="text/javascript" src="../sites/all/themes/bizusa/scripts/paging.js"></script>-->

