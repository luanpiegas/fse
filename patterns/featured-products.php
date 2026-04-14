<?php
/**
 * Title: Featured Products
 * Slug: fse/featured-products
 * Categories: fse-store
 * Description: A curated product collection section for store landing pages.
 *
 * @package FSE
 */
?>

<!-- wp:group {"align":"wide"} -->
<div class="wp-block-group alignwide">
	<!-- wp:group {"className":"section-card featured-products-shell"} -->
	<div class="wp-block-group section-card featured-products-shell">
		<!-- wp:heading {"level":2} -->
		<h2><?php esc_html_e( 'Product stories with quieter framing.', 'fse' ); ?></h2>
		<!-- /wp:heading -->

		<!-- wp:paragraph -->
		<p><?php esc_html_e( 'Each card keeps the image, teaser, and pricing legible so the catalog feels calm and easy to scan.', 'fse' ); ?></p>
		<!-- /wp:paragraph -->

		<!-- wp:woocommerce/product-collection {"queryId":2,"query":{"perPage":4,"pages":0,"offset":0,"postType":"product","order":"desc","orderBy":"date","search":"","exclude":[],"inherit":false,"taxQuery":[],"isProductCollectionBlock":true,"woocommerceOnSale":false,"woocommerceStockStatus":["instock","outofstock","onbackorder"],"woocommerceAttributes":[],"woocommerceHandPickedProducts":[]},"tagName":"div","displayLayout":{"type":"flex","columns":4},"queryContextIncludes":["collection"],"align":"wide"} -->
		<div class="wp-block-woocommerce-product-collection alignwide">
			<!-- wp:woocommerce/product-template -->
			<!-- wp:woocommerce/product-image {"showSaleBadge":false,"imageSizing":"thumbnail","isDescendentOfQueryLoop":true} -->
				<!-- wp:woocommerce/product-sale-badge {"isDescendentOfQueryLoop":true,"align":"right"} /-->
			<!-- /wp:woocommerce/product-image -->

			<!-- wp:post-title {"level":3,"isLink":true,"fontSize":"medium","__woocommerceNamespace":"woocommerce/product-collection/product-title"} /-->

			<!-- wp:woocommerce/product-price {"isDescendentOfQueryLoop":true,"fontSize":"small"} /-->

			<!-- wp:woocommerce/product-button {"isDescendentOfQueryLoop":true} /-->
			<!-- /wp:woocommerce/product-template -->
		</div>
		<!-- /wp:woocommerce/product-collection -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
