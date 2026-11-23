# Notatka projektowa — ujednolicenie danych autorów książek

**Status: do wykonania — decyzja kierunkowa**

## Stan obecny

- Na stronie głównej autorzy są pobierani z pola WordPressa `_nc_book_author`.
- Jeśli pole jest puste, autor nie jest wyświetlany.
- Na stronie „Jesienne inspiracje” zastosowano dodatkową listę autorów zapisaną bezpośrednio w kodzie jako rozwiązanie awaryjne.

## Cel docelowy

Ujednolicić sposób przechowywania i pobierania autorów dla całego serwisu „Nieprzypadkowy Czytelnik”.

Założenia:

1. Każda książka powinna mieć autora zapisanego w jednym, wspólnym źródle danych w WordPressie.
2. Strona główna, „Jesienne inspiracje” i mechanizm losowania kolejnych książek powinny korzystać z tego samego źródła.
3. Docelowo usunąć osobne, ręczne listy autorów zapisane w kodzie.
4. Brak autora powinien być możliwy do wykrycia, a nie powodować cichego pomijania informacji.

## Kolejność działań

1. Sprawdzić, gdzie obecnie zapisani są autorzy książek i czy nie istnieje już inne pole z tymi danymi.
2. Ustalić jedno właściwe źródło danych.
3. Uzupełnić lub uporządkować dane książek.
4. Zmodyfikować wspólny mechanizm pobierania autora.
5. Zweryfikować wyświetlanie autorów na wszystkich podstronach i w losowanych propozycjach.

## Ważne

Nie wprowadzać zmian przed rozpoznaniem obecnej struktury danych. Nie przepisywać nazwisk na chybił trafił. Obecne rozwiązanie dla „Jesiennych inspiracji” pozostaje bez zmian do czasu wdrożenia i sprawdzenia rozwiązania docelowego.
