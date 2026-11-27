<?php
/**
 * 天气数据代理（Aria Typecho 主题）
 *
 * 全部使用国内接口，备案站可用、前端零跨域：
 *   1. 访客 IP 归属地：https://whois.pconline.com.cn/ipJson.jsp（国内 IP 库，GBK）
 *   2. 城市名 → 中国天气网代码：https://toy1.weather.com.cn/search（JSONP）
 *   3. 实况天气：https://d1.weather.com.cn/sk_2d/{code}.html（中国天气网）
 *
 * 入参：
 *   ?city=杭州 / ?city=101210101  固定城市（可选）
 *   无 city 参数时按访客 IP 自动定位；后台 weatherCity 留空即为此模式。
 * 缓存：天气 30 分钟、城市代码 7 天、IP 归属地 12 小时，主题目录不可写时自动跳过。
 * 失败一律返回 ok=false，前端优雅降级，绝不拖累页面。
 */
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

$result = array('ok' => false);

/* ── 通用工具 ───────────────────────────────────────────── */
function ar_weather_http($url, $referer, $timeout)
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
        if ($referer !== '') curl_setopt($ch, CURLOPT_REFERER, $referer);
        $raw = curl_exec($ch);
        curl_close($ch);
    } elseif (ini_get('allow_url_fopen')) {
        $header = "User-Agent: Mozilla/5.0 (compatible; AriaTheme/1.0)\r\n";
        if ($referer !== '') $header .= 'Referer: ' . $referer . "\r\n";
        $ctx = stream_context_create(array('http' => array(
            'timeout' => $timeout, 'header' => $header, 'ignore_errors' => true
        )));
        $raw = @file_get_contents($url, false, $ctx);
    }
    return is_string($raw) ? $raw : '';
}

function ar_weather_utf8($text)
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

function ar_weather_json($raw)
{
    // 容忍 JSONP 包裹、前后空白与 BOM
    $raw = trim($raw);
    $raw = preg_replace('/^\xEF\xBB\xBF/', '', $raw);
    if (preg_match('/^\s*[A-Za-z_$][\w$]*\s*\((.*)\)\s*;?\s*$/s', $raw, $m)) {
        $raw = $m[1];
    } elseif (preg_match('/(\{[\s\S]*\}|\[[\s\S]*\])/s', $raw, $m)) {
        $raw = $m[1];
    }
    return json_decode($raw, true);
}

function ar_weather_cache_read($file, $ttl)
{
    if (!is_file($file) || (time() - filemtime($file)) >= $ttl) return null;
    $data = @json_decode((string)@file_get_contents($file), true);
    return is_array($data) ? $data : null;
}

function ar_weather_cache_write($file, $data)
{
    if (!is_writable(dirname($file))) return;
    @file_put_contents($file, json_encode($data, JSON_UNESCAPED_UNICODE), LOCK_EX);
}

/* ── 访客 IP（只采信公网 IP，兼容 CDN / 反代） ───────────── */
function ar_visitor_ip()
{
    $keys = array('HTTP_X_FORWARDED_FOR', 'HTTP_CF_CONNECTING_IP', 'HTTP_X_REAL_IP', 'HTTP_CLIENT_IP', 'REMOTE_ADDR');
    $private = array();
    foreach ($keys as $key) {
        if (empty($_SERVER[$key])) continue;
        foreach (explode(',', (string)$_SERVER[$key]) as $candidate) {
            $ip = trim($candidate);
            if ($ip === '' || !filter_var($ip, FILTER_VALIDATE_IP)) continue;
            if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                return $ip;
            }
            if (!in_array($ip, $private, true)) $private[] = $ip;
        }
    }
    // 没有公网 IP 时不传 ip 参数，让 IP 库使用服务器出口 IP（本地/内网预览也有结果）
    return '';
}

/* ── IP → 城市（太平洋电脑网国内 IP 库） ─────────────────── */
function ar_ip_city($ip)
{
    $network = $ip;
    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
        $parts = explode('.', $ip);
        $network = implode('.', array_slice($parts, 0, 3)); // 同网段共用缓存，减少文件数
    } elseif (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
        $parts = explode(':', $ip);
        $network = implode(':', array_slice($parts, 0, 4));
    }
    $cacheFile = dirname(__FILE__) . '/.weather-geo-' . md5($network) . '.json';
    $cached = ar_weather_cache_read($cacheFile, 43200);
    if (is_array($cached) && isset($cached['city'])) return $cached;

    $url = 'https://whois.pconline.com.cn/ipJson.jsp?json=true' . ($ip !== '' ? '&ip=' . rawurlencode($ip) : '');
    $json = ar_weather_json(ar_weather_utf8(ar_weather_http($url, '', 4)));
    $city = '';
    $province = '';
    if (is_array($json)) {
        $city = trim((string)(isset($json['city']) ? $json['city'] : ''));
        $province = trim((string)(isset($json['pro']) ? $json['pro'] : ''));
        if (isset($json['err']) && $json['err'] !== '') { $city = ''; $province = ''; }
    }
    $city = preg_replace('/(市|地区|自治州|盟|特别行政区)$/u', '', $city);
    $province = preg_replace('/(省|市|自治区|特别行政区)$/u', '', $province);
    if ($city === '' && $province !== '') $city = $province;

    $geo = array('city' => $city, 'province' => $province);
    if ($city !== '') ar_weather_cache_write($cacheFile, $geo);
    return $geo;
}

