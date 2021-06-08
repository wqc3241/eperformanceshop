<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link       https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package    WordPress
 * @subpackage Azirspares
 * @since      1.0
 * @version    1.0
 */
get_header();
?>
    <div class="main-container text-center">
        <div class="container">
            <h1 class="page-title"><?php esc_html_e( '404', 'azirspares' ); ?></h1>
        </div>
        <div class="container">
            <div id="primary" class="content-area">
                <main id="main" class="site-main" role="main">
                    <section class="error-404 not-found">
                        <h1 class="title"><?php echo esc_html__( 'Opps! This page Could Not Be Found!', 'azirspares' ); ?></h1>
                        <p class="subtitle"><?php echo esc_html__( 'Sorry bit the page you are looking for does not exist, have been removed or name changed', 'azirspares' ); ?></p>
                        <!-- .page-content -->
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>"
                           class="button"><?php echo esc_html__( 'Back to hompage', 'azirspares' ); ?></a>
                    </section><!-- .error-404 -->
                </main><!-- #main -->
            </div><!-- #primary -->
        </div>
    </div>
<?php
get_footer();
