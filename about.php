<?php
/**
 * 关于
 *
 * @package custom
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
?>
<?php $this->need('header.php'); ?>

<?php
$authorName = trim((string)ar_opt('authorName', ''));
if ($authorName === '') { $authorName = $this->options->title(); }
$avatar = (string)ar_opt('avatar', '');
$aboutTitle = trim((string)ar_opt('aboutTitle', ''));
if ($aboutTitle === '') { $aboutTitle = $authorName; }
$counts = ar_counts();
$siteAge = ar_site_age();
$socials = ar_socials();
$timeline = ar_lines(ar_opt('aboutTimeline', ''));
$timelineStartYear = trim((string)ar_opt('footerStartYear', ''));
if ($timelineStartYear === '') { $timelineStartYear = $siteAge[0] !== '' ? substr($siteAge[0], 0, 4) : date('Y'); }
$guestbook = trim((string)ar_opt('guestbookUrl', ''));
$recent = array_slice(ar_post_pool(30), 0, 6);
$tagCount = 0;
try {
    $db = ar_db();
    $row = $db->fetchRow($db->select(array('COUNT(mid)' => 'num'))->from('table.metas')->where('type = ?', 'tag'));
    if ($row) $tagCount = (int)$row['num'];
} catch (Exception $e) {}
?>
<div class="card standalone-page about-page">
    <div class="about-head ar-reveal">
        <svg class="ico page-hero-deco"><use href="#i-user"></use></svg>
        <div class="about-profile">
            <div class="about-avatar">
                <?php if ($avatar !== ''): ?><img src="<?php echo ar_e($avatar); ?>" alt="<?php echo ar_e($authorName); ?>">
                <?php else: ?><span><?php echo ar_e(ar_first($authorName)); ?></span><?php endif; ?>
            </div>
            <div class="about-intro">
                <span class="page-eyebrow"><svg class="ico"><use href="#i-user"></use></svg> ABOUT · 关于</span>
                <h1 class="about-title"><?php echo ar_e($aboutTitle); ?></h1>
                <p class="about-meta"><span><?php echo ar_e($authorName); ?></span><i></i><span><?php _e('建于 '); ?><?php echo ar_e($siteAge[0]); ?></span><i></i><span><?php _e('运行 '); ?><?php echo $siteAge[1]; ?><?php _e(' 天'); ?></span></p>
            </div>
        </div>

        <?php if ($socials): ?>
        <div class="about-links">
            <?php foreach ($socials as $social): ?>
                <a href="<?php echo ar_e($social['url']); ?>" target="_blank" rel="noopener nofollow" title="<?php echo ar_e($social['name']); ?>"><svg class="ico"><use href="#<?php echo ar_e(ar_social_icon($social)); ?>"></use></svg><?php echo ar_e($social['name']); ?></a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <div class="about-stats">
            <div class="about-stat"><b><?php echo $counts[0]; ?></b><span><?php _e('篇文章'); ?></span></div>
            <div class="about-stat"><b><?php echo $counts[1]; ?></b><span><?php _e('条评论'); ?></span></div>
            <div class="about-stat"><b><?php echo $tagCount; ?></b><span><?php _e('个标签'); ?></span></div>
            <div class="about-stat"><b><?php echo isset($recent[0]['date']) ? ar_e(str_replace('-', '.', substr($recent[0]['date'], 2))) : date('y.m.d'); ?></b><span><?php _e('最近更新'); ?></span></div>
        </div>
    </div>

    <?php if ($this->content): ?>
        <section class="about-section ar-reveal">
            <div class="about-sec-head"><h2><?php _e('关于我'); ?></h2><span>ABOUT ME</span></div>
            <div class="about-story page-raw-content"><?php $this->content(); ?></div>
        </section>
    <?php endif; ?>

    <?php if ($timeline): ?>
    <section class="about-section ar-reveal">
        <div class="about-sec-head"><h2><?php _e('建站历程'); ?></h2><span><?php echo ar_e($timelineStartYear); ?> — <?php echo date('Y'); ?></span></div>
        <div class="about-story">
            <ul>
                <?php foreach ($timeline as $line): ?>
                    <?php $parts = ar_pipe($line, 3); ?>
                    <li><time><?php echo ar_e($parts[0]); ?></time><strong><?php echo ar_e($parts[1]); ?></strong><?php echo ar_e($parts[2]); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </section>
    <?php endif; ?>

    <?php if ($recent): ?>
    <section class="about-section ar-reveal">
        <div class="about-sec-head"><h2><?php _e('最近更新'); ?></h2><a class="about-more" href="<?php $this->options->siteUrl(); ?>"><?php _e('全部文章'); ?><svg class="ico"><use href="#i-arrow-right"></use></svg></a></div>
        <ul class="about-posts">
            <?php foreach ($recent as $post): ?>
                <li><a href="<?php echo ar_e($post['url']); ?>"><time><?php echo ar_e(isset($post['date']) ? substr($post['date'], 5) : ''); ?></time><span><?php echo ar_e($post['title']); ?></span><svg class="ico"><use href="#i-arrow-right"></use></svg></a></li>
            <?php endforeach; ?>
        </ul>
    </section>
    <?php endif; ?>

    <?php if ($guestbook !== ''): ?>
    <div class="page-cta ar-reveal">
        <span><?php _e('想聊点什么，或者只是路过打个招呼。'); ?></span>
        <a href="<?php echo ar_e($guestbook); ?>" target="_blank" rel="noopener"><?php _e('去留言'); ?><svg class="ico"><use href="#i-arrow-right"></use></svg></a>
    </div>
    <?php endif; ?>
</div>

<?php if ($this->allow('comment')): ?>
    <?php $this->need('comments.php'); ?>
<?php endif; ?>

<?php $this->need('footer.php'); ?>
