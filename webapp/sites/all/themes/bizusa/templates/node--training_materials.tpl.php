<div class="col-sm-12 with-padding">
	<div id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?>">

	 <?php print render($title_prefix); ?>
	  
	  <?php if (!$page): ?>
		<h2<?php print $title_attributes; ?>><a href="<?php print $node_url; ?>"><?php print $title; ?></a></h2>
	  <?php endif; ?>
	  <?php print render($title_suffix); ?>   

	  <?php
		// We hide the comments and links now so that we can render them later.
		
		
		 dsm("This content's (\"{$node->title}\") {$node->type} landing page is generated from " . __FILE__);
		hide($content['comments']);
		hide($content['links']);
		
		dsm("The following is the \$content variable in this file. This is a [Drupal] renderable array of items which have been constructed by Drupal theming preprocessors");
		dsm( $content );
		
		dsm("The following is \$node (the individual node) in which this landing base is based on ");
		dsm( $node );
		
		 
	  ?>
	  
	  <!--  <h2>The following markup comes after CoderBookmark:CB-V1ILCQE-BC in <?php print basename(__FILE__); ?></h2>   -->
			<div class="training-materials-maincontent">
		<?php //print ($node->title); ?>
		
		 
		<?php 
		dsm($node->field_has_video['und'][0]['value']);
		if ( $node->field_has_video['und'][0]['value'] == "0" ) {
			drupal_goto( $node->field_external_url['und'][0]['value'] );
		}
		
			$videoUrl = false;
			if ( !empty($node->field_video_source['und'][0]['value']) ) {
				$videoUrl = $node->field_video_source['und'][0]['value']; 
			}	 
		?>
		
		<?php if ( $videoUrl !== false ): ?>
			
			<div class="flowplayer-container">
				<a href="<?php print $videoUrl; ?>" id="player" class="flowplayer"></a>
				<?php
					flowplayer_add(
						'#player',
						array(
							'clip' => array(
								'autoPlay' => FALSE, // Turn autoplay off
								'linkUrl' => $videoUrl, // When clicked on
							),
						)
					);
				?>
			</div>
			</br> 
		<?php endif; ?>
	  <?php print ($node->body['und'][0]['value']); ?>
	   <a href="<?php print ($node->field_video_source['und'][0]['value']);?>" target="_blank"><?php print ($node->field_video_source['und'][0]['value']);?></a>

	  <!--  <h2>The following markup comes after Coder Bookmark: CB-PJY3RS5-BC in <?php print basename(__FILE__); ?></h2>
		<h2 style="color:red">Remember that all fields and labels inside $content can be added, removed, and rearranged via the <a href="http://busasf.vm/admin/structure/types/manage/<?php print str_replace('_', '-', $node->type); ?>/display">content-type display settings.</a></h2>
		
	   <?php print render($content); ?> -->
		</div>
			<div class="training-materials-sidebar">

				<?php if($node->field_video_list_code['und'][0]['value']&&$node->field_video_list_number['und'][0]['value']):?>
				<div class="training-materials-related-videos">
					<h3>Related Videos</h3>
					<ul class="raining-materials-related-videolist">
						<?php
						$query = new EntityFieldQuery();
						$query->entityCondition('entity_type', 'node')
							->propertyCondition('type', 'training_materials')
							->fieldCondition('field_video_list_code', 'value', $node->field_video_list_code['und'][0]['value'], '=')//code all related videos share
							->fieldOrderBy("field_video_list_number",'value', 'ASC');
						$result = $query->execute();
						$vid_list_nodes = entity_load('node', array_keys($result['node']));
						foreach($vid_list_nodes as $vid_list_node):
							$sel = '';
							if($node->nid == $vid_list_node->nid){
								$sel = 'current_video';
							}
						echo "<li class='".$sel."'><a href='".$vid_list_node->nid."'>".$vid_list_node->title."</a></li>";
						endforeach;
						?>
					</ul>
				</div>
				<?php endif;?>
			</div>
	 <?php if (!empty($content['last_date_modified'])): ?>
		<?php print render($content['last_date_modified']); ?>
	 <?php endif; ?>       
	</div> <!-- /node-->
</div>
