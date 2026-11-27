<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<aside class="col profile-col" aria-label="<?php _e('作者信息'); ?>">
    <?php
    $authorName = ar_opt('authorName', '');
    if ($authorName === '') {
        $authorName = $this->options->title();
    }
    $avatar = ar_opt('avatar', '');
    $initial = ar_first($authorName);
    $counts = ar_counts();
    $badge = ar_opt('badge', '');
    $profileTags = ar_lines(ar_opt('profileTags', ''));
    $bio = ar_opt('bio', '');
    ?>
    <section class="card profile-card<?php echo ar_enhance('Fade') ? ' ar-reveal' : ''; ?>" style="--ar-reveal-dur:.6s">
        <div class="avatar-wrap">
            <?php if ($avatar !== ''): ?>
                <img class="avatar" src="<?php echo ar_e($avatar); ?>" alt="<?php echo ar_e($authorName); ?>">
            <?php else: ?>
                <div class="avatar avatar-fallback"><?php echo ar_e($initial); ?></div>
            <?php endif; ?>
            <span class="avatar-badge" title="<?php _e('认证作者'); ?>"><svg class="ico"><use href="#i-check"></use></svg></span>
        </div>
        <div class="profile-name"><?php echo ar_e($authorName); ?></div>
        <?php if ($badge !== ''): ?><div class="profile-badge-row"><span class="profile-badge"><?php echo ar_e($badge); ?></span></div><?php endif; ?>
        <?php if ($profileTags): ?>
            <div class="profile-tags">
                <?php foreach ($profileTags as $i => $tag): ?>
                    <span class="profile-tag profile-tag-<?php echo $i % 4; ?>"><?php echo ar_e($tag); ?></span>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <?php if ($bio !== ''): ?><p class="profile-bio"><?php echo nl2br(ar_e($bio)); ?></p><?php endif; ?>
        <div class="profile-ip"><?php _e('记录生活 · 分享热爱'); ?></div>
    </section>

    <div class="stat-card<?php echo ar_enhance('Fade') ? ' ar-reveal' : ''; ?>" style="--ar-reveal-dur:.45s">
        <a class="stat-item" href="<?php $this->options->siteUrl(); ?>">
            <div class="stat-label"><span><?php _e('文章'); ?></span><svg class="ico"><use href="#i-up"></use></svg></div>
            <div class="stat-num"><?php echo $counts[0]; ?></div>
            <span class="stat-deco"></span>
        </a>
        <a class="stat-item" href="<?php $this->options->siteUrl(); ?>#comments">
            <div class="stat-label"><span><?php _e('评论'); ?></span><svg class="ico"><use href="#i-up"></use></svg></div>
            <div class="stat-num"><?php echo $counts[1]; ?></div>
            <span class="stat-deco"></span>
        </a>
    </div>

    <?php $socials = ar_socials(); ?>
    <?php if ($socials): ?>
        <section class="card<?php echo ar_enhance('Fade') ? ' ar-reveal' : ''; ?>" style="margin-top:16px;--ar-reveal-dur:.5s">
            <h2 class="card-title"><span>📟 <?php _e('社交媒体'); ?></span></h2>
            <div class="social-row">
                <?php foreach ($socials as $social): ?>
                    <a class="social-link" href="<?php echo ar_e($social['url']); ?>" title="<?php echo ar_e($social['name']); ?>" aria-label="<?php echo ar_e($social['name']); ?>" target="_blank" rel="noopener nofollow"><svg class="ico"><use href="#<?php echo ar_e(ar_social_icon($social)); ?>"></use></svg></a>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>

    <?php if (ar_module('Ad') && trim((string)ar_opt('adCode', '')) !== ''): ?>
        <section class="card<?php echo ar_enhance('Fade') ? ' ar-reveal' : ''; ?>" style="margin-top:16px;--ar-reveal-dur:.5s">
            <h2 class="card-title"><span>📷 <?php _e('广而告之'); ?></span></h2>
            <?php ar_ad('compact'); ?>
        </section>
    <?php endif; ?>
</aside>
