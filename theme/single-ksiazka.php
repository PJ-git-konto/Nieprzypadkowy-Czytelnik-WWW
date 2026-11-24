<?php
/**
 * Szablon pojedynczej książki.
 */
get_header();

while (have_posts()) :
    the_post();

    $book_id = get_the_ID();
    $book_author = (string) get_post_meta($book_id, '_nc_book_author', true);
    $book_terms = get_the_terms($book_id, 'kategoria_ksiazki');
    $book_excerpt = get_the_excerpt();
    $archive_url = get_post_type_archive_link('ksiazka');
    ?>
    <main class="nc-main nc-book-detail">
      <div class="nc-book-detail-inner">
        <a class="nc-book-detail-back" href="<?php echo esc_url($archive_url ?: home_url('/')); ?>">
          <span aria-hidden="true">←</span> Wróć do książek
        </a>

        <article <?php post_class('nc-book-detail-article'); ?>>
          <header class="nc-book-detail-header">
            <div class="nc-book-detail-cover">
              <?php if (has_post_thumbnail()) : ?>
                <?php the_post_thumbnail('large', [
                    'class' => 'nc-book-detail-cover-image',
                    'alt' => esc_attr(get_the_title() . ' — okładka'),
                ]); ?>
              <?php else : ?>
                <div class="nc-book-detail-cover-placeholder">Miejsce na okładkę</div>
              <?php endif; ?>
            </div>

            <div class="nc-book-detail-intro">
              <p class="nc-book-detail-eyebrow">Poznaj tę książkę</p>
              <h1><?php the_title(); ?></h1>
              <?php if ($book_author !== '') : ?>
                <p class="nc-book-detail-author"><?php echo esc_html($book_author); ?></p>
              <?php endif; ?>

              <?php if ($book_terms && !is_wp_error($book_terms)) : ?>
                <div class="nc-book-detail-categories" aria-label="Kategorie książki">
                  <?php foreach ($book_terms as $term) : ?>
                    <a href="<?php echo esc_url(get_term_link($term)); ?>"><?php echo esc_html($term->name); ?></a>
                  <?php endforeach; ?>
                </div>
              <?php endif; ?>

              <?php if ($book_excerpt !== '') : ?>
                <div class="nc-book-detail-excerpt"><?php echo wp_kses_post(wpautop($book_excerpt)); ?></div>
              <?php endif; ?>
            </div>
          </header>

          <?php if (trim((string) get_the_content()) !== '') : ?>
            <section class="nc-book-detail-content">
              <h2>O książce</h2>
              <?php the_content(); ?>
            </section>
          <?php endif; ?>
        </article>
      </div>
    </main>
    <?php
endwhile;

get_footer();
