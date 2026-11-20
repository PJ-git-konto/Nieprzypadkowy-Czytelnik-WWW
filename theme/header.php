<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<header class="nc-site-header">
  <div class="nc-container nc-header-inner">
    <a class="nc-brand" href="<?php echo esc_url(home_url('/')); ?>" aria-label="<?php esc_attr_e('Nieprzypadkowy Czytelnik — strona główna', 'nieprzypadkowy-czytelnik'); ?>">
      <img class="nc-brand-logo" src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/logo-nieprzypadkowy-czytelnik.png'); ?>" alt="Nieprzypadkowy Czytelnik — Dobre książki. Świadome wybory.">
    </a>
    <nav class="nc-nav" aria-label="<?php esc_attr_e('Menu główne', 'nieprzypadkowy-czytelnik'); ?>">
      <?php
      wp_nav_menu([
          'theme_location' => 'primary',
          'container' => false,
          'fallback_cb' => static function (): void {
              echo '<ul><li><a href="' . esc_url(home_url('/ksiazki/')) . '">Książki</a></li></ul>';
          },
      ]);
      ?>
    </nav>
  </div>
</header>
