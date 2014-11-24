
<?php
// This is to make the site map dynamic upto three levels
$sitemap_menu = menu_tree_all_data('main-menu');
?>

<div class="row no-margin with-padding">

	<div class="sitemap-contentContainer col-sm-12">
		<h2 style="margin-top:0;">
			Main Navigation
		</h2>
		<ul class="site-map-menu">
			<?php 
			foreach($sitemap_menu as $item) {
					if(sizeof($item['below']) == 0) { // Menu link without a child menu link - First level
						?>
						<li class="leaf">
							<a href=<?php echo add_link($item); ?> title=""> <?php echo $item['link']['title'];?> </a>
						</li>
						<?php
					}
					else { // Second Level
					?>
	<li class="expanded">
		<a href=<?php echo add_link($item);?> title=""> <?php echo $item['link']['title'];?></a>
	</li>
	<ul class="site-map-menu">
		<?php
		foreach($item['below'] as $sub_1_item) {
			if(sizeof($sub_1_item['below']) == 0) { 
				?>
				<li class="leaf">
					<a href=<?php echo add_link($sub_1_item);?> title=""> <?php echo $sub_1_item['link']['title'];?> </a>
				</li>
				<?php
			}
else { // Third level
	?>
	<li class="expanded">
		<a href= <?php echo add_link($sub_1_item);?> title=""><?php echo $sub_1_item['link']['title'];?></a>
	</li>
	<ul class="site-map-menu">
		<?php 
		foreach($sub_1_item['below'] as $sub_2_item) { 
			if(sizeof($sub_2_item['below']) == 0) {
				?>
				<li class="leaf">
					<a href=<?php echo add_link($sub_2_item);?> title=""> <?php echo $sub_2_item['link']['title'];?> </a>
				</li>
				<?php
			}
			else { // Fourth level
				?>
				<li class="expanded">
					<a href=<?php echo add_link($sub_2_item);?> title=""><?php echo $sub_2_item['link']['title'];?></a>
				</li>
				<ul class="site-map-menu">
					<?php
					foreach($sub_2_item['below'] as $sub_3_item) { ?>
					<li class="leaf">
						<a href=<?php echo add_link($sub_3_item);?> title=""> <?php echo $sub_3_item['link']['title'];?> </a>
					</li>
					<?php
				}
				?>
			</ul>
			<?php
		} 
	}
	?>
</ul>
<?php
}
} 
?>
</ul>
<?php
}
}
?>
<!--<li class="leaf">
	<a title="" href="/usgbs/">US Global Business Solutions</a>
</li>
<li class="leaf">
	<a title="" href="/business-sunday">Business Sunday</a>
</li>-->
</ul>
</div>

</div>


<?php 
function add_link($item){
	if($item['link']['link_path'] === '<front>') { 
		$link = '/';
		return $link;
	} 
	else if(strpos($item['link']['link_path'], 'http') !== false) {
		$link = $item['link']['link_path'];
		return $link;
	}
	else { 
		if(array_key_exists('fragment', $item['link']['localized_options'])) {
			if(sizeof($item['link']['localized_options']['fragment']) >= 1){
				$link = '/'.$item['link']['link_path'].'#'.$item['link']['localized_options']['fragment'];
				return $link;
			}
		}
		else if(array_key_exists('query', $item['link']['localized_options'])){
			if(sizeof($item['link']['localized_options']['query']) >= 1){
				foreach($item['link']['localized_options']['query'] as $query => $value) {
					$link = '/'.$item['link']['link_path'].'?'.$query.'='.$value;
					return $link;
				}
			}
		}
		else{
			$link = '/'.$item['link']['link_path'];
			return $link;
		}    
	} 
}