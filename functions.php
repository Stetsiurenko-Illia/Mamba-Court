<?php
/* ==========================================================================
   1. ОСНОВНІ НАЛАШТУВАННЯ ТЕМИ (Setup & Scripts)
   ========================================================================== */

function mamba_court_scripts() {

    wp_enqueue_style('mamba-fonts', 'https://fonts.googleapis.com/css2?family=Anton&family=Montserrat:wght@400;700&display=swap', array(), null);

    $theme_version = wp_get_theme()->get('Version');

    wp_enqueue_style('mamba-style', get_stylesheet_uri(), array(), $theme_version);

    wp_enqueue_script('jquery');
    wp_enqueue_script('mamba-main-js', get_template_directory_uri() . '/assets/js/main.js', array('jquery'), $theme_version, true);

    wp_localize_script('mamba-main-js', 'mamba_ajax', array(
        'url' => admin_url('admin-ajax.php')
    ));
}
add_action('wp_enqueue_scripts', 'mamba_court_scripts', 99);

function mamba_court_add_woocommerce_support() {
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
}
add_action('after_setup_theme', 'mamba_court_add_woocommerce_support');

function mamba_court_menus() {
    register_nav_menus( array(
        'primary' => 'Головне меню (Header)',
    ) );
}
add_action( 'init', 'mamba_court_menus' );


/* ==========================================================================
   2. НАЛАШТУВАННЯ КАТАЛОГУ WOOCOMMERCE (Shop Layout & Core)
   ========================================================================== */

remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );

add_action( 'woocommerce_before_shop_loop', 'mamba_open_shop_layout', 15 );
function mamba_open_shop_layout() {
    if ( is_shop() || is_product_category() || is_product_tag() || is_tax('pwb-brand') ) {
        echo '<div class="mamba-shop-layout">'; 
        echo '<aside class="mamba-shop-sidebar">';
        echo do_shortcode('[fe_widget]');
        echo '</aside>';
        echo '<div class="mamba-shop-products">'; 
    } else {
        echo '<div class="mamba-shop-products-full">';
    }
}

add_action( 'woocommerce_after_shop_loop', 'mamba_close_shop_layout', 15 );
function mamba_close_shop_layout() {
    if ( is_shop() || is_product_category() || is_product_tag() ) {
        echo '</div></div>'; 
    } else {
        echo '</div>'; 
    }
}

add_filter( 'loop_shop_per_page', 'mamba_custom_products_per_page', 20 );
function mamba_custom_products_per_page( $cols ) {
    return wp_is_mobile() ? 8 : 12;
}

add_filter('get_terms_args', 'pwb_custom_brands_order', 10, 2);
function pwb_custom_brands_order($args, $taxonomies) {
    if (in_array('pwb-brand', $taxonomies)) {
        if (isset($args['orderby']) && ($args['orderby'] == 'term_order' || $args['orderby'] == 'menu_order')) {
            $args['orderby'] = 'term_order'; 
        }
    }
    return $args;
}


/* ==========================================================================
   3. КАРТКА ТОВАРУ ТА SINGLE PRODUCT (Badges, Tags, Tabs)
   ========================================================================== */

add_action( 'woocommerce_shop_loop_item_title', 'mamba_display_tech_tag', 15 );
function mamba_display_tech_tag() {
    $tech = get_field('mamba_tech');
    if ( $tech ) {
        echo '<div class="product-tech-tag">' . esc_html( $tech ) . '</div>';
    }
}

add_action( 'woocommerce_single_product_summary', 'mamba_display_single_tech', 5 );
function mamba_display_single_tech() {
    $tech = get_field('mamba_tech');
    if ( $tech ) {
        echo '<p class="single-product-tech">' . esc_html( $tech ) . '</p>';
    }
}

add_filter( 'woocommerce_display_product_attributes', 'mamba_add_acf_to_attributes_table', 10, 2 );
function mamba_add_acf_to_attributes_table( $product_attributes, $product ) {
    $mamba_tech = get_field( 'mamba_tech', $product->get_id() );
    if ( ! empty( $mamba_tech ) ) {
        $product_attributes['custom_mamba_tech'] = array(
            'label' => 'Технології', 
            'value' => esc_html( $mamba_tech ), 
        );
    }
    return $product_attributes;
}

remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );

