<?php get_header(); ?>

<main id="primary" class="site-main">

    <section class="mamba-hero">
        <div class="container hero-flex">
            
            <div class="hero-content">
                <span class="hero-subtitle">Mamba Mentality</span>
                <h1 class="hero-title">ТВІЙ КОРТ.<br><span class="highlight">ТВОЇ ПРАВИЛА.</span></h1>
                <p class="hero-desc">Оригінальні баскетбольні кросівки та екіпірування для тих, хто грає на перемогу. Обирай найкраще.</p>
                
                <div class="hero-actions">
                    <a href="<?php echo esc_url( home_url( '/shop' ) ); ?>" class="btn-hero-primary">ПЕРЕГЛЯНУТИ КАТАЛОГ</a>
                    <a href="<?php echo esc_url( home_url( '/product-category/discount/' ) ); ?>" class="btn-hero-secondary">SALE %</a>
                </div>
            </div>

            <div class="hero-image-wrapper">
                <img src="<?php echo get_template_directory_uri(); ?>/images/mamba-hero-logo.png" alt="Mamba Court Logo" class="hero-main-image">
                <div class="hero-glow"></div>
            </div>

        </div>
    </section>

    <section class="mamba-products-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">НОВІ <span>ДРОПИ</span></h2>
                <a href="<?php echo esc_url( home_url( '/product-category/new/' ) ); ?>" class="view-all-link">Всі товари →</a>
            </div>
            
            <div class="mamba-home-grid">
                <?php echo do_shortcode('[products limit="4" columns="4" orderby="date" order="DESC"]'); ?>
            </div>
        </div>
    </section>

    <section class="mamba-brands-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">ПОПУЛЯРНІ <span>БРЕНДИ</span></h2>
                <a href="<?php echo esc_url( home_url( '/brand' ) ); ?>" class="view-all-link">Всі бренди →</a>
            </div>
            <?php echo do_shortcode('[pwb-all-brands per_page="5" limit="5" title_position="none" hide_empty="false" image_size="medium" order_by="term_order" order="ASC"]'); ?>
        </div>
    </section>

</main>

<?php get_footer(); ?>