<?php

class RestAPI {

    static public function route($routes) {
        Events::fire('before_request');
        $request_method = strtolower($_SERVER['REQUEST_METHOD']);

        $scheme = parse_url($_SERVER['REQUEST_URI']);
        $requestedPath = isset($scheme['path']) ? $scheme['path'] : '/';
        $isHandlerAvailable = null;
        $regex_matches = array();

        if (isset($routes[$requestedPath])) {
            $isHandlerAvailable = $routes[$requestedPath];
        } else if ($routes) {

            $tokens = array(
                ':string' => '([a-zA-Z]+)',
                ':number' => '([0-9]+)',
                ':alpha' => '([a-zA-Z0-9-_]+)'
            );
            foreach ($routes as $pattern => $handler_name) {
                $pattern = strtr($pattern, $tokens);
                if (preg_match('#^/?' . $pattern . '/?$#', $requestedPath, $matches)) {
                    $isHandlerAvailable = $handler_name;
                    $regex_matches = $matches;
                    break;
                }
            }
        }

        if ($isHandlerAvailable && class_exists($isHandlerAvailable)) {
            unset($regex_matches[0]);
            $handler_instance = new $isHandlerAvailable();

            if (self::isAjax() && method_exists($isHandlerAvailable, $request_method . '_xhr')) {
                self::headers();
                $request_method .= '_xhr';
            }

            if (method_exists($handler_instance, $request_method)) {
                Events::fire('before_handler');
                call_user_func_array(array($handler_instance, $request_method), $regex_matches);
                Events::fire('after_handler');
            } else {
                Events::fire('404');
            }
        } else {
            Events::fire('404');
        }

        Events::fire('after_request');
    }

    static protected function headers() {
        header('Content-type: application/json');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: private, no-store, no-cache, must-revalidate');
        header('Cache-Control: post-check=0, pre-check=0', false);
        header('Pragma: no-cache');
    }

    static protected function isAjax() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    }

}
