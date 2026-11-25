<?php
declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

function nc_theme_setup(): void {
    load_theme_textdomain('nieprzypadkowy-czytelnik', get_template_directory() . '/languages');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script']);
    register_nav_menus(['primary' => __('Menu główne', 'nieprzypadkowy-czytelnik')]);
}
add_action('after_setup_theme', 'nc_theme_setup');

function nc_enqueue_assets(): void {
    $style_file = get_stylesheet_directory() . '/style.css';
    $style_version = file_exists($style_file) ? (string) filemtime($style_file) : wp_get_theme()->get('Version');
    wp_enqueue_style('nc-style', get_stylesheet_uri(), [], $style_version);
    if (is_front_page()) {
        $home_script = get_template_directory() . '/assets/js/home.js';
        $script_version = file_exists($home_script) ? (string) filemtime($home_script) : wp_get_theme()->get('Version');
        wp_enqueue_script('nc-home', get_template_directory_uri() . '/assets/js/home.js', [], $script_version, true);
        wp_localize_script('nc-home', 'ncHome', [
            'endpoint' => esc_url_raw(rest_url('nc/v1/recommendations')),
            'categoryIconSprite' => esc_url_raw(get_template_directory_uri() . '/assets/images/category-icons.svg'),
            'loading' => __('Szukam kolejnych propozycji…', 'nieprzypadkowy-czytelnik'),
            'error' => __('Nie udało się pobrać kolejnych książek. Spróbuj ponownie.', 'nieprzypadkowy-czytelnik'),
            'empty' => __('Dodajemy kolejne książki do naszej redakcyjnej półki.', 'nieprzypadkowy-czytelnik'),
        ]);
    }
}
add_action('wp_enqueue_scripts', 'nc_enqueue_assets');

function nc_register_book_type(): void {
    register_post_type('ksiazka', [
        'labels' => [
            'name' => __('Książki', 'nieprzypadkowy-czytelnik'),
            'singular_name' => __('Książka', 'nieprzypadkowy-czytelnik'),
            'add_new_item' => __('Dodaj książkę', 'nieprzypadkowy-czytelnik'),
            'edit_item' => __('Edytuj książkę', 'nieprzypadkowy-czytelnik'),
        ],
        'public' => true,
        'has_archive' => true,
        'rewrite' => ['slug' => 'ksiazki'],
        'menu_icon' => 'dashicons-book-alt',
        'supports' => ['title', 'editor', 'excerpt', 'thumbnail', 'custom-fields'],
        'show_in_rest' => true,
    ]);

    // Udostępnia istniejące pole autora w REST API, bez zmiany zapisanych danych.
    register_post_meta('ksiazka', '_nc_book_author', [
        'type' => 'string',
        'single' => true,
        'show_in_rest' => true,
        'sanitize_callback' => 'sanitize_text_field',
        'auth_callback' => static function ($allowed, $meta_key, $post_id): bool {
            return current_user_can('edit_post', $post_id);
        },
    ]);

    register_post_meta('ksiazka', '_nc_travel_category', [
        'type' => 'string',
        'single' => true,
        'show_in_rest' => true,
        'sanitize_callback' => 'sanitize_key',
        'auth_callback' => static function ($allowed, $meta_key, $post_id): bool {
            return current_user_can('edit_post', $post_id);
        },
    ]);

    register_post_meta('ksiazka', '_nc_test_record', [
        'type' => 'boolean',
        'single' => true,
        'show_in_rest' => true,
        'default' => false,
        'auth_callback' => static function ($allowed, $meta_key, $post_id): bool {
            return current_user_can('edit_post', $post_id);
        },
    ]);

    register_taxonomy('kategoria_ksiazki', ['ksiazka'], [
        'labels' => [
            'name' => __('Kategorie książek', 'nieprzypadkowy-czytelnik'),
            'singular_name' => __('Kategoria książki', 'nieprzypadkowy-czytelnik'),
            'search_items' => __('Szukaj kategorii', 'nieprzypadkowy-czytelnik'),
            'all_items' => __('Wszystkie kategorie', 'nieprzypadkowy-czytelnik'),
            'edit_item' => __('Edytuj kategorię', 'nieprzypadkowy-czytelnik'),
            'add_new_item' => __('Dodaj kategorię książki', 'nieprzypadkowy-czytelnik'),
            'menu_name' => __('Kategorie książek', 'nieprzypadkowy-czytelnik'),
        ],
        'public' => true,
        'hierarchical' => true,
        'show_admin_column' => true,
        'show_in_rest' => true,
        'rewrite' => ['slug' => 'kategoria-ksiazki'],
    ]);
}
add_action('init', 'nc_register_book_type');

