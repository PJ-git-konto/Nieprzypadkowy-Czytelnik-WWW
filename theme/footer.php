<footer class="nc-site-footer">
  <div class="nc-footer-inner">
    <div class="nc-footer-about">
      <div class="nc-footer-brand">Nieprzypadkowy Czytelnik</div>
      <p class="nc-footer-tagline">Dobre książki. Świadome wybory.</p>
      <p class="nc-footer-description">Miejsce dla tych, którzy szukają czegoś więcej niż kolejnej książki na półkę.</p>
    </div>
    <nav class="nc-footer-nav" aria-label="Informacje">
      <h2>Poznaj nas</h2>
      <a href="<?php echo esc_url(home_url('/?page_id=75')); ?>">O nas</a>
      <a href="<?php echo esc_url(home_url('/?page_id=71')); ?>">Kontakt</a>
      <a href="https://www.instagram.com/nieprzypadkowyczytelnik/" target="_blank" rel="noopener noreferrer">Instagram <span aria-hidden="true">↗</span></a>
    </nav>
    <p class="nc-footer-disclosure">Niektóre linki na stronie są afiliacyjne. Dokonując zakupu poprzez nasze rekomendacje, wspierasz rozwój naszego serwisu.</p>
  </div>
  <div class="nc-footer-bottom">
    <span>© <?php echo esc_html(wp_date('Y')); ?> Nieprzypadkowy Czytelnik</span>
    <span>Dobre książki. Świadome wybory.</span>
  </div>
</footer>
<?php wp_footer(); ?>
<script>
(function(){
 const toggle=document.querySelector('.nc-menu-toggle');
 const nav=document.getElementById('nc-primary-nav');
 if(!toggle||!nav)return;
 toggle.addEventListener('click',function(){
  const open=toggle.getAttribute('aria-expanded')==='true';
  toggle.setAttribute('aria-expanded',String(!open));
  nav.classList.toggle('is-open',!open);
 });
})();
</script>
</body>
</html>