/* ── 常用城市代码表（无网络时兜底，同时避免频繁查询） ─────── */
$arCityMap = array(
    '北京' => '101010100', '上海' => '101020100', '广州' => '101280101', '深圳' => '101280601',
    '杭州' => '101210101', '南京' => '101190101', '成都' => '101270101', '重庆' => '101040100',
    '武汉' => '101200101', '西安' => '101110101', '苏州' => '101190401', '天津' => '101030100',
    '长沙' => '101250101', '郑州' => '101180101', '青岛' => '101120201', '济南' => '101120101',
    '厦门' => '101230201', '福州' => '101230101', '合肥' => '101220101', '昆明' => '101290101',
    '香港' => '101320100', '澳门' => '101330100', '台北' => '101340100', '珠海' => '101280701',
    '东莞' => '101281601', '佛山' => '101280800', '宁波' => '101210401', '无锡' => '101190201',
    '温州' => '101210701', '大连' => '101070201', '沈阳' => '101070101', '哈尔滨' => '101050101',
    '长春' => '101060101', '石家庄' => '101090101', '太原' => '101100101', '南昌' => '101240101',
    '贵阳' => '101260101', '南宁' => '101290201', '海口' => '101310101', '兰州' => '101160101',
    '银川' => '101170101', '西宁' => '101150101', '呼和浩特' => '101080101', '乌鲁木齐' => '101130101',
    '拉萨' => '101140101', '三亚' => '101310201', '桂林' => '101270401', '丽江' => '101291401',
    '新加坡' => '104010100', '东京' => '103010100'
);

function ar_city_code($name, $whiteList)
{
    global $arCityMap;
    $name = trim((string)$name);
    $name = preg_replace('/(市|地区|自治州|盟|特别行政区)$/u', '', $name);
    if ($name === '') return '';
    if (isset($arCityMap[$name])) return $arCityMap[$name];
    if (isset($whiteList[$name])) return $whiteList[$name];

    $cacheFile = dirname(__FILE__) . '/.weather-city-' . md5($name) . '.json';
    $cached = ar_weather_cache_read($cacheFile, 604800);
    if (is_array($cached) && isset($cached['code']) && preg_match('/^\d{9}$/', $cached['code'])) {
        return $cached['code'];
    }

    // 中国天气网城市搜索（HTTPS + JSONP，全部国内节点）
    $url = 'https://toy1.weather.com.cn/search?cityname=' . rawurlencode($name) . '&callback=arCb';
    $list = ar_weather_json(ar_weather_http($url, 'https://www.weather.com.cn/', 4));
    if (!is_array($list)) return '';

    $firstCode = '';
    foreach ($list as $item) {
        if (!is_array($item) || empty($item['ref'])) continue;
        $parts = explode('~', (string)$item['ref']);
        $code = isset($parts[0]) ? trim($parts[0]) : '';
        if (!preg_match('/^\d{9}$/', $code)) continue;
        if ($firstCode === '') $firstCode = $code;
        $zh = isset($parts[2]) ? trim($parts[2]) : '';
        $en = isset($parts[3]) ? trim($parts[3]) : '';
        if ($zh === $name || strcasecmp($en, $name) === 0) {
            ar_weather_cache_write($cacheFile, array('code' => $code));
            return $code;
        }
    }
    if ($firstCode !== '') ar_weather_cache_write($cacheFile, array('code' => $firstCode));
    return $firstCode;
}

/* ── 入参：固定城市 or 按 IP 自动定位 ───────────────────── */
$cityInput = isset($_GET['city']) ? trim((string)$_GET['city']) : '';
$isAuto = ($cityInput === '');
$displayCity = '';
$cityCode = '';

if (!$isAuto) {
    if (preg_match('/^\d{9}$/', $cityInput)) {
        $cityCode = $cityInput;
    } elseif (preg_match('/^[\x{4e00}-\x{9fa5}]{2,12}$/u', $cityInput)) {
        $displayCity = preg_replace('/(市|地区|自治州|盟)$/u', '', $cityInput);
        $cityCode = ar_city_code($cityInput, $arCityMap);
    }
} else {
    $ip = ar_visitor_ip();
    $geo = ar_ip_city($ip);
    $displayCity = isset($geo['city']) ? $geo['city'] : '';
    $cityCode = ar_city_code($displayCity, $arCityMap);
}