add_action( 'woocommerce_before_shop_loop_item_title', 'mamba_custom_product_badges', 10 );
add_action( 'woocommerce_before_single_product_summary', 'mamba_custom_product_badges', 10 );
function mamba_custom_product_badges() {
    global $product;
    if ( ! $product ) return;
    
    echo '<div class="mamba-badges">';
    
    $in_new_category = has_term( 'new', 'product_cat', $product->get_id() );
    if ( $in_new_category ) {
        echo '<span class="mamba-badge badge-new">NEW</span>';
    }
    
    if ( $product->is_on_sale() ) {
        $percentage = '';
        if ( $product->is_type( 'variable' ) ) {
            $percentages = array();
            $prices = $product->get_variation_prices();
            foreach( $prices['price'] as $key => $price ){
                if( $prices['regular_price'][$key] !== $price ){
                    $percentages[] = round( 100 - ( $prices['sale_price'][$key] / $prices['regular_price'][$key] * 100 ) );
                }
            }
            if ( !empty($percentages) ) {
                $percentage = max($percentages) . '%';
            }
        } elseif ( $product->is_type( 'simple' ) ) {
            if ( $product->get_regular_price() ) {
                $percentage = round( 100 - ( $product->get_sale_price() / $product->get_regular_price() * 100 ) ) . '%';
            }
        }
        
        if ( $percentage ) {
            echo '<span class="mamba-badge badge-sale">-' . $percentage . '</span>';
        } else {
            echo '<span class="mamba-badge badge-sale">SALE</span>';
        }
    }
    echo '</div>';
}

add_filter( 'woocommerce_product_tabs', 'mamba_customize_tabs', 98 );
function mamba_customize_tabs( $tabs ) {
    if ( isset( $tabs['description'] ) ) {
        $tabs['description']['title'] = 'Огляд товару';
        $tabs['description']['priority'] = 10;
    }
    if ( isset( $tabs['additional_information'] ) ) {
        $tabs['additional_information']['title'] = 'Характеристики';
        $tabs['additional_information']['priority'] = 20;
    }
    if ( isset( $tabs['reviews'] ) ) {
        $tabs['reviews']['title'] = 'Відгуки';
        $tabs['reviews']['priority'] = 40;
    }

    $tabs['mamba_delivery'] = array(
        'title'    => 'Доставка і оплата',
        'priority' => 30,
        'callback' => 'mamba_delivery_tab_content'
    );

    return $tabs;
}

add_filter( 'woocommerce_product_tabs', 'mamba_remove_product_tabs', 98 );
function mamba_remove_product_tabs( $tabs ) {
    if ( isset( $tabs['pwb_tab'] ) ) unset( $tabs['pwb_tab'] );
    if ( isset( $tabs['brand'] ) ) unset( $tabs['brand'] );
    return $tabs;
}

