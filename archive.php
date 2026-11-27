<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php $this->need('header.php'); ?>

<div class="card page-head">
    <h1><?php $this->archiveTitle(array(
        'category' => _t('分类 %s 下的文章'),
        'tag'      => _t('标签 %s 下的文章'),
        'author'   => _t('%s 发布的文章'),
        'date'     => _t('%s 的归档')
    ), '', ''); ?></h1>
    <p><?php _e('共收录'); ?> <?php echo $this->getTotal(); ?> <?php _e('篇文章'); ?></p>
</div>

<?php $this->need('loop.php'); ?>
<?php $this->pageNav(_t('上一页'), _t('下一页')); ?>

<?php $this->need('footer.php'); ?>
