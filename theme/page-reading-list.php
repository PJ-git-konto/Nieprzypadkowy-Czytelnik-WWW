<?php
/**
 * Template Name: Redakcyjne zestawienie książek
 * Description: Szablon stron z kolekcjami z redakcyjnego notesu.
 */
get_header();

$page_slug = get_post_field('post_name', get_queried_object_id());

$editorial_lists = [
    'jesienne-inspiracje' => [
        'intro' => 'Jesienne wieczory sprzyjają czytaniu. Przygotowaliśmy zestaw książek, z którymi można zwolnić tempo, zanurzyć się w opowieści i na chwilę odciąć od codziennego pośpiechu.',
        'eyebrow' => 'Na długie wieczory',
    ],
    'podroze-bez-biletu' => [
        'intro' => 'Nie zawsze trzeba pakować walizkę, żeby wyruszyć w drogę. Dobre historie przenoszą do innych miejsc, czasów i światów — wystarczy otworzyć książkę.',
        'eyebrow' => 'Wyrusz w literacką podróż',
    ],
    'klasyka-ktora-wciaz-zachwyca' => [
        'intro' => 'Są książki, do których kolejne pokolenia czytelników wracają z podobnymi emocjami. Ta lista przypomina o klasyce, która nie traci siły opowiadania.',
        'eyebrow' => 'Ponadczasowe opowieści',
    ],
];
$list = $editorial_lists[$page_slug] ?? [
    'intro' => 'Redakcyjne zestawienie książek, które warto poznać.',
    'eyebrow' => 'Z redakcyjnego notesu',
];

