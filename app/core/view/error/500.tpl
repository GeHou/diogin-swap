<?php
/**
 * ...
 *
 * @copyright Copyright (c) 2009-2012 Jingcheng Zhang <diogin@gmail.com>. All rights reserved.
 * @license   See "LICENSE" file bundled with this distribution.
 */

header('HTTP/1.1 500 Internal Server Error');

echo <<< EOT
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Internal server error</title>
</head>
<body>
EOT;

if (swap\is_debug) {
    if ($e instanceof swap\error) {
        echo '<h1>Internal server error: Hard error</h1><hr />';
    } else if ($e instanceof swap\except) {
        echo '<h1>Internal server error: Soft except</h1><hr />';
    } else {
        echo '<h1>Internal server error: Unknown exception</h1><hr />';
    }
    echo '<h2>Detailed information:</h2>';
    echo '<pre>' . $e->getMessage() . '</pre>';
    echo 'in file: ' . $e->getFile() . '<br />';
    echo 'in line: ' . $e->getLine();
    echo '<h3>Code trace</h3>';
    echo '<pre>' . var_export($e->getTrace(), true) . '</pre>';
} else {
    echo '<h1>Internal server error</h1><hr />';
    echo '<h1>Please contact web master.</h1>';
}

echo <<< EOT
</body>
</html>
EOT;
