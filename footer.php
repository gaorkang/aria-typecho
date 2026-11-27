<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
    </main>
    <?php if (!$this->is('post')): ?>
        <?php $this->need('sidebar.php'); ?>
    <?php endif; ?>
</div>

<?php
$arFooterText = trim((string)ar_opt('footerText', ''));
$arIcp = trim((string)ar_opt('icp', ''));
$arPoliceIcp = trim((string)ar_opt('policeIcp', ''));
$arPoliceIcon = trim((string)ar_opt('policeIcpIcon', ''));
$arSiteAge = ar_site_age();
$arStartYear = trim((string)ar_opt('footerStartYear', ''));
if ($arStartYear === '' && isset($arSiteAge[0]) && $arSiteAge[0] !== '') {
    $arStartYear = substr($arSiteAge[0], 0, 4);
}
$arAuthor = trim((string)ar_opt('authorName', ''));
if ($arAuthor === '') {
    $arAuthor = $this->options->title();
}
?>
<footer class="footer ar-footer">
    <div class="ar-footer-line">
        <?php if ($arFooterText !== ''): ?>
            <?php echo ar_e($arFooterText); ?>
        <?php else: ?>
            &copy;<?php echo $arStartYear !== '' ? ar_e($arStartYear) . ' - ' : ''; ?><?php echo date('Y'); ?> By <?php echo ar_e($arAuthor); ?>
        <?php endif; ?>
    </div>
    <div class="ar-footer-line">
        <?php _e('框架'); ?> <a href="https://typecho.org/" target="_blank" rel="noopener">Typecho</a> |
        <?php _e('主题'); ?> <a href="https://github.com/gaorkang/aria-typecho" rel="noopener" target="_blank">Aria</a> |
        <a href="<?php $this->options->feedUrl(); ?>">RSS</a> |
        <a href="<?php $this->options->siteUrl(); ?>"><?php _e('首页'); ?></a>
    </div>
    <?php if ($arIcp !== '' || $arPoliceIcp !== ''): ?>
    <div class="ar-footer-line ar-footer-beian">
        <?php if ($arIcp !== ''): ?>
            <a href="https://beian.miit.gov.cn/" target="_blank" rel="noopener nofollow"><?php echo ar_e($arIcp); ?></a>
        <?php endif; ?>
        <?php if ($arIcp !== '' && $arPoliceIcp !== ''): ?> | <?php endif; ?>
        <?php if ($arPoliceIcp !== ''): ?>
            <a href="https://www.beian.gov.cn/" target="_blank" rel="noopener nofollow">
                <?php if ($arPoliceIcon !== ''): ?><img class="ar-beian-icon" src="<?php echo ar_e($arPoliceIcon); ?>" alt="" loading="lazy"><?php endif; ?><?php echo ar_e($arPoliceIcp); ?>
            </a>
        <?php endif; ?>
    </div>
    <?php endif; ?>
    <?php if ($arSiteAge[0] !== ''): ?>
    <div class="ar-footer-line ar-footer-age" id="arSiteAge" data-start="<?php echo ar_e($arSiteAge[0]); ?>"><?php _e('网站已上线'); ?> <?php echo $arSiteAge[1]; ?> <?php _e('天'); ?></div>
    <?php endif; ?>
</footer>

<div class="toc-fab" id="tocFab">
    <button type="button" id="tocToggle" aria-label="<?php _e('文章目录'); ?>"><svg class="ico"><use href="#i-list"></use></svg></button>
    <div class="toc-panel" id="tocPanel"></div>
</div>

<button class="back-top" id="backTop" type="button" aria-label="<?php _e('回到顶部'); ?>">
    <svg class="scroll-ring" viewBox="0 0 40 40" aria-hidden="true">
        <circle class="scroll-ring-bg" cx="20" cy="20" r="17"></circle>
        <circle class="scroll-ring-bar" id="scrollRingBar" cx="20" cy="20" r="17"></circle>
    </svg>
    <svg class="ico back-arrow"><use href="#i-arrow"></use></svg>
    <span class="scroll-pct" id="scrollPct"></span>
</button>

<div class="toast" id="toast" role="status" aria-live="polite"></div>

