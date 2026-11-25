<?php
/**
 * Template Name: Redakcyjne zestawienie książek
 * Description: Szablon stron z kolekcjami z redakcyjnego notesu.
 */
get_header();

$page_slug = get_post_field('post_name', get_queried_object_id());

$editorial_lists = [
    'jesienne-inspiracje' => [
        'intro' => 'Przedłużające się wieczory to czas, by zwolnić tempo, zaparzyć ulubioną herbatę i zanurzyć się w literaturze. Przygotowaliśmy dla Ciebie subiektywny wybór książek, które otulają ciepłem, skłaniają do refleksji i zostają w pamięci na długo po zamknięciu ostatniej strony.',
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
    // Jedna wyróżniona książka oraz dziewięć rekomendacji podzielonych na trzy strony.
    $featured_book_id = 7;
    $autumn_map_book_ids = [8, 9, 22, 23, 24, 25, 26, 27, 28];
    // Autorzy domyślni dla tej kolekcji; zapisane dane książki mają pierwszeństwo.
    $autumn_authors = [
        7 => 'Bolesław Prus',
        8 => 'Henryk Sienkiewicz',
        9 => 'Władysław Reymont',
        22 => 'Charlotte Brontë',
        23 => 'Emily Brontë',
        24 => 'Charles Dickens',
        25 => 'Oscar Wilde',
        26 => 'Frances Hodgson Burnett',
        27 => 'Fiodor Dostojewski',
        28 => 'Louisa May Alcott',
    ];
    $autumn_page = isset($_GET['strona']) ? absint(wp_unslash($_GET['strona'])) : 1;
    $autumn_page = max(1, min(3, $autumn_page));
    $map_page_ids = array_slice($autumn_map_book_ids, ($autumn_page - 1) * 3, 3);
    $autumn_book_ids = array_merge([$featured_book_id], $map_page_ids);
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
    $featured_book = $autumn_book_map[$featured_book_id] ?? null;
    $map_books = [];
    foreach ($map_page_ids as $book_id) {
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
          <p class="nc-autumn-subheading">10 książek, które umilą długie wieczory</p>
          <p class="nc-autumn-intro"><?php echo esc_html($list['intro']); ?></p>
          <span class="nc-autumn-rule" aria-hidden="true"></span>
          <p class="nc-autumn-small-note">Trzy historie. Trzy różne światy. Jeden dobry powód, by zostać dziś trochę dłużej z książką.</p>
        </div>

        <?php if ($featured_book) :
            $featured_id = $featured_book->ID;
            $featured_author = get_post_meta($featured_id, '_nc_book_author', true) ?: ($autumn_authors[$featured_id] ?? '');
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
                    <span>
                      <svg class="nc-category-icon" xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><use href="<?php echo esc_url(get_template_directory_uri() . '/assets/images/category-icons.svg#nc-icon-' . nc_book_category_icon_key($term->slug)); ?>"></use></svg>
                      <?php echo esc_html($term->name); ?>
                    </span>
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
              $author = get_post_meta($book_id, '_nc_book_author', true) ?: ($autumn_authors[$book_id] ?? '');
              $terms = get_the_terms($book_id, 'kategoria_ksiazki');
              ?>
            <article class="nc-autumn-stop">
              <div class="nc-autumn-stop-number"><?php echo esc_html((($autumn_page - 1) * 3) + $index + 2); ?></div>
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
                <p class="nc-autumn-stop-description"><?php echo esc_html(get_the_excerpt($book_id)); ?></p>
                <?php if ($terms && !is_wp_error($terms)) : ?>
                  <div class="nc-autumn-tags">
                    <?php foreach ($terms as $term) : ?>
                      <span>
                        <svg class="nc-category-icon" xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><use href="<?php echo esc_url(get_template_directory_uri() . '/assets/images/category-icons.svg#nc-icon-' . nc_book_category_icon_key($term->slug)); ?>"></use></svg>
                        <?php echo esc_html($term->name); ?>
                      </span>
                    <?php endforeach; ?>
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
        <nav id="nc-autumn-pagination" class="nc-autumn-pagination" aria-label="Strony rekomendacji">
          <?php for ($page_number = 1; $page_number <= 3; $page_number++) : ?>
            <a href="<?php echo esc_url(add_query_arg('strona', $page_number, get_permalink()) . '#nc-autumn-pagination'); ?>"
               class="<?php echo $autumn_page === $page_number ? 'is-active' : ''; ?>"
               <?php echo $autumn_page === $page_number ? 'aria-current="page"' : ''; ?>>
              <?php echo esc_html($page_number); ?>
            </a>
          <?php endfor; ?>
        </nav>
      </section>

      <footer class="nc-autumn-closing">
        <span class="nc-autumn-closing-mark" aria-hidden="true">✦</span>
        <p>Niektóre wieczory najlepiej smakują z dobrą książką.</p>
        <span class="nc-autumn-closing-mark" aria-hidden="true">✦</span>
      </footer>
    </main>
    <?php

elseif ($page_slug === 'podroze-bez-biletu') :
    $travel_categories = nc_travel_categories();
    $travel_page_keys = [
        'polnoc-dzikie-bezkresy' => 'polnoc',
        'morza-wyspy-oceany' => 'morza',
        'podroze-nieznane-wewnetrzne' => 'nieznane',
    ];
    $travel_pages = [];
    foreach ($travel_page_keys as $category_slug => $query_key) {
        $travel_pages[$category_slug] = isset($_GET[$query_key]) ? max(1, absint(wp_unslash($_GET[$query_key]))) : 1;
    }

    // Pierwszy przystanek jest wyróżnioną rekomendacją całej wyprawy.
    $featured_query = new WP_Query([
        'post_type' => 'ksiazka',
        'post_status' => 'publish',
        'posts_per_page' => 1,
        'meta_key' => '_nc_travel_category',
        'meta_value' => 'polnoc-dzikie-bezkresy',
        'orderby' => 'date',
        'order' => 'DESC',
        'no_found_rows' => true,
    ]);
    $featured_book = $featured_query->have_posts() ? $featured_query->posts[0] : null;
    $featured_id = $featured_book ? (int) $featured_book->ID : 0;

    $travel_queries = [];
    foreach ($travel_categories as $category_slug => $category_label) {
        $args = [
            'post_type' => 'ksiazka',
            'post_status' => 'publish',
            'posts_per_page' => 3,
            'paged' => $travel_pages[$category_slug],
            'meta_key' => '_nc_travel_category',
            'meta_value' => $category_slug,
            'orderby' => 'date',
            'order' => 'DESC',
        ];
        if ($category_slug === 'polnoc-dzikie-bezkresy' && $featured_id) {
            $args['post__not_in'] = [$featured_id];
        }
        $travel_queries[$category_slug] = new WP_Query($args);
        $max_pages = (int) $travel_queries[$category_slug]->max_num_pages;
        if ($max_pages > 0 && $travel_pages[$category_slug] > $max_pages) {
            $travel_pages[$category_slug] = $max_pages;
            $args['paged'] = $max_pages;
            $travel_queries[$category_slug] = new WP_Query($args);
        }
    }
    $travel_return_base = get_permalink();
    ?>
    <main class="nc-main nc-travel-page">
      <div class="nc-travel-background" aria-hidden="true"></div>
      <section class="nc-travel-opening">
        <div class="nc-travel-opening-copy">
          <nav class="nc-travel-breadcrumbs" aria-label="Okruszki nawigacyjne">
            <a href="<?php echo esc_url(home_url('/')); ?>">Kolekcje</a><span>/</span><span>Podróże bez biletu</span><span>·</span><span>Literacka ekspedycja</span>
          </nav>
          <h1><?php the_title(); ?></h1>
          <p class="nc-travel-subheading">Literackie podróże w nieznane.</p>
          <p>Są książki, które potrafią przenieść nas na najdalsze krańce ziemi bez wstawania z fotela. To opowieści o nieprzetartych szlakach, dalekich kulturach i wewnętrznych wędrówkach, które na zawsze zmieniają perspektywę.</p>
          <p>Przedstawiamy autorską kolekcję 10 dzieł – Twój literacki paszport w nieznane.</p>
        </div>

        <?php if ($featured_book) :
            $featured_id = (int) $featured_book->ID;
            $featured_author = (string) get_post_meta($featured_id, '_nc_book_author', true);
            $featured_terms = get_the_terms($featured_id, 'kategoria_ksiazki');
            $featured_return = add_query_arg($travel_page_keys, array_values($travel_pages), $travel_return_base);
            $featured_return = add_query_arg('nc_return', rawurlencode($featured_return . '#nc-travel-chapter-polnoc-dzikie-bezkresy'), get_permalink($featured_id));
            ?>
          <article class="nc-travel-featured">
            <a class="nc-travel-featured-cover" href="<?php echo esc_url($featured_return); ?>">
              <?php if (has_post_thumbnail($featured_id)) : ?>
                <?php echo get_the_post_thumbnail($featured_id, 'large', ['alt' => esc_attr(get_the_title($featured_id) . ' — okładka')]); ?>
              <?php else : ?><span class="nc-cover-placeholder">Miejsce<br>na okładkę</span><?php endif; ?>
            </a>
            <div class="nc-travel-featured-copy">
              <p class="nc-travel-station-label"><span aria-hidden="true">◉</span> STACJA #1&nbsp; · &nbsp;GŁÓWNA REKOMENDACJA REDAKCJI</p>
              <h2>1. <a href="<?php echo esc_url($featured_return); ?>"><?php echo esc_html(get_the_title($featured_id)); ?></a></h2>
              <?php if ($featured_author !== '') : ?><p class="nc-travel-author"><?php echo esc_html($featured_author); ?></p><?php endif; ?>
              <p class="nc-travel-featured-description"><?php echo esc_html(get_the_excerpt($featured_id)); ?></p>
              <?php if ($featured_terms && !is_wp_error($featured_terms)) : ?>
                <p class="nc-travel-book-meta">Gatunek: <?php echo esc_html($featured_terms[0]->name); ?> <span>•</span> Kierunek: Północ i dzikie bezkresy</p>
              <?php endif; ?>
              <div class="nc-travel-featured-actions">
                <a class="nc-travel-primary-link" href="<?php echo esc_url($featured_return); ?>">Przeczytaj pełną recenzję <span aria-hidden="true">→</span></a>
                <a class="nc-travel-secondary-link" href="<?php echo esc_url($featured_return); ?>">Zobacz szczegóły książki</a>
              </div>
            </div>
            <div class="nc-travel-compass" aria-hidden="true">✥<small>N<br>W&nbsp;&nbsp;&nbsp;E<br>S</small></div>
          </article>
        <?php else : ?>
          <div class="nc-travel-featured nc-travel-featured-empty">
            <p>Wkrótce pojawi się tu główna rekomendacja redakcji.</p>
          </div>
        <?php endif; ?>
      </section>

      <section class="nc-travel-expedition" aria-labelledby="nc-travel-expedition-title">
        <header class="nc-travel-expedition-heading">
          <span></span>
          <div><h2 id="nc-travel-expedition-title">SZLAK EKSPEDYCJI <b>·</b> DZIEWIĘĆ KOLEJNYCH PRZYSTANKÓW</h2><p>Trasa literackich odkryć przez lądy, oceany i bezkresne przestrzenie</p></div>
          <span></span>
        </header>
        <div class="nc-travel-chapters">
          <?php foreach ($travel_categories as $category_slug => $category_label) :
              $query = $travel_queries[$category_slug];
              $query_key = $travel_page_keys[$category_slug];
              $chapter_return = add_query_arg($travel_page_keys, array_values($travel_pages), $travel_return_base);
              ?>
            <section class="nc-travel-chapter" id="nc-travel-chapter-<?php echo esc_attr($category_slug); ?>">
              <h3><?php echo esc_html($category_label); ?></h3>
              <div class="nc-travel-stops">
                <?php if ($query->have_posts()) :
                    $number_offset = ($travel_pages[$category_slug] - 1) * 3 + ($category_slug === 'polnoc-dzikie-bezkresy' ? 2 : 5);
                    while ($query->have_posts()) : $query->the_post();
                        $book_id = get_the_ID();
                        $author = (string) get_post_meta($book_id, '_nc_book_author', true);
                        $terms = get_the_terms($book_id, 'kategoria_ksiazki');
                        $book_return = add_query_arg('nc_return', rawurlencode($chapter_return . '#nc-travel-chapter-' . $category_slug), get_permalink($book_id));
                        ?>
                  <article class="nc-travel-stop">
                    <span class="nc-travel-stop-number"><?php echo esc_html($number_offset++); ?></span>
                    <a class="nc-travel-stop-cover" href="<?php echo esc_url($book_return); ?>">
                      <?php if (has_post_thumbnail()) : ?><?php the_post_thumbnail('medium', ['alt' => esc_attr(get_the_title() . ' — okładka')]); ?><?php else : ?><span class="nc-cover-placeholder">Okładka</span><?php endif; ?>
                    </a>
                    <div class="nc-travel-stop-copy">
                      <h4><a href="<?php echo esc_url($book_return); ?>"><?php the_title(); ?></a></h4>
                      <?php if ($author !== '') : ?><p class="nc-travel-author"><?php echo esc_html($author); ?></p><?php endif; ?>
                      <p class="nc-travel-stop-description"><?php echo esc_html(get_the_excerpt()); ?></p>
                      <?php if ($terms && !is_wp_error($terms)) : ?><span class="nc-travel-stop-tag"><?php echo esc_html($terms[0]->name); ?></span><?php endif; ?>
                      <a class="nc-travel-read-more" href="<?php echo esc_url($book_return); ?>">Czytaj dalej <span aria-hidden="true">→</span></a>
                    </div>
                  </article>
                <?php endwhile; wp_reset_postdata(); else : ?>
                  <p class="nc-travel-empty">Kolejne przystanki tej trasy są jeszcze przygotowywane.</p>
                <?php endif; ?>
              </div>
              <?php
                $max_pages = (int) $query->max_num_pages;
                if ($max_pages > 1) :
                    $pagination_base = add_query_arg($travel_page_keys, array_values($travel_pages), $travel_return_base);
                    ?>
                <nav class="nc-travel-pagination" aria-label="<?php echo esc_attr('Strony kategorii: ' . $category_label); ?>">
                  <?php if ($travel_pages[$category_slug] > 1) : ?>
                    <a href="<?php echo esc_url(add_query_arg($query_key, $travel_pages[$category_slug] - 1, $pagination_base) . '#nc-travel-chapter-' . $category_slug); ?>">←</a>
                  <?php endif; ?>
                  <?php for ($page_number = 1; $page_number <= $max_pages; $page_number++) : ?>
                    <a href="<?php echo esc_url(add_query_arg($query_key, $page_number, $pagination_base) . '#nc-travel-chapter-' . $category_slug); ?>" class="<?php echo $travel_pages[$category_slug] === $page_number ? 'is-active' : ''; ?>" <?php echo $travel_pages[$category_slug] === $page_number ? 'aria-current="page"' : ''; ?>><?php echo esc_html($page_number); ?></a>
                  <?php endfor; ?>
                  <?php if ($travel_pages[$category_slug] < $max_pages) : ?>
                    <a href="<?php echo esc_url(add_query_arg($query_key, $travel_pages[$category_slug] + 1, $pagination_base) . '#nc-travel-chapter-' . $category_slug); ?>">Następne →</a>
                  <?php endif; ?>
                </nav>
              <?php endif; ?>
            </section>
          <?php endforeach; ?>
        </div>
      </section>
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
                        <span class="nc-book-category">
                          <svg class="nc-category-icon" xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><use href="<?php echo esc_url(get_template_directory_uri() . '/assets/images/category-icons.svg#nc-icon-' . nc_book_category_icon_key($book_category->slug)); ?>"></use></svg>
                          <span><?php echo esc_html($book_category->name); ?></span>
                        </span>
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
