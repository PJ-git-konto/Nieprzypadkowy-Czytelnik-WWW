<?php
/**
 * Wspólne emblematy kategorii książki.
 * Wymaga zmiennych: $book_categories oraz opcjonalnie $nc_category_limit.
 */
if (!isset($book_categories) || !$book_categories || is_wp_error($book_categories)) {
    return;
}
$nc_category_limit = isset($nc_category_limit) ? max(1, (int) $nc_category_limit) : 3;
?>
<div class="nc-book-category-list" aria-label="Kategorie książki">
  <?php foreach (array_slice($book_categories, 0, $nc_category_limit) as $book_category) : ?>
    <span class="nc-book-category">
      <svg class="nc-category-icon" xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
        <use href="<?php echo esc_url(get_template_directory_uri() . '/assets/images/category-icons.svg#nc-icon-' . nc_book_category_icon_key($book_category->slug)); ?>"></use>
      </svg>
      <span><?php echo esc_html($book_category->name); ?></span>
    </span>
  <?php endforeach; ?>
</div>
