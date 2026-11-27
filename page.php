<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php $this->need('header.php'); ?>

<article class="card detail-card" itemscope itemtype="http://schema.org/Article">
    <h1 class="detail-title" style="text-align:left;margin-top:0"><?php $this->title(); ?></h1>
    <div class="detail-meta" style="justify-content:flex-start">
        <span><?php $this->date('Y-m-d'); ?></span>
        <span><?php $this->commentsNum(_t('0 条评论'), _t('1 条评论'), _t('%d 条评论')); ?></span>
    </div>
    <div class="post-content" itemprop="articleBody">
        <?php $this->content(); ?>
    </div>
</article>

<?php if ($this->allow('comment')): ?>
    <?php $this->need('comments.php'); ?>
<?php endif; ?>

<?php $this->need('footer.php'); ?>
