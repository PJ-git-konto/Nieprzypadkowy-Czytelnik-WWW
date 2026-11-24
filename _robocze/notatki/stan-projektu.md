# Stan projektu — Nieprzypadkowy Czytelnik

## Blok „Wybrane dla Ciebie”
**Status: ZAKOŃCZONY na obecnym etapie (24.11.2026).**

Użytkownik zaakceptował obecny wygląd i działanie bloku na stronie głównej. Nie wprowadzać dalszych zmian w tym bloku bez wyraźnego polecenia lub zgłoszenia konkretnego problemu.

Zakres uznany za zakończony:
- układ i geometria kart książek;
- okładki w naturalnych proporcjach, bez przycinania;
- tytuły, autorzy i opisy (do 3 linii na desktopie/tablecie, do 2 na telefonie);
- kategorie i separator w kartach;
- przycisk „Pokaż mi kolejne trzy książki” oraz mechanizm ładowania kolejnych propozycji.

Ostatnia drobna poprawka stabilizująca wysokość komunikatu ładowania została zapisana w `theme/style.css` (commit: `7ea3a6bd37d087eef3b99be16b8513f89918550e`). W chwili zapisu notatki workflow wdrożeniowy był jeszcze w kolejce — wymaga potwierdzenia po stronie GitHub Actions.

Nie traktować naturalnie różnej ilości widocznego tekstu opisu jako błędu: krótszy tytuł i autor powodują, że trzy linie opisu optycznie zajmują mniej miejsca, ale to zaakceptowane zachowanie.
