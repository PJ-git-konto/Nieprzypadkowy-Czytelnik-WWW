<?php
/**
 * Standard page template for static editorial and information pages.
 */
get_header();
?>
<main class="nc-main nc-static-page">
  <?php while (have_posts()) : the_post(); ?>
    <article <?php post_class('nc-static-page-inner'); ?>>
      <header class="nc-static-page-header">
        <p class="nc-static-page-eyebrow">Nieprzypadkowy Czytelnik</p>
        <h1><?php the_title(); ?></h1>
      </header>
      <div class="nc-static-page-content">
        <?php the_content(); ?>
      </div>
    </article>
  <?php endwhile; ?>
</main>
<?php get_footer(); ?>
