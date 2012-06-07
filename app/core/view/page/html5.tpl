<?php
/**
 * HTML5 页面骨架
 *
 * @copyright Copyright (c) 2009-2012 Jingcheng Zhang <diogin@gmail.com>. All rights reserved.
 * @license   See "LICENSE" file bundled with this distribution.
 */

echo '<!doctype html>' . "\n";
echo '<html>' . "\n";
echo '<head>' . "\n";
echo '<meta charset="utf-8" />' . "\n";
if (self::$refresh_to !== array()) {
    echo '<meta http-equiv="Refresh" content="' . self::$refresh_to[1] . ';url=' . self::$refresh_to[0] . '" />' . "\n";
}
if (self::$keywords !== '') {
    echo '<meta name="keywords" content="' . self::$keywords . '" />' . "\n";
}
if (self::$description !== '') {
    echo '<meta name="description" content="' . self::$description . '" />' . "\n";
}
if (self::$author !== '') {
    echo '<meta name="author" content="' . self::$author . '" />' . "\n";
}
echo '<title>' . self::$title . '</title>' . "\n";
echo '<link rel="shortcut icon" href="' . swap\url_manager::www_url('favicon.ico') . (parent::$static_version === 0 ? '' : '?' . parent::$version_key . '=' . parent::$static_version) . '" type="image/x-icon" />' . "\n";

self::echo_top_links();

echo '</head>' . "\n";

echo $_html;

self::echo_bottom_links();

echo '</html>' . "\n";
