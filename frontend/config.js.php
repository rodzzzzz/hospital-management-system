<?php
/**
 * Outputs a <script> tag that sets window.API_BASE_URL and window.AUTH_TOKEN.
 * Also patches global fetch() to auto-attach Bearer token for API requests.
 * Include this file in every frontend page that makes fetch() calls.
 *
 * Usage: <?php include __DIR__ . '/config.js.php'; ?>
 */
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth.php';

$__authToken = auth_get_token();
?>
<script>
    window.API_BASE_URL = <?php echo json_encode(rtrim(API_BASE_URL, '/')); ?>;
    window.AUTH_TOKEN = <?php echo json_encode($__authToken); ?>;

    // Auto-attach Bearer token to all fetch requests targeting the backend API
    (function() {
        var _origFetch = window.fetch;
        window.fetch = function(input, init) {
            var url = (typeof input === 'string') ? input : (input && input.url ? input.url : '');
            if (window.AUTH_TOKEN && url.indexOf(window.API_BASE_URL) === 0) {
                init = init || {};
                init.headers = init.headers || {};
                if (init.headers instanceof Headers) {
                    if (!init.headers.has('Authorization')) {
                        init.headers.set('Authorization', 'Bearer ' + window.AUTH_TOKEN);
                    }
                } else if (typeof init.headers === 'object') {
                    if (!init.headers['Authorization'] && !init.headers['authorization']) {
                        init.headers['Authorization'] = 'Bearer ' + window.AUTH_TOKEN;
                    }
                }
            }
            return _origFetch.call(this, input, init);
        };
    })();
</script>
