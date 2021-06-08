<?php
/**
 * Name:  Header style 05
 **/
?>
<?php
$azirspares_icon = Azirspares_Functions::azirspares_get_option('header_icon');
$azirspares_phone = Azirspares_Functions::azirspares_get_option('header_phone');
$azirspares_text = Azirspares_Functions::azirspares_get_option('header_text');
$enable_sticky = Azirspares_Functions::azirspares_get_option('azirspares_enable_sticky_menu');
$enable_header_mobile = Azirspares_Functions::azirspares_get_option('enable_header_mobile');
$class = array('header', 'style5');
if ($enable_sticky == 1)
    $class[] = 'header-sticky';
if (($enable_header_mobile == 1) && (azirspares_is_mobile())) {
    get_template_part('templates/header', 'mobile');
} else { ?>
    <header id="header" class="<?php echo esc_attr(implode(' ', $class)); ?>">
        <?php azirspares_header_background(); ?>
        <div class="header-slide">
            <?php if (has_nav_menu('top_left_menu') || has_nav_menu('top_right_menu')): ?>
                <div class="header-top">
                    <div class="container">
                        <div class="header-top-inner">
                            <?php
                            if (has_nav_menu('top_left_menu')) {
                                wp_nav_menu(array(
                                        'menu' => 'top_left_menu',
                                        'theme_location' => 'top_left_menu',
                                        'depth' => 1,
                                        'container' => '',
                                        'container_class' => '',
                                        'container_id' => '',
                                        'menu_class' => 'azirspares-nav top-bar-menu',
                                        'fallback_cb' => 'Azirspares_navwalker::fallback',
                                        'walker' => new Azirspares_navwalker(),
                                    )
                                );
                            }
                            if (has_nav_menu('top_right_menu')) {
                                wp_nav_menu(array(
                                        'menu' => 'top_right_menu',
                                        'theme_location' => 'top_right_menu',
                                        'depth' => 1,
                                        'container' => '',
                                        'container_class' => '',
                                        'container_id' => '',
                                        'menu_class' => 'azirspares-nav top-bar-menu right',
                                        'fallback_cb' => 'Azirspares_navwalker::fallback',
                                        'walker' => new Azirspares_navwalker(),
                                    )
                                );
                            }
                            ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <div class="header-wrap-stick">
                <div class="header-position">
                    <div class="header-middle">
                        <div class="container">
                            <div class="header-middle-inner">
                                <div class="header-logo">
                                    <?php azirspares_get_logo(); ?>
                                </div>
                                <?php azirspares_search_form(); ?>
                                <div class="header-control">
                                    <div class="header-control-inner">
                                        <div class="meta-woo">
                                            <?php azirspares_header_burger(); ?>
                                            <div class="block-menu-bar">
                                                <a class="menu-bar menu-toggle" href="#">
                                                    <span></span>
                                                    <span></span>
                                                    <span></span>
                                                </a>
                                            </div>
                                            <?php
                                            do_action('azirspares_header_wishlist');
                                            azirspares_user_link();
                                            do_action('azirspares_header_mini_cart');
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="sticky-cart"><?php do_action('azirspares_header_mini_cart'); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="header-nav">
                <div class="container">
                    <div class="azirspares-menu-wapper"></div>
                    <div class="header-nav-inner">
                        <div class="box-header-nav">
                            <?php
                            wp_nav_menu(array(
                                    'menu' => 'primary',
                                    'theme_location' => 'primary',
                                    'depth' => 3,
                                    'container' => '',
                                    'container_class' => '',
                                    'container_id' => '',
                                    'menu_class' => 'clone-main-menu azirspares-clone-mobile-menu azirspares-nav main-menu',
                                    'fallback_cb' => 'Azirspares_navwalker::fallback',
                                    'walker' => new Azirspares_navwalker(),
                                )
                            );
                            ?>
                        </div>
                        <?php if ($azirspares_phone) : ?>
                            <div class="phone-header style5">
                                <div class="phone-inner">
                                    <?php if ($azirspares_icon) : ?>
                                        <span class="phone-icon">
                                        <span class="<?php echo esc_attr($azirspares_icon); ?>"></span>
                                    </span>
                                    <?php endif; ?>
                                    <div class="phone-number">
                                        <p><?php echo esc_html($azirspares_text); ?></p>
                                        <p><?php echo esc_html($azirspares_phone); ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </header>
<?php } ?>