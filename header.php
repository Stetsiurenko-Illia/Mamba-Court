<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="mamba-site-header">
    
    <div class="top-bar">
        <div class="container top-bar-flex">
            <div class="top-bar-left">
                <span>🔥 Безкоштовна доставка від 4000 грн</span>
            </div>
            <div class="top-bar-right">
                <a href="<?php echo esc_url( home_url( '/delivery' ) ); ?>">Оплата і доставка</a>
                <a href="<?php echo esc_url( home_url( '/contacts' ) ); ?>">Контакти</a>
            </div>
        </div>
    </div>

    <div class="mamba-main-header">
        <div class="container header-flex">
            
            <div class="logo">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a>
            </div>

            <div class="header-search">
                <form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <input type="search" class="search-field" placeholder="Пошук кросівок (напр. Jordan 4)..." value="<?php echo get_search_query(); ?>" name="s" />
                    <input type="hidden" name="post_type" value="product" />
                    <button type="submit" class="search-submit">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24"><path d="M10 2a8 8 0 105.293 14.707l5 5a1 1 0 001.414-1.414l-5-5A8 8 0 0010 2zm0 2a6 6 0 110 12 6 6 0 010-12z"/></svg>
                    </button>
                </form>
            </div>

            <div class="header-actions">
                <?php echo do_shortcode('[ti_wishlist_products_counter]'); ?>
                <a href="<?php echo esc_url( get_permalink( get_option('woocommerce_myaccount_page_id') ) ); ?>" class="action-icon" title="Мій профіль">👤</a>
                <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="cart-contents">
                    🛒 <span class="amount"><?php echo WC()->cart->get_cart_subtotal(); ?></span>
                    <?php if ( WC()->cart->get_cart_contents_count() > 0 ) : ?>
                        <span class="cart-count">(<?php echo WC()->cart->get_cart_contents_count(); ?>)</span>
                    <?php endif; ?>
                </a>
            </div>

        </div>
    </div>

    <div class="mamba-navigation">
        <div class="container">
            <?php
            wp_nav_menu( array(
                'theme_location' => 'primary', 
                'container'      => false,     
                'menu_class'     => 'nav-list', 
                'fallback_cb'    => false,
            ) );
            ?>
        </div>
    </div>

</header>