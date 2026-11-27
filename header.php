<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="<?php $this->options->charset(); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title><?php $this->archiveTitle(array(
        'category'  => _t('分类 %s 下的文章'),
        'search'    => _t('包含关键字 %s 的文章'),
        'tag'       => _t('标签 %s 下的文章'),
        'author'    => _t('%s 发布的文章')
    ), '', ' - '); ?><?php $this->options->title(); ?></title>
    <meta name="description" content="<?php echo ar_e(!empty($this->archiveDescription) ? $this->archiveDescription : $this->options->description); ?>">
    <link rel="icon" type="image/svg+xml" href="<?php $this->options->themeUrl('assets/favicon.svg'); ?>">
    <link rel="alternate icon" type="image/png" href="<?php $this->options->themeUrl('assets/favicon-32.png'); ?>">
    <link rel="apple-touch-icon" href="<?php $this->options->themeUrl('assets/favicon-180.png'); ?>">
    <link rel="stylesheet" href="<?php $this->options->themeUrl('style.css'); ?>">
    <?php
    ob_start();
    $this->header('social=0&description=0&keywords=0');
    // 去掉 Typecho 自带 canonical，统一由 seo.php 输出规范化版本（空格 %20）
    echo preg_replace('#<link rel="canonical"[^>]*>\s*#i', '', ob_get_clean());
    ?>
    <?php $this->need('seo.php'); ?>
    <script>
        (function () {
            try {
                var saved = localStorage.getItem('ar-theme');
                var theme = saved || (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
                document.documentElement.setAttribute('data-theme', theme);
            } catch (e) {}
        })();
    </script>
</head>
<body class="<?php if ($this->is('post')) echo 'is-post-page'; ?>">

<div class="site-background" aria-hidden="true"></div>
<?php if (ar_enhance('Orbs')): ?>
<div class="orbs" id="orbs" aria-hidden="true">
    <span class="orb orb-1"></span>
    <span class="orb orb-2"></span>
    <span class="orb orb-3"></span>
</div>
<?php endif; ?>
<?php if (ar_has('ReadMode')): ?>
    <div class="read-progress" id="readProgress"></div>
<?php endif; ?>

<svg class="svg-sprite" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
    <!-- 品牌图形路径来自 Font Awesome Free 6.5.2（CC BY 4.0） -->
    <symbol id="i-moon" viewBox="0 0 24 24"><path d="M20 14.5A8.5 8.5 0 1 1 9.5 4a7 7 0 0 0 10.5 10.5Z"/></symbol>
    <symbol id="i-sun" viewBox="0 0 24 24"><circle cx="12" cy="12" r="4"/><path d="M12 2v2m0 16v2M2 12h2m16 0h2M4.9 4.9l1.4 1.4m11.4 11.4 1.4 1.4M19.1 4.9l-1.4 1.4M6.3 17.7l-1.4 1.4"/></symbol>
    <symbol id="i-search" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></symbol>
    <symbol id="i-shuffle" viewBox="0 0 24 24"><path d="M3 7h4l10 10h4M17 3l4 4-4 4M3 17h4l3-3m4-4 3-3h4m-4-4 4 4-4 4"/></symbol>
    <symbol id="i-menu" viewBox="0 0 24 24"><path d="M4 7h16M4 12h16M4 17h16"/></symbol>
    <symbol id="i-close" viewBox="0 0 24 24"><path d="M6 6l12 12M18 6 6 18"/></symbol>
    <symbol id="i-eye" viewBox="0 0 24 24"><path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6-10-6-10-6Z"/><circle cx="12" cy="12" r="2.5"/></symbol>
    <symbol id="i-chat" viewBox="0 0 24 24"><path d="M20 15a3 3 0 0 1-3 3H8l-4 3V6a3 3 0 0 1 3-3h10a3 3 0 0 1 3 3Z"/></symbol>
    <symbol id="i-star" viewBox="0 0 24 24"><path d="m12 3 2.7 5.5 6.1.9-4.4 4.3 1 6.1-5.4-2.9-5.4 2.9 1-6.1L3.2 9.4l6.1-.9Z"/></symbol>
    <symbol id="i-heart" viewBox="0 0 24 24"><path d="M20.8 5.8a5 5 0 0 0-7.1 0L12 7.5l-1.7-1.7a5 5 0 0 0-7.1 7.1L12 21l8.8-8.1a5 5 0 0 0 0-7.1Z"/></symbol>
    <symbol id="i-up" viewBox="0 0 24 24"><path d="M7 17 17 7M9 7h8v8"/></symbol>
    <symbol id="i-check" viewBox="0 0 24 24"><path d="m5 13 4 4L19 7"/></symbol>
    <symbol id="i-arrow" viewBox="0 0 24 24"><path d="M12 20V4m-6 6 6-6 6 6"/></symbol>
    <symbol id="i-fire" viewBox="0 0 24 24"><path d="M12 22c4 0 7-3 7-7 0-4-3-6-4-9-1 2-2 3-3 3 0-3-2-5-3-7-1 4-4 6-4 10 0 4 3 7 7 7Z"/></symbol>
    <symbol id="i-list" viewBox="0 0 24 24"><path d="M9 6h11M9 12h11M9 18h11M4 6h.01M4 12h.01M4 18h.01"/></symbol>
    <symbol id="i-home" viewBox="0 0 24 24"><path d="M3 11 12 3l9 8v9a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1Z"/></symbol>
    <symbol id="i-grid" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></symbol>
    <symbol id="i-reply" viewBox="0 0 24 24"><path d="M9 10 4 15l5 5M4 15h9a7 7 0 0 0 7-7V4"/></symbol>
    <symbol id="i-pencil" viewBox="0 0 24 24"><path d="m4 20 4-1 11-11-3-3L5 16Zm11-14 3 3"/></symbol>
    <symbol id="i-rss" viewBox="0 0 24 24"><path d="M4 11a9 9 0 0 1 9 9M4 4a16 16 0 0 1 16 16"/><circle cx="5" cy="19" r="1"/></symbol>
    <symbol id="i-bilibili" viewBox="0 0 512 512"><path fill="currentColor" stroke="none" d="M488.6 104.1C505.3 122.2 513 143.8 511.9 169.8V372.2C511.5 398.6 502.7 420.3 485.4 437.3C468.2 454.3 446.3 463.2 419.9 464H92.02C65.57 463.2 43.81 454.2 26.74 436.8C9.682 419.4 .7667 396.5 0 368.2V169.8C.7667 143.8 9.682 122.2 26.74 104.1C43.81 87.75 65.57 78.77 92.02 78H121.4L96.05 52.19C90.3 46.46 87.42 39.19 87.42 30.4C87.42 21.6 90.3 14.34 96.05 8.603C101.8 2.868 109.1 0 117.9 0C126.7 0 134 2.868 139.8 8.603L213.1 78H301.1L375.6 8.603C381.7 2.868 389.2 0 398 0C406.8 0 414.1 2.868 419.9 8.603C425.6 14.34 428.5 21.6 428.5 30.4C428.5 39.19 425.6 46.46 419.9 52.19L394.6 78L423.9 78C450.3 78.77 471.9 87.75 488.6 104.1H488.6zM449.8 173.8C449.4 164.2 446.1 156.4 439.1 150.3C433.9 144.2 425.1 140.9 416.4 140.5H96.05C86.46 140.9 78.6 144.2 72.47 150.3C66.33 156.4 63.07 164.2 62.69 173.8V368.2C62.69 377.4 65.95 385.2 72.47 391.7C78.99 398.2 86.85 401.5 96.05 401.5H416.4C425.6 401.5 433.4 398.2 439.7 391.7C446 385.2 449.4 377.4 449.8 368.2L449.8 173.8zM185.5 216.5C191.8 222.8 195.2 230.6 195.6 239.7V273C195.2 282.2 191.9 289.9 185.8 296.2C179.6 302.5 171.8 305.7 162.2 305.7C152.6 305.7 144.7 302.5 138.6 296.2C132.5 289.9 129.2 282.2 128.8 273V239.7C129.2 230.6 132.6 222.8 138.9 216.5C145.2 210.2 152.1 206.9 162.2 206.5C171.4 206.9 179.2 210.2 185.5 216.5H185.5zM377 216.5C383.3 222.8 386.7 230.6 387.1 239.7V273C386.7 282.2 383.4 289.9 377.3 296.2C371.2 302.5 363.3 305.7 353.7 305.7C344.1 305.7 336.3 302.5 330.1 296.2C323.1 289.9 320.7 282.2 320.4 273V239.7C320.7 230.6 324.1 222.8 330.4 216.5C336.7 210.2 344.5 206.9 353.7 206.5C362.9 206.9 370.7 210.2 377 216.5H377z"/></symbol>
    <symbol id="i-zhihu" viewBox="0 0 640 512"><path fill="currentColor" stroke="none" d="M170.54 148.13v217.54l23.43.01 7.71 26.37 42.01-26.37h49.53V148.13H170.54zm97.75 193.93h-27.94l-27.9 17.51-5.08-17.47-11.9-.04V171.75h72.82v170.31zm-118.46-94.39H97.5c1.74-27.1 2.2-51.59 2.2-73.46h51.16s1.97-22.56-8.58-22.31h-88.5c3.49-13.12 7.87-26.66 13.12-40.67 0 0-24.07 0-32.27 21.57-3.39 8.9-13.21 43.14-30.7 78.12 5.89-.64 25.37-1.18 36.84-22.21 2.11-5.89 2.51-6.66 5.14-14.53h28.87c0 10.5-1.2 66.88-1.68 73.44H20.83c-11.74 0-15.56 23.62-15.56 23.62h65.58C66.45 321.1 42.83 363.12 0 396.34c20.49 5.85 40.91-.93 51-9.9 0 0 22.98-20.9 35.59-69.25l53.96 64.94s7.91-26.89-1.24-39.99c-7.58-8.92-28.06-33.06-36.79-41.81L87.9 311.95c4.36-13.98 6.99-27.55 7.87-40.67h61.65s-.09-23.62-7.59-23.62v.01zm412.02-1.6c20.83-25.64 44.98-58.57 44.98-58.57s-18.65-14.8-27.38-4.06c-6 8.15-36.83 48.2-36.83 48.2l19.23 14.43zm-150.09-59.09c-9.01-8.25-25.91 2.13-25.91 2.13s39.52 55.04 41.12 57.45l19.46-13.73s-25.67-37.61-34.66-45.86h-.01zM640 258.35c-19.78 0-130.91.93-131.06.93v-101c4.81 0 12.42-.4 22.85-1.2 40.88-2.41 70.13-4 87.77-4.81 0 0 12.22-27.19-.59-33.44-3.07-1.18-23.17 4.58-23.17 4.58s-165.22 16.49-232.36 18.05c1.6 8.82 7.62 17.08 15.78 19.55 13.31 3.48 22.69 1.7 49.15.89 24.83-1.6 43.68-2.43 56.51-2.43v99.81H351.41s2.82 22.31 25.51 22.85h107.94v70.92c0 13.97-11.19 21.99-24.48 21.12-14.08.11-26.08-1.15-41.69-1.81 1.99 3.97 6.33 14.39 19.31 21.84 9.88 4.81 16.17 6.57 26.02 6.57 29.56 0 45.67-17.28 44.89-45.31v-73.32h122.36c9.68 0 8.7-23.78 8.7-23.78l.03-.01z"/></symbol>
    <symbol id="i-youtube" viewBox="0 0 24 24"><rect x="2.5" y="5" width="19" height="14" rx="4"/><path d="m10 9 5 3-5 3Z"/></symbol>
    <symbol id="i-github" viewBox="0 0 24 24"><path d="M9 20c-4 1.2-4-2-6-2m12 4v-3.5a3 3 0 0 0-.8-2.3c2.7-.3 5.5-1.3 5.5-6A4.7 4.7 0 0 0 18.4 6 4.4 4.4 0 0 0 18.3 2S17 1.6 15 3a10 10 0 0 0-6 0C7 1.6 5.7 2 5.7 2A4.4 4.4 0 0 0 5.6 6 4.7 4.7 0 0 0 4.3 10.2c0 4.7 2.8 5.7 5.5 6A3 3 0 0 0 9 18.5V22"/></symbol>
    <symbol id="i-weibo" viewBox="0 0 24 24"><path d="M4 12a8 5 0 0 1 16 0 8 5 0 0 1-16 0Z"/><circle cx="12" cy="12" r="2"/><path d="M7 5c2-1 4-1 6 0"/></symbol>
    <symbol id="i-clock" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></symbol>
    <symbol id="i-quote" viewBox="0 0 24 24"><path d="M9 7H5a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h2v1a3 3 0 0 1-3 3M20 7h-4a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h2v1a3 3 0 0 1-3 3"/></symbol>
    <symbol id="i-arrow-left" viewBox="0 0 24 24"><path d="M15 18 9 12l6-6"/></symbol>
    <symbol id="i-arrow-right" viewBox="0 0 24 24"><path d="m9 18 6-6-6-6"/></symbol>
    <symbol id="i-location" viewBox="0 0 24 24"><path d="M19 10c0 5-7 11-7 11S5 15 5 10a7 7 0 1 1 14 0Z"/><circle cx="12" cy="10" r="2.5"/></symbol>
    <symbol id="i-device" viewBox="0 0 24 24"><rect x="3" y="4" width="14" height="11" rx="2"/><path d="M7 19h6M10 15v4M18 8h3v11h-3"/></symbol>
    <symbol id="i-link" viewBox="0 0 24 24"><path d="M10 13a5 5 0 0 0 7.1 0l2.1-2.1a5 5 0 0 0-7.1-7.1L11 5M14 11a5 5 0 0 0-7.1 0l-2.1 2.1a5 5 0 1 0 7.1 7.1L13 19"/></symbol>
    <symbol id="i-archive" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="4" rx="1"/><path d="M5 8v11a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V8M10 12h4"/></symbol>
    <symbol id="i-copy" viewBox="0 0 24 24"><rect x="8" y="8" width="12" height="12" rx="2"/><path d="M16 8V6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h2"/></symbol>
    <symbol id="i-mail" viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></symbol>
    <symbol id="i-image" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="8.5" cy="9" r="1.5"/><path d="m3 17 5-5 4 4 3-3 6 6"/></symbol>
    <symbol id="i-file" viewBox="0 0 24 24"><path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8Z"/><path d="M14 3v5h5M9 13h6M9 17h4"/></symbol>
    <symbol id="i-user" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/></symbol>
    <symbol id="i-tag" viewBox="0 0 24 24"><path d="M20.6 13.4 12 22l-9-9V4h9l8.6 8.6a1 1 0 0 1 0 1.4Z"/><circle cx="7.5" cy="7.5" r="1"/></symbol>
    <symbol id="w-sun" viewBox="0 0 24 24"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/></symbol>
    <symbol id="w-cloud-sun" viewBox="0 0 24 24"><circle cx="8" cy="8" r="3"/><path d="M8 1.5v1.5M2.5 8H1M4 4l1 1M18 14h1a3 3 0 0 1 0 6H8a4 4 0 0 1-.4-8A5 5 0 0 1 17 13"/></symbol>
    <symbol id="w-cloud" viewBox="0 0 24 24"><path d="M6 18h12a4 4 0 0 0 0-8 6 6 0 0 0-11.6-1.5A4.3 4.3 0 0 0 6 18Z"/></symbol>
    <symbol id="w-rain" viewBox="0 0 24 24"><path d="M6 15h12a4 4 0 0 0 0-8 6 6 0 0 0-11.6-1.5A4.3 4.3 0 0 0 6 15Z"/><path d="m8 18-1 3M12 18l-1 3M16 18l-1 3"/></symbol>
    <symbol id="w-snow" viewBox="0 0 24 24"><path d="M6 14h12a4 4 0 0 0 0-8 6 6 0 0 0-11.6-1.5A4.3 4.3 0 0 0 6 14Z"/><path d="M9 18h.01M13 19h.01M17 18h.01"/></symbol>
    <symbol id="w-storm" viewBox="0 0 24 24"><path d="M6 14h12a4 4 0 0 0 0-8 6 6 0 0 0-11.6-1.5A4.3 4.3 0 0 0 6 14Z"/><path d="m13 16-3 4h4l-2 3"/></symbol>
    <symbol id="w-fog" viewBox="0 0 24 24"><path d="M6 12h12a4 4 0 0 0 0-8 6 6 0 0 0-11.6-1.5A4.3 4.3 0 0 0 6 12Z"/><path d="M4 16h16M6 19h12"/></symbol>
</svg>

<header class="topbar" id="topbar">
    <div class="topbar-inner">
        <h1 class="brand">
            <a href="<?php $this->options->siteUrl(); ?>" rel="home">
                <?php $logo = ar_opt('logoText', ''); ?>
                <?php if ($logo !== ''): ?><?php echo ar_e($logo); ?><sup>®</sup><?php else: ?><?php $this->options->title(); ?><?php endif; ?>
            </a>
        </h1>

        <?php $navBase = rtrim((string)$this->options->siteUrl, '/') . '/index.php/'; ?>
        <nav class="nav" aria-label="<?php _e('主导航'); ?>">
            <ul class="nav-list">
                <li class="<?php if ($this->is('index')) echo 'current'; ?>"><a href="<?php $this->options->siteUrl(); ?>"><?php _e('首页'); ?></a></li>
                <li class="<?php if ($this->is('page', 'categories')) echo 'current'; ?>"><a href="<?php echo ar_e($navBase . 'categories.html'); ?>"><?php _e('分类'); ?></a></li>
                <li class="<?php if ($this->is('page', 'archives')) echo 'current'; ?>"><a href="<?php echo ar_e($navBase . 'archives.html'); ?>"><?php _e('归档'); ?></a></li>
                <li class="<?php if ($this->is('page', 'links')) echo 'current'; ?>"><a href="<?php echo ar_e($navBase . 'links.html'); ?>"><?php _e('友链'); ?></a></li>
                <li class="<?php if ($this->is('page', 'guestbook')) echo 'current'; ?>"><a href="<?php echo ar_e($navBase . 'guestbook.html'); ?>"><?php _e('留言'); ?></a></li>
                <li class="<?php if ($this->is('page', 'start-page')) echo 'current'; ?>"><a href="<?php echo ar_e($navBase . 'start-page.html'); ?>"><?php _e('关于'); ?></a></li>
            </ul>
        </nav>

        <div class="top-actions">
            <button class="icon-btn" id="themeToggle" type="button" title="<?php _e('切换深色模式'); ?>" aria-label="<?php _e('切换深色模式'); ?>">
                <svg class="ico"><use href="#i-moon" id="themeIcon"></use></svg>
            </button>
            <button class="icon-btn" id="randomPost" type="button" title="<?php _e('随机文章'); ?>" aria-label="<?php _e('随机文章'); ?>">
                <svg class="ico"><use href="#i-shuffle"></use></svg>
            </button>
            <button class="icon-btn" id="searchOpen" type="button" title="<?php _e('搜索'); ?>" aria-label="<?php _e('搜索'); ?>">
                <svg class="ico"><use href="#i-search"></use></svg>
            </button>
        </div>
    </div>
</header>

<div class="search-modal" id="searchModal" role="dialog" aria-label="<?php _e('搜索'); ?>">
    <div class="search-box">
        <form method="post" action="<?php $this->options->siteUrl(); ?>" role="search">
            <input type="text" name="s" id="searchInput" placeholder="<?php _e('搜索文章...'); ?>" autocomplete="off" required>
            <button type="submit"><?php _e('搜索'); ?></button>
        </form>
        <div class="search-hint"><?php _e('输入关键词后回车搜索 · Esc 关闭'); ?></div>
    </div>
</div>

<div class="drawer-mask" id="drawerMask"></div>
<aside class="drawer" id="drawer" aria-label="<?php _e('移动端菜单'); ?>">
    <div class="drawer-head">
        <span><?php _e('导航菜单'); ?></span>
        <button class="icon-btn" id="drawerClose" type="button" aria-label="<?php _e('关闭'); ?>"><svg class="ico"><use href="#i-close"></use></svg></button>
    </div>
    <div class="drawer-nav">
        <a href="<?php $this->options->siteUrl(); ?>" class="<?php if ($this->is('index')) echo 'current'; ?>"><?php _e('首页'); ?></a>
        <?php
        $drawerCategories = ar_widget(array('Widget\Metas\Category\Rows', 'Widget_Meta_Category'));
        while ($drawerCategories->next()):
            if (!$drawerCategories->count) continue;
        ?>
            <a href="<?php $drawerCategories->permalink(); ?>"><?php $drawerCategories->name(); ?></a>
        <?php endwhile; ?>
        <?php
        $drawerPages = ar_widget(array('Widget\Contents\Page\Rows', 'Widget_Contents_Page_Rows'));
        while ($drawerPages->next()):
        ?>
            <a href="<?php $drawerPages->permalink(); ?>"><?php $drawerPages->title(); ?></a>
        <?php endwhile; ?>
    </div>
    <div class="drawer-section">
        <a href="<?php $this->options->feedUrl(); ?>">RSS</a> ·
        <a href="<?php $this->options->commentsFeedUrl(); ?>">评论 RSS</a>
    </div>
</aside>

<div class="layout">
    <?php if ($this->is('post')): ?>
        <aside class="col post-toc-col" aria-label="<?php _e('文章目录'); ?>">
            <div class="post-toc-sticky">
                <div class="post-toc-heading"><svg class="ico"><use href="#i-list"></use></svg><?php _e('目录'); ?></div>
                <nav class="post-toc-list" id="postTocList"></nav>
                <div class="post-toc-foot"><span id="postTocCurrent"><?php _e('开始阅读'); ?></span></div>
            </div>
        </aside>
    <?php else: ?>
        <?php $this->need('profile.php'); ?>
    <?php endif; ?>
    <main class="col main-col" role="main">
