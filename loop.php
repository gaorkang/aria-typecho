<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php
$globalAvatar = ar_opt('avatar', '');
while ($this->next()):
    $images = ar_content_images(isset($this->content) ? $this->content : '', 3);
    $fieldThumb = isset($this->fields->thumb) ? trim((string)$this->fields->thumb) : '';
    if (!$images && $fieldThumb !== '') {
        $images[] = $fieldThumb;
    }
    $likeBase = isset($this->fields->likes) ? (int)$this->fields->likes : 0;
    $starBase = isset($this->fields->stars) ? (int)$this->fields->stars : 0;
    $viewCount = ar_views($this->cid, isset($this->fields->views) ? $this->fields->views : 0);
?>
    <article class="card post-card<?php echo ar_enhance('Fade') ? ' ar-reveal' : ''; ?>" data-views="<?php echo (int)$viewCount; ?>" data-comments="<?php echo (int)$this->commentsNum; ?>"<?php echo ar_enhance('Fade') ? ' style="--ar-reveal-dur:.55s"' : ''; ?>>
        <div class="post-head">
            <a href="<?php $this->author->permalink(); ?>">
                <?php if ($globalAvatar !== ''): ?>
                    <img class="avatar-sm" src="<?php echo ar_e($globalAvatar); ?>" alt="<?php $this->author(); ?>">
                <?php else: ?>
                    <span class="avatar-sm avatar-fallback" style="display:grid;place-items:center;font-size:14px"><?php echo ar_e(ar_first(ar_author_name($this))); ?></span>
                <?php endif; ?>
            </a>
            <a class="post-author-link" href="<?php $this->author->permalink(); ?>">
                <span class="post-author"><?php $this->author(); ?></span>
                <span class="post-meta"><?php echo ar_e(ar_time_ago($this->date)); ?></span>
            </a>
        </div>

        <h2 class="post-title">
            <?php if (!empty($this->sticky)): ?><span class="sticky-badge"><?php _e('置顶'); ?></span><?php endif; ?>
            <a href="<?php $this->permalink(); ?>"><?php $this->title(); ?></a>
        </h2>
        <p class="post-excerpt"><?php $this->excerpt(180, '...'); ?></p>

        <?php if ($images): ?>
            <div class="post-images<?php echo count($images) === 1 ? ' single' : ''; ?>">
                <?php foreach ($images as $image): ?>
                    <img class="<?php echo ar_enhance('Shimmer') ? 'ar-shimmer' : ''; ?>" src="<?php echo ar_e($image); ?>" alt="<?php $this->title(); ?>" loading="lazy">
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($this->tags)): ?>
            <div class="tags">
                <?php foreach ($this->tags as $tag): ?>
                    <span class="tag"># <a href="<?php echo ar_e($tag['permalink']); ?>"><?php echo ar_e($tag['name']); ?></a></span>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="post-actions">
            <?php if (ar_has('Views')): ?>
                <a class="action" href="<?php $this->permalink(); ?>" title="<?php _e('浏览'); ?>">
                    <svg class="ico"><use href="#i-eye"></use></svg><span><?php echo $viewCount; ?></span>
                </a>
            <?php endif; ?>
            <a class="action" href="<?php $this->permalink(); ?>#comments" title="<?php _e('评论'); ?>">
                <svg class="ico"><use href="#i-chat"></use></svg><span><?php $this->commentsNum(); ?></span>
            </a>
            <button class="action star-action" type="button" data-star data-cid="<?php $this->cid(); ?>" data-base="<?php echo $starBase; ?>" title="<?php _e('收藏'); ?>">
                <svg class="ico"><use href="#i-star"></use></svg><span><?php echo $starBase; ?></span>
            </button>
            <button class="action like-action" type="button" data-like data-cid="<?php $this->cid(); ?>" data-base="<?php echo $likeBase; ?>" title="<?php _e('喜欢'); ?>">
                <svg class="ico"><use href="#i-heart"></use></svg><span><?php echo $likeBase; ?></span>
            </button>
        </div>
    </article>
<?php endwhile; ?>