if ($page_slug === 'jesienne-inspiracje') :
    // Na etapie pierwszego podglądu korzystamy wyłącznie z trzech istniejących książek.
    $autumn_book_ids = [7, 8, 9];
    $autumn_books = new WP_Query([
        'post_type' => 'ksiazka',
        'post_status' => 'publish',
        'post__in' => $autumn_book_ids,
        'orderby' => 'post__in',
        'posts_per_page' => count($autumn_book_ids),
        'no_found_rows' => true,
    ]);
    $autumn_book_map = [];
    if ($autumn_books->have_posts()) {
        while ($autumn_books->have_posts()) {
            $autumn_books->the_post();
            $autumn_book_map[get_the_ID()] = get_post();
        }
        wp_reset_postdata();
    }
    $featured_book = $autumn_book_map[7] ?? null;
    $map_books = [];
    foreach ([8, 9] as $book_id) {
        if (isset($autumn_book_map[$book_id])) {
            $map_books[] = $autumn_book_map[$book_id];
        }
    }
    ?>
    <main class="nc-main nc-container nc-autumn-page">
      <section class="nc-autumn-opening">
        <div class="nc-autumn-opening-copy">
          <a class="nc-reading-list-back" href="<?php echo esc_url(home_url('/')); ?>">← Strona główna</a>
          <p class="nc-autumn-eyebrow"><?php echo esc_html($list['eyebrow']); ?></p>
          <h1><?php the_title(); ?></h1>
          <p class="nc-autumn-intro"><?php echo esc_html($list['intro']); ?></p>
          <span class="nc-autumn-rule" aria-hidden="true"></span>
          <p class="nc-autumn-small-note">Trzy historie. Trzy różne światy. Jeden dobry powód, by zostać dziś trochę dłużej z książką.</p>
        </div>

        <?php if ($featured_book) :
            $featured_id = $featured_book->ID;
            $featured_author = get_post_meta($featured_id, '_nc_book_author', true);
            $featured_terms = get_the_terms($featured_id, 'kategoria_ksiazki');
            ?>
          <article class="nc-autumn-featured">
            <div class="nc-autumn-featured-cover">
              <a href="<?php echo esc_url(get_permalink($featured_id)); ?>" aria-label="<?php echo esc_attr('Przejdź do książki: ' . get_the_title($featured_id)); ?>">
                <?php if (has_post_thumbnail($featured_id)) : ?>
                  <?php echo get_the_post_thumbnail($featured_id, 'large', ['alt' => esc_attr(get_the_title($featured_id) . ' — okładka')]); ?>
                <?php else : ?>
                  <span class="nc-cover-placeholder">Miejsce<br>na okładkę</span>
                <?php endif; ?>
              </a>
            </div>
            <div class="nc-autumn-featured-copy">
              <p class="nc-autumn-featured-label">Wyróżniona lektura</p>
              <h2><a href="<?php echo esc_url(get_permalink($featured_id)); ?>"><?php echo esc_html(get_the_title($featured_id)); ?></a></h2>
              <?php if ($featured_author) : ?><p class="nc-autumn-author"><?php echo esc_html($featured_author); ?></p><?php endif; ?>
              <p class="nc-autumn-description"><?php echo esc_html(get_the_excerpt($featured_id)); ?></p>
              <?php if ($featured_terms && !is_wp_error($featured_terms)) : ?>
                <div class="nc-autumn-tags" aria-label="Kategorie książki">
                  <?php foreach ($featured_terms as $term) : ?>
                    <span><?php echo esc_html($term->name); ?></span>
                  <?php endforeach; ?>
                </div>
              <?php endif; ?>
              <a class="nc-autumn-cta" href="<?php echo esc_url(get_permalink($featured_id)); ?>">Poznaj tę książkę <span aria-hidden="true">→</span></a>
            </div>
          </article>
        <?php endif; ?>
      </section>

      <nav class="nc-autumn-filters" aria-label="Filtry kolekcji">
        <a class="is-active" href="#nc-autumn-map">Wszystkie (10)</a>
        <a href="#nc-autumn-map">Przytulne historie</a>
        <a href="#nc-autumn-map">Mroczne tajemnice</a>
        <a href="#nc-autumn-map">Sagi i wspomnienia</a>
        <a href="#nc-autumn-map">Literatura faktu</a>
      </nav>

      <section class="nc-autumn-map" id="nc-autumn-map" aria-labelledby="nc-autumn-map-title">
        <header class="nc-autumn-section-heading">
          <h2 id="nc-autumn-map-title">Literacka mapa<br>jesiennych wieczorów</h2>
          <p>Ścieżka nastrojów i kolejnych rekomendacji</p>
        </header>
        <div class="nc-autumn-stops">
          <?php foreach ($map_books as $index => $book) :
              $book_id = $book->ID;
              $author = get_post_meta($book_id, '_nc_book_author', true);
              $terms = get_the_terms($book_id, 'kategoria_ksiazki');
              ?>
            <article class="nc-autumn-stop">
              <div class="nc-autumn-stop-number"><?php echo esc_html($index + 2); ?></div>
              <a class="nc-autumn-stop-cover" href="<?php echo esc_url(get_permalink($book_id)); ?>">
                <?php if (has_post_thumbnail($book_id)) : ?>
                  <?php echo get_the_post_thumbnail($book_id, 'medium', ['alt' => esc_attr(get_the_title($book_id) . ' — okładka')]); ?>
                <?php else : ?>
                  <span class="nc-cover-placeholder">Miejsce<br>na okładkę</span>
                <?php endif; ?>
              </a>
              <div class="nc-autumn-stop-copy">
                <h3><a href="<?php echo esc_url(get_permalink($book_id)); ?>"><?php echo esc_html(get_the_title($book_id)); ?></a></h3>
                <?php if ($author) : ?><p class="nc-autumn-author"><?php echo esc_html($author); ?></p><?php endif; ?>
                <p><?php echo esc_html(get_the_excerpt($book_id)); ?></p>
                <?php if ($terms && !is_wp_error($terms)) : ?>
                  <div class="nc-autumn-tags">
                    <?php foreach ($terms as $term) : ?><span><?php echo esc_html($term->name); ?></span><?php endforeach; ?>
                  </div>
                <?php endif; ?>
                <a class="nc-autumn-text-link" href="<?php echo esc_url(get_permalink($book_id)); ?>">Zobacz recenzję <span aria-hidden="true">→</span></a>
              </div>
            </article>
          <?php endforeach; ?>
          <?php if (count($map_books) < 3) : ?>
            <article class="nc-autumn-stop nc-autumn-stop-placeholder" aria-label="Kolejna rekomendacja w przygotowaniu">
              <div class="nc-autumn-stop-number"><?php echo esc_html(count($map_books) + 2); ?></div>
              <div class="nc-autumn-placeholder-cover" aria-hidden="true">✦</div>
              <div class="nc-autumn-stop-copy">
                <h3>Kolejny przystanek</h3>
                <p class="nc-autumn-placeholder-note">Przygotowujemy kolejne literackie odkrycie na długie jesienne wieczory.</p>
                <span class="nc-autumn-text-link">Wkrótce na mapie</span>
              </div>
            </article>
          <?php endif; ?>
        </div>
      </section>

      <footer class="nc-autumn-closing">
        <span class="nc-autumn-closing-mark" aria-hidden="true">✦</span>
        <p>Niektóre wieczory najlepiej smakują z dobrą książką.</p>
        <span class="nc-autumn-closing-mark" aria-hidden="true">✦</span>
      </footer>
    </main>
    <?php
