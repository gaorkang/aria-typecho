<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php $this->need('header.php'); ?>

<?php ar_ad(''); ?>

<article class="card detail-card" itemscope itemtype="http://schema.org/BlogPosting">
    <div class="detail-head">
        <?php $avatar = ar_opt('avatar', ''); ?>
        <?php if ($avatar !== ''): ?>
            <img class="avatar-sm" src="<?php echo ar_e($avatar); ?>" alt="<?php $this->author(); ?>">
        <?php else: ?>
            <span class="avatar-sm avatar-fallback" style="display:grid;place-items:center;font-size:14px"><?php echo ar_e(ar_first(ar_author_name($this))); ?></span>
        <?php endif; ?>
        <div>
            <a class="post-author" href="<?php $this->author->permalink(); ?>"><?php $this->author(); ?></a>
            <div class="post-meta"><?php echo ar_e(ar_time_ago($this->date)); ?> · <?php $this->date('Y-m-d'); ?></div>
        </div>
    </div>

    <h1 class="detail-title" itemprop="name headline"><?php $this->title(); ?></h1>

    <?php
    ob_start();
    $this->content();
    $contentHtml = (string)ob_get_clean();
    ?>
    <?php $stats = ar_read_stats($this); ?>
    <?php $views = ar_views($this->cid, isset($this->fields->views) ? $this->fields->views : 0); ?>
    <div class="detail-meta">
        <span><?php _e('文章字数：'); ?><?php echo $stats[0]; ?></span>
        <span><?php _e('阅读时间：'); ?><?php echo $stats[1]; ?><?php _e(' 分钟'); ?></span>
        <?php if (ar_has('Views')): ?><span><?php _e('浏览：'); ?><?php echo $views; ?></span><?php endif; ?>
        <span><?php $this->commentsNum(_t('0 条评论'), _t('1 条评论'), _t('%d 条评论')); ?></span>
    </div>

    <div class="post-content" itemprop="articleBody">
        <?php
        $paywallText = ar_opt('paywallText', '');
        if ($paywallText !== '') {
            $paywallUrl = ar_opt('paywallUrl', '');
            $paywallCard = '<div class="paywall"><div>🔒 ' . ar_e($paywallText) . '</div>'
                . ($paywallUrl !== '' ? '<a class="btn-primary" href="' . ar_e($paywallUrl) . '" target="_blank" rel="noopener nofollow">' . _t('前往查看') . '</a>' : '')
                . '</div>';
            $contentHtml = preg_replace('/<\/p>/i', '</p>' . $paywallCard, $contentHtml, 1);
            if (strpos($contentHtml, 'class="paywall"') === false) {
                $contentHtml .= $paywallCard;
            }
        }
        echo $contentHtml;
        ?>
    </div>

    <?php if (!empty($this->tags)): ?>
        <?php
        $postTags = array_values($this->tags);
        $tagLimit = 8;
        ?>
        <div class="tags" id="postTags">
            <?php foreach ($postTags as $i => $tag): ?>
                <span class="tag<?php if ($i >= $tagLimit) echo ' tag-extra'; ?>"># <a href="<?php echo ar_e($tag['permalink']); ?>"><?php echo ar_e($tag['name']); ?></a></span>
            <?php endforeach; ?>
            <?php if (count($postTags) > $tagLimit): ?>
                <button type="button" class="tag-more" id="tagMore" aria-expanded="false" title="<?php _e('展开全部标签'); ?>">···</button>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="vote-row">
        <?php $starBase = isset($this->fields->stars) ? (int)$this->fields->stars : 0; ?>
        <?php $likeBase = isset($this->fields->likes) ? (int)$this->fields->likes : 0; ?>
        <button class="action star-action" type="button" data-star data-cid="<?php $this->cid(); ?>" data-base="<?php echo $starBase; ?>">
            <svg class="ico"><use href="#i-star"></use></svg><span><?php echo $starBase; ?></span> <?php _e('收藏'); ?>
        </button>
        <button class="action like-action" type="button" data-like data-cid="<?php $this->cid(); ?>" data-base="<?php echo $likeBase; ?>">
            <svg class="ico"><use href="#i-heart"></use></svg><span><?php echo $likeBase; ?></span> <?php _e('喜欢'); ?>
        </button>
        <a class="action" href="#comments">
            <svg class="ico"><use href="#i-chat"></use></svg><span><?php $this->commentsNum(); ?></span> <?php _e('评论'); ?>
        </a>
    </div>
</article>

<?php ar_ad(''); ?>

<?php
$related = array();
foreach (ar_post_pool(40) as $post) {
    if ($post['cid'] != $this->cid) {
        $related[] = $post;
    }
    if (count($related) >= 4) break;
}
?>
<?php if ($related): ?>
    <section class="card side-card">
        <h2 class="card-title"><span>🔗 <?php _e('继续阅读'); ?></span></h2>
        <ul class="mini-list">
            <?php foreach ($related as $post): ?>
                <li><a href="<?php echo ar_e($post['url']); ?>"><?php echo ar_e($post['title']); ?></a></li>
            <?php endforeach; ?>
        </ul>
    </section>
<?php endif; ?>

<ul class="post-near">
    <li><span><?php _e('上一篇'); ?></span><?php $this->thePrev('%s', _t('没有了')); ?></li>
    <li><span><?php _e('下一篇'); ?></span><?php $this->theNext('%s', _t('没有了')); ?></li>
</ul>

<?php $this->need('comments.php'); ?>
<?php $this->need('footer.php'); ?>
