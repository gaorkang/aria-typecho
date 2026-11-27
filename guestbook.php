<?php
/**
 * 留言板
 *
 * @package custom
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
?>
<?php $this->need('header.php'); ?>

<?php
$gbStats = ar_guestbook_stats($this->cid);
$gbSub = trim((string)ar_opt('guestbookSub', ''));
if ($gbSub === '') $gbSub = _t('不必想好开场白，路过就留下点什么吧。');
$gbStats[2] = $gbStats[2] !== '' ? $gbStats[2] : _t('暂无');
?>
<div class="card standalone-page guestbook-page">
    <div class="page-hero gb-hero ar-reveal">
        <svg class="ico page-hero-deco"><use href="#i-chat"></use></svg>
        <div class="page-hero-body">
            <span class="page-eyebrow"><svg class="ico"><use href="#i-chat"></use></svg> GUESTBOOK · 留言</span>
            <h1 class="page-hero-title"><?php $this->title(); ?></h1>
            <p class="page-hero-sub"><?php echo ar_e($gbSub); ?></p>
        </div>
        <div class="page-hero-stats gb-stats">
            <div class="hero-stat"><b><?php echo $gbStats[0]; ?></b><span><?php _e('条留言'); ?></span></div>
            <div class="hero-stat"><b><?php echo $gbStats[1]; ?></b><span><?php _e('位留言者'); ?></span></div>
            <div class="hero-stat"><b class="gb-stat-time"><?php echo ar_e($gbStats[2]); ?></b><span><?php _e('最近留言'); ?></span></div>
        </div>
    </div>

    <?php if ($this->content): ?>
        <div class="page-raw-content"><?php $this->content(); ?></div>
    <?php endif; ?>

    <div class="gb-tips">
        <div class="gb-tip ar-reveal"><svg class="ico"><use href="#i-pencil"></use></svg><span><b><?php _e('文章相关'); ?></b><?php _e('讨论请到文章下留言'); ?></span></div>
        <div class="gb-tip ar-reveal"><svg class="ico"><use href="#i-link"></use></svg><span><b><?php _e('友链申请'); ?></b><?php _e('请在本页留言'); ?></span></div>
        <div class="gb-tip ar-reveal"><svg class="ico"><use href="#i-check"></use></svg><span><b><?php _e('邮箱仅用于头像'); ?></b><?php _e('，不会公开'); ?></span></div>
    </div>
</div>

<?php if ($this->allow('comment')): ?>
    <?php $GLOBALS['arCommentNoun'] = _t('留言'); ?>
    <?php $this->need('comments.php'); ?>
<?php endif; ?>

<?php $this->need('footer.php'); ?>
