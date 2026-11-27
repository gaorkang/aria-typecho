<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php $this->need('header.php'); ?>

<?php
$stickyPosts = $this->is('index') ? ar_sticky_posts(6) : array();
if (!empty($stickyPosts)):
    $heroStart = 0; // 默认从第 1 篇开始
?>
<section class="hero hero-standard<?php echo ar_enhance('Fade') ? ' ar-reveal' : ''; ?>" id="hero" style="--ar-reveal-dur:.8s">
    <div class="hero-bgs" id="heroBgs">
        <?php foreach ($stickyPosts as $i => $spost): ?>
        <div class="hero-bg-layer<?php echo $i === $heroStart ? ' is-active' : ''; ?>" data-bg="<?php echo ar_e($spost['thumb']); ?>"<?php if ($spost['thumb'] !== '' && $i === $heroStart): ?> style="background-image:url(&quot;<?php echo ar_e($spost['thumb']); ?>&quot;)"<?php endif; ?>></div>
        <?php endforeach; ?>
    </div>
    <div class="hero-scrim"></div>
    <div class="hero-content">
        <div class="hero-head">
            <?php
            // weatherCity 留空 = 按访客 IP 自动定位；填城市名 = 固定城市；off/关闭 = 关闭天气条
            $arWeatherCity = trim(ar_opt('weatherCity', ''));
            $arWeatherOff  = in_array(strtolower($arWeatherCity), array('off', '关闭', '0', 'false'), true);
            if (!$arWeatherOff):
            ?>
            <div class="hero-weather<?php echo $arWeatherCity === '' ? ' is-auto' : ''; ?>" id="heroWeather" data-city="<?php echo ar_e($arWeatherCity); ?>" title="<?php _e('根据访客所在地显示天气 · 数据来源：中国天气网'); ?>">
                <svg class="ico hw-icon"><use href="#w-cloud"></use></svg>
                <span class="hw-city"></span>
                <span class="hw-temp">--°</span>
                <span class="hw-desc"><?php _e('加载中…'); ?></span>
                <span class="hw-meta"></span>
            </div>
            <?php endif; ?>
        </div>
        <div class="hero-feature" id="heroFeature" data-start="<?php echo $heroStart; ?>">
            <ul class="hero-feat-track">
                <?php foreach ($stickyPosts as $i => $spost): ?>
                <li class="hero-feat<?php echo $i === $heroStart ? ' is-active' : ''; ?>" data-bg="<?php echo ar_e($spost['thumb']); ?>">
                    <a href="<?php echo ar_e($spost['url']); ?>">
                        <span class="hf-title"><?php echo ar_e($spost['title']); ?></span>
                        <span class="hf-meta">
                            <span><svg class="ico"><use href="#i-clock"></use></svg><?php echo ar_e($spost['date']); ?></span>
                            <span><svg class="ico"><use href="#i-clock"></use></svg><?php echo sprintf(_t('约 %d 分钟'), $spost['readtime']); ?></span>
                            <?php if ($spost['category'] !== ''): ?>
                            <span class="hf-cat"><?php echo ar_e($spost['category']); ?></span>
                            <?php endif; ?>
                        </span>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="hero-foot">
            <div class="hero-progress">
                <div class="hero-dots" id="heroDots"></div>
                <span class="hero-count" id="heroCount"></span>
            </div>
            <div class="hero-rec-ctrl">
                <button type="button" class="hero-rec-nav" id="heroPrev" aria-label="<?php _e('上一篇'); ?>"><svg class="ico"><use href="#i-arrow-left"></use></svg></button>
                <button type="button" class="hero-rec-nav" id="heroNext" aria-label="<?php _e('下一篇'); ?>"><svg class="ico"><use href="#i-arrow-right"></use></svg></button>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<?php ar_ad(''); ?>

<?php if ($this->is('index') && ar_enhance('Sort')): ?>
<div class="sort-tabs<?php echo ar_enhance('Fade') ? ' ar-reveal' : ''; ?>" id="sortTabs" style="--ar-reveal-dur:.4s">
    <button type="button" class="sort-tab active" data-sort="default"><?php _e('最新发布'); ?></button>
    <button type="button" class="sort-tab" data-sort="views"><?php _e('热度排序'); ?></button>
    <button type="button" class="sort-tab" data-sort="comments"><?php _e('评论排序'); ?></button>
</div>
<?php endif; ?>

<?php $this->need('loop.php'); ?>
<?php $this->pageNav(_t('上一页'), _t('下一页')); ?>

<?php $this->need('footer.php'); ?>
