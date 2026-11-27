<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

/**
 * Aria for Typecho - 主题配置与轻量辅助函数
 */

function themeConfig($form)
{
    $logoText = new Typecho_Widget_Helper_Form_Element_Text('logoText', null, '', _t('站点 LOGO 文字'), _t('留空则使用站点标题'));
    $form->addInput($logoText);

    $avatar = new Typecho_Widget_Helper_Form_Element_Text('avatar', null, '', _t('作者头像地址'), _t('填写图片 URL，留空则使用内置的默认头像图形'));
    $form->addInput($avatar);

    $gravatarUrl = new Typecho_Widget_Helper_Form_Element_Text('gravatarUrl', null, 'https://cravatar.cn/avatar/', _t('评论头像 Gravatar 服务地址'), _t('留空或填 base URL（如 https://cravatar.cn/avatar/），根据评论邮箱生成头像；国内镜像默认 cravatar.cn'));
    $form->addInput($gravatarUrl);

    $authorName = new Typecho_Widget_Helper_Form_Element_Text('authorName', null, '', _t('作者名称'), _t('留空则使用站点标题'));
    $form->addInput($authorName);

    $badge = new Typecho_Widget_Helper_Form_Element_Text('badge', null, _t('博主'), _t('作者徽章文字'));
    $form->addInput($badge);

    $profileTags = new Typecho_Widget_Helper_Form_Element_Textarea('profileTags', null,
        "数码爱好者\n梦想成为旅行家\n咖啡重度依赖者",
        _t('作者标签'), _t('一行一个，显示在作者徽章下方；留空则不显示'));
    $form->addInput($profileTags);

    $bio = new Typecho_Widget_Helper_Form_Element_Textarea('bio', null, '', _t('作者简介'), _t('显示在左侧作者卡片中'));
    $form->addInput($bio);

    $notice = new Typecho_Widget_Helper_Form_Element_Text('notice', null, '', _t('右侧公告文字'), _t('右侧栏顶部滚动公告，留空则不显示'));
    $form->addInput($notice);
    $noticeUrl = new Typecho_Widget_Helper_Form_Element_Text('noticeUrl', null, '', _t('公告链接地址'));
    $form->addInput($noticeUrl);

    $socials = new Typecho_Widget_Helper_Form_Element_Textarea('socials', null,
        "B站|https://example.com/bilibili|bilibili\n邮箱|mailto:hello@example.com|email\nRSS|https://example.com/atom.xml|rss\n知乎|https://example.com/zhihu|zhihu",
        _t('社交链接'), _t('一行一个：名称|链接|图标标识；支持 bilibili/email/rss/zhihu/youtube/github/weibo，未识别时回退为链接图标'));
    $form->addInput($socials);

    $hotPosts = new Typecho_Widget_Helper_Form_Element_Textarea('hotPosts', null, '',
        _t('热门文章'), _t('一行一个：标题|链接；留空则自动取最近文章'));
    $form->addInput($hotPosts);

    $gallery = new Typecho_Widget_Helper_Form_Element_Textarea('gallery', null, '',
        _t('图集图片'), _t('一行一个：图片地址|跳转地址（可省略）；留空则自动取文章插图'));
    $form->addInput($gallery);

    $linksGroups = new Typecho_Widget_Helper_Form_Element_Textarea('linksGroups', null,
        "记录生活|示例博客|https://example.com/|https://example.com/favicon.ico|一个记录生活的地方\n竞争对手|友站名称|https://example.org/||网站简介",
        _t('友情链接'), _t('一行一个：分组|名称|链接|头像地址|简介。头像留空则取名称首字，兼容旧的四段格式「分组|名称|链接|图标文字」；在「友链」页面模板中显示'));
    $form->addInput($linksGroups);

    $aboutTitle = new Typecho_Widget_Helper_Form_Element_Text('aboutTitle', null, '',
        _t('关于页标语'), _t('「关于」页面头部的大标题，留空则使用站点标题'));
    $form->addInput($aboutTitle);

    $siteStartDate = new Typecho_Widget_Helper_Form_Element_Text('siteStartDate', null, '',
        _t('建站日期'), _t('格式 2021-08-26，用于关于页显示建站时间与运行天数，留空则取最早一篇文章的日期'));
    $form->addInput($siteStartDate);

    $aboutTimeline = new Typecho_Widget_Helper_Form_Element_Textarea('aboutTimeline', null,
        "2026.11|主题升级|从 Hexo 迁移到 Typecho，给博客加上友链、归档与关于页。\n2025.06|开始记录|搭建了 Hexo 博客，记录生活与热爱。\n2025.05|换用 Hexo|从 Halo 迁移到 Hexo，重新搭建博客。\n2022.04|开始建站|搭建了 Halo 博客，域名完成备案。",
        _t('建站历程'), _t('「关于」页时间轴，一行一个：日期|标题|描述'));
    $form->addInput($aboutTimeline);

    $guestbookUrl = new Typecho_Widget_Helper_Form_Element_Text('guestbookUrl', null, '',
        _t('留言板链接'), _t('关于页底部「去留言」按钮的链接，留空则不显示'));
    $form->addInput($guestbookUrl);

    $guestbookSub = new Typecho_Widget_Helper_Form_Element_Text('guestbookSub', null, '',
        _t('留言板副标题'), _t('「留言板」页面头部标题下的说明文字，留空则使用默认文案'));
    $form->addInput($guestbookSub);

    $adCode = new Typecho_Widget_Helper_Form_Element_Textarea('adCode', null, '',
        _t('广告位 HTML'), _t('填写图片 <img> 或任意 HTML 后立即显示；留空则自动隐藏广告位'));
    $form->addInput($adCode);

    $modules = new Typecho_Widget_Helper_Form_Element_Checkbox('modules',
        array(
            'Hot'      => _t('热门文章'),
            'Gallery'  => _t('图集'),
            'Comments' => _t('最新回复'),
            'Random'   => _t('随机文章'),
            'Tags'     => _t('标签云'),
            'Ad'       => _t('侧栏广告位')
        ),
        array('Hot', 'Gallery', 'Comments', 'Random', 'Tags', 'Ad'),
        _t('右侧栏模块')
    );
    $form->addInput($modules->multiMode());

    $features = new Typecho_Widget_Helper_Form_Element_Checkbox('features',
        array(
            'Views'     => _t('浏览数统计（自定义字段 views）'),
            'Toc'       => _t('文章目录'),
            'Lightbox'  => _t('图片灯箱'),
            'ReadMode'  => _t('阅读进度条')
        ),
        array('Views', 'Toc', 'Lightbox', 'ReadMode'),
        _t('功能开关')
    );
    $form->addInput($features->multiMode());

    $paywallText = new Typecho_Widget_Helper_Form_Element_Text('paywallText', null, '',
        _t('付费提示文字'), _t('填写后会在文章第一段后插入付费提示卡片，例如：本文为付费内容'));
    $form->addInput($paywallText);

    $paywallUrl = new Typecho_Widget_Helper_Form_Element_Text('paywallUrl', null, '',
        _t('付费按钮链接'), _t('点击付费提示按钮后跳转的地址'));
    $form->addInput($paywallUrl);

    /* ── 增强功能模块 ── */
    $stickyPostIds = new Typecho_Widget_Helper_Form_Element_Text('stickyPostIds', null, '',
        _t('首页置顶文章 CID'), _t('多个用英文逗号分隔，最多 6 篇，按填写顺序显示。留空则不显示置顶卡片'));
    $form->addInput($stickyPostIds);

    $weatherCity = new Typecho_Widget_Helper_Form_Element_Text('weatherCity', null, '',
        _t('天气城市（留空按访客 IP 自动定位）'), _t('首页 Hero 右上角单行天气长条。留空：根据访客 IP 自动识别所在城市（国内 IP 库 + 中国天气网）；也可填中文城市名（如 杭州）或 9 位城市代码固定显示；填 off 关闭。全部数据走本站服务端代理，来自国内接口，缓存 30 分钟'));
    $form->addInput($weatherCity);

    $enhanceFeatures = new Typecho_Widget_Helper_Form_Element_Checkbox('enhanceFeatures',
        array(
            'Sticky'    => _t('置顶卡片'),
            'Sort'      => _t('排序标签'),
            'Orbs'      => _t('动态光球背景'),
            'Fade'      => _t('卡片淡入动画'),
            'Shimmer'   => _t('图片加载扫光'),
            'Heart'     => _t('点赞心跳动画')
        ),
        array('Sticky', 'Sort', 'Orbs', 'Fade', 'Shimmer', 'Heart'),
        _t('增强功能')
    );
    $form->addInput($enhanceFeatures->multiMode());

    $icp = new Typecho_Widget_Helper_Form_Element_Text('icp', null, '', _t('ICP 备案号'), _t('填写后显示在页脚，如：京ICP备12345678号-1'));
    $form->addInput($icp);

    $policeIcp = new Typecho_Widget_Helper_Form_Element_Text('policeIcp', null, '', _t('公安备案号'), _t('填写后显示在页脚 ICP 之后，如：京公网安备11010102000001号'));
    $form->addInput($policeIcp);

    $policeIcpIcon = new Typecho_Widget_Helper_Form_Element_Text('policeIcpIcon', null, '', _t('公安备案图标地址'), _t('公安备案号前的小图标，留空则不显示'));
    $form->addInput($policeIcpIcon);

    $footerStartYear = new Typecho_Widget_Helper_Form_Element_Text('footerStartYear', null, '', _t('版权起始年份'), _t('留空则使用建站日期的年份，如：2022'));
    $form->addInput($footerStartYear);

    $footerText = new Typecho_Widget_Helper_Form_Element_Text('footerText', null, '',
        _t('页脚自定义文字'), _t('留空则显示 Hexo 同款版权行；填写后覆盖版权行'));
    $form->addInput($footerText);

    $ogImage = new Typecho_Widget_Helper_Form_Element_Text('ogImage', null, '',
        _t('社交分享默认图片'), _t('文章无特色图时使用的 og:image，留空则用作者头像'));
    $form->addInput($ogImage);

    $siteVerification = new Typecho_Widget_Helper_Form_Element_Textarea('siteVerification', null, '',
        _t('站长验证 meta'), _t('一行一个，原样输出到 head：如 <meta name="google-site-verification" content="xxx">'));
    $form->addInput($siteVerification);

    $seoBaiduPush = new Typecho_Widget_Helper_Form_Element_Text('seoBaiduPush', null, '',
        _t('百度自动推送脚本'), _t('填写百度自动推送 JS 的 URL（含 https://），留空则不加载'));
    $form->addInput($seoBaiduPush);
}

