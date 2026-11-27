<?php
/**
 * 归档
 *
 * @package custom
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
?>
<?php $this->need('header.php'); ?>

<?php
$tree = ar_archive_tree(500);
$total = 0;
$yearKeys = array_keys($tree);
foreach ($tree as $year => $info) { $total += $info['count']; }
$counts = ar_counts();
$siteAge = ar_site_age();
$firstYear = !empty($yearKeys) ? end($yearKeys) : date('Y');
?>
<div class="card standalone-page archive-page">
    <div class="page-hero archive-hero ar-reveal">
        <svg class="ico page-hero-deco"><use href="#i-archive"></use></svg>
        <div class="page-hero-body">
            <span class="page-eyebrow"><svg class="ico"><use href="#i-archive"></use></svg> ARCHIVE · 归档</span>
            <h1 class="page-hero-title"><?php $this->title(); ?></h1>
            <p class="page-hero-sub"><?php _e('写过的日子都收在这里，按年份慢慢翻。'); ?></p>
            <?php if ($yearKeys): ?>
            <div class="tab-pills year-tabs" id="archiveTabs">
                <?php foreach ($yearKeys as $i => $year): ?>
                    <button class="tab-pill<?php echo $i === 0 ? ' is-active' : ''; ?>" type="button" data-year="<?php echo ar_e($year); ?>"><?php echo ar_e($year); ?> <span class="tab-pill-count"><?php echo $tree[$year]['count']; ?> <?php _e('篇'); ?></span></button>
                <?php endforeach; ?>
                <span class="pill-thumb" aria-hidden="true"></span>
            </div>
            <?php endif; ?>
        </div>
        <div class="page-hero-stats">
            <div class="hero-stat"><b><?php echo $total; ?></b><span><?php _e('篇文章'); ?></span></div>
            <div class="hero-stat"><b><?php echo count($yearKeys); ?></b><span><?php _e('个年份'); ?></span></div>
            <div class="hero-stat"><b><?php echo $counts[1]; ?></b><span><?php _e('条评论'); ?></span></div>
            <div class="hero-stat"><b><?php echo ar_e($firstYear); ?></b><span><?php _e('开始记录'); ?></span></div>
        </div>
    </div>

    <?php if ($this->content): ?>
        <div class="page-raw-content"><?php $this->content(); ?></div>
    <?php endif; ?>

    <?php foreach ($yearKeys as $i => $year): ?>
        <section class="archive-year-panel" data-year="<?php echo ar_e($year); ?>"<?php echo $i > 0 ? ' hidden' : ''; ?>>
            <?php foreach ($tree[$year]['months'] as $month => $group): ?>
                <div class="archive-month"><b><?php echo (int)$month; ?><?php _e('月'); ?></b><span><?php echo $group['count']; ?> <?php _e('篇'); ?></span><i></i></div>
                <?php foreach ($group['posts'] as $post): ?>
                    <a class="archive-row ar-reveal" href="<?php echo ar_e($post['url']); ?>">
                        <span class="archive-dot"></span>
                        <span class="archive-date"><b><?php echo ar_e($post['day']); ?></b><i><?php echo ar_e($post['week']); ?></i></span>
                        <span class="archive-title"><?php echo ar_e($post['title']); ?></span>
                        <span class="archive-meta">
                            <?php if (ar_has('Views')): ?><span><svg class="ico"><use href="#i-eye"></use></svg><?php echo $post['views']; ?></span><?php endif; ?>
                            <span><svg class="ico"><use href="#i-chat"></use></svg><?php echo $post['comments']; ?></span>
                        </span>
                    </a>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </section>
    <?php endforeach; ?>

    <?php if (!$tree): ?>
        <div class="empty-note"><?php _e('还没有归档的文章。'); ?></div>
    <?php endif; ?>

    <div class="page-cta ar-reveal">
        <span><?php _e('以「'); ?><?php echo ar_e($siteAge[0]); ?><?php _e('」为起点，已经记录了 '); ?><?php echo $siteAge[1]; ?><?php _e(' 天。'); ?></span>
        <a href="<?php $this->options->siteUrl(); ?>"><?php _e('返回首页'); ?><svg class="ico"><use href="#i-arrow-right"></use></svg></a>
    </div>
</div>

<?php if ($this->allow('comment')): ?>
    <?php $this->need('comments.php'); ?>
<?php endif; ?>

<?php $this->need('footer.php'); ?>
