<?php
\xeki\routes::any('', 'home');

\xeki\routes::any('demo-function', function () {
    \xeki\core::PrintJson(
        [
            "response" => 'demo',
            "date" => date("Y-m-d")
        ]
    );
});