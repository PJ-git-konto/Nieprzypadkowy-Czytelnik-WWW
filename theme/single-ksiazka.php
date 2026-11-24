<?php
/**
 * Szablon pojedynczej książki — układ zgodny z makietą redakcyjną.
 */
get_header();

while (have_posts()) :
    the_post();

    $book_id = get_the_ID();
    $book_author = (string) get_post_meta($book_id, '_nc_book_author', true);
    $book_terms = get_the_terms($book_id, 'kategoria_ksiazki');
    $book_excerpt = get_the_excerpt();
    $book_recommendation = (string) get_post_meta($book_id, '_nc_book_recommendation', true);
    $book_audience = (string) get_post_meta($book_id, '_nc_book_audience', true);
    $book_discover = (string) get_post_meta($book_id, '_nc_book_discover', true);
    $book_observations = (string) get_post_meta($book_id, '_nc_book_observations', true);
    $purchase_links = [
        'empik' => (string) get_post_meta($book_id, '_nc_book_empik_url', true),
        'allegro' => (string) get_post_meta($book_id, '_nc_book_allegro_url', true),
        'amazon' => (string) get_post_meta($book_id, '_nc_book_amazon_url', true),
        'more' => (string) get_post_meta($book_id, '_nc_book_more_url', true),
    ];
    $has_purchase_links = (bool) array_filter($purchase_links);
    $archive_url = get_post_type_archive_link('ksiazka');

    $similar_args = [
        'post_type' => 'ksiazka',
        'post_status' => 'publish',
        'posts_per_page' => 3,
        'post__not_in' => [$book_id],
        'orderby' => 'rand',
        'no_found_rows' => true,
    ];
    if ($book_terms && !is_wp_error($book_terms)) {
        $similar_args['tax_query'] = [[
            'taxonomy' => 'kategoria_ksiazki',
            'field' => 'term_id',
            'terms' => wp_list_pluck($book_terms, 'term_id'),
        ]];
    }
    $similar_books = new WP_Query($similar_args);
    ?>
    <main class="nc-main nc-book-detail">
      <section class="nc-book-detail-hero nc-hero">
        <div class="nc-book-detail-hero-inner nc-hero-inner">
          <div class="nc-book-detail-cover">
            <?php if (has_post_thumbnail()) : ?>
              <?php the_post_thumbnail('large', [
                  'class' => 'nc-book-detail-cover-image',
                  'alt' => esc_attr(get_the_title() . ' — okładka'),
              ]); ?>
            <?php else : ?>
              <div class="nc-book-detail-cover-placeholder">Okładka książki</div>
            <?php endif; ?>
          </div>
          <div class="nc-book-detail-intro nc-hero-copy">
            <div class="nc-book-detail-badges">
              <?php if (get_post_meta($book_id, '_nc_book_recommended', true)) : ?>
                <span class="nc-book-recommendation-stamp"><span aria-hidden="true">★</span> Nasza rekomendacja</span>
              <?php endif; ?>
              <?php
                $book_categories = $book_terms;
                $nc_category_limit = 3;
                include get_theme_file_path('/template-parts/book-category-chips.php');
              ?>
            </div>
            <h1><?php the_title(); ?></h1>
            <?php if ($book_author !== '') : ?><p class="nc-book-detail-author"><?php echo esc_html($book_author); ?></p><?php endif; ?>
            <div class="nc-book-detail-title-rule"></div>
            <?php if ($book_excerpt !== '') : ?><div class="nc-book-detail-tagline"><?php echo wp_kses_post(wpautop($book_excerpt)); ?></div><?php endif; ?>
            <a class="nc-book-detail-buy-button" href="#nc-book-buy"><span class="nc-book-detail-buy-cart" aria-hidden="true">🛒</span><span>Może bym kupił</span><span aria-hidden="true">→</span></a>
          </div>
          <img class="nc-hero-image" src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/hero-ksiazki-kubek.png'); ?>" alt="" aria-hidden="true">
        </div>
      </section>

      <div class="nc-book-detail-layout">
        <article <?php post_class('nc-book-detail-article'); ?>>
          <?php if ($book_recommendation !== '' || ($book_excerpt !== '' && trim(wp_strip_all_tags($book_excerpt)) !== '')) : ?>
            <section class="nc-book-detail-panel nc-book-detail-feature">
              <span class="nc-book-detail-section-icon" aria-hidden="true">★</span>
              <div>
                <h2>Nasza rekomendacja</h2>
                <?php if ($book_recommendation !== '') : ?>
                  <?php echo wpautop(wp_kses_post($book_recommendation)); ?>
                <?php else : ?>
                  <?php echo wpautop(wp_kses_post($book_excerpt)); ?>
                <?php endif; ?>
              </div>
            </section>
          <?php endif; ?>

          <?php if ($book_audience !== '') : ?>
            <section class="nc-book-detail-panel">
              <span class="nc-book-detail-section-icon" aria-hidden="true">♟</span>
              <div>
                <h2>Dla kogo jest ta książka?</h2>
                <?php echo wpautop(wp_kses_post($book_audience)); ?>
              </div>
            </section>
          <?php endif; ?>

          <?php if ($book_discover !== '') : ?>
            <section class="nc-book-detail-panel">
              <span class="nc-book-detail-section-icon" aria-hidden="true">✦</span>
              <div>
                <h2>Co warto w niej odkryć?</h2>
                <?php echo wpautop(wp_kses_post($book_discover)); ?>
              </div>
            </section>
          <?php endif; ?>

          <?php if ($book_observations !== '') : ?>
            <section class="nc-book-detail-panel">
              <span class="nc-book-detail-section-icon" aria-hidden="true">•••</span>
              <div>
                <h2>Nasze spostrzeżenia</h2>
                <?php echo wpautop(wp_kses_post($book_observations)); ?>
              </div>
            </section>
          <?php endif; ?>

          <?php if (trim((string) get_the_content()) !== '') : ?>
            <section class="nc-book-detail-content">
              <h2>O książce</h2>
              <?php the_content(); ?>
            </section>
          <?php endif; ?>
        </article>

        <?php if ($similar_books->have_posts()) : ?>
          <aside class="nc-book-detail-similar">
            <h2>Podobne książki</h2>
            <?php while ($similar_books->have_posts()) : $similar_books->the_post(); ?>
              <a class="nc-book-detail-similar-item" href="<?php the_permalink(); ?>">
                <span class="nc-book-detail-similar-cover">
                  <?php if (has_post_thumbnail()) : ?>
                    <?php the_post_thumbnail('thumbnail', ['alt' => esc_attr(get_the_title() . ' — okładka')]); ?>
                  <?php endif; ?>
                </span>
                <span class="nc-book-detail-similar-copy">
                  <strong><?php the_title(); ?></strong>
                  <span><?php echo esc_html((string) get_post_meta(get_the_ID(), '_nc_book_author', true)); ?></span>
                </span>
                <span class="nc-book-detail-similar-arrow" aria-hidden="true">›</span>
              </a>
            <?php endwhile; ?>
          </aside>
        <?php endif; ?>
      </div>
      <?php wp_reset_postdata(); ?>

      <section class="nc-book-detail-buy" id="nc-book-buy">
        <div class="nc-book-detail-buy-copy">
          <span class="nc-book-detail-buy-icon" aria-hidden="true">🛒</span>
          <div>
            <h2>Gdzie kupić?</h2>
            <p>Sprawdź dostępność książki w księgarniach:</p>
          </div>
        </div>
        <?php if ($has_purchase_links) : ?>
          <div class="nc-book-detail-buy-links">
            <?php foreach (['empik' => 'empik', 'allegro' => 'allegro', 'amazon' => 'amazon.pl', 'more' => 'Zobacz więcej ofert'] as $key => $label) : ?>
              <?php if ($purchase_links[$key] !== '') : ?>
                <a href="<?php echo esc_url($purchase_links[$key]); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html($label); ?><?php if ($key === 'more') : ?> <span aria-hidden="true">→</span><?php endif; ?></a>
              <?php endif; ?>
            <?php endforeach; ?>
          </div>
        <?php else : ?>
          <p class="nc-book-detail-buy-empty">Linki do księgarni dodamy, gdy będą dostępne.</p>
        <?php endif; ?>
      </section>

      <footer class="nc-book-detail-footer">
        <a href="<?php echo esc_url($archive_url ?: home_url('/')); ?>"><span aria-hidden="true">←</span> Powrót do listy książek</a>
        <span>Dobra książka. Lepszy dzień.</span>
        <span class="nc-book-detail-footer-mark" aria-hidden="true">📖</span>
      </footer>
    </main>
    <?php
endwhile;

get_footer();
