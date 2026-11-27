<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php $this->need('header.php'); ?>

<div class="card notfound">
    <h1>404</h1>
    <p><?php _e('页面走丢了，不如去首页看看？'); ?></p>
    <a class="btn-primary" href="<?php $this->options->siteUrl(); ?>"><?php _e('返回首页'); ?></a>
</div>

<?php $this->need('footer.php'); ?>
