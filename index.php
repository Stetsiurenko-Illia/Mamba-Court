<?php get_header(); ?>

<main class="site-main container">
    <?php
    if ( have_posts() ) :
        while ( have_posts() ) : the_post();
            the_content();
        endwhile;
    else :
        echo '<p>Контент не знайдено</p>';
    endif;
    ?>
</main>

<?php get_footer(); ?>