else :
    $books_query = new WP_Query([
        'post_type' => 'ksiazka',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'orderby' => 'title',
        'order' => 'ASC',
        'no_found_rows' => true,
    ]);
    ?>
    <main class="nc-main nc-container nc-reading-list">
      <header class="nc-reading-list-header">
        <a class="nc-reading-list-back" href="<?php echo esc_url(home_url('/')); ?>">← Strona główna</a>
        <p class="nc-reading-list-eyebrow"><?php echo esc_html($list['eyebrow']); ?></p>
        <h1><?php the_title(); ?></h1>
        <p class="nc-reading-list-intro"><?php echo esc_html($list['intro']); ?></p>
      </header>

      <section class="nc-reading-list-books" aria-labelledby="nc-reading-list-books-title">
        <div class="nc-section-title">
          <span></span><h2 id="nc-reading-list-books-title">Książki w zestawieniu</h2><span></span>
        </div>
        <p class="nc-reading-list-test-note">To testowa wersja zestawienia — na każdej z trzech stron prezentujemy obecnie tę samą bazę trzech książek.</p>
        <?php if ($books_query->have_posts()) : ?>
          <div class="nc-reading-list-grid">
            <?php while ($books_query->have_posts()) : $books_query->the_post(); ?>
              <article class="nc-reading-book-card">
                <a class="nc-reading-book-cover" href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr(sprintf(__('Przejdź do książki: %s', 'nieprzypadkowy-czytelnik'), get_the_title())); ?>">
                  <?php if (has_post_thumbnail()) : ?>
                    <?php the_post_thumbnail('large'); ?>
                  <?php else : ?>
                    <span class="nc-cover-placeholder">Miejsce<br>na okładkę</span>
                  <?php endif; ?>
                </a>
                <div class="nc-reading-book-details">
                  <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                  <?php $author = get_post_meta(get_the_ID(), '_nc_book_author', true); ?>
                  <?php if ($author) : ?><p class="nc-book-author"><?php echo esc_html($author); ?></p><?php endif; ?>
                  <p class="nc-book-excerpt"><?php echo esc_html(get_the_excerpt()); ?></p>
                  <?php $book_categories = get_the_terms(get_the_ID(), 'kategoria_ksiazki'); ?>
                  <?php if ($book_categories && !is_wp_error($book_categories)) : ?>
                    <div class="nc-book-category-list" aria-label="Kategorie książki">
                      <?php foreach ($book_categories as $book_category) : ?>
                        <span class="nc-book-category"><?php echo esc_html($book_category->name); ?></span>
                      <?php endforeach; ?>
                    </div>
                  <?php endif; ?>
                </div>
              </article>
            <?php endwhile; wp_reset_postdata(); ?>
          </div>
        <?php else : ?>
          <p class="nc-reading-list-empty">W tym zestawieniu pojawią się książki, gdy dodamy je do redakcyjnej biblioteki.</p>
        <?php endif; ?>
      </section>
    </main>
    <?php
endif;
get_footer();
