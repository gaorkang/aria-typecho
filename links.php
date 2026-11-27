<?php
/**
 * 友链
 *
 * @package custom
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
?>
<?php $this->need('header.php'); ?>

<?php
// 初始化评论组件，令 $this->token() 在申请弹窗内可用
$arComments = null;
$this->comments()->to($arComments);
$groups = ar_link_groups();
$siteName = $this->options->title;
$siteUrlRaw = rtrim((string)$this->options->siteUrl, '/') . '/';
$siteAvatar = trim((string)ar_opt('avatar', ''));
if ($siteAvatar === '') $siteAvatar = $siteUrlRaw . 'favicon.ico';
$siteDesc = trim((string)$this->options->description);
if ($siteDesc === '') $siteDesc = _t('生活再平凡也是限量版');
?>
<div class="card standalone-page links-page">
    <div class="page-hero links-hero ar-reveal">
        <svg class="ico page-hero-deco"><use href="#i-link"></use></svg>
        <div class="page-hero-body">
            <span class="page-eyebrow"><svg class="ico"><use href="#i-link"></use></svg> LINK · 友链</span>
            <h1 class="page-hero-title"><?php $this->title(); ?></h1>
            <p class="page-hero-sub"><?php _e('和有趣的人交换链接，也交换一点生活的灵感。'); ?></p>
            <div class="tab-pills" id="linkTabs">
                <button class="tab-pill is-active" type="button" data-group="all"><?php _e('全部'); ?> <span class="tab-pill-count"><?php echo array_sum(array_map('count', $groups)); ?></span></button>
                <?php foreach ($groups as $name => $links): ?>
                    <button class="tab-pill" type="button" data-group="<?php echo ar_e($name); ?>"><?php echo ar_e($name); ?> <span class="tab-pill-count"><?php echo count($links); ?></span></button>
                <?php endforeach; ?>
                <span class="pill-thumb" aria-hidden="true"></span>
            </div>
        </div>
    </div>

    <?php if ($groups): ?>
        <?php foreach ($groups as $name => $links): ?>
            <section class="link-group-panel" data-group="<?php echo ar_e($name); ?>">
                <h2 class="group-title"><svg class="ico"><use href="#i-tag"></use></svg><?php echo ar_e($name); ?> <span><?php echo count($links); ?></span></h2>
                <div class="links-grid">
                    <?php foreach ($links as $link): ?>
                        <a class="link-card ar-reveal" href="<?php echo ar_e($link['url']); ?>" target="_blank" rel="noopener nofollow">
                            <span class="link-avatar">
                                <?php if ($link['avatar'] !== ''): ?>
                                    <img src="<?php echo ar_e($link['avatar']); ?>" alt="<?php echo ar_e($link['name']); ?>" loading="lazy" onerror="this.style.display='none';this.nextElementSibling.style.display='grid'">
                                    <span class="avatar-letter" style="display:none"><?php echo ar_e(ar_first($link['name'])); ?></span>
                                <?php else: ?>
                                    <span class="avatar-letter"><?php echo ar_e($link['icon'] !== '' ? $link['icon'] : ar_first($link['name'])); ?></span>
                                <?php endif; ?>
                            </span>
                            <span class="link-info">
                                <span class="link-name"><?php echo ar_e($link['name']); ?></span>
                                <span class="link-desc"><?php echo ar_e($link['desc']); ?></span>
                                <span class="link-meta">
                                    <span class="link-tag"><svg class="ico"><use href="#i-tag"></use></svg><?php echo ar_e($name); ?></span>
                                </span>
                            </span>
                            <svg class="ico link-arrow"><use href="#i-arrow-right"></use></svg>
                        </a>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="empty-note"><?php _e('暂无友情链接，请在主题设置中添加。'); ?></div>
    <?php endif; ?>

    <?php if ($this->content): ?>
        <div class="page-raw-content"><?php $this->content(); ?></div>
    <?php endif; ?>

    <?php if ($this->allow('comment')): ?>
    <section class="link-apply ar-reveal" id="link-apply">
        <svg class="ico link-apply-deco"><use href="#i-link"></use></svg>
        <div class="link-apply-head">
            <div class="link-apply-head-text">
                <h3 class="link-apply-title"><?php _e('申请友链'); ?><span class="link-apply-badge"><svg class="ico"><use href="#i-link"></use></svg> Friend Link</span></h3>
                <p class="link-apply-sub"><?php _e('欢迎交换友情链接，点击「立即申请」填写资料即可'); ?></p>
            </div>
            <button type="button" class="link-apply-btn" data-ar-apply-open><?php _e('立即申请'); ?><svg class="ico"><use href="#i-arrow-right"></use></svg></button>
        </div>
        <div class="link-apply-body">
            <div class="link-apply-panel">
                <div class="link-apply-col-title"><?php _e('本站信息'); ?></div>
                <ul class="link-apply-list">
                    <li><span class="lk"><?php _e('名称'); ?></span><span class="lv"><?php echo ar_e($siteName); ?></span><button type="button" class="lk-copy" data-copy="<?php echo ar_e($siteName); ?>" aria-label="<?php _e('复制名称'); ?>"><svg class="ico"><use href="#i-copy"></use></svg></button></li>
                    <li><span class="lk"><?php _e('地址'); ?></span><a class="lv" href="<?php echo ar_e($siteUrlRaw); ?>" target="_blank" rel="noopener"><?php echo ar_e($siteUrlRaw); ?></a><button type="button" class="lk-copy" data-copy="<?php echo ar_e($siteUrlRaw); ?>" aria-label="<?php _e('复制地址'); ?>"><svg class="ico"><use href="#i-copy"></use></svg></button></li>
                    <li><span class="lk"><?php _e('头像地址'); ?></span><a class="lv" href="<?php echo ar_e($siteAvatar); ?>" target="_blank" rel="noopener"><?php echo ar_e($siteAvatar); ?></a><button type="button" class="lk-copy" data-copy="<?php echo ar_e($siteAvatar); ?>" aria-label="<?php _e('复制头像地址'); ?>"><svg class="ico"><use href="#i-copy"></use></svg></button></li>
                    <li><span class="lk"><?php _e('简介'); ?></span><span class="lv"><?php echo ar_e($siteDesc); ?></span><button type="button" class="lk-copy" data-copy="<?php echo ar_e($siteDesc); ?>" aria-label="<?php _e('复制简介'); ?>"><svg class="ico"><use href="#i-copy"></use></svg></button></li>
                </ul>
            </div>
            <div class="link-apply-panel">
                <div class="link-apply-col-title"><?php _e('申请要求'); ?></div>
                <ul class="link-apply-rules">
                    <li><span class="rule-dot"><svg class="ico"><use href="#i-check"></use></svg></span><span><?php _e('内容原创或有一定质量，无违法违规内容'); ?></span></li>
                    <li><span class="rule-dot"><svg class="ico"><use href="#i-check"></use></svg></span><span><?php _e('已在贵站添加本站友链'); ?></span></li>
                    <li><span class="rule-dot"><svg class="ico"><use href="#i-check"></use></svg></span><span><?php _e('保持更新，拒绝纯采集 / 长期停更站点'); ?></span></li>
                </ul>
            </div>
        </div>
    </section>
    <?php endif; ?>
</div>

<?php if ($this->allow('comment')): ?>
<div class="ar-modal lap-modal" id="arApplyModal" aria-hidden="true">
    <div class="ar-modal-mask" data-ar-apply-close></div>
    <div class="ar-modal-card lap-card" role="dialog" aria-modal="true" aria-labelledby="arApplyTitle">
        <button type="button" class="ar-modal-close lap-close" data-ar-apply-close aria-label="<?php _e('关闭'); ?>"><svg class="ico"><use href="#i-close"></use></svg></button>
        <header class="lap-hero">
            <svg class="ico lap-deco"><use href="#i-link"></use></svg>
            <div class="lap-hero-body">
                <h3 class="lap-hero-title" id="arApplyTitle"><?php _e('申请友链'); ?><span class="lap-badge"><svg class="ico"><use href="#i-link"></use></svg> Friend Link</span></h3>
                <p class="lap-hero-sub"><?php _e('填写资料提交，新申请会直达站长'); ?></p>
            </div>
        </header>
        <div class="lap-body">
            <form method="post" action="<?php $this->commentUrl(); ?>" id="arApplyForm" class="lap-form" data-apply-mode="1">
                <?php $this->token(); ?>
                <input type="hidden" name="author" id="applyAuthor">
                <input type="hidden" name="mail" id="applyMail">
                <input type="hidden" name="url" id="applyUrl">
                <input type="hidden" name="text" id="applyText">
                <div class="lap-grid">
                    <label class="lap-field">
                        <span class="lap-label"><svg class="ico"><use href="#i-user"></use></svg><?php _e('站点名称'); ?><b>*</b></span>
                        <input type="text" id="applyName" maxlength="40" placeholder="<?php _e('我的小站'); ?>" required autocomplete="organization">
                    </label>
                    <label class="lap-field">
                        <span class="lap-label"><svg class="ico"><use href="#i-mail"></use></svg><?php _e('联系邮箱'); ?><b>*</b></span>
                        <input type="email" id="applyEmail" maxlength="100" placeholder="you@example.com" required autocomplete="email">
                    </label>
                    <label class="lap-field">
                        <span class="lap-label"><svg class="ico"><use href="#i-link"></use></svg><?php _e('站点地址'); ?><b>*</b></span>
                        <input type="url" id="applySite" maxlength="200" placeholder="https://example.com" required>
                    </label>
                    <label class="lap-field">
                        <span class="lap-label"><svg class="ico"><use href="#i-image"></use></svg><?php _e('头像地址'); ?><span class="lap-opt"><?php _e('选填'); ?></span></span>
                        <input type="url" id="applyAvatar" maxlength="200" placeholder="<?php _e('留空自动使用站点图标'); ?>">
                    </label>
                    <label class="lap-field lap-span">
                        <span class="lap-label"><svg class="ico"><use href="#i-file"></use></svg><?php _e('站点简介'); ?><b>*</b></span>
                        <textarea id="applyDesc" rows="3" maxlength="200" placeholder="<?php _e('一句话介绍你的站点，并附上你的友链地址…'); ?>" required></textarea>
                        <span class="lap-hint"><?php _e('200 字以内，通过后会展示在上方友链列表'); ?></span>
                    </label>
                </div>
                <div class="lap-bar">
                    <span class="lap-bar-note"><svg class="ico"><use href="#i-check"></use></svg><?php _e('仅用于通知站长，不公开、不作为评论展示'); ?></span>
                    <button type="submit" class="lap-submit" id="arApplySubmit"><?php _e('提交申请'); ?><svg class="ico"><use href="#i-arrow-right"></use></svg></button>
                </div>
                <div class="lap-err" id="arApplyErr" hidden></div>
            </form>
            <div class="lap-done" id="arApplyDone" hidden>
                <span class="lap-done-ico"><svg class="ico"><use href="#i-check"></use></svg></span>
                <h4><?php _e('申请已提交'); ?></h4>
                <p><?php _e('已发送给站长，审核通过后即可在上方友链列表看到你的站点。'); ?></p>
                <button type="button" class="lap-done-btn" data-ar-apply-close><?php _e('知道了'); ?></button>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php if ($this->allow('comment')): ?>
<div id="arApplyRealRespond" hidden aria-hidden="true">
    <?php $this->need('comments.php'); ?>
</div>
<?php endif; ?>

<?php $this->need('footer.php'); ?>
