<?php
get_header();
?>
<main class="nc-main nc-container" style="padding-block:48px 80px">
  <?php if (have_posts()) : ?>
    <?php while (have_posts()) : the_post(); ?>
      <article <?php post_class(); ?>>
        <h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
        <?php the_excerpt(); ?>
      </article>
    <?php endwhile; ?>
    <?php the_posts_pagination(); ?>
  <?php else : ?>
    <p><?php esc_html_e('Nie znaleziono treści.', 'nieprzypadkowy-czytelnik'); ?></p>
  <?php endif; ?>
</main>
<?php get_footer(); ?>