<!-- 底部常驻导航（移动端）：仿 IT 之家，可展开分类列表 -->
<nav class="dock" id="dock" aria-label="<?php _e('底部导航'); ?>">
    <a class="dock-item<?php if ($this->is('index') || $this->is('post') || $this->is('category') || $this->is('tag') || $this->is('search')) echo ' active'; ?>" href="<?php $this->options->siteUrl(); ?>" data-label="<?php _e('首页'); ?>">
        <svg class="ico"><use href="#i-home"></use></svg><span><?php _e('首页'); ?></span>
    </a>
    <button class="dock-item<?php if ($this->is('page', 'categories')) echo ' active'; ?>" id="dockCatsBtn" type="button" data-label="<?php _e('分类'); ?>" aria-expanded="false">
        <svg class="ico"><use href="#i-grid"></use></svg><span><?php _e('分类'); ?></span>
    </button>
    <a class="dock-item<?php if ($this->is('page', 'archives')) echo ' active'; ?>" href="<?php $this->options->siteUrl(); ?>index.php/archives.html" data-label="<?php _e('归档'); ?>">
        <svg class="ico"><use href="#i-archive"></use></svg><span><?php _e('归档'); ?></span>
    </a>
    <a class="dock-item<?php if ($this->is('page', 'links')) echo ' active'; ?>" href="<?php $this->options->siteUrl(); ?>index.php/links.html" data-label="<?php _e('友链'); ?>">
        <svg class="ico"><use href="#i-link"></use></svg><span><?php _e('友链'); ?></span>
    </a>
    <a class="dock-item<?php if ($this->is('page', 'guestbook')) echo ' active'; ?>" href="<?php $this->options->siteUrl(); ?>index.php/guestbook.html" data-label="<?php _e('留言'); ?>">
        <svg class="ico"><use href="#i-chat"></use></svg><span><?php _e('留言'); ?></span>
    </a>
    <a class="dock-item<?php if ($this->is('page', 'start-page')) echo ' active'; ?>" href="<?php $this->options->siteUrl(); ?>index.php/start-page.html" data-label="<?php _e('关于'); ?>">
        <svg class="ico"><use href="#i-user"></use></svg><span><?php _e('关于'); ?></span>
    </a>
</nav>

<div class="dock-mask" id="dockMask"></div>
<section class="dock-sheet" id="dockSheet" role="dialog" aria-label="<?php _e('分类列表'); ?>">
    <div class="dock-sheet-grip"></div>
    <div class="dock-sheet-head">
        <strong><?php _e('浏览分类'); ?></strong>
        <button class="icon-btn" id="dockSheetClose" type="button" aria-label="<?php _e('关闭'); ?>"><svg class="ico"><use href="#i-close"></use></svg></button>
    </div>
    <a class="dock-all-link" href="<?php $this->options->siteUrl(); ?>index.php/categories.html"><?php _e('查看全部分类页'); ?> <svg class="ico"><use href="#i-arrow-right"></use></svg></a>
    <div class="dock-cats">
        <?php
        $dockCats = ar_widget(array('Widget\Metas\Category\Rows', 'Widget_Meta_Category'));
        while ($dockCats->next()):
            if (!$dockCats->count) continue;
        ?>
            <a class="dock-cat<?php if ($this->is('category', $dockCats->slug)) echo ' active'; ?>" href="<?php $dockCats->permalink(); ?>">
                <svg class="ico"><use href="#i-tag"></use></svg>
                <span><?php $dockCats->name(); ?></span>
                <small><?php echo (int)$dockCats->count; ?></small>
            </a>
        <?php endwhile; ?>
        <a class="dock-cat" href="<?php $this->options->siteUrl(); ?>index.php/guestbook.html">
            <svg class="ico"><use href="#i-chat"></use></svg>
            <span><?php _e('留言板'); ?></span>
        </a>
    </div>
</section>

<div class="lightbox" id="lightbox" role="dialog" aria-label="<?php _e('图片预览'); ?>">
    <button type="button" id="lightboxClose" aria-label="<?php _e('关闭'); ?>">&times;</button>
    <img id="lightboxImg" src="" alt="">
</div>

<script type="application/json" id="arPostPool"><?php
    echo json_encode(ar_post_pool(40), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
?></script>
<script>window.AR_THEME_URL = '<?php echo rtrim($this->options->siteUrl, '/') . '/usr/themes/' . $this->options->theme; ?>';</script>
<script src="<?php $this->options->themeUrl('assets/app.js'); ?>" defer></script>
<?php $this->footer(); ?>
</body>
</html>
