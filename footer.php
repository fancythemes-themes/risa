<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the "site-content" div and all content after.
 *
 * 
 * @package Risa
 * @since Risa 1.0
 */
?>

	</div><!-- .site-content -->

</div><!-- .site -->

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="site-info">
			<?php
				/**
				 * Fires before the Risa footer text for footer customization.
				 *
				 * @since Risa 1.0
				 */
				do_action( 'risa_credits' );
			?>
			<?php risa_footer_credit() ?>
		</div><!-- .site-info -->
	</footer><!-- .site-footer -->
	
<?php wp_footer(); ?>

</body>
</html>