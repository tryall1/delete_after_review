<?php get_header(); ?>

<main class="site-main">
    <?php if (have_posts()) : ?>
        <h1>Search results for: <?php echo get_search_query(); ?></h1>
        
        <?php while (have_posts()) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                <div class="entry-content">
                    <?php the_excerpt(); ?>
                </div>
            </article>
        <?php endwhile; ?>
        
    <?php else : ?>
        <h1>Nothing found</h1>
        <p>Please try adjusting your search parameters.</p>
    <?php endif; ?>
</main>

<?php get_footer(); ?>
