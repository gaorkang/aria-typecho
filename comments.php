<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php $this->comments()->to($comments); ?>
<?php
$arCommentNoun = isset($GLOBALS['arCommentNoun']) ? $GLOBALS['arCommentNoun'] : _t('评论');
$arCommentZero = sprintf(_t('暂无%s'), $arCommentNoun);
$arCommentOne  = sprintf(_t('1 条%s'), $arCommentNoun);
$arCommentMany = sprintf(_t('%%d 条%s'), $arCommentNoun);
$arCommentSubmit = sprintf(_t('发表%s'), $arCommentNoun);
$arIsGuestbook = isset($GLOBALS['arCommentNoun']);
$arCommentPlaceholder = $arIsGuestbook
    ? sprintf(_t('说点什么，留下你的%s...'), $arCommentNoun)
    : _t('说点什么吧...');
unset($GLOBALS['arCommentNoun']);
?>
<section class="card comments-card" id="comments">
    <h2 class="comment-title"><?php $this->commentsNum($arCommentZero, $arCommentOne, $arCommentMany); ?></h2>

    <?php if ($this->allow('comment')): ?>
        <div id="<?php $this->respondId(); ?>" class="respond">
            <div class="cancel-comment-reply"><?php $comments->cancelReply(); ?></div>
            <form method="post" action="<?php $this->commentUrl(); ?>" id="comment-form" class="comment-form" role="form">
                <?php if ($this->user->hasLogin()): ?>
                    <input type="hidden" name="author" value="<?php echo ar_e($this->user->screenName); ?>">
                    <input type="hidden" name="mail" value="<?php echo ar_e($this->user->mail); ?>">
                    <input type="hidden" name="url" value="<?php echo ar_e($this->user->url); ?>">
                    <div style="grid-column:1/-1;color:var(--muted);font-size:12px"><?php echo ar_e($this->user->screenName); ?> <?php echo ar_e(sprintf(_t('正在发表%s'), $arCommentNoun)); ?></div>
                <?php else: ?>
                    <input type="text" name="author" id="author" placeholder="<?php _e('称呼'); ?>" value="<?php $this->remember('author'); ?>" required>
                    <input type="email" name="mail" id="mail" placeholder="<?php _e('邮箱'); ?>" value="<?php $this->remember('mail'); ?>"<?php if ($this->options->commentsRequireMail): ?> required<?php endif; ?>>
                    <input type="url" name="url" id="url" placeholder="<?php _e('网站（选填）'); ?>" value="<?php $this->remember('url'); ?>"<?php if ($this->options->commentsRequireUrl): ?> required<?php endif; ?>>
                <?php endif; ?>
                <textarea name="text" id="textarea" rows="5" placeholder="<?php echo ar_e($arCommentPlaceholder); ?>" required><?php $this->remember('text'); ?></textarea>
                <?php $this->token(); ?>
                <div class="submit-wrap"><button type="submit" class="btn-primary"><?php echo ar_e($arCommentSubmit); ?></button></div>
            </form>
        </div>
    <?php else: ?>
        <p style="color:var(--muted);font-size:12px"><?php _e('评论已关闭'); ?></p>
    <?php endif; ?>

    <?php if ($comments->have()): ?>
        <?php $comments->listComments(); ?>
        <?php $comments->pageNav(_t('上一页'), _t('下一页')); ?>
    <?php endif; ?>
</section>

<?php if ($this->allow('comment')): ?>
    <div class="comment-dock" id="commentDock">
        <svg class="ico" style="color:var(--primary)"><use href="#i-pencil"></use></svg>
        <span><?php _e('我来说说...'); ?></span>
        <button type="button" id="dockSubmit" aria-label="<?php _e('发表评论'); ?>"><svg class="ico"><use href="#i-pencil"></use></svg></button>
    </div>
<?php endif; ?>