/**
 * 文章编辑页的自定义字段
 */
function themeFields($layout)
{
    $thumb = new Typecho_Widget_Helper_Form_Element_Text('thumb', null, null,
        _t('文章缩略图'), _t('填写图片地址，留空则自动取正文第一张图'));
    $layout->addItem($thumb);

    $likes = new Typecho_Widget_Helper_Form_Element_Text('likes', null, '0', _t('初始喜欢数'));
    $layout->addItem($likes);

    $stars = new Typecho_Widget_Helper_Form_Element_Text('stars', null, '0', _t('初始收藏数'));
    $layout->addItem($stars);
}

/**
 * 读取主题选项，带默认值
 */
function ar_opt($key, $default = '')
{
    $options = ar_widget(array('Widget\Options', 'Widget_Options'));
    $value = isset($options->$key) ? $options->$key : null;
    if ($value === null || $value === '') {
        return $default;
    }
    return $value;
}

/**
 * 跨版本获取组件：Typecho 1.3 使用命名空间类名，1.2 使用旧式类名
 */
function ar_widget($classes, $params = '')
{
    foreach ((array)$classes as $class) {
        if (class_exists($class)) {
            return Typecho_Widget::widget($class, $params);
        }
    }
    return null;
}

function ar_e($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

/**
 * 作者名：Typecho 1.3 的 $archive->author 是用户对象，1.2 是字符串
 */
function ar_author_name($archive)
{
    $author = isset($archive->author) ? $archive->author : '';
    if (is_object($author)) {
        if (isset($author->screenName)) return (string)$author->screenName;
        if (isset($author->name)) return (string)$author->name;
        return '';
    }
    return (string)$author;
}

function ar_first($value)
{
    $value = (string)$value;
    return function_exists('mb_substr') ? mb_substr($value, 0, 1, 'UTF-8') : substr($value, 0, 1);
}

/**
 * 摘要：Typecho 的 excerpt() 是直接输出的，这里用属性自行截取
 */
function ar_excerpt($value, $length = 26)
{
    $text = trim(strip_tags((string)$value));
    if (function_exists('mb_strlen')) {
        return mb_substr($text, 0, $length, 'UTF-8') . (mb_strlen($text, 'UTF-8') > $length ? '...' : '');
    }
    return substr($text, 0, $length) . (strlen($text) > $length ? '...' : '');
}

/**
 * 评论者设备：直接解析评论库里保存的 User-Agent，无任何外部请求
 * 只返回简短的系统标识（Win / Mac / Linux / iOS / Android …），浏览器信息放在 title 里。
 */
function ar_comment_device($ua)
{
    $ua = trim((string)$ua);
    if ($ua === '') return '未知设备';

    $isAppleMobile = stripos($ua, 'iPhone') !== false || stripos($ua, 'iPad') !== false || stripos($ua, 'iPod') !== false
        || (stripos($ua, 'Macintosh') !== false && stripos($ua, 'Mobile/') !== false);

    if (stripos($ua, 'Windows') !== false) return 'Win';
    if ($isAppleMobile) {
        return (stripos($ua, 'iPad') !== false || stripos($ua, 'Macintosh') !== false) ? 'iPad' : 'iOS';
    }
    if (stripos($ua, 'Android') !== false) return 'Android';
    if (stripos($ua, 'Mac OS X') !== false || stripos($ua, 'Macintosh') !== false) return 'Mac';
    if (stripos($ua, 'Linux') !== false || stripos($ua, 'X11') !== false) return 'Linux';

    // UA 有内容但识别不出系统时，尽量用客户端名兜底
    if (stripos($ua, 'MicroMessenger') !== false) return '微信';
    if (stripos($ua, 'QQ') !== false) return 'QQ';
    if (stripos($ua, 'DingTalk') !== false) return '钉钉';
    if (stripos($ua, 'Weibo') !== false) return '微博';
    return '其他设备';
}

function ar_has($feature)
{
    $features = ar_opt('features', array());
    if (is_string($features)) {
        $features = explode(',', $features);
    }
    return in_array($feature, (array)$features, true);
}

function ar_module($module)
{
    $modules = ar_opt('modules', array());
    if (is_string($modules)) {
        $modules = explode(',', $modules);
    }
    return in_array($module, (array)$modules, true);
}

/**
 * 增强功能开关，默认全部开启
 */
function ar_enhance($key)
{
    $features = ar_opt('enhanceFeatures', null);
    if ($features === null || $features === '') {
        return true;
    }
    if (is_string($features)) {
        $features = explode(',', $features);
    }
    return in_array($key, (array)$features, true);
}

/**
 * 跨版本获取数据库实例
 */
function ar_db()
{
    if (class_exists('Typecho\Db')) {
        return Typecho\Db::get();
    }
    return Typecho_Db::get();
}

/**
 * 置顶文章：读取后台配置的 CID，按填写顺序返回文章数据（含封面、浏览、评论）
 */
function ar_sticky_posts($limit = 6)
{
    if (!ar_enhance('Sticky')) {
        return array();
    }
    $idsText = trim(ar_opt('stickyPostIds', ''));
    if ($idsText === '') {
        return array();
    }
    $ids = array_filter(array_map('intval', explode(',', $idsText)), function ($v) { return $v > 0; });
    $ids = array_values(array_unique($ids));
    $ids = array_slice($ids, 0, max(1, min(6, $limit)));
    if (!$ids) {
        return array();
    }

    try {
        $db = ar_db();
        $rows = $db->fetchAll(
            $db->select('cid', 'title', 'slug', 'text', 'created', 'commentsNum')
                ->from('table.contents')
                ->where('type = ?', 'post')
                ->where('status = ?', 'publish')
                ->where('cid IN ?', $ids)
        );
    } catch (Exception $e) {
        return array();
    }

    // 批量取这些文章的第一个分类名
    $cats = ar_categories_for($ids);

    $orderMap = array_flip($ids);
    usort($rows, function ($a, $b) use ($orderMap) {
        $ka = isset($orderMap[$a['cid']]) ? $orderMap[$a['cid']] : 999;
        $kb = isset($orderMap[$b['cid']]) ? $orderMap[$b['cid']] : 999;
        return $ka - $kb;
    });

    $list = array();
    foreach ($rows as $row) {
        $text = isset($row['text']) ? $row['text'] : '';
        $images = ar_content_images($text, 1);
        $plain = ar_plain_word_count($text);
        $words = $plain;
        $list[] = array(
            'cid'      => (int)$row['cid'],
            'title'    => (string)$row['title'],
            'url'      => ar_post_url($row),
            'thumb'    => isset($images[0]) ? $images[0] : '',
            'date'     => date('Y-m-d', (int)$row['created']),
            'readtime' => max(1, (int)ceil($words / 400)),
            'category' => isset($cats[$row['cid']]) ? $cats[$row['cid']] : '',
            'views'    => ar_views_stored((int)$row['cid']),
            'comments' => (int)$row['commentsNum']
        );
    }
    return $list;
}

/**
 * 批量取文章的第一个分类名：cid => 分类名
 */
function ar_categories_for($cids)
{
    $result = array();
    if (!$cids) return $result;
    try {
        $db = ar_db();
        $rows = $db->fetchAll(
            $db->select('table.relationships.cid', 'table.metas.name')
                ->from('table.relationships')
                ->join('table.metas', 'table.relationships.mid = table.metas.mid')
                ->where('table.relationships.cid IN ?', $cids)
                ->where('table.metas.type = ?', 'category')
        );
        foreach ($rows as $r) {
            if (!isset($result[$r['cid']])) {
                $result[$r['cid']] = $r['name'];
            }
        }
    } catch (Exception $e) {}
    return $result;
}

/**
 * 用 Typecho 路由生成文章链接（兼容 1.2 / 1.3）
 */
function ar_post_url($row)
{
    $params = array('cid' => $row['cid']);
    if (!empty($row['slug'])) {
        // 与 Typecho 原生 Contents::getRouterParam 保持一致（urlencode 空格→+）
        $params['slug'] = urlencode($row['slug']);
    }
    // 日期式固定链接（/YYYY/MM/DD/slug/）需要补齐年月日，否则会留下 {year} 占位符
    if (isset($row['created'])) {
        $tz = (int)(\Helper::options()->timezone) - (int)(\Helper::options()->serverTimezone);
        $local = (int)$row['created'] + $tz;
        $params['year'] = date('Y', $local);
        $params['month'] = date('m', $local);
        $params['day'] = date('d', $local);
    }
    if (class_exists('Typecho\Router')) {
        return \Typecho\Router::url('post', $params, \Helper::options()->index);
    }
    if (class_exists('Typecho_Router')) {
        return Typecho_Router::url('post', $params, \Helper::options()->index);
    }
    return \Helper::options()->index . '/archives/' . $row['cid'] . '/';
}

/**
 * 只读取浏览量，不计数（置顶卡片展示用，避免重复计数）
 */
function ar_views_stored($cid)
{
    try {
        $db = ar_db();
        $row = $db->fetchRow($db->select('int_value', 'str_value')
            ->from('table.fields')->where('cid = ? AND name = ?', (int)$cid, 'views'));
        if ($row) {
            if (isset($row['int_value']) && $row['int_value']) return (int)$row['int_value'];
            if (isset($row['str_value']) && $row['str_value']) return (int)$row['str_value'];
        }
    } catch (Exception $e) {}
    return 0;
}

/**
 * 把多行文本拆成数组
 */
function ar_lines($text)
{
    $lines = array();
    foreach (explode("\n", (string)$text) as $line) {
        $line = trim($line);
        if ($line !== '') {
            $lines[] = $line;
        }
    }
    return $lines;
}

/**
 * 按 | 拆分一行并补齐长度
 */
function ar_pipe($line, $count)
{
    $parts = array_map('trim', explode('|', (string)$line));
    return array_pad($parts, $count, '');
}

/**
 * 从正文中取第一张图片
 */
function ar_first_image($content)
{
    $images = ar_content_images($content, 1);
    return isset($images[0]) ? $images[0] : '';
}

/**
 * 从正文（HTML 或 Markdown）中提取图片
 */
function ar_content_images($content, $limit = 3)
{
    $content = (string)$content;
    $images = array();

    if (preg_match_all('/<img[^>]*\ssrc=["\']([^"\']+)["\']/i', $content, $matches)) {
        $images = array_merge($images, $matches[1]);
    }
    if (preg_match_all('/!\[[^\]]*\]\(\s*<?([^)\s>]+)>?/i', $content, $matches)) {
        $images = array_merge($images, $matches[1]);
    }

    $images = array_values(array_unique(array_filter($images)));
    return array_slice($images, 0, $limit);
}

/**
 * 文章缩略图：优先自定义字段 thumb，否则取正文第一张图
 */
function ar_thumb($archive)
{
    $thumb = '';
    if (isset($archive->fields->thumb)) {
        $thumb = trim((string)$archive->fields->thumb);
    }
    if ($thumb === '' && isset($archive->content)) {
        $thumb = ar_first_image($archive->content);
    }
    return $thumb;
}

/**
 * 统计文章正文的真实字数：剔除 Markdown 图片/链接 URL、代码块与 HTML 标签
 */
function ar_plain_word_count($text)
{
    $text = (string)$text;
    // 去掉 Markdown 图片与链接的 URL 部分
    $text = preg_replace('/!?\[[^\]]*\]\(\s*<?[^)\s>]+>?[^)]*\)/u', '', $text);
    // 去掉裸 URL
    $text = preg_replace('/https?:\/\/\S+/u', '', $text);
    // 去掉围栏代码块
    $text = preg_replace('/```[\s\S]*?```/u', '', $text);
    // 去掉 HTML 标签与属性中的 URL
    $text = preg_replace('/<[^>]*>/', '', $text);
    // 去掉空白
    $text = preg_replace('/\s+/', '', $text);
    return function_exists('mb_strlen') ? mb_strlen($text, 'UTF-8') : strlen($text);
}

/**
 * 字数与预计阅读时间
 */
function ar_read_stats($archive)
{
    $text = isset($archive->content) ? (string)$archive->content : '';
    $words = ar_plain_word_count($text);
    return array($words, max(1, (int)ceil($words / 400)));
}

/**
 * 相对时间
 */
/**
 * 从 Typecho 1.2/1.3 各种日期形态里取出时间戳
 */
function ar_date_timestamp($date)
{
    if (is_object($date)) {
        foreach (array('timeStamp', 'timestamp') as $property) {
            if (isset($date->$property)) return (int)$date->$property;
        }
        if (method_exists($date, 'format')) return (int)$date->format('U');
        return 0;
    }
    if (is_numeric($date)) return (int)$date;
    return (int)strtotime((string)$date);
}

function ar_time_ago($date)
{
    $timestamp = 0;
    if (is_object($date)) {
        // Typecho 1.3 起 $this->date 是日期对象
        foreach (array('timeStamp', 'timestamp') as $property) {
            if (isset($date->$property)) {
                $timestamp = (int)$date->$property;
                break;
            }
        }
        if (!$timestamp && method_exists($date, 'format')) {
            $timestamp = (int)$date->format('U');
        }
    } elseif (is_numeric($date)) {
        $timestamp = (int)$date;
    } else {
        $timestamp = strtotime((string)$date);
    }
    if (!$timestamp) return '';
    $diff = time() - $timestamp;
    if ($diff < 60) return _t('刚刚');
    if ($diff < 3600) return floor($diff / 60) . _t(' 分钟前');
    if ($diff < 86400) return floor($diff / 3600) . _t(' 小时前');
    if ($diff < 2592000) return floor($diff / 86400) . _t(' 天前');
    if ($diff < 31536000) return floor($diff / 2592000) . _t(' 个月前');
    return floor($diff / 31536000) . _t(' 年前');
}

/**
 * 浏览量：使用 views 字段，同一访客 7 天内只记一次
 */
function ar_views($cid, $stored = 0)
{
    if (!ar_has('Views')) {
        return (int)$stored;
    }

    $cid = (int)$cid;
    $cookie = 'ar_viewed_' . $cid;
    $db = Typecho_Db::get();
    $row = $db->fetchRow($db->select('type', 'int_value', 'str_value')
        ->from('table.fields')->where('cid = ? AND name = ?', $cid, 'views'));

    $views = (int)$stored;
    if ($row) {
        $views = (int)(isset($row['int_value']) && $row['int_value'] ? $row['int_value'] : (isset($row['str_value']) ? $row['str_value'] : $stored));
    }

    if (!isset($_COOKIE[$cookie])) {
        $views++;
        $data = array('type' => 'int', 'int_value' => $views, 'str_value' => (string)$views);
        if ($row) {
            $db->query($db->update('table.fields')->rows($data)
                ->where('cid = ? AND name = ?', $cid, 'views'));
        } else {
            $data['cid'] = $cid;
            $data['name'] = 'views';
            $db->query($db->insert('table.fields')->rows($data));
        }
        @setcookie($cookie, '1', time() + 604800, '/');
    }

    return $views;
}

/**
 * 文章池：供随机文章、图集、相关文章复用，只查询一次
 */
function ar_post_pool($limit = 40)
{
    static $pool = null;
    if ($pool !== null) {
        return $pool;
    }

    $pool = array();
    $posts = ar_widget(array('Widget\Contents\Post\Recent', 'Widget_Contents_Post_Recent'), 'pageSize=' . intval($limit));
    while ($posts->next()) {
        $pool[] = array(
            'title' => (string)$posts->title,
            'url'   => (string)$posts->permalink,
            'thumb' => ar_first_image($posts->content),
            'cid'   => (int)$posts->cid,
            'date'  => date('Y-m-d', ar_date_timestamp($posts->date))
        );
    }
    return $pool;
}

function ar_hot_posts($limit = 8)
{
    $configured = ar_lines(ar_opt('hotPosts', ''));
    if ($configured) {
        $list = array();
        foreach ($configured as $line) {
            $parts = ar_pipe($line, 2);
            if ($parts[0] !== '') {
                $list[] = array('title' => $parts[0], 'url' => $parts[1]);
            }
        }
        return array_slice($list, 0, $limit);
    }
    return array_slice(ar_post_pool(40), 0, $limit);
}

function ar_gallery_items($limit = 9)
{
    $configured = ar_lines(ar_opt('gallery', ''));
    if ($configured) {
        $list = array();
        foreach ($configured as $line) {
            $parts = ar_pipe($line, 2);
            if ($parts[0] !== '') {
                $list[] = array('thumb' => $parts[0], 'url' => $parts[1]);
            }
        }
        return array_slice($list, 0, $limit);
    }

    $list = array();
    foreach (ar_post_pool(40) as $post) {
        if ($post['thumb'] !== '') {
            $list[] = array('thumb' => $post['thumb'], 'url' => $post['url']);
        }
        if (count($list) >= $limit) {
            break;
        }
    }
    return $list;
}

function ar_socials()
{
    $list = array();
    foreach (ar_lines(ar_opt('socials', '')) as $line) {
        $parts = ar_pipe($line, 3);
        if ($parts[0] !== '' && $parts[1] !== '') {
            $list[] = array('name' => $parts[0], 'url' => $parts[1], 'icon' => $parts[2]);
        }
    }
    return $list;
}

/**
 * 把社交链接映射到内联 SVG 图标，兼容旧的文字图标配置。
 */
function ar_social_icon($social)
{
    $map = array(
        'bilibili' => 'i-bilibili', 'b站' => 'i-bilibili', '哔哩哔哩' => 'i-bilibili',
        'email' => 'i-mail', 'mail' => 'i-mail', '邮箱' => 'i-mail', '邮件' => 'i-mail',
        'rss' => 'i-rss', '订阅' => 'i-rss',
        'zhihu' => 'i-zhihu', '知乎' => 'i-zhihu',
        'youtube' => 'i-youtube', '油管' => 'i-youtube',
        'github' => 'i-github', '微博' => 'i-weibo', 'weibo' => 'i-weibo'
    );
    $keys = array(strtolower(trim(isset($social['icon']) ? $social['icon'] : '')), strtolower(trim(isset($social['name']) ? $social['name'] : '')));
    foreach ($keys as $key) {
        if (isset($map[$key])) {
            return $map[$key];
        }
    }
    $url = strtolower(isset($social['url']) ? $social['url'] : '');
    if (strpos($url, 'bilibili.com') !== false) return 'i-bilibili';
    if (strpos($url, 'zhihu.com') !== false) return 'i-zhihu';
    if (strpos($url, 'youtube.com') !== false) return 'i-youtube';
    if (strpos($url, 'mailto:') === 0) return 'i-mail';
    if (strpos($url, 'atom.xml') !== false || strpos($url, '/feed') !== false) return 'i-rss';
    if (strpos($url, 'github.com') !== false) return 'i-github';
    if (strpos($url, 'weibo.com') !== false) return 'i-weibo';
    return 'i-link';
}

function ar_link_groups()
{
    $groups = array();
    foreach (ar_lines(ar_opt('linksGroups', '')) as $line) {
        $parts = ar_pipe($line, 5);
        if ($parts[1] === '') {
            continue;
        }
        if (!isset($groups[$parts[0]])) {
            $groups[$parts[0]] = array();
        }
        // 兼容旧格式：第四段是图标文字而不是 URL
        $avatar = trim($parts[3]);
        $icon = '';
        if ($avatar !== '' && !preg_match('#^(https?:)?//#i', $avatar)) {
            $icon = $avatar;
            $avatar = '';
        }
        $groups[$parts[0]][] = array(
            'name' => $parts[1], 'url' => $parts[2],
            'avatar' => $avatar, 'icon' => $icon, 'desc' => $parts[4]
        );
    }
    return $groups;
}

/**
 * 分类页：按分类聚合已发布文章（含浏览/评论数）
 * 返回 array(分类 meta => array('name','slug','count','posts' => array(...)))
 */
function ar_categories_tree($limit = 500)
{
    $tree = array();
    try {
        $db = ar_db();
        $metas = $db->fetchAll(
            $db->select('mid', 'name', 'slug', 'description', 'count')
                ->from('table.metas')
                ->where('type = ?', 'category')
                ->order('count', Typecho_Db::SORT_DESC)
        );
        $posts = $db->fetchAll(
            $db->select('cid', 'title', 'slug', 'created', 'commentsNum')
                ->from('table.contents')
                ->where('type = ? AND status = ?', 'post', 'publish')
                ->order('created', Typecho_Db::SORT_DESC)
                ->limit(intval($limit))
        );
        $rels = $db->fetchAll($db->select('cid', 'mid')->from('table.relationships'));
    } catch (Exception $e) {
        return $tree;
    }
    $byMeta = array();
    foreach ($rels as $rel) {
        $byMeta[(int)$rel['mid']][] = (int)$rel['cid'];
    }
    $postMap = array();
    foreach ($posts as $row) {
        $postMap[(int)$row['cid']] = $row;
    }
    foreach ($metas as $meta) {
        $mid = (int)$meta['mid'];
        $items = array();
        if (isset($byMeta[$mid])) {
            foreach ($byMeta[$mid] as $cid) {
                if (!isset($postMap[$cid])) continue;
                $row = $postMap[$cid];
                $ts = (int)$row['created'];
                $items[] = array(
                    'title'    => (string)$row['title'],
                    'url'      => ar_post_url($row),
                    'date'     => date('m-d', $ts),
                    'views'    => ar_views_stored($cid),
                    'comments' => (int)$row['commentsNum']
                );
            }
        }
        $tree[] = array(
            'name'        => (string)$meta['name'],
            'slug'        => (string)$meta['slug'],
            'description' => trim((string)$meta['description']),
            'count'       => count($items),
            'posts'       => $items
        );
    }
    return $tree;
}

/**
 * 归档页：按 年-月 聚合全部已发布文章（含浏览/评论数）
 * 返回 array(年 => array('count' => n, 'months' => array(月 => array('count' => n, 'posts' => array(...)))))
 */
function ar_archive_tree($limit = 500)
{
    $tree = array();
    try {
        $db = ar_db();
        $rows = $db->fetchAll(
            $db->select('cid', 'title', 'slug', 'created', 'commentsNum')
                ->from('table.contents')
                ->where('type = ? AND status = ?', 'post', 'publish')
                ->order('created', Typecho_Db::SORT_DESC)
                ->limit(intval($limit))
        );
    } catch (Exception $e) {
        return $tree;
    }
    foreach ($rows as $row) {
        $ts = (int)$row['created'];
        $year = date('Y', $ts);
        $month = date('n', $ts);
        if (!isset($tree[$year])) {
            $tree[$year] = array('count' => 0, 'months' => array());
        }
        if (!isset($tree[$year]['months'][$month])) {
            $tree[$year]['months'][$month] = array('count' => 0, 'posts' => array());
        }
        $tree[$year]['count']++;
        $tree[$year]['months'][$month]['count']++;
        $tree[$year]['months'][$month]['posts'][] = array(
            'title'    => (string)$row['title'],
            'url'      => ar_post_url($row),
            'day'      => date('d', $ts),
            'week'     => ar_week_cn($ts),
            'date'     => date('m-d', $ts),
            'views'    => ar_views_stored((int)$row['cid']),
            'comments' => (int)$row['commentsNum']
        );
    }
    return $tree;
}

function ar_week_cn($ts)
{
    $weeks = array('周日', '周一', '周二', '周三', '周四', '周五', '周六');
    return $weeks[(int)date('w', $ts)];
}

/**
 * 站点运行天数与开始日期
 */
function ar_site_age()
{
    $start = trim((string)ar_opt('siteStartDate', ''));
    if ($start !== '') {
        $ts = strtotime($start);
        if ($ts) {
            return array(date('Y.m.d', $ts), max(1, (int)floor((time() - $ts) / 86400)));
        }
    }
    try {
        $db = ar_db();
        $row = $db->fetchRow($db->select('created')->from('table.contents')
            ->where('type = ? AND status = ?', 'post', 'publish')
            ->order('created', Typecho_Db::SORT_ASC)->limit(1));
        if ($row) {
            $ts = (int)$row['created'];
            return array(date('Y.m.d', $ts), max(1, (int)floor((time() - $ts) / 86400)));
        }
    } catch (Exception $e) {}
    return array(date('Y.m.d'), 1);
}

function ar_counts()
{
    static $counts = null;
    if ($counts !== null) {
        return $counts;
    }
    $db = Typecho_Db::get();
    $posts = $db->fetchObject($db->select(array('COUNT(cid)' => 'num'))
        ->from('table.contents')->where('type = ? AND status = ?', 'post', 'publish'));
    $comments = $db->fetchObject($db->select(array('COUNT(coid)' => 'num'))
        ->from('table.comments')->where('status = ?', 'approved'));
    $counts = array((int)$posts->num, (int)$comments->num);
    return $counts;
}

/**
 * 留言板统计：本页留言数 / 留言者数 / 最近留言时间
 */
function ar_guestbook_stats($cid)
{
    $result = array(0, 0, '');
    $cid = (int)$cid;
    if ($cid <= 0) return $result;
    try {
        $db = ar_db();
        $row = $db->fetchObject($db->select(array('COUNT(coid)' => 'num'))
            ->from('table.comments')->where('cid = ? AND status = ?', $cid, 'approved'));
        if ($row) $result[0] = (int)$row->num;
        $row = $db->fetchObject($db->select(array('COUNT(DISTINCT author)' => 'num'))
            ->from('table.comments')->where('cid = ? AND status = ?', $cid, 'approved'));
        if ($row) $result[1] = (int)$row->num;
        $row = $db->fetchObject($db->select(array('MAX(created)' => 'last'))
            ->from('table.comments')->where('cid = ? AND status = ?', $cid, 'approved'));
        if ($row && $row->last) $result[2] = ar_time_ago($row->last);
    } catch (Exception $e) {}
    return $result;
}

/**
 * 输出轻量广告横幅
 */
function ar_ad($class = '')
{
    $code = trim((string)ar_opt('adCode', ''));
    if ($code === '') {
        return;
    }
    echo '<div class="ad-banner ' . ar_e($class) . '">';
    if ($code !== '') {
        echo $code;
    } else {
        echo '<span class="ad-label">AD SPACE</span><span class="ad-note">640 × 108</span>';
    }
    echo '</div>';
}

/**
 * 评论头像：按邮箱生成 Gravatar URL（默认走国内镜像 cravatar.cn）。
 */
function ar_gravatar_url($mail, $size = 36)
{
    $size = (int)$size > 0 ? (int)$size : 36;
    $base = trim((string)ar_opt('gravatarUrl', 'https://cravatar.cn/avatar/'));
    if ($base === '') {
        $base = 'https://cravatar.cn/avatar/';
    }
    if (substr($base, -1) !== '/') {
        $base .= '/';
    }
    $mail = strtolower(trim((string)$mail));
    if ($mail === '') {
        $mail = 'anonymous@example.com';
    }
    return $base . md5($mail) . '?s=' . $size . '&d=identicon&r=G';
}

/**
 * 自定义评论列表结构（Typecho 检测到该函数后会自动调用）
 */
if (!function_exists('threadedComments')) {
    function threadedComments($comments, $options)
    {
        $classes = 'comment-item';
        if ($comments->levels > 0) {
            $classes .= ' is-child';
        }
        if ($comments->authorId && $comments->authorId == $comments->ownerId) {
            $classes .= ' is-author';
        }
        ?>
        <li id="<?php $comments->theId(); ?>" class="<?php echo $classes; ?>">
            <div class="comment-head">
                <span class="comment-avatar"><img class="avatar" src="<?php echo ar_e(ar_gravatar_url(isset($comments->mail) ? $comments->mail : '', 36)); ?>" alt="<?php echo ar_e(isset($comments->author) ? (string)$comments->author : ''); ?>" width="36" height="36"></span>
                <div class="comment-meta">
                    <span class="comment-name"><?php $comments->author(); ?></span>
                    <a class="comment-time" href="<?php $comments->permalink(); ?>"><?php $comments->date('Y-m-d H:i'); ?></a>
                    <div class="comment-extra">
                        <?php
                        $commentIp = isset($comments->ip) ? trim((string)$comments->ip) : '';
                        if ($commentIp !== '' && filter_var($commentIp, FILTER_VALIDATE_IP)):
                        ?>
                        <span class="comment-from" data-ip="<?php echo ar_e($commentIp); ?>" title="<?php _e('IP 归属地由国内 IP 库解析，仅显示省份与城市'); ?>">
                            <svg class="ico"><use href="#i-location"></use></svg><span class="cf-text"><?php _e('地区获取中…'); ?></span>
                        </span>
                        <?php endif; ?>
                        <?php
                        $commentAgent = isset($comments->agent) ? (string)$comments->agent : '';
                        $commentDevice = ar_comment_device($commentAgent);
                        $commentDeviceTitle = $commentAgent !== '' ? $commentAgent : _t('未获取到设备信息');
                        ?>
                        <span class="comment-device" title="<?php echo ar_e($commentDeviceTitle); ?>">
                            <svg class="ico"><use href="#i-device"></use></svg><?php echo ar_e($commentDevice); ?>
                        </span>
                    </div>
                </div>
                <div class="comment-reply"><?php $comments->reply('<svg class="ico"><use href="#i-reply"></use></svg>'); ?></div>
            </div>
            <div class="comment-body-text"><?php $comments->content(); ?></div>
            <?php if ($comments->status == 'waiting'): ?>
                <div class="comment-waiting"><?php $options->commentStatus(); ?></div>
            <?php endif; ?>
            <?php if ($comments->children): ?>
                <ol class="comment-children"><?php $comments->threadedComments(); ?></ol>
            <?php endif; ?>
        </li>
        <?php
    }
}
