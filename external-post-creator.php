<?php
/**
 * Plugin Name: External Post Creator
 * Description: Umożliwia tworzenie szkiców artykułów z zewnętrznego źródła (API), z miniaturką i meta.
 * Version: 1.0
 * Author: Eskim83
 */
 
/**
⚠️ Ostrzeżenie dotyczące bezpieczeństwa

Ta wtyczka została stworzona jako **prosty przykład do celów edukacyjnych lub demonstracyjnych**.

Nie implementuje ona pełnego uwierzytelniania ani zabezpieczeń typowych dla produkcyjnych aplikacji. Korzystasz z niej **na własną odpowiedzialność**.

W przypadku wykorzystania w środowisku produkcyjnym zaleca się:
- wdrożenie silniejszego uwierzytelniania (np. JWT, OAuth, Application Passwords),
- walidację typu pliku przy pobieraniu obrazów,
- ograniczenie liczby żądań (rate limiting),
- odpowiednią walidację i filtrowanie danych wejściowych.


Wtyczka nie zapewnia pełnej ochrony przed atakami typu injection, XSS czy nieautoryzowanym dostępem. Zaleca się dokładne przejrzenie kodu i dopasowanie go do własnych potrzeb oraz standardów bezpieczeństwa.
*/

add_action('rest_api_init', function () {
    register_rest_route('external-post/v1', '/create/', [
        'methods' => 'POST',
        'callback' => 'epc_create_post',
        'permission_callback' => 'epc_permission_check',
    ]);
});

function epc_permission_check($request) {
    $token = $request->get_header('x-api-token');
    return $token === 'twoj_super_tajny_token'; // Zmienna lub stała!
}

function epc_create_post($request) {
    $params = $request->get_json_params();

    $post_data = [
        'post_title'    => sanitize_text_field($params['title'] ?? 'Bez tytułu'),
        'post_content'  => wp_kses_post($params['content'] ?? ''),
        'post_status'   => 'draft',
        'post_author'   => 1,
        'post_type'     => 'post',
    ];

    $post_id = wp_insert_post($post_data);

    if (is_wp_error($post_id)) {
        return new WP_Error('create_failed', 'Nie udało się utworzyć wpisu', ['status' => 500]);
    }

    // Dodaj obrazek wyróżniający z URL
    if (!empty($params['thumbnail_url'])) {
        $thumb_id = epc_download_and_attach_image($params['thumbnail_url'], $post_id);
        if ($thumb_id) {
            set_post_thumbnail($post_id, $thumb_id);
        }
    }

    // Meta pola (np. ACF albo standardowe)
    if (!empty($params['meta']) && is_array($params['meta'])) {
        foreach ($params['meta'] as $key => $value) {
            update_post_meta($post_id, sanitize_key($key), sanitize_text_field($value));
        }
    }

    return ['success' => true, 'post_id' => $post_id];
}

function epc_download_and_attach_image($url, $post_id) {
    require_once ABSPATH . 'wp-admin/includes/image.php';
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';

    $tmp = download_url($url);

    if (is_wp_error($tmp)) {
        return false;
    }

    $file_array = [
        'name'     => basename($url),
        'tmp_name' => $tmp,
    ];

    $id = media_handle_sideload($file_array, $post_id);

    if (is_wp_error($id)) {
        @unlink($tmp);
        return false;
    }

    return $id;
}