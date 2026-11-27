<?php
/**
 * 分类
 *
 * @package custom
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
?>
<?php $this->need('header.php'); ?>

<?php
$catTree = ar_categories_tree(500);
$catTotal = 0;
foreach ($catTree as $cat) { $catTotal += $cat['count']; }
$counts = ar_counts();
$siteAge = ar_site_age();
?>
<div class="card standalone-page categories-page">
    <div class="page-hero categories-hero ar-reveal">
        <svg class="ico page-hero-deco"><use href="#i-grid"></use></svg>
        <div class="page-hero-body">
            <span class="page-eyebrow"><svg class="ico"><use href="#i-grid"></use></svg> CATEGORY · 分类</span>
            <h1 class="page-hero-title"><?php $this->title(); ?></h1>
            <p class="page-hero-sub"><?php _e('按兴趣分门别类，找到你想看的那一片。'); ?></p>
            <div class="tab-pills" id="categoryTabs">
                <button class="tab-pill is-active" type="button" data-cat="all"><?php _e('全部'); ?> <span class="tab-pill-count"><?php echo $catTotal; ?></span></button>
                <?php foreach ($catTree as $cat): ?>
                    <button class="tab-pill" type="button" data-cat="<?php echo ar_e($cat['slug']); ?>"><?php echo ar_e($cat['name']); ?> <span class="tab-pill-count"><?php echo $cat['count']; ?></span></button>
                <?php endforeach; ?>
                <span class="pill-thumb" aria-hidden="true"></span>
            </div>
        </div>
        <div class="page-hero-stats">
            <div class="hero-stat"><b><?php echo count($catTree); ?></b><span><?php _e('个分类'); ?></span></div>
            <div class="hero-stat"><b><?php echo $catTotal; ?></b><span><?php _e('篇文章'); ?></span></div>
            <div class="hero-stat"><b><?php echo $counts[1]; ?></b><span><?php _e('条评论'); ?></span></div>
            <div class="hero-stat"><b><?php echo ar_e(substr($siteAge[0], 0, 4)); ?></b><span><?php _e('开始记录'); ?></span></div>
        </div>
    </div>

    <?php if ($this->content): ?>
        <div class="page-raw-content"><?php $this->content(); ?></div>
    <?php endif; ?>

    <?php
    // 每张分类卡片固定 4 行槽位：放不下时最后一行换成"更多"跳转，不足则留白
    $catSlots   = 4;
    $siteUrlRaw = rtrim((string)$this->options->siteUrl, '/') . '/';
    ?>
    <div class="cat-grid">
        <?php foreach ($catTree as $cat): ?>
            <?php
            $catPosts    = $cat['posts'];
            $catCount    = count($catPosts);
            $hasMore     = $catCount > $catSlots;
            $visibleNum  = $hasMore ? ($catSlots - 1) : $catSlots;
            $catLink     = $siteUrlRaw . 'index.php/category/' . rawurlencode($cat['slug']) . '/';
            ?>
            <section class="cat-panel ar-reveal" data-cat="<?php echo ar_e($cat['slug']); ?>">
                <h2 class="group-title cat-panel-title">
                    <svg class="ico"><use href="#i-tag"></use></svg><?php echo ar_e($cat['name']); ?> <span><?php echo $cat['count']; ?> <?php _e('篇'); ?></span>
                </h2>
                <?php if ($cat['description'] !== ''): ?>
                    <p class="cat-desc"><?php echo ar_e($cat['description']); ?></p>
                <?php else: ?>
                    <p class="cat-desc cat-desc-empty" aria-hidden="true">&nbsp;</p>
                <?php endif; ?>
                <div class="cat-posts">
                    <?php if (!$catPosts): ?>
                        <div class="empty-note"><?php _e('暂无文章'); ?></div>
                    <?php else: ?>
                        <?php foreach (array_slice($catPosts, 0, $visibleNum) as $post): ?>
                            <a class="archive-row" href="<?php echo ar_e($post['url']); ?>">
                                <span class="archive-dot"></span>
                                <span class="archive-date"><b><?php echo ar_e($post['date']); ?></b></span>
                                <span class="archive-title"><?php echo ar_e($post['title']); ?></span>
                                <span class="archive-meta">
                                    <?php if (ar_has('Views')): ?><span><svg class="ico"><use href="#i-eye"></use></svg><?php echo $post['views']; ?></span><?php endif; ?>
                                    <span><svg class="ico"><use href="#i-chat"></use></svg><?php echo $post['comments']; ?></span>
                                </span>
                            </a>
                        <?php endforeach; ?>
                        <?php if ($hasMore): ?>
                            <a class="cat-more" href="<?php echo ar_e($catLink); ?>"><?php _e('更多'); ?> <?php echo count($catPosts) - $catVisiblePosts; ?> <?php _e('篇'); ?><svg class="ico"><use href="#i-arrow-right"></use></svg></a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </section>
        <?php endforeach; ?>
    </div>

    <?php if (!$catTree): ?>
        <div class="empty-note"><?php _e('还没有分类。'); ?></div>
    <?php endif; ?>

    <div class="page-cta ar-reveal">
        <span><?php _e('共 '); ?><?php echo count($catTree); ?><?php _e(' 个分类，'); ?><?php echo $catTotal; ?><?php _e(' 篇文章。'); ?></span>
        <a href="<?php $this->options->siteUrl(); ?>"><?php _e('返回首页'); ?><svg class="ico"><use href="#i-arrow-right"></use></svg></a>
    </div>
</div>

<?php $this->need('footer.php'); ?>
