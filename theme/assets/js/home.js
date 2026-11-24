(function () {
  const button = document.getElementById('nc-next-books');
  const grid = document.getElementById('nc-book-grid');
  const status = document.getElementById('nc-books-status');
  if (!button || !grid || !window.ncHome) return;

  const escapeHtml = (value) => String(value || '').replace(/[&<>"']/g, (char) => ({
    '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;'
  }[char]));

  const renderCategories = (categories) => {
    if (!Array.isArray(categories)) categories = [];
    return '<div class="nc-book-category-list" aria-label="Kategorie książki">' +
      categories.map((category) => {
        const name = escapeHtml(category.name);
        const icon = /^[a-z-]+$/.test(category.icon || '') ? category.icon : 'book';
        const sprite = escapeHtml(window.ncHome.categoryIconSprite);
        return '<span class="nc-book-category">' +
          '<svg class="nc-category-icon" xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" aria-hidden="true" focusable="false" style="display:block;width:17px;height:17px;min-width:17px;max-width:17px;flex:0 0 17px;fill:none;stroke:#ad741f;stroke-width:1.45;stroke-linecap:round;stroke-linejoin:round"><use href="' + sprite + '#nc-icon-' + icon + '"></use></svg>' +
          '<span>' + name + '</span></span>';
      }).join('') +
      '</div>';
  };

  button.addEventListener('click', async () => {
    const currentIds = Array.from(grid.querySelectorAll('[data-book-id]'))
      .map((card) => card.dataset.bookId)
      .filter(Boolean);
    button.disabled = true;
    status.textContent = window.ncHome.loading;

    try {
      const url = new URL(window.ncHome.endpoint);
      if (currentIds.length) url.searchParams.set('exclude', currentIds.join(','));
      const response = await fetch(url.toString(), { headers: { Accept: 'application/json' } });
      if (!response.ok) throw new Error('Request failed');
      const books = await response.json();

      if (!Array.isArray(books) || books.length === 0) {
        status.textContent = window.ncHome.empty;
        return;
      }

      grid.innerHTML = books.map((book) => {
        const cover = book.image
          ? '<img src="' + escapeHtml(book.image) + '" alt="Okładka książki ' + escapeHtml(book.title) + '">'
          : '<span class="nc-cover-placeholder">Miejsce<br>na okładkę</span>';
        return '<article class="nc-book-card" data-book-id="' + escapeHtml(book.id) + '">' +
          '<a class="nc-book-cover" href="' + escapeHtml(book.url) + '" aria-label="Przejdź do książki: ' + escapeHtml(book.title) + '">' + cover + '</a>' +
          '<div class="nc-book-details"><h3><a href="' + escapeHtml(book.url) + '">' + escapeHtml(book.title) + '</a></h3>' +
          '<p class="nc-book-author">' + escapeHtml(book.author) + '</p>' +
          '<p class="nc-book-excerpt">' + escapeHtml(book.excerpt) + '</p>' +
          renderCategories(book.categories) + '</div></article>';
      }).join('');
      status.textContent = '';
    } catch (error) {
      status.textContent = window.ncHome.error;
    } finally {
      button.disabled = false;
    }
  });
})();
