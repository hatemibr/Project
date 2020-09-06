<?php if( !is_product() ):?>
	<div class="toolbar-products toolbar-top clear-both">
		<div class="short-by">
			<?php woocommerce_catalog_ordering();?>
		</div>
		<div class="shop-perpage">
			<?php fastshop_shop_post_perpage(); ?>
		</div>
		<div class="modes">
			<?php fastshop_shop_view_more();?>
		</div>
	</div>
<?php endif;?>