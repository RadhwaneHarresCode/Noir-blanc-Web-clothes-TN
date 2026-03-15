<?php

get_header();
?>

<div class="container" style="padding-top: calc(var(--header-height) + var(--space-lg)); padding-bottom: var(--space-xl);">

    <div class="section-header" style="margin-bottom: var(--space-xl);">
        <h1 class="section-title">
            <?php
            if (is_home())          _e('Blog', 'noirblancshop');
            elseif (is_archive())   the_archive_title();
            elseif (is_search())    printf(__('Search: "%s"', 'noirblancshop'), get_search_query());
            else                    the_title();
            ?>
        </h1>
    </div>

    <?php if (have_posts()): ?>

        <div class="grid-3">
            <?php while (have_posts()): the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class('product-card'); ?>>
                    <?php if (has_post_thumbnail()): ?>
                        <div class="product-card__image">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail('product-card'); ?>
                            </a>
                        </div>
                    <?php endif; ?>

                    <div class="product-card__body">
                        <div class="product-card__category">
                            <?php the_date('d M Y'); ?>
                        </div>
                        <h2 class="product-card__name">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h2>
                        <p style="font-size:0.9rem; color:var(--color-gray-dark); margin-bottom:var(--space-md)">
                            <?php the_excerpt(); ?>
                        </p>
                        <a href="<?php the_permalink(); ?>" class="btn btn--outline btn--sm">
                            Read More →
                        </a>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>

        <!-- Pagination -->
        <div style="margin-top: var(--space-xl); text-align:center">
            <?php the_posts_pagination(['mid_size' => 2]); ?>
        </div>

    <?php else: ?>

        <div style="text-align:center; padding: var(--space-xl) 0">
            <p style="color:var(--color-gray-mid); font-size:1.1rem">
                Nothing found here yet.
            </p>
            <a href="<?php echo wc_get_page_permalink('shop'); ?>" class="btn btn--primary" style="margin-top:var(--space-md)">
                Back to Shop →
            </a>
        </div>

    <?php endif; ?>

</div>

<?php get_footer(); ?>
