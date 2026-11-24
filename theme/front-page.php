<?php
get_header();
$books_query = new WP_Query([
    'post_type' => 'ksiazka',
    'post_status' => 'publish',
    'posts_per_page' => 3,
    'orderby' => 'rand',
    'no_found_rows' => true,
]);
?>
<main class="nc-main">
  <section class="nc-hero">
    <div class="nc-hero-inner">
      <img class="nc-hero-image" src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/hero-ksiazki-kubek.png'); ?>" alt="Książki ułożone obok kubka z kawą">
      <div class="nc-hero-copy">
      <h1>Odkrywaj książki,<br>które warto czytać.</h1>
      <p>Nieprzypadkowy Czytelnik to miejsce dla wszystkich,<br>którzy szukają dobrych książek – z sensem, emocjami i duszą.</p>
      <a class="nc-button nc-button-gold" href="<?php echo esc_url(home_url('/o-nas/')); ?>">Dowiedz się więcej <span aria-hidden="true">→</span></a>
      </div>
    </div>
  </section>

  <section class="nc-featured" aria-labelledby="nc-recommendations-title">
    <div class="nc-featured-main">
      <div class="nc-section-title">
        <span></span><h2 id="nc-recommendations-title">Wybrane dla Ciebie</h2><span></span>
      </div>
      <div class="nc-book-grid" id="nc-book-grid" aria-live="polite">
        <?php if ($books_query->have_posts()) : ?>
          <?php while ($books_query->have_posts()) : $books_query->the_post(); ?>
            <article class="nc-book-card" data-book-id="<?php the_ID(); ?>">
              <a class="nc-book-cover" href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr(sprintf(__('Przejdź do książki: %s', 'nieprzypadkowy-czytelnik'), get_the_title())); ?>">
                <?php if (has_post_thumbnail()) : ?>
                  <?php the_post_thumbnail('large'); ?>
                <?php else : ?>
                  <span class="nc-cover-placeholder">Miejsce<br>na okładkę</span>
                <?php endif; ?>
              </a>
              <div class="nc-book-details">
                <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                <?php $author = get_post_meta(get_the_ID(), '_nc_book_author', true); ?>
                <p class="nc-book-author"><?php echo esc_html($author); ?></p>
                <p class="nc-book-excerpt"><?php echo esc_html(wp_trim_words(get_the_excerpt(), 16, '…')); ?></p>
                <?php $book_categories = get_the_terms(get_the_ID(), 'kategoria_ksiazki'); ?>
                <div class="nc-book-category-list" aria-label="Kategorie książki">
                  <?php if ($book_categories && !is_wp_error($book_categories)) : ?>
                    <?php foreach (array_slice($book_categories, 0, 1) as $book_category) : ?>
                      <span class="nc-book-category">
                        <svg class="nc-category-icon" xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" aria-hidden="true" focusable="false" style="display:block;width:17px;height:17px;min-width:17px;max-width:17px;flex:0 0 17px;fill:none;stroke:#ad741f;stroke-width:1.45;stroke-linecap:round;stroke-linejoin:round"><use href="<?php echo esc_url(get_template_directory_uri() . '/assets/images/category-icons.svg#nc-icon-' . nc_book_category_icon_key($book_category->slug)); ?>"></use></svg>
                        <span><?php echo esc_html($book_category->name); ?></span>
                      </span>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </div>
              </div>
            </article>
          <?php endwhile; wp_reset_postdata(); ?>
        <?php else : ?>
          <div class="nc-empty">Pierwsze rekomendacje pojawią się tutaj po dodaniu książek do redakcyjnej półki.</div>
        <?php endif; ?>
      </div>
      <div class="nc-actions">
        <button class="nc-button" id="nc-next-books" type="button">Pokaż mi kolejne trzy książki <span aria-hidden="true">→</span></button>
      </div>
      <p class="nc-status" id="nc-books-status" role="status" aria-live="polite"></p>
    </div>

    <aside class="nc-editorial" aria-labelledby="nc-editorial-title">
      <h2 id="nc-editorial-title">Z redakcyjnego notesu</h2>
      <article class="nc-editorial-item">
        <span class="nc-editorial-icon" aria-hidden="true"><svg class="nc-editorial-symbol" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" focusable="false"><use href="<?php echo esc_url(get_template_directory_uri() . '/assets/images/category-icons.svg#nc-icon-leaf'); ?>"></use></svg></span>
        <div><h3>Jesienne inspiracje</h3><p>10 książek, które umilą długie wieczory.</p><a class="nc-editorial-link" href="<?php echo esc_url(get_permalink(get_page_by_path('jesienne-inspiracje'))); ?>">Zobacz listę <b aria-hidden="true">→</b></a></div>
      </article>
      <article class="nc-editorial-item">
        <span class="nc-editorial-icon" aria-hidden="true"><svg class="nc-editorial-symbol" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" focusable="false"><use href="<?php echo esc_url(get_template_directory_uri() . '/assets/images/category-icons.svg#nc-icon-compass'); ?>"></use></svg></span>
        <div><h3>Podróże bez biletu</h3><p>Literackie podróże w nieznane.</p><a class="nc-editorial-link" href="<?php echo esc_url(get_permalink(get_page_by_path('podroze-bez-biletu'))); ?>">Zobacz listę <b aria-hidden="true">→</b></a></div>
      </article>
      <article class="nc-editorial-item">
        <span class="nc-editorial-icon" aria-hidden="true"><svg class="nc-editorial-symbol" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" focusable="false"><use href="<?php echo esc_url(get_template_directory_uri() . '/assets/images/category-icons.svg#nc-icon-quill'); ?>"></use></svg></span>
        <div><h3>Klasyka, która wciąż zachwyca</h3><p>Ponadczasowe książki na każdą okazję.</p><a class="nc-editorial-link" href="<?php echo esc_url(get_permalink(get_page_by_path('klasyka-ktora-wciaz-zachwyca'))); ?>">Zobacz listę <b aria-hidden="true">→</b></a></div>
      </article>
    </aside>
  </section>

  <section class="nc-quote-band">
    <div class="nc-open-book-placeholder" role="img" aria-label="Miejsce na ilustrację otwartej książki"><span>Miejsce na ilustrację</span></div>
    <p>Dobra książka to zawsze dobry wybór.</p>
    <a class="nc-button nc-button-gold" href="<?php echo esc_url(home_url('/o-nas/')); ?>">Dołącz do nas <span aria-hidden="true">→</span></a>
  </section>
</main>
<?php get_footer(); ?>
