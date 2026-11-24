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
        <span class="nc-editorial-icon" aria-hidden="true"><svg class="nc-editorial-symbol" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" focusable="false"><path d="M10 38C18 31 24 22 36 9" /><path d="M13 34C5 27 7 16 9 10c9 1 17 7 17 16" /><path d="M17 30c-1-7-3-12-7-18M21 25c-1-5-1-9-1-13M25 21c3-6 7-9 12-12-1 8-4 14-11 17" /><path d="M10 38c-2 2-3 4-4 6M10 38l7 2" /><path d="M30 15l5 1M25 22l6 1" opacity=".7"/></svg></span>
        <div><h3>Jesienne inspiracje</h3><p>10 książek, które umilą długie wieczory.</p><a class="nc-editorial-link" href="<?php echo esc_url(get_permalink(get_page_by_path('jesienne-inspiracje'))); ?>">Zobacz listę <b aria-hidden="true">→</b></a></div>
      </article>
      <article class="nc-editorial-item">
        <span class="nc-editorial-icon" aria-hidden="true"><svg class="nc-editorial-symbol" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" focusable="false"><circle cx="24" cy="24" r="17.5"/><circle cx="24" cy="24" r="2.5"/><path d="M24 6.5v4M24 37.5v4M6.5 24h4M37.5 24h4M11.6 11.6l2.8 2.8M33.6 33.6l2.8 2.8M36.4 11.6l-2.8 2.8M14.4 33.6l-2.8 2.8" /><path d="M24 12l3.8 10.2L36 24l-10.2 3.8L24 36l-3.8-8.2L12 24l8.2-3.8L24 12z"/><path d="M24 18.5v11M18.5 24h11" opacity=".65"/></svg></span>
        <div><h3>Podróże bez biletu</h3><p>Literackie podróże w nieznane.</p><a class="nc-editorial-link" href="<?php echo esc_url(get_permalink(get_page_by_path('podroze-bez-biletu'))); ?>">Zobacz listę <b aria-hidden="true">→</b></a></div>
      </article>
      <article class="nc-editorial-item">
        <span class="nc-editorial-icon" aria-hidden="true"><svg class="nc-editorial-symbol" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" focusable="false"><path d="M9 39C12 32 14 24 17 17 20 10 29 6 39 7c-1 10-5 19-12 23-6 4-12 6-18 9z"/><path d="M10 38c8-9 15-17 27-29M18 30l-1-9M24 24l-1-9M29 19l-1-7M18 30l8 1M24 24l8 1M29 19l7 1"/><path d="M8 40l-2 3 4-1M39 7l3-2-1 4" /><path d="M15 35l-3-1M33 13l-1-3" opacity=".7"/></svg></span>
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