function mamba_delivery_tab_content() {
    ?>
    <div class="delivery-tab-content">
        <h3>Способи доставки:</h3>
        <ul>
            <li>🚚 Нова Пошта (у відділення або кур'єром)</li>
            <li>📦 Самовивіз з нашого корту в Києві</li>
        </ul>
        <h3>Способи оплати:</h3>
        <ul>
            <li>💳 Оплата карткою на сайті (Visa/Mastercard)</li>
            <li>💵 Накладений платіж при отриманні</li>
            <li>📱 Apple Pay / Google Pay</li>
        </ul>
    </div>
    <?php
}


/* ==========================================================================
   4. КОШИК ТА ОБРОБКА AJAX (Backend)
   ========================================================================== */

add_filter( 'woocommerce_add_to_cart_fragments', 'mamba_court_header_add_to_cart_fragment' );
function mamba_court_header_add_to_cart_fragment( $fragments ) {
    ob_start();
    ?>
    <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="cart-contents">
        🛒 <span class="amount"><?php echo WC()->cart->get_cart_subtotal(); ?></span>
        <?php if ( WC()->cart->get_cart_contents_count() > 0 ) : ?>
            <span class="cart-count">(<?php echo WC()->cart->get_cart_contents_count(); ?>)</span>
        <?php endif; ?>
    </a>
    <?php
    $fragments['a.cart-contents'] = ob_get_clean();
    return $fragments;
}

// Обробник AJAX-запиту для пошуку
add_action( 'wp_ajax_nopriv_mamba_live_search', 'mamba_live_search_handler' );
add_action( 'wp_ajax_mamba_live_search', 'mamba_live_search_handler' );
function mamba_live_search_handler() {
    $search_term = sanitize_text_field( $_POST['keyword'] );
    $args = array(
        'post_type'      => 'product',
        'post_status'    => 'publish',
        's'              => $search_term,
        'posts_per_page' => 5, 
    );

    $query = new WP_Query( $args );
    
    if ( $query->have_posts() ) {
        echo '<ul class="ajax-search-results-list">';
        while ( $query->have_posts() ) {
            $query->the_post();
            global $product;
            ?>
            <li>
                <a href="<?php echo esc_url( get_permalink() ); ?>" class="ajax-search-item">
                    <div class="ajax-search-img">
                        <?php echo $product->get_image('thumbnail'); ?>
                    </div>
                    <div class="ajax-search-info">
                        <span class="ajax-search-title"><?php the_title(); ?></span>
                        <span class="ajax-search-price"><?php echo $product->get_price_html(); ?></span>
                    </div>
                </a>
            </li>
            <?php
        }
        echo '</ul>';
        echo '<div class="ajax-search-all"><a href="' . esc_url( home_url( '/?s=' . $search_term . '&post_type=product' ) ) . '">Дивитись всі результати →</a></div>';
    } else {
        echo '<div class="ajax-search-not-found">На жаль, таких кросівок не знайдено 🏀</div>';
    }
    wp_die();
}


/* ==========================================================================
   5. МОБІЛЬНА АДАПТАЦІЯ (HTML Output)
   ========================================================================== */

add_action( 'woocommerce_before_shop_loop', 'mamba_mobile_catalog_controls', 25 );
function mamba_mobile_catalog_controls() {
    if ( ! wp_is_mobile() ) return; 
    
    remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
    
    echo '<div class="mamba-mobile-controls">';
        echo '<button id="open-mamba-filters" class="mamba-ctrl-btn"><i class="fa fa-filter"></i> Фільтри</button>';
        echo '<button id="open-mamba-sorting" class="mamba-ctrl-btn"><i class="fa fa-sort"></i> Сортування</button>';
    echo '</div>';

    echo '<div id="mamba-sorting-overlay" class="mamba-catalog-overlay">
            <div class="mamba-sorting-content">
                <div class="mamba-overlay-header">
                    <h3>Сортування</h3>
                    <span class="close-mamba-overlay">&times;</span>
                </div>
                <div class="mamba-sorting-list">';
                    woocommerce_catalog_ordering(); 
    echo '      </div>
            </div>
          </div>';
          
    echo '<div id="mamba-filters-bg" class="mamba-catalog-overlay-bg"></div>';
}

function mamba_custom_mobile_sorting_list() {
    $catalog_orderby_options = array(
        'menu_order' => 'За замовчуванням',
        'popularity' => 'За популярністю',
        'rating'     => 'За рейтингом',
        'date'       => 'Новинки',
        'price'      => 'Ціна: від низької',
        'price-desc' => 'Ціна: від високої',
    );
    
    echo '<ul class="mamba-custom-sort-list">';
    foreach ( $catalog_orderby_options as $id => $name ) {
        $link = add_query_arg( 'orderby', $id );
        echo '<li><a href="' . esc_url( $link ) . '">' . esc_html( $name ) . '</a></li>';
    }
    echo '</ul>';
}


/* ==========================================================================
   6. СЛУЖБОВІ ФУНКЦІЇ (Cron, Transients, System fixes)
   ========================================================================== */

add_action( 'init', 'mamba_auto_sync_new_category' );
function mamba_auto_sync_new_category() {
    if ( false === get_transient( 'mamba_new_products_synced' ) ) {
        $new_category_slug = 'new'; 
        $category = get_term_by( 'slug', $new_category_slug, 'product_cat' );
        
        if ( $category ) {
            $newness_days = 30; 
            $limit_date = date( 'Y-m-d H:i:s', strtotime( '-' . $newness_days . ' days' ) );

            $products = get_posts( array(
                'post_type'      => 'product',
                'posts_per_page' => -1,
                'post_status'    => 'publish',
            ));

            foreach ( $products as $product ) {
                $is_new = ( $product->post_date >= $limit_date );
                $current_terms = wp_get_post_terms( $product->ID, 'product_cat', array('fields' => 'ids') );
                
                if ( $is_new && ! in_array( $category->term_id, $current_terms ) ) {
                    wp_set_post_terms( $product->ID, array( $category->term_id ), 'product_cat', true );
                } elseif ( ! $is_new && in_array( $category->term_id, $current_terms ) ) {
                    $updated_terms = array_diff( $current_terms, array( $category->term_id ) );
                    wp_set_post_terms( $product->ID, $updated_terms, 'product_cat', false );
                }
            }
        }
        set_transient( 'mamba_new_products_synced', true, 12 * HOUR_IN_SECONDS );
    }
}

add_filter( 'styles_inline_size_limit', '__return_zero' );