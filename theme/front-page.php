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
    <div class="nc-container">
      <span class="nc-eyebrow">Nieprzypadkowy Czytelnik</span>
      <h1>Odkrywaj dobre książki</h1>
      <p>Starannie wybieramy tytuły, do których warto zajrzeć. Bez przypadkowych rekomendacji — z ciekawością, uważnością i miejscem na własne odkrycia.</p>
    </div>
  </section>

  <section class="nc-section" aria-labelledby="nc-recommendations-title">
    <div class="nc-container">
      <div class="nc-section-heading">
        <div>
          <h2 id="nc-recommendations-title">Poznaj nasze wybory</h2>
          <p>Trzy książki, od których możesz zacząć.</p>
        </div>
      </div>

      <div class="nc-home-grid">
        <div>
          <div class="nc-book-grid" id="nc-book-grid" aria-live="polite">
            <?php if ($books_query->have_posts()) : ?>
              <?php while ($books_query->have_posts()) : $books_query->the_post(); ?>
                <article class="nc-book-card" data-book-id="<?php the_ID(); ?>">
                  <a class="nc-book-cover" href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr(sprintf(__('Przejdź do książki: %s', 'nieprzypadkowy-czytelnik'), get_the_title())); ?>">
                    <?php if (has_post_thumbnail()) : ?>
                      <?php the_post_thumbnail('large'); ?>
                    <?php else : ?>
                      <span class="nc-cover-placeholder">Nieprzypadkowy<br>Czytelnik</span>
                    <?php endif; ?>
                  </a>
                  <div>
                    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                    <?php $author = get_post_meta(get_the_ID(), '_nc_book_author', true); ?>
                    <?php if ($author) : ?><p class="nc-book-author"><?php echo esc_html($author); ?></p><?php endif; ?>
                    <p class="nc-book-excerpt"><?php echo esc_html(get_the_excerpt()); ?></p>
                    <a class="nc-read-more" href="<?php the_permalink(); ?>">Poznaj tę książkę</a>
                  </div>
                </article>
              <?php endwhile; wp_reset_postdata(); ?>
            <?php else : ?>
              <div class="nc-empty"><?php esc_html_e('Pierwsze rekomendacje pojawią się tutaj po dodaniu książek do redakcyjnej półki.', 'nieprzypadkowy-czytelnik'); ?></div>
            <?php endif; ?>
          </div>
          <div class="nc-actions">
            <button class="nc-button" id="nc-next-books" type="button">Pokaż mi kolejne trzy książki</button>
          </div>
          <p class="nc-status" id="nc-books-status" role="status" aria-live="polite"></p>
        </div>

        <aside class="nc-sidebar" aria-labelledby="nc-discover-title">
          <h2 id="nc-discover-title">Warto odkryć</h2>
          <p>Redakcyjne zestawienia i tematy, które pomagają znaleźć książkę dla siebie.</p>
          <?php
          $categories = get_categories(['hide_empty' => true, 'number' => 5]);
          if ($categories) :
          ?>
            <ul>
              <?php foreach ($categories as $category) : ?>
                <li><a href="<?php echo esc_url(get_category_link($category)); ?>"><?php echo esc_html($category->name); ?></a></li>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>
        </aside>
      </div>
    </div>
  </section>
</main>
<?php get_footer(); ?>
