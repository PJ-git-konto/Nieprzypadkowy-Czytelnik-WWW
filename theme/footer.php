<footer class="nc-site-footer">
  <div class="nc-footer-inner">
    <div><div class="nc-footer-brand">Nieprzypadkowy Czytelnik</div><p class="nc-footer-note">Dobre książki. Świadome wybory.</p></div>
    <div class="nc-footer-links">
      <a href="<?php echo esc_url(home_url('/ksiazki/')); ?>">Kolekcje</a>
      <a href="<?php echo esc_url(home_url('/polityka-prywatnosci/')); ?>">Prywatność</a>
    </div>
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
