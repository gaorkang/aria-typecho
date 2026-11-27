<?php
/**
 * 评论 IP 归属地查询（Aria Typecho 主题）
 *
 * 只使用国内 IP 库：https://whois.pconline.com.cn/ipJson.jsp
 * 前端只请求本站 /usr/themes/<theme>/ipinfo.php?ip=xxx，不直连外部接口。
 * 入参 ip 必填；仅返回省份/城市，不回显完整 IP，保护访客隐私。
 * 缓存：按 IPv4 /24、IPv6 /48 聚合，12 小时；主题目录不可写时自动跳过。
 */
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

function ar_ipinfo_http($url, $timeout)
{
    $raw = '';
    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt_array($ch, array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => $timeout,
            CURLOPT_CONNECTTIMEOUT => min(3, $timeout),
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_USERAGENT      => 'Mozilla/5.0 (compatible; AriaTheme/1.0)',
        ));
        $raw = curl_exec($ch);
        curl_close($ch);
    } elseif (ini_get('allow_url_fopen')) {
        $ctx = stream_context_create(array('http' => array(
            'timeout' => $timeout,
            'header'  => "User-Agent: Mozilla/5.0 (compatible; AriaTheme/1.0)\r\n",
            'ignore_errors' => true
        )));
        $raw = @file_get_contents($url, false, $ctx);
    }
    return is_string($raw) ? $raw : '';
}

function ar_ipinfo_utf8($text)
{
    if ($text === '') return '';
    if (function_exists('mb_check_encoding') && mb_check_encoding($text, 'UTF-8')) return $text;
    if (function_exists('mb_convert_encoding')) return mb_convert_encoding($text, 'UTF-8', 'GBK,GB2312');
    if (function_exists('iconv')) {
        $converted = @iconv('GBK', 'UTF-8//IGNORE', $text);
        if ($converted !== false) return $converted;
    }
    return $text;
}

function ar_ipinfo_lookup($ip)
{
    if ($ip === '' || !filter_var($ip, FILTER_VALIDATE_IP)) {
        return array('ok' => false, 'text' => '');
    }
    $public = filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE);
    if (!$public) {
        return array('ok' => true, 'city' => '内网', 'province' => '', 'text' => '内网/本地');
    }

    // 同网段共用缓存，避免大量缓存文件
    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
        $parts = explode('.', $ip);
        $network = implode('.', array_slice($parts, 0, 3));
    } else {
        $parts = explode(':', $ip);
        $network = implode(':', array_slice($parts, 0, 4));
    }
    $cacheFile = dirname(__FILE__) . '/.ipinfo-' . md5($network) . '.json';
    if (is_file($cacheFile) && (time() - filemtime($cacheFile)) < 43200) {
        $cached = @json_decode((string)@file_get_contents($cacheFile), true);
        if (is_array($cached) && isset($cached['ok'])) return $cached;
    }

    $url = 'https://whois.pconline.com.cn/ipJson.jsp?json=true&ip=' . rawurlencode($ip);
    $raw = ar_ipinfo_utf8(ar_ipinfo_http($url, 4));
    $raw = trim($raw);
    if (preg_match('/(\{[\s\S]*\})/s', $raw, $m)) $raw = $m[1];
    $json = json_decode($raw, true);

    $province = '';
    $city = '';
    $failed = false;
    if (!is_array($json)) {
        $failed = true;
    } else {
        $err = isset($json['err']) ? (string)$json['err'] : '';
        $addr = isset($json['addr']) ? (string)$json['addr'] : '';
        if ($err === '') {
            $province = trim((string)(isset($json['pro']) ? $json['pro'] : ''));
            $city = trim((string)(isset($json['city']) ? $json['city'] : ''));
        } elseif ($err === 'noprovince') {
            // 海外 IP：从地址串中给出更友好的概括
            $countries = array('美国', '日本', '韩国', '新加坡', '英国', '德国', '法国', '加拿大', '澳大利亚', '俄罗斯', '印度', '越南', '泰国', '香港', '澳门', '台湾');
            foreach ($countries as $country) {
                if (strpos($addr, $country) !== false) { $province = $country; break; }
            }
            if ($province === '') $province = '海外';
        } else {
            $failed = true;
        }
    }

    if ($failed) {
        $text = '未知地区';
        $province = '';
        $city = '';
    } else {
        $province = preg_replace('/(省|市|自治区|特别行政区)$/u', '', $province);
        $city = preg_replace('/(市|地区|自治州|盟|特别行政区)$/u', '', $city);
        if ($city !== '' && $province !== '' && strpos($province, $city) !== false) {
            $text = $province; // 直辖市：北京 北京市 → 只显示北京
        } else {
            $text = trim($province . ' ' . $city);
        }
        if ($text === '') $text = '未知地区';
    }

    $result = array(
        'ok'       => true,
        'province' => $province,
        'city'     => $city,
        'text'     => $text
    );
    if (is_writable(dirname($cacheFile))) {
        @file_put_contents($cacheFile, json_encode($result, JSON_UNESCAPED_UNICODE), LOCK_EX);
    }
    return $result;
}

$ip = isset($_GET['ip']) ? trim((string)$_GET['ip']) : '';
echo json_encode(ar_ipinfo_lookup($ip), JSON_UNESCAPED_UNICODE);
