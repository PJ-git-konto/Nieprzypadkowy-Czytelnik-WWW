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
  <div class="nc-header-inner">
    <a class="nc-brand" href="<?php echo esc_url(home_url('/')); ?>" aria-label="<?php esc_attr_e('Nieprzypadkowy Czytelnik — strona główna', 'nieprzypadkowy-czytelnik'); ?>">
      <img class="nc-brand-logo" src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/logo-poziome-v2.png'); ?>" alt="Nieprzypadkowy Czytelnik">
    </a>
    <p class="nc-header-tagline">Dobre książki. Świadome wybory.</p>
    <button class="nc-menu-toggle" type="button" aria-expanded="false" aria-controls="nc-primary-nav">
      <span class="screen-reader-text">Otwórz menu</span><span></span><span></span><span></span>
    </button>
    <nav class="nc-nav" id="nc-primary-nav" aria-label="<?php esc_attr_e('Menu główne', 'nieprzypadkowy-czytelnik'); ?>">
      <?php
      wp_nav_menu([
          'theme_location' => 'primary',
          'container' => false,
          'fallback_cb' => static function (): void {
              echo '<ul class="menu"><li class="current-menu-item"><a href="' . esc_url(home_url('/')) . '">Strona główna</a></li><li><a href="' . esc_url(home_url('/ksiazki/')) . '">Kolekcje</a></li><li><a href="' . esc_url(home_url('/o-nas/')) . '">O nas</a></li><li><a href="' . esc_url(home_url('/blog/')) . '">Blog</a></li></ul>';
          },
      ]);
      ?>
      <form class="nc-search" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
        <label class="screen-reader-text" for="nc-search-field">Szukaj</label>
        <input id="nc-search-field" type="search" name="s" placeholder="Szukaj…" value="<?php echo esc_attr(get_search_query()); ?>">
        <button type="submit" aria-label="Szukaj"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="10.8" cy="10.8" r="7.2"></circle><path d="m16.2 16.2 5 5"></path></svg></button>
      </form>
    </nav>
  </div>
</header>
