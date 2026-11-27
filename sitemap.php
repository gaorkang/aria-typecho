<?php
/**
 * Aria 辅助文件：轻量 sitemap.xml 生成器（v1.1）
 *
 * 不属于主题目录，请放到 Typecho 站点根目录（与 index.php 同级）。
 * nginx 示例：
 *   location = /sitemap.xml { rewrite ^/sitemap.xml$ /sitemap.php last; }
 * robots.txt 中指向：Sitemap: https://你的域名/sitemap.xml
 *
 * 默认读取同目录 usr/typecho.db；也可通过环境变量 TYPECHO_DB 指定数据库路径。
 */
header('Content-Type: application/xml; charset=UTF-8');
header('Cache-Control: public, max-age=3600');

function seo_esc($s) { return htmlspecialchars($s, ENT_XML1 | ENT_QUOTES, 'UTF-8'); }

$dbPath = getenv('TYPECHO_DB');
if (!$dbPath) {
    $dbPath = defined('__TYPECHO_ROOT_DIR__')
        ? rtrim(__TYPECHO_ROOT_DIR__, '/') . '/usr/typecho.db'
        : __DIR__ . '/usr/typecho.db';
}
if (!is_file($dbPath)) {
    header('HTTP/1.1 404 Not Found');
    exit('Typecho database not found: ' . $dbPath);
}

$db = new PDO('sqlite:' . $dbPath);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// 站点配置
$cfg = array();
foreach ($db->query("SELECT name, value FROM typecho_options WHERE user = 0") as $r) {
    $cfg[$r['name']] = $r['value'];
}
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$defaultUrl = $scheme . '://' . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost');
$siteUrl = rtrim(isset($cfg['siteUrl']) ? $cfg['siteUrl'] : $defaultUrl, '/');
$tzShift = (int)(isset($cfg['timezone']) ? $cfg['timezone'] : 0) - idate('Z');

$entries = array(array('loc' => $siteUrl . '/', 'lastmod' => date('c'), 'priority' => '1.0'));

// 文章（日期式链接，空格用 %20）
$stmt = $db->query("SELECT created, modified, slug FROM typecho_contents
    WHERE status = 'publish' AND type = 'post' ORDER BY created DESC");
foreach ($stmt as $r) {
    $ts = (int)$r['created'] + $tzShift;
    $mod = (int)$r['modified'] + $tzShift;
    $slug = rawurlencode($r['slug']);
    $entries[] = array(
        'loc' => $siteUrl . '/' . date('Y/m/d', $ts) . '/' . $slug . '/',
        'lastmod' => date('c', $mod),
        'priority' => '0.8',
    );
}

// 独立页
$stmt = $db->query("SELECT modified, slug FROM typecho_contents
    WHERE status = 'publish' AND type = 'page' ORDER BY created DESC");
foreach ($stmt as $r) {
    $mod = (int)$r['modified'] + $tzShift;
    $entries[] = array(
        'loc' => $siteUrl . '/' . rawurlencode($r['slug']) . '.html',
        'lastmod' => date('c', $mod),
        'priority' => '0.6',
    );
}

// 分类与标签
$stmt = $db->query("SELECT slug, type FROM typecho_metas WHERE type IN ('category','tag')");
foreach ($stmt as $r) {
    $prefix = $r['type'] === 'category' ? 'category' : 'tag';
    $entries[] = array(
        'loc' => $siteUrl . '/index.php/' . $prefix . '/' . rawurlencode($r['slug']) . '/',
        'lastmod' => date('c'),
        'priority' => $r['type'] === 'category' ? '0.5' : '0.4',
    );
}

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
foreach ($entries as $e) {
    echo "  <url>\n";
    echo '    <loc>' . seo_esc($e['loc']) . "</loc>\n";
    echo '    <lastmod>' . $e['lastmod'] . "</lastmod>\n";
    echo "    <changefreq>weekly</changefreq>\n";
    echo '    <priority>' . $e['priority'] . "</priority>\n";
    echo "  </url>\n";
}
echo "</urlset>\n";
