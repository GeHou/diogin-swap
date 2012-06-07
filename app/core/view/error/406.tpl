<?php
/**
 * ...
 *
 * @copyright Copyright (c) 2009-2012 Jingcheng Zhang <diogin@gmail.com>. All rights reserved.
 * @license   See "LICENSE" file bundled with this distribution.
 */

header('HTTP/1.1 406 Not Acceptable');

echo <<< EOT
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Visitor error: Not Acceptable</title>
</head>
<body>
<h1>Visitor error: Not Acceptable</h1><hr />
EOT;

if (swap\is_debug) {
    echo '<pre>';
    var_dump($e);
    echo '</pre>';
} else {
    echo '<h1>406 Not Acceptable</h1>';
}

echo <<< EOT
</body>
</html>
EOT;
