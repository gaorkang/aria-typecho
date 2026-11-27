<?php
/**
 * 轻量 SEO 输出：canonical / Open Graph / Twitter Card / JSON-LD
 * 不依赖插件，随主题加载；由 header.php 引入。
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

/**
 * 把 Typecho 生成的 URL 规范化：空格用 %20（与 Hexo 原地址一致），并保留原域名大小写。
 */
function ar_seo_url($url)
{
    $url = trim((string)$url);
    if ($url === '') return '';
    return str_replace('+', '%20', $url);
}

/**
 * 截断纯文本描述
 */
function ar_seo_desc($text, $limit = 155)
{
    $text = trim(preg_replace('/\s+/u', ' ', strip_tags((string)$text)));
    $text = str_replace(array('"', '<', '>'), array('&quot;', '&lt;', '&gt;'), $text);
    if (function_exists('mb_strimwidth')) {
        return mb_strimwidth($text, 0, $limit, '…', 'UTF-8');
    }
    return $text;
}

$isSingle = $this->is('single') && !$this->is('index');
$siteUrl  = rtrim((string)$this->options->siteUrl, '/');
$siteName = (string)$this->options->title;
$siteDesc = trim((string)$this->options->description);

// canonical：文章用当前 permalink，首页/归档用当前路径
if ($isSingle) {
    $canonical = ar_seo_url($this->permalink);
    if (strpos($canonical, 'http') !== 0) {
        $canonical = ar_seo_url($siteUrl . '/' . ltrim($canonical, '/'));
    }
} else {
    $canonical = ar_seo_url($this->options->siteUrl . $this->request->getRequestUrl());
    $canonical = preg_replace('#/index\.php/#', '/', $canonical);
    $canonical = rtrim($canonical, '/') . '/';
}

// 描述与关键词
$description = $siteDesc;
$keywords    = trim((string)$this->options->keywords);
if ($isSingle) {
    if (!empty($this->archiveDescription)) {
        $description = ar_seo_desc($this->archiveDescription);
    }
    if (!empty($this->archiveKeywords)) {
        $keywords = $this->archiveKeywords;
    }
}

// 社交分享图：文章特色图 → 正文首图 → 后台默认图 → 作者头像
$ogImage = trim((string)ar_opt('ogImage', ''));
if ($isSingle) {
    $thumb = trim((string)$this->fields->thumb);
    if ($thumb !== '') {
        $ogImage = $thumb;
    } else {
        $firstImg = ar_first_image($this->content);
        if ($firstImg !== '') $ogImage = $firstImg;
    }
}
if ($ogImage === '') {
    $ogImage = trim((string)ar_opt('avatar', ''));
}
if ($ogImage !== '' && strpos($ogImage, 'http') !== 0) {
    $ogImage = $siteUrl . '/' . ltrim($ogImage, '/');
}
// 图片 URL 的路径段单独编码，避免中文/空格出现在分享卡片里
if ($ogImage !== '') {
    $parts = parse_url($ogImage);
    if ($parts && isset($parts['scheme'], $parts['host'])) {
        $encodedPath = implode('/', array_map('rawurlencode', explode('/', ltrim($parts['path'], '/'))));
        $ogImage = $parts['scheme'] . '://' . $parts['host']
            . (isset($parts['port']) ? ':' . $parts['port'] : '')
            . '/' . $encodedPath
            . (isset($parts['query']) ? '?' . $parts['query'] : '');
    }
}
$ogImage = ar_seo_url($ogImage);

$ogTitle = $isSingle ? $this->title : ($this->archiveTitle ? $this->archiveTitle . ' - ' . $siteName : $siteName);
$ogType  = $isSingle ? 'article' : 'website';
?>
<link rel="canonical" href="<?php echo ar_e($canonical); ?>">
<meta property="og:type" content="<?php echo $ogType; ?>">
<meta property="og:site_name" content="<?php echo ar_e($siteName); ?>">
<meta property="og:title" content="<?php echo ar_e($ogTitle); ?>">
<meta property="og:description" content="<?php echo ar_e($description); ?>">
<meta property="og:url" content="<?php echo ar_e($canonical); ?>">
<?php if ($ogImage !== ''): ?>
<meta property="og:image" content="<?php echo ar_e($ogImage); ?>">
<?php endif; ?>
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?php echo ar_e($ogTitle); ?>">
<meta name="twitter:description" content="<?php echo ar_e($description); ?>">
<?php if ($ogImage !== ''): ?>
<meta name="twitter:image" content="<?php echo ar_e($ogImage); ?>">
<?php endif; ?>
<?php if ($keywords !== ''): ?>
<meta name="keywords" content="<?php echo ar_e($keywords); ?>">
<?php endif; ?>
<script type="application/ld+json">
<?php
$ld = array(
    '@context' => 'https://schema.org',
    '@type' => 'WebSite',
    'name' => $siteName,
    'url' => $siteUrl . '/',
    'description' => $siteDesc,
);
if ($isSingle) {
    $article = array(
        '@context' => 'https://schema.org',
        '@type' => 'BlogPosting',
        'headline' => $this->title,
        'url' => $canonical,
        'datePublished' => date('c', $this->created),
        'dateModified' => date('c', $this->modified),
        'author' => array('@type' => 'Person', 'name' => ar_author_name($this)),
        'publisher' => array('@type' => 'Organization', 'name' => $siteName),
        'mainEntityOfPage' => array('@type' => 'WebPage', '@id' => $canonical),
    );
    if ($description !== '') $article['description'] = $description;
    if ($ogImage !== '') $article['image'] = $ogImage;
    if (!empty($this->tags)) {
        $names = array();
        foreach ($this->tags as $tag) $names[] = $tag['name'];
        $article['keywords'] = implode(',', $names);
    }
    $ld = array('@context' => 'https://schema.org', '@graph' => array($ld, $article));
}
echo json_encode($ld, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
?>
</script>
<?php
$verify = trim((string)ar_opt('siteVerification', ''));
if ($verify !== ''):
    foreach (ar_lines($verify) as $line):
        if (stripos($line, '<meta') === 0) echo $line . "\n";
    endforeach;
endif;
$baiduPush = trim((string)ar_opt('seoBaiduPush', ''));
if ($baiduPush !== '' && strpos($baiduPush, 'http') === 0):
?>
<script>(function(){var bp=document.createElement('script');bp.src=<?php echo json_encode($baiduPush); ?>;var s=document.getElementsByTagName('script')[0];s.parentNode.insertBefore(bp,s);})();</script>
<?php endif; ?>
