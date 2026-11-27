<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php $this->need('header.php'); ?>

<div class="card page-head">
    <h1><?php _e('搜索结果'); ?></h1>
    <p><?php _e('关键词：'); ?><?php $this->keywords(); ?> · <?php _e('共找到'); ?> <?php echo $this->getTotal(); ?> <?php _e('篇文章'); ?></p>
</div>

<?php $this->need('loop.php'); ?>
<?php $this->pageNav(_t('上一页'), _t('下一页')); ?>

<?php $this->need('footer.php'); ?>