function nc_add_book_author_box(): void {
    add_meta_box('nc_book_details', __('Dane książki', 'nieprzypadkowy-czytelnik'), 'nc_render_book_author_box', 'ksiazka', 'side');
}
add_action('add_meta_boxes', 'nc_add_book_author_box');

function nc_travel_categories(): array {
    return [
        'polnoc-dzikie-bezkresy' => __('Północ i dzikie bezkresy', 'nieprzypadkowy-czytelnik'),
        'morza-wyspy-oceany' => __('Morza, wyspy i oceany', 'nieprzypadkowy-czytelnik'),
        'podroze-nieznane-wewnetrzne' => __('Podróże w nieznane i wewnętrzne', 'nieprzypadkowy-czytelnik'),
    ];
}

function nc_render_book_author_box(WP_Post $post): void {
    wp_nonce_field('nc_save_book_details', 'nc_book_details_nonce');
    $author = get_post_meta($post->ID, '_nc_book_author', true);
    $travel_category = (string) get_post_meta($post->ID, '_nc_travel_category', true);
    $is_test_record = (bool) get_post_meta($post->ID, '_nc_test_record', true);
    ?>
    <p><label for="nc_book_author"><?php esc_html_e('Autor / autorka', 'nieprzypadkowy-czytelnik'); ?></label></p>
    <input type="text" id="nc_book_author" name="nc_book_author" value="<?php echo esc_attr((string) $author); ?>" class="widefat">
    <hr>
    <p><label for="nc_travel_category"><?php esc_html_e('Kategoria podróży', 'nieprzypadkowy-czytelnik'); ?></label></p>
    <select id="nc_travel_category" name="nc_travel_category" class="widefat">
      <option value=""><?php esc_html_e('— Wybierz kategorię —', 'nieprzypadkowy-czytelnik'); ?></option>
      <?php foreach (nc_travel_categories() as $slug => $label) : ?>
        <option value="<?php echo esc_attr($slug); ?>" <?php selected($travel_category, $slug); ?>><?php echo esc_html($label); ?></option>
      <?php endforeach; ?>
    </select>
    <hr>
    <p><label><input type="checkbox" name="nc_test_record" value="1" <?php checked($is_test_record); ?>> <?php esc_html_e('Rekord testowy', 'nieprzypadkowy-czytelnik'); ?></label></p>
    <p class="description"><?php esc_html_e('Rekordy testowe są pomijane w zwykłych wynikach i rekomendacjach, ale mogą być pokazywane w kolekcjach testowych.', 'nieprzypadkowy-czytelnik'); ?></p>
    <p class="description"><?php esc_html_e('Okładkę dodaj jako obrazek wyróżniający. Kategoria podróży jest niezależna od kategorii literackich.', 'nieprzypadkowy-czytelnik'); ?></p>
    <?php
}

function nc_save_book_details(int $post_id): void {
    if (!isset($_POST['nc_book_details_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nc_book_details_nonce'])), 'nc_save_book_details')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    if (isset($_POST['nc_book_author'])) {
        update_post_meta($post_id, '_nc_book_author', sanitize_text_field(wp_unslash($_POST['nc_book_author'])));
    }
    update_post_meta($post_id, '_nc_test_record', isset($_POST['nc_test_record']) && $_POST['nc_test_record'] === '1' ? 1 : 0);
    if (isset($_POST['nc_travel_category'])) {
        $travel_category = sanitize_key(wp_unslash($_POST['nc_travel_category']));
        if (array_key_exists($travel_category, nc_travel_categories())) {
            update_post_meta($post_id, '_nc_travel_category', $travel_category);
        } elseif ($travel_category === '') {
            delete_post_meta($post_id, '_nc_travel_category');
        }
    }
}
add_action('save_post_ksiazka', 'nc_save_book_details');

