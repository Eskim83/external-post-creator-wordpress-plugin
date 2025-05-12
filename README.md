# External Post Creator

**Contributors:** eskimpl

**Tags:** REST API, posts, external content, thumbnail, meta

**Requires at least:** 5.5

**Tested up to:** 6.5

**Requires PHP:** 7.4

**Stable tag:** 1.0

**License:** GPLv2 or later

**License URI:** [https://www.gnu.org/licenses/gpl-2.0.html](https://www.gnu.org/licenses/gpl-2.0.html)


---

## ⚠️ Ostrzeżenie dotyczące bezpieczeństwa

Ta wtyczka została stworzona jako **prosty przykład do celów edukacyjnych lub demonstracyjnych**.

Nie implementuje ona pełnego uwierzytelniania ani zabezpieczeń typowych dla produkcyjnych aplikacji. Korzystasz z niej **na własną odpowiedzialność**.

W przypadku wykorzystania w środowisku produkcyjnym zaleca się:

* wdrożenie silniejszego uwierzytelniania (np. JWT, OAuth, Application Passwords),
* walidację typu pliku przy pobieraniu obrazów,
* ograniczenie liczby żądań (rate limiting),
* odpowiednią walidację i filtrowanie danych wejściowych.

Wtyczka nie zapewnia pełnej ochrony przed atakami typu injection, XSS czy nieautoryzowanym dostępem. Zaleca się dokładne przejrzenie kodu i dopasowanie go do własnych potrzeb oraz standardów bezpieczeństwa.

Artykuł: [Custom GPT jako wirtualny asystent](https://eskim.pl/custom-gpt-wirtualny-asystent/)

---

Umożliwia zdalne tworzenie szkiców wpisów w WordPressie z zewnętrznych źródeł (np. AI, narzędzi zewnętrznych). Obsługuje tytuł, treść, miniaturkę oraz meta dane.

## Opis

Ta wtyczka rejestruje prosty endpoint REST API, który pozwala tworzyć szkice wpisów. Przykład zastosowania:

* Integracja z narzędziami AI
* Automatyczne publikowanie z zewnętrznych systemów
* Zdalne tworzenie draftów np. przez cron z innego serwera

## Funkcje

* Tworzenie szkiców postów (title, content)
* Ustawianie miniatury z URL
* Dodawanie metadanych (standardowych lub ACF)
* Autoryzacja przez nagłówek `x-api-token`

## Instalacja

1. Skopiuj plik `external-post-creator.php` do katalogu `wp-content/plugins/external-post-creator/`.
2. Aktywuj wtyczkę w panelu WordPressa.
3. Ustaw swój własny token API w funkcji `epc_permission_check()`.

## Użycie API

### Endpoint

```
POST /wp-json/external-post/v1/create/
```

### Nagłówki

```
x-api-token: twoj_super_tajny_token
Content-Type: application/json
```

### Body JSON

```json
{
  "title": "Tytuł artykułu",
  "content": "Treść wpisu",
  "thumbnail_url": "https://example.com/image.jpg",
  "meta": {
    "custom_key": "custom_value"
  }
}
```

## Zmiany

### 1.0

* Pierwsza wersja
* Tworzenie szkiców wpisów z REST API
* Obsługa miniatury i meta danych

## Autor

Eskim – [eskim.pl](https://eskim.pl)

GitHub: [Eskim83](https://github.com/Eskim83)

Buy me a coffee: [buymecoffee.com/eskim](https://www.buymeacoffee.com/eskim)
