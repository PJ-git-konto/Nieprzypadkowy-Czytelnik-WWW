<?php
/**
 * Template Name: Redakcyjne zestawienie książek
 * Description: Wspólny szablon stron z listami książek z redakcyjnego notesu.
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
                      <svg class="nc-category-icon" xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" aria-hidden="true" focusable="false" style="display:block;width:17px;height:17px;min-width:17px;max-width:17px;flex:0 0 17px;fill:none;stroke:#ad741f;stroke-width:1.45;stroke-linecap:round;stroke-linejoin:round"><use href="<?php echo esc_url(get_template_directory_uri() . '/assets/images/category-icons.svg#nc-icon-' . nc_book_category_icon_key($book_category->slug)); ?>"></use></svg>
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
<?php get_footer(); ?>