if ($cityCode === '') {
    $cityCode = '101010100'; // 降级北京
    if ($displayCity === '') $displayCity = '北京';
}

/* ── 实况天气缓存（30 分钟） ─────────────────────────────── */
$cacheFile = dirname(__FILE__) . '/.weather-' . $cityCode . '.json';
$cached = ar_weather_cache_read($cacheFile, 1800);
if (is_array($cached) && !empty($cached['ok'])) {
    if ($isAuto && $displayCity !== '') $cached['city'] = $displayCity; // IP 城市优先展示
    echo json_encode($cached, JSON_UNESCAPED_UNICODE);
    exit;
}

/* ── 上游请求：中国天气网官方实况 ────────────────────────── */
$url = 'https://d1.weather.com.cn/sk_2d/' . $cityCode . '.html';
$raw = ar_weather_http($url, 'https://www.weather.com.cn/', 6);
if ($raw === '') {
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
    exit;
}
$raw = ar_weather_utf8($raw);

$json = '';
if (preg_match('/var\s+dataSK\s*=\s*(\{.*\})\s*;?\s*$/s', trim($raw), $m)) {
    $json = $m[1];
} elseif (preg_match('/(\{.*\})/s', $raw, $m)) {
    $json = $m[1];
}
$data = json_decode($json, true);
if (!is_array($data)) {
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
    exit;
}

$temp = isset($data['temp']) ? trim((string)$data['temp']) : '';
if ($temp === '') {
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
    exit;
}
$tempNum = round((float)$temp);
$cond = isset($data['weather']) ? trim((string)$data['weather']) : '';
$humidity = isset($data['SD']) ? preg_replace('/[^\d]/', '', (string)$data['SD']) : '';
$wind = trim((isset($data['WD']) ? (string)$data['WD'] : '') . (isset($data['WS']) ? (string)$data['WS'] : ''));

/* 体感温度：高温用热指数、低温用风寒指数，无上游字段时本地估算 */
function ar_feels_like($temp, $humidity, $wind)
{
    $t = (float)$temp;
    $rh = min(100, max(0, (float)$humidity));
    $level = 0;
    if (preg_match('/([\d.]+)/', $wind, $m)) $level = (float)$m[1];
    $speed = min(12, $level * 1.5); // 风级 → m/s 近似值
    $feels = $t;
    if ($t >= 27 && $rh > 0) {
        $feels = -8.784695 + 1.61139411 * $t + 2.338549 * $rh - 0.14611605 * $t * $rh
            - 0.012308094 * $t * $t - 0.016424828 * $rh * $rh
            + 0.002211732 * $t * $t * $rh + 0.00072546 * $t * $rh * $rh
            - 0.000003582 * $t * $t * $rh * $rh;
    } elseif ($t <= 10 && $speed >= 1.5) {
        $v = $speed * 3.6;
        $feels = 13.12 + 0.6215 * $t - 11.37 * pow($v, 0.16) + 0.3965 * $t * pow($v, 0.16);
    }
    return (string)round(max($t - 8, min($t + 8, $feels)));
}

/* 天气现象 → 图标标识 */
$iconMap = array(
    '雷阵雨' => 'storm', '雷' => 'storm',
    '雨夹雪' => 'snow', '小雪' => 'snow', '中雪' => 'snow', '大雪' => 'snow', '雪' => 'snow',
    '暴雨' => 'rain', '大雨' => 'rain', '中雨' => 'rain', '小雨' => 'rain', '阵雨' => 'rain', '雨' => 'rain',
    '雾' => 'fog', '霾' => 'fog', '薄雾' => 'fog',
    '多云' => 'cloud-sun', '阴' => 'cloud', '晴' => 'sun'
);
$icon = 'cloud';
foreach ($iconMap as $key => $val) {
    if (strpos($cond, $key) !== false) { $icon = $val; break; }
}

$result = array(
    'ok'        => true,
    'city'      => $displayCity !== '' ? $displayCity : (isset($data['cityname']) ? trim((string)$data['cityname']) : ''),
    'cityCode'  => $cityCode,
    'temp'      => (string)$tempNum,
    'cond'      => $cond,
    'icon'      => $icon,
    'wind'      => $wind,
    'humidity'  => $humidity !== '' ? $humidity . '%' : '',
    'feels'     => ar_feels_like($temp, $humidity, $wind),
    'aqi'       => isset($data['aqi']) ? trim((string)$data['aqi']) : '',
    'time'      => isset($data['time']) ? trim((string)$data['time']) : '',
    'byIp'      => $isAuto ? 1 : 0
);

ar_weather_cache_write($cacheFile, $result);
echo json_encode($result, JSON_UNESCAPED_UNICODE);
