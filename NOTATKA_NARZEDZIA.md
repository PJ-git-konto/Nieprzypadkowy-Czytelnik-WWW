# NOTATKA_NARZĘDZIA — Nieprzypadkowy Czytelnik

Ostatnia aktualizacja: 2026-11-25

Ten plik jest instrukcją operacyjną dla asystenta. Przed rozpoczęciem pracy nad stroną należy go przeczytać i korzystać z niego zamiast ponownie zgadywać, jakie narzędzia są dostępne.

## 1. Gdzie pracujemy

- Repozytorium strony: `PJ-git-konto/Nieprzypadkowy-Czytelnik-WWW`
- Główna gałąź: `main`
- Strona testowa WordPress: `https://test.nieprzypadkowyczytelnik.pl`
- Strona produkcyjna: `https://nieprzypadkowyczytelnik.pl`
- Zmiany i testy treści wykonujemy wyłącznie na stronie testowej, chyba że użytkownik wyraźnie poleci inaczej.
- Repozytorium jest dostępne przez GitHub connector; potwierdzone uprawnienia obejmują odczyt i zapis.

## 2. WordPress — treści i katalog książek

Do operacji na stronie używaj narzędzi WordPress dostępnych przez `functions.exec` i `tools.mcp__WordPress__...`.

Najważniejsze narzędzia:
- `wpcom_content_types_list` — lista typów treści.
- `wpcom_content_types_get` — szczegóły typu, w tym taksonomie i obsługiwane pola.
- `wpcom_content_items_list` — lista rekordów danego typu.
- `wpcom_content_items_get` — pełny odczyt rekordu, w tym treść i meta.
- `wpcom_content_items_create` — tworzenie wpisu/rekordu.
- `wpcom_content_items_update` — aktualizacja rekordu.
- `wpcom_content_terms_list` / `wpcom_content_terms_create` — odczyt i tworzenie terminów taksonomii.
- `wpcom_media_create` — dodanie obrazu do biblioteki mediów z publicznego URL, base64 lub przesłanego pliku.
- `wpcom_media_upload` — uzyskanie grantów do bezpośredniego przesyłania plików; po przesłaniu użyj `wpcom_media_create` z `upload_ref` i SHA-256.
- `wpcom_plugin_list` — sprawdzanie zainstalowanych wtyczek.

### Katalog książek

- Właściwy typ wpisu to CPT `ksiazka`, nie zwykłe wpisy blogowe.
- Dodawanie i publikowanie rekordów `ksiazka` przez WordPress connector działało wcześniej — utworzono m.in. „Lalkę” (ID 7), „Quo vadis” (ID 8) i „Chłopów” (ID 9).
- W bazie testowej było później 10 opublikowanych rekordów. Przed kolejnym importem zawsze odczytaj aktualną listę, aby nie tworzyć duplikatów.
- Tworzenie rekordu: `wpcom_content_items_create({wpcom_site:"test.nieprzypadkowyczytelnik.pl", post_type:"ksiazka", ...})`.
- Aktualizacja: `wpcom_content_items_update({wpcom_site:"test.nieprzypadkowyczytelnik.pl", post_type:"ksiazka", id:..., ...})`.
- Wartości zarejestrowanych pól meta przekazuje się jako obiekt `meta`; pola taksonomii przekazuje się pod nazwą ich REST base.
- Pole `_nc_travel_category` jest zarejestrowane w REST i było używane dla książek podróżniczych.
- `featured_media` przyjmuje numeryczne ID załącznika z biblioteki mediów, a nie adres URL.
- Zwykłe narzędzia treści obejmują wspólne pola REST oraz zarejestrowane meta. Jeśli zapis pola nie działa, sprawdź najpierw rejestrację pola w aktywnym kodzie witryny; nie zakładaj, że connector uszkodził się lub że nie ma dostępu do katalogu.

### Kategorie podróżnicze

Meta `_nc_travel_category`:
1. `polnoc-dzikie-bezkresy` — Północ i dzikie bezkresy
2. `morza-wyspy-oceany` — Morza, wyspy i oceany
3. `podroze-nieznane-wewnetrzne` — Podróże w nieznane i wewnętrzne

Kategorie podróżnicze są niezależne od kategorii literackich.

## 3. WordPress — media i okładki