/**
 * Dobiera emblemat do znaczenia kategorii, a nie do jej dosłownej nazwy.
 * Nowe kategorie bez własnej reguły otrzymują neutralny symbol książki.
 */
/**
 * Warunek wykluczający rekordy testowe z publicznych list książek.
 */
function nc_exclude_test_books_meta_query(): array {
    return [
        'relation' => 'OR',
        ['key' => '_nc_test_record', 'compare' => 'NOT EXISTS'],
        ['key' => '_nc_test_record', 'value' => '1', 'compare' => '!='],
    ];
}

/**
 * Ukrywa rekordy testowe w głównych publicznych zapytaniach WordPressa.
 */
function nc_exclude_test_books_from_main_queries(WP_Query $query): void {
    if (is_admin() || !$query->is_main_query() || (!$query->is_post_type_archive('ksiazka') && !$query->is_search() && !$query->is_home())) {
        return;
    }
    $post_type = $query->get('post_type');
    if ($post_type && $post_type !== 'ksiazka' && !(is_array($post_type) && in_array('ksiazka', $post_type, true))) {
        return;
    }
    $meta_query = (array) $query->get('meta_query');
    $meta_query[] = nc_exclude_test_books_meta_query();
    $query->set('meta_query', $meta_query);
}
add_action('pre_get_posts', 'nc_exclude_test_books_from_main_queries');

function nc_book_category_icon_key(string $slug): string {
    $icons = [
        'klasyka' => 'laurel',
        'literatura' => 'leaf',
        'literatura-piekna' => 'leaf',
        'powiesc-historyczna' => 'compass',
        'powiesc-obyczajowa' => 'flower',
        'psychologia' => 'star',
        'powiesc-psychologiczna' => 'star',
        'kryminal' => 'magnifier',
        'sensacja' => 'magnifier',
        'fantasy' => 'moon',
        'science-fiction' => 'orbit',
        'biografia' => 'portrait',
        'poradniki' => 'lightbulb',
        'rozwoj-osobisty' => 'lightbulb',
        'poezja' => 'quill',
    ];

    return $icons[$slug] ?? 'book';
}

function nc_book_card_data(int $post_id): array {
    $image = get_the_post_thumbnail_url($post_id, 'large');
    $terms = get_the_terms($post_id, 'kategoria_ksiazki');
    $categories = [];

    if ($terms && !is_wp_error($terms)) {
        foreach (array_slice($terms, 0, 1) as $term) {
            $categories[] = [
                'name' => $term->name,
                'slug' => $term->slug,
                'icon' => nc_book_category_icon_key($term->slug),
            ];
        }
    }

    return [
        'id' => $post_id,
        'title' => get_the_title($post_id),
        'author' => (string) get_post_meta($post_id, '_nc_book_author', true),
        'excerpt' => wp_trim_words(get_the_excerpt($post_id), 16, '…'),
        'url' => get_permalink($post_id),
        'image' => $image ? esc_url($image) : '',
        'categories' => $categories,
    ];
}

function nc_register_recommendations_route(): void {
    register_rest_route('nc/v1', '/recommendations', [
        'methods' => WP_REST_Server::READABLE,
        'callback' => 'nc_get_random_recommendations',
        'permission_callback' => '__return_true',
    ]);
}
add_action('rest_api_init', 'nc_register_recommendations_route');

function nc_get_random_recommendations(WP_REST_Request $request): WP_REST_Response {
    $exclude = array_filter(array_map('absint', explode(',', (string) $request->get_param('exclude'))));
    $query = new WP_Query([
        'post_type' => 'ksiazka',
        'post_status' => 'publish',
        'posts_per_page' => 3,
        'orderby' => 'rand',
        'post__not_in' => $exclude,
        'meta_query' => [nc_exclude_test_books_meta_query()],
        'no_found_rows' => true,
    ]);
    if ($query->post_count < 3 && $exclude) {
        $query = new WP_Query([
            'post_type' => 'ksiazka',
            'post_status' => 'publish',
            'posts_per_page' => 3,
            'orderby' => 'rand',
            'meta_query' => [nc_exclude_test_books_meta_query()],
            'no_found_rows' => true,
        ]);
    }
    $books = array_map(static fn($post) => nc_book_card_data((int) $post->ID), $query->posts);
    return new WP_REST_Response($books, 200);
}
