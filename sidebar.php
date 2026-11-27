<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<aside class="col side-col" aria-label="<?php _e('侧边栏'); ?>">
    <?php $notice = ar_opt('notice', ''); ?>
    <?php if ($notice !== ''): ?>
        <div class="notice-bar">
            <svg class="ico"><use href="#i-fire"></use></svg>
            <?php $noticeUrl = ar_opt('noticeUrl', ''); ?>
            <?php if ($noticeUrl !== ''): ?>
                <a href="<?php echo ar_e($noticeUrl); ?>" target="_blank" rel="noopener"><span><?php echo ar_e($notice); ?></span></a>
            <?php else: ?>
                <span><?php echo ar_e($notice); ?></span>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if (ar_module('Hot')): ?>
        <?php $hot = ar_hot_posts(8); ?>
        <?php if ($hot): ?>
            <section class="card side-card<?php echo ar_enhance('Fade') ? ' ar-reveal' : ''; ?>" style="--ar-reveal-dur:.5s">
                <h2 class="card-title"><span>🔥 <?php _e('文章聚合'); ?></span><a class="more" href="<?php $this->options->siteUrl(); ?>"><?php _e('更多'); ?></a></h2>
                <div class="hot-list">
                    <?php foreach ($hot as $item): ?>
                        <a class="hot-item" href="<?php echo ar_e($item['url']); ?>">
                            <span class="title"><?php echo ar_e($item['title']); ?></span>
                            <span class="hot-badge">HOT</span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>
    <?php endif; ?>

    <?php if (ar_module('Gallery')): ?>
        <?php $gallery = ar_gallery_items(9); ?>
        <?php if ($gallery): ?>
            <section class="card side-card<?php echo ar_enhance('Fade') ? ' ar-reveal' : ''; ?>" style="--ar-reveal-dur:.5s">
                <h2 class="card-title"><span>🖼️ <?php _e('图集'); ?></span></h2>
                <div class="gallery-grid">
                    <?php foreach ($gallery as $item): ?>
                        <a href="<?php echo ar_e($item['url'] !== '' ? $item['url'] : $item['thumb']); ?>" target="_blank" rel="noopener">
                            <img src="<?php echo ar_e($item['thumb']); ?>" alt="" loading="lazy">
                        </a>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>
    <?php endif; ?>

    <?php if (ar_module('Comments')): ?>
        <?php
        $recentComments = ar_widget(array('Widget\Comments\Recent', 'Widget_Comments_Recent'), 'pageSize=5');
        $commentRows = array();
        while ($recentComments->next()) {
            $commentRows[] = array(
                'author'  => (string)$recentComments->author,
                'url'     => (string)$recentComments->permalink,
                'excerpt' => ar_excerpt($recentComments->content, 26)
            );
        }
        ?>
        <?php if ($commentRows): ?>
            <section class="card side-card<?php echo ar_enhance('Fade') ? ' ar-reveal' : ''; ?>" style="--ar-reveal-dur:.5s">
                <h2 class="card-title"><span>💬 <?php _e('最新回复'); ?></span></h2>
                <ul class="mini-list">
                    <?php foreach ($commentRows as $row): ?>
                        <li>
                            <a href="<?php echo ar_e($row['url']); ?>"><?php echo ar_e($row['author']); ?></a>
                            <div class="mini-excerpt"><?php echo ar_e($row['excerpt']); ?></div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </section>
        <?php endif; ?>
    <?php endif; ?>

    <?php if (ar_module('Random')): ?>
        <?php
        $randomPool = ar_post_pool(40);
        shuffle($randomPool);
        $randomPosts = array_slice($randomPool, 0, 5);
        ?>
        <?php if ($randomPosts): ?>
            <section class="card side-card<?php echo ar_enhance('Fade') ? ' ar-reveal' : ''; ?>" style="--ar-reveal-dur:.5s">
                <h2 class="card-title"><span>🎲 <?php _e('随机文章'); ?></span><svg class="ico"><use href="#i-shuffle"></use></svg></h2>
                <ul class="mini-list">
                    <?php foreach ($randomPosts as $post): ?>
                        <li><a href="<?php echo ar_e($post['url']); ?>"><?php echo ar_e($post['title']); ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </section>
        <?php endif; ?>
    <?php endif; ?>

    <?php if (ar_module('Tags')): ?>
        <?php
        $tags = ar_widget(array('Widget\Metas\Tag\Cloud', 'Widget_Meta_Tag'), 'pageSize=24');
        $tagRows = array();
        while ($tags->next()) {
            $tagRows[] = array('name' => $tags->name, 'url' => $tags->permalink);
        }
        ?>
        <?php if ($tagRows): ?>
            <?php
            // 分成 3 行，每行标签数量尽量均匀；行内复制 3 份做无缝滚动
            $tagRowCount   = 3;
            $tagPerRow     = max(1, (int)ceil(count($tagRows) / $tagRowCount));
            $tagCloudRows  = array_chunk($tagRows, $tagPerRow);
            ?>
            <section class="card side-card<?php echo ar_enhance('Fade') ? ' ar-reveal' : ''; ?>" style="--ar-reveal-dur:.5s">
                <h2 class="card-title"><span>🏷️ <?php _e('标签云'); ?></span></h2>
                <div class="tag-cloud-marquee" aria-label="<?php _e('标签云'); ?>">
                    <?php foreach ($tagCloudRows as $rowIndex => $rowTags): ?>
                        <div class="tag-cloud-row<?php echo ($rowIndex % 2 === 1) ? ' reverse' : ''; ?>">
                            <div class="tag-cloud-track">
                                <?php for ($copy = 0; $copy < 3; $copy++): ?>
                                    <div class="tag-cloud-set"<?php if ($copy > 0) echo ' aria-hidden="true"'; ?>>
                                        <?php foreach ($rowTags as $tag): ?>
                                            <a class="tag" href="<?php echo ar_e($tag['url']); ?>"<?php if ($copy > 0) echo ' tabindex="-1"'; ?>><?php echo ar_e($tag['name']); ?></a>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endfor; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>
    <?php endif; ?>
</aside>