- Najpierw pozyskaj plik obrazu w środowisku pracy lub użyj bezpośredniego, publicznego URL obrazu.
- Dodaj obraz do biblioteki testowej strony przez `wpcom_media_create` albo sekwencję `wpcom_media_upload` → przesłanie pliku → `wpcom_media_create`.
- Zapisz zwrócone ID mediów jako `featured_media) w rekordzie książki, jeśli typ `ksiazka` obsługuje miniatury.
- Nie wpisuj URL okładki w `featured_media`.
- Ustaw sensowny tytuł i tekst alternatywny.
- Nie twórz fikcyjnych okładek sugerujących prawdziwe wydania konkretnej książki. Przy okładkach używaj wiarygodnych źródeł lub wyraźnie oznaczonych grafik testowych.

## 4. GitHub — pliki motywu i dokumentacja

Używaj narzędzi GitHub przez `functions.exec`:
- `mcp__GitHub__fetch_file` — odczyt pliku; zwraca treść i aktualny blob SHA.
- `mcp__GitHub__create_file` — utworzenie nowego pliku.
- `mcp__GitHub__update_file` — aktualizacja istniejącego pliku (najpierw pobierz aktualny SHA).
- Pozostałe operacje GitHub wyszukuj w `ALL_TOOLS`, gdy są potrzebne.

Zasady:
1. Przed edycją zawsze pobierz aktualny plik z `main` i jego SHA.
2. Aktualizuj plik z aktualnym SHA — nie używaj starej kopii ani SHA z pamięci.
3. Po zmianie sprawdź odpowiedź narzędzia i commit.
4. Commit w GitHub **nie oznacza**, że zmiana pojawiła się na stronie.
5. Nie twierdź, że strona została wdrożona, dopóki nie potwierdzisz wdrożenia i efektu na stronie testowej.
6. Pliki robocze i niezaakceptowane assety umieszczaj w `_robocze/` (w szczególności `_robocze/assets/images/`). Zaakceptowanych grafik nie reinterpretuj ani nie podmieniaj samodzielnie.

### Gdy nie da się odczytać grafiki z GitHuba

1. Użyj `mcp__GitHub__fetch_file` z `encoding:"base64"`.
2. W `functions.exec` przekaż zawartość do podglądu jako obraz, np. `image("data:image/jpeg;base64," + wynik.result.content)`.
3. Dobierz poprawny MIME (`image/jpeg`, `image/png`, `image/webp` itd.).
4. Jeśli ta metoda nie zadziała, sprawdź publiczny raw URL lub inną metodę odczytu.
5. Dopiero po wyczerpaniu tych sposobów poproś użytkownika o przesłanie pliku.

## 5. Wdrażanie kodu — potwierdzone ograniczenie

- Próba użycia `wpcom_code_deployments_list` dla tej strony zwróciła błąd: wdrożenia kodu są dostępne tylko dla witryn Atomic, a bieżący użytkownik nie ma dostępu do tego zasobu.
- To ograniczenie dotyczy endpointu WordPress.com Code Deployments. **Nie oznacza**, że nie można odczytywać ani tworzyć treści WordPress.
- Nie ponawiaj tego samego wywołania bez zmiany planu, uprawnień lub zasobu.
- Nie zakładaj, że push do GitHuba automatycznie wdraża motyw.
- Nie instaluj ani nie aktywuj wtyczek bez wyraźnej zgody użytkownika.
- Jeśli potrzebna jest zmiana PHP/CSS, najpierw sprawdź dostępne narzędzia i realne możliwości hostingu. Jetpack nie jest sam w sobie narzędziem do dowolnego wdrażania plików motywu.

## 6. Wtyczki i Jetpack

Ostatni potwierdzony odczyt listy wtyczek na stronie testowej:
- Jetpack 16.4 — aktywny; `can_deactivate:false`.
- Akismet 5.7.2 — nieaktywny.
- Hello Dolly 1.7.2 — nieaktywny.

Do ponownego sprawdzenia aktualnego stanu użyj `wpcom_plugin_list`; nie traktuj powyższych wersji jako gwarancji aktualnego stanu.

## 7. Reguły operacyjne — żeby nie wracać do tych samych diagnoz

- Rozdzielaj trzy niezależne obszary: **treści WordPress**, **pliki w GitHub**, **wdrożenie kodu na hostingu**.
- Jeśli wcześniej potwierdzono, że narzędzie działa (np. tworzenie książek w CPT), nie twierdź później bez testu, że ta funkcja jest niedostępna.
- Gdy pojawi się błąd, zapisz dokładnie, którego narzędzia i operacji dotyczy; nie rozszerzaj błędu jednego endpointu na całą integrację.
- Przed pytaniem użytkownika sprawdź dostępne narzędzia w `ALL_TOOLS` i istniejące pliki/rekordy.
- Nie proś ponownie o dane ani pliki, które można pobrać z repozytorium lub odczytać przez WordPress connector.
- Zmiany wprowadzaj najpierw na stronie testowej. Produkcji nie dotykaj bez wyraźnego polecenia.
- Po każdej istotnej operacji podaj: co wykonano, gdzie, jaki jest wynik oraz co pozostaje niepotwierdzone.
- Nie deklaruj sukcesu bez odpowiedzi narzędzia i — gdy chodzi o wygląd/stronę — bez sprawdzenia efektu w przeglądarce.

## 8. Stan bieżącego zadania (2026-11-25)

- Celem jest dodanie ok. 20 książek podróżniczych do CPT `ksiazka` na stronie testowej, z okładkami, opisami i przypisaniem do jednej z trzech kategorii podróżniczych.
- Przed dodaniem odczytaj aktualne rekordy (nie zakładaj, że nadal jest ich dokładnie 10).
- Użytkownik chce oznaczyć istniejące i nowe książki jako testowe, aby później można je było wykluczyć ze zwykłych zestawień i usunąć/oczyścić w kontrolowany sposób.
- Meta `_nc_test_record` zostało przygotowane w zmianach kodu w GitHub, ale nie jest potwierdzone jako zarejestrowane w aktywnym kodzie strony testowej. Próba zapisu przez REST przed wdrożeniem rejestracji meta nie zadziałała.
- Zmiany kodu w GitHub nie są równoznaczne z wdrożeniem. Nie oznaczaj książek jako testowych przez to meta, dopóki pole nie będzie potwierdzone w REST.
- Nie kasuj żadnych rekordów ani nie zmieniaj ich statusu bez uzgodnienia.

## 9. Główna zasada

**Najpierw sprawdź notatkę, narzędzia i stan faktyczny. Potem działaj. Nie zaczynaj każdej rozmowy od ponownego odkrywania dostępu.**
