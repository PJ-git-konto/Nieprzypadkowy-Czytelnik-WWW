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
    $version = wp_get_theme()->get('Version');
    wp_enqueue_style('nc-style', get_stylesheet_uri(), [], $version);
    if (is_front_page()) {
        wp_enqueue_script('nc-home', get_template_directory_uri() . '/assets/js/home.js', [], $version, true);
        wp_localize_script('nc-home', 'ncHome', [
            'endpoint' => esc_url_raw(rest_url('nc/v1/recommendations')),
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
        'supports' => ['title', 'editor', 'excerpt', 'thumbnail'],
        'show_in_rest' => true,
    ]);
}
add_action('init', 'nc_register_book_type');

function nc_add_book_author_box(): void {
    add_meta_box('nc_book_details', __('Dane książki', 'nieprzypadkowy-czytelnik'), 'nc_render_book_author_box', 'ksiazka', 'side');
}
add_action('add_meta_boxes', 'nc_add_book_author_box');

function nc_render_book_author_box(WP_Post $post): void {
    wp_nonce_field('nc_save_book_details', 'nc_book_details_nonce');
    $author = get_post_meta($post->ID, '_nc_book_author', true);
    ?>
    <p><label for="nc_book_author"><?php esc_html_e('Autor / autorka', 'nieprzypadkowy-czytelnik'); ?></label></p>
    <input type="text" id="nc_book_author" name="nc_book_author" value="<?php echo esc_attr((string) $author); ?>" class="widefat">
    <p class="description"><?php esc_html_e('Okładkę dodaj jako obrazek wyróżniający.', 'nieprzypadkowy-czytelnik'); ?></p>
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
}
add_action('save_post_ksiazka', 'nc_save_book_details');

function nc_book_card_data(int $post_id): array {
    $image = get_the_post_thumbnail_url($post_id, 'large');
    return [
        'id' => $post_id,
        'title' => get_the_title($post_id),
        'author' => (string) get_post_meta($post_id, '_nc_book_author', true),
        'excerpt' => get_the_excerpt($post_id),
        'url' => get_permalink($post_id),
        'image' => $image ? esc_url($image) : '',
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
        'no_found_rows' => true,
    ]);
    if ($query->post_count < 3 && $exclude) {
        $query = new WP_Query([
            'post_type' => 'ksiazka',
            'post_status' => 'publish',
            'posts_per_page' => 3,
            'orderby' => 'rand',
            'no_found_rows' => true,
        ]);
    }
    $books = array_map(static fn($post) => nc_book_card_data((int) $post->ID), $query->posts);
    return new WP_REST_Response($books, 200);
}
