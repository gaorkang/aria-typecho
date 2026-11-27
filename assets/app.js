(function () {
    'use strict';

    var $ = function (selector, root) { return (root || document).querySelector(selector); };
    var $$ = function (selector, root) { return Array.prototype.slice.call((root || document).querySelectorAll(selector)); };

    function ready(fn) {
        if (document.readyState !== 'loading') fn();
        else document.addEventListener('DOMContentLoaded', fn);
    }

    ready(function () {
        /* 页脚动态上线时长（仿 Hexo：网站已上线 N 天 HH:MM:SS） */
        var siteAge = $('#arSiteAge');
        if (siteAge && siteAge.dataset.start) {
            var startRaw = String(siteAge.dataset.start).trim();
            var m = startRaw.match(/^(\d{4})[.\-/](\d{1,2})[.\-/](\d{1,2})/);
            var startAt = m
                ? new Date(+m[1], +m[2] - 1, +m[3], 0, 0, 0)
                : new Date(startRaw.replace(' ', 'T'));
            if (!isNaN(startAt.getTime())) {
                var tickAge = function () {
                    var diff = Date.now() - startAt.getTime();
                    if (diff < 0) return;
                    var days = Math.floor(diff / 86400000);
                    var pad = function (n) { return String(n).padStart(2, '0'); };
                    var hours = pad(Math.floor((diff % 86400000) / 3600000));
                    var minutes = pad(Math.floor((diff % 3600000) / 60000));
                    var seconds = pad(Math.floor((diff % 60000) / 1000));
                    siteAge.textContent = '网站已上线 ' + days + ' 天 ' + hours + ':' + minutes + ':' + seconds;
                };
                tickAge();
                setInterval(tickAge, 1000);
            }
        }

        /* 深色模式 */
        var html = document.documentElement;
        var themeToggle = $('#themeToggle');
        var themeIcon = $('#themeIcon');

        function syncThemeIcon() {
            if (!themeIcon) return;
            themeIcon.setAttribute('href', html.getAttribute('data-theme') === 'dark' ? '#i-sun' : '#i-moon');
        }
        syncThemeIcon();

        if (themeToggle) {
            themeToggle.addEventListener('click', function () {
                var next = html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
                html.setAttribute('data-theme', next);
                try { localStorage.setItem('ar-theme', next); } catch (e) {}
                syncThemeIcon();
            });
        }

        /* 顶部导航态与滚动组件 */
        var topbar = $('#topbar');
        var progress = $('#readProgress');
        var backTop = $('#backTop');
        var ticking = false;

        var ringBar = $('#scrollRingBar');
        var scrollPct = $('#scrollPct');
        var RING = 106.81;

        function onScroll() {
            var y = window.pageYOffset || document.documentElement.scrollTop;
            if (topbar) topbar.classList.toggle('is-stuck', y > 12);
            if (backTop) backTop.classList.toggle('show', y > 400);
            if (progress) {
                var height = document.documentElement.scrollHeight - window.innerHeight;
                var pct = height > 0 ? Math.min(100, y / height * 100) : 0;
                progress.style.width = pct + '%';
                if (ringBar) ringBar.style.strokeDashoffset = String(RING - (RING * pct / 100));
                if (backTop) backTop.classList.toggle('scrolled', pct >= 99);
                if (scrollPct) scrollPct.textContent = pct >= 99 ? '↑' : Math.round(pct) + '%';
            }
            highlightToc();
            ticking = false;
        }

        window.addEventListener('scroll', function () {
            if (!ticking) {
                window.requestAnimationFrame(onScroll);
                ticking = true;
            }
        }, { passive: true });
        onScroll();

        if (backTop) {
            backTop.addEventListener('click', function () {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        }

        /* 搜索弹层 */
        var searchModal = $('#searchModal');
        var searchInput = $('#searchInput');
        var searchOpen = $('#searchOpen');

        function closeSearch() {
            if (searchModal) searchModal.classList.remove('open');
        }
        if (searchOpen && searchModal) {
            searchOpen.addEventListener('click', function () {
                searchModal.classList.add('open');
                setTimeout(function () { if (searchInput) searchInput.focus(); }, 30);
            });
            searchModal.addEventListener('click', function (e) {
                if (e.target === searchModal) closeSearch();
            });
        }

        /* 移动端抽屉 */
        var drawer = $('#drawer');
        var drawerMask = $('#drawerMask');

        function closeDrawer() {
            if (drawer) drawer.classList.remove('open');
            if (drawerMask) drawerMask.classList.remove('open');
        }
        var menuOpen = $('#menuOpen');
        if (menuOpen && drawer) {
            menuOpen.addEventListener('click', function () {
                drawer.classList.add('open');
                if (drawerMask) drawerMask.classList.add('open');
            });
        }
        var drawerClose = $('#drawerClose');
        if (drawerClose) drawerClose.addEventListener('click', closeDrawer);
        if (drawerMask) drawerMask.addEventListener('click', closeDrawer);

        /* 底部导航：分类列表展开 / 收起 */
        var dockSheet = $('#dockSheet');
        var dockMask = $('#dockMask');
        var dockCatsBtn = $('#dockCatsBtn');
        function closeDockSheet() {
            if (dockSheet) dockSheet.classList.remove('open');
            if (dockMask) dockMask.classList.remove('open');
            if (dockCatsBtn) dockCatsBtn.setAttribute('aria-expanded', 'false');
        }
        if (dockCatsBtn && dockSheet) {
            dockCatsBtn.addEventListener('click', function () {
                var willOpen = !dockSheet.classList.contains('open');
                dockSheet.classList.toggle('open', willOpen);
                if (dockMask) dockMask.classList.toggle('open', willOpen);
                dockCatsBtn.setAttribute('aria-expanded', String(willOpen));
            });
        }
        var dockSheetClose = $('#dockSheetClose');
        if (dockSheetClose) dockSheetClose.addEventListener('click', closeDockSheet);
        if (dockMask) dockMask.addEventListener('click', closeDockSheet);
        if (dockSheet) {
            dockSheet.addEventListener('click', function (e) {
                if (e.target.closest('a')) closeDockSheet();
            });
        }

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeSearch();
                closeDrawer();
                closeDockSheet();
                closeLightbox();
                var tocPanel = $('#tocPanel');
                if (tocPanel) tocPanel.classList.remove('open');
            }
        });

        /* 随机文章 */
        var randomPost = $('#randomPost');
        if (randomPost) {
            randomPost.addEventListener('click', function () {
                var pool = [];
                var poolEl = $('#arPostPool');
                if (poolEl) {
                    try { pool = JSON.parse(poolEl.textContent || '[]'); } catch (e) { pool = []; }
                }
                if (!pool.length) return;
                var target = pool[Math.floor(Math.random() * pool.length)];
                if (target && target.url) window.location.href = target.url;
            });
        }

        /* Toast 轻提示 */
        var toastEl = $('#toast');
        var toastTimer = null;
        function showToast(message) {
            if (!toastEl) return;
            toastEl.textContent = message;
            toastEl.classList.add('show');
            clearTimeout(toastTimer);
            toastTimer = setTimeout(function () { toastEl.classList.remove('show'); }, 1800);
        }

        /* 点赞 / 收藏：本地记录，不给服务端增加负担 */
        function bindVote(selector, type, activeClass) {
            $$(selector).forEach(function (button) {
                var cid = button.getAttribute('data-cid');
                var base = parseInt(button.getAttribute('data-base') || '0', 10);
                var key = 'ar-' + type + '-' + cid;
                var active = false;
                try { active = localStorage.getItem(key) === '1'; } catch (e) {}
                var num = $('span', button);

                function paint() {
                    button.classList.toggle(activeClass, active);
                    if (num) num.textContent = String(base + (active ? 1 : 0));
                }
                paint();

                button.addEventListener('click', function () {
                    active = !active;
                    try { localStorage.setItem(key, active ? '1' : '0'); } catch (e) {}
                    paint();
                    if (type === 'like' && active) {
                        var float = document.createElement('span');
                        float.className = 'heart-float';
                        float.innerHTML = '<svg class="ico"><use href="#i-heart"></use></svg>';
                        button.appendChild(float);
                        setTimeout(function () { if (float.parentNode) float.parentNode.removeChild(float); }, 850);
                        showToast('❤ ' + '谢谢喜欢');
                    } else if (type === 'star') {
                        showToast(active ? '★ ' + '已加入收藏' : '☆ ' + '已取消收藏');
                    }
                });
            });
        }
        bindVote('.like-action', 'like', 'is-active');
        bindVote('.star-action', 'star', 'is-starred');

        /* 文章目录：桌面渲染到左侧栏，移动端仍用浮动面板 */
        var tocPanel = $('#tocPanel');
        var tocFab = $('#tocFab');
        var tocToggle = $('#tocToggle');
        var tocList = $('#postTocList');
        var tocCurrent = $('#postTocCurrent');
        var tocLinks = [];
        var headings = $$('.post-content h2, .post-content h3');

        if (headings.length) {
            headings.forEach(function (heading, index) {
                var id = 'ar-toc-' + (index + 1);
                heading.id = id;
                var text = heading.textContent.trim();
                var links = [];
                if (tocList) {
                    var sideLink = document.createElement('a');
                    sideLink.href = '#' + id;
                    sideLink.className = 'level-' + heading.tagName.toLowerCase();
                    sideLink.textContent = text;
                    tocList.appendChild(sideLink);
                    links.push(sideLink);
                }
                if (tocPanel) {
                    var floatLink = document.createElement('a');
                    floatLink.href = '#' + id;
                    floatLink.className = 'level-' + heading.tagName.toLowerCase();
                    floatLink.textContent = text;
                    tocPanel.appendChild(floatLink);
                    links.push(floatLink);
                }
                tocLinks.push({ links: links, heading: heading, text: text });
            });
            if (tocFab) tocFab.classList.add('show');
        } else if (tocList) {
            var empty = document.createElement('div');
            empty.className = 'post-toc-empty';
            empty.textContent = '暂无目录';
            tocList.appendChild(empty);
        }

        function highlightToc() {
            if (!tocLinks || !tocLinks.length) return;
            var activeIndex = 0;
            tocLinks.forEach(function (item, index) {
                if (item.heading.getBoundingClientRect().top < 160) activeIndex = index;
            });
            tocLinks.forEach(function (item, index) {
                var active = index === activeIndex;
                item.links.forEach(function (link) {
                    link.classList.toggle('is-active', active);
                    if (link.parentNode === tocPanel) link.style.color = '';
                });
            });
            if (tocCurrent) tocCurrent.textContent = tocLinks[activeIndex].text;
        }
        highlightToc();

        if (tocToggle && tocPanel) {
            tocToggle.addEventListener('click', function () {
                tocPanel.classList.toggle('open');
            });
            tocPanel.addEventListener('click', function (e) {
                if (e.target.tagName === 'A') tocPanel.classList.remove('open');
            });
        }

        /* 图片灯箱 */
        var lightbox = $('#lightbox');
        var lightboxImg = $('#lightboxImg');
        var lightboxClose = $('#lightboxClose');

        function closeLightbox() {
            if (lightbox) lightbox.classList.remove('open');
        }
        if (lightbox) {
            lightbox.addEventListener('click', function (e) {
                if (e.target === lightbox) closeLightbox();
            });
        }
        if (lightboxClose) lightboxClose.addEventListener('click', closeLightbox);

        document.addEventListener('click', function (e) {
            var img = e.target.closest('.post-content img, .post-images img, .gallery-grid img');
            if (!img || !lightbox || !lightboxImg) return;
            e.preventDefault();
            lightboxImg.src = img.src;
            lightboxImg.alt = img.alt || '';
            lightbox.classList.add('open');
        });

        /* 评论输入条 */
        var dock = $('#commentDock');
        if (dock) {
            document.body.classList.add('has-comment-dock');
            dock.addEventListener('click', function (e) {
                if (e.target.closest('#dockSubmit')) {
                    var form = $('#comment-form');
                    if (form) form.submit();
                    return;
                }
                var textarea = $('#textarea');
                if (!textarea) return;
                var target = $('#comments') || textarea;
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                setTimeout(function () { textarea.focus(); }, 350);
            });
        }

        /* 文章标签：默认只显示前 8 个，点“…”展开/收起 */
        var tagMore = $('#tagMore');
        var postTags = $('#postTags');
        if (tagMore && postTags) {
            tagMore.addEventListener('click', function () {
                var expanded = postTags.classList.toggle('expanded');
                tagMore.setAttribute('aria-expanded', expanded ? 'true' : 'false');
                tagMore.textContent = expanded ? '收起' : '···';
                tagMore.title = expanded ? '收起标签' : '展开全部标签';
            });
        }

        /* 标签云：每行独立按内容宽度计算位移与时长，多标签也匀速可读 */
        $$('.tag-cloud-track').forEach(function (track) {
            var firstSet = track.querySelector('.tag-cloud-set');
            if (!firstSet) return;
            var setWidth = firstSet.getBoundingClientRect().width;
            if (setWidth <= 0) return;
            track.style.setProperty('--tag-shift', (-setWidth) + 'px');
            track.style.animationDuration = Math.max(18, Math.round(setWidth / 45)) + 's';
        });

        /* ── 滚动缩放淡入：仿 AOS zoom-in，原生 IO，无外部库 ──
           同批进入视口的元素按 DOM 顺序错开 60ms，动画播完清掉 will-change 释放合成层 */
        var revealEls = $$('.ar-reveal');
        if (revealEls.length) {
            html.classList.add('ar-anim-ready');
            var showReveal = function (el, delay) {
                if (el.classList.contains('is-visible')) return;
                el.style.animationDelay = delay + 's';
                el.classList.add('is-visible');
                el.addEventListener('animationend', function () {
                    el.style.willChange = 'auto';
                }, { once: true });
            };
            if ('IntersectionObserver' in window) {
                var observer = new IntersectionObserver(function (entries) {
                    var batch = entries.filter(function (e) { return e.isIntersecting; });
                    // 同批按页面顺序错落，避免逐个进入时 delay 越来越大
                    batch.sort(function (a, b) {
                        return revealEls.indexOf(a.target) - revealEls.indexOf(b.target);
                    });
                    batch.forEach(function (entry, i) {
                        showReveal(entry.target, Math.min(i, 5) * 0.06);
                        observer.unobserve(entry.target);
                    });
                }, { threshold: 0.05, rootMargin: '0px 0px -40px 0px' });
                revealEls.forEach(function (el) { observer.observe(el); });
            } else {
                revealEls.forEach(function (el) { showReveal(el, 0); });
            }
        }

        /* ── 融合增强：图片加载扫光移除 ── */
        function bindShimmer(root) {
            $$('.ar-shimmer', root || document).forEach(function (img) {
                if (img.classList.contains('is-loaded')) return;
                var done = function () { img.classList.add('is-loaded'); };
                if (img.complete) done();
                else {
                    img.addEventListener('load', done);
                    img.addEventListener('error', done);
                }
            });
        }
        bindShimmer();

        /* ── Hero 天气长条：国内服务端代理 + IP 自动定位 + 会话缓存 ── */
        (function () {
            var box = $('#heroWeather');
            if (!box) return;
            var city = (box.getAttribute('data-city') || '').trim();
            var auto = !city || box.classList.contains('is-auto');
            var icon = box.querySelector('.hw-icon use');
            var cityEl = box.querySelector('.hw-city');
            var tempEl = box.querySelector('.hw-temp');
            var descEl = box.querySelector('.hw-desc');
            var metaEl = box.querySelector('.hw-meta');
            var proxy = (window.AR_THEME_URL || '') + '/weather.php';

            function render(d) {
                if (!d || !d.ok) return false;
                if (cityEl) cityEl.textContent = d.city || city || '本地';
                if (tempEl) tempEl.textContent = d.temp + '°';
                if (descEl) descEl.textContent = d.cond || '—';
                if (metaEl) {
                    var parts = [];
                    if (d.feels) parts.push('体感 ' + d.feels + '°');
                    if (d.humidity) parts.push('湿度 ' + d.humidity);
                    if (d.wind) parts.push(d.wind);
                    metaEl.textContent = parts.join(' · ');
                }
                if (icon) icon.setAttribute('href', '#w-' + (d.icon || 'cloud'));
                box.classList.remove('is-loading', 'is-error');
                return true;
            }

            // 会话内缓存，避免同页多次请求；IP 自动定位与固定城市分开缓存
            var cacheKey = 'ar-weather-' + (auto ? 'auto' : city);
            var cached = null;
            try { cached = JSON.parse(sessionStorage.getItem(cacheKey) || 'null'); } catch (e) {}
            if (cached && render(cached)) return;

            box.classList.add('is-loading');
            var ctrl = typeof AbortController !== 'undefined' ? new AbortController() : null;
            var timer = setTimeout(function () { if (ctrl) ctrl.abort(); }, 8000);
            var url = proxy + (auto ? '' : '?city=' + encodeURIComponent(city));
            fetch(url, ctrl ? { signal: ctrl.signal } : {})
                .then(function (r) { if (!r.ok) throw new Error('status ' + r.status); return r.json(); })
                .then(function (d) {
                    clearTimeout(timer);
                    if (!render(d)) throw new Error('empty weather');
                    try { sessionStorage.setItem(cacheKey, JSON.stringify(d)); } catch (e) {}
                })
                .catch(function () {
                    clearTimeout(timer);
                    box.classList.remove('is-loading');
                    box.classList.add('is-error');
                    if (descEl) descEl.textContent = '天气暂不可用';
                    if (tempEl) tempEl.textContent = '';
                    if (metaEl) metaEl.textContent = '';
                });
        })();

        /* ── 首屏 Hero 大图轮播 ── */
        (function () {
            var hero = $('#hero');
            var feature = $('#heroFeature');
            if (!hero || !feature || feature.dataset.ready === '1') return;
            var slides = $$('.hero-feat', feature);
            if (!slides.length) return;
            feature.dataset.ready = '1';

            var layers = $$('.hero-bg-layer', hero);
            var dotsBox = $('#heroDots');
            var prev = $('#heroPrev');
            var next = $('#heroNext');
            var total = slides.length;
            var idx = parseInt(feature.getAttribute('data-start'), 10);
            if (isNaN(idx) || idx < 0 || idx >= total) idx = 0;
            var layerIdx = 0;
            var firstShown = false;
            var cache = {};
            var timer = null;
            var dots = [];

            slides.forEach(function (_, i) {
                var b = document.createElement('button');
                b.type = 'button';
                b.className = 'hero-dot' + (i === idx ? ' is-active' : '');
                b.setAttribute('aria-label', '切换到第 ' + (i + 1) + ' 篇');
                b.addEventListener('click', function () { goTo(i); restart(); });
                if (dotsBox) dotsBox.appendChild(b);
                dots.push(b);
            });
            function pad2(n) { return (n < 10 ? '0' : '') + n; }
            function paintCount() {
                var el = $('#heroCount');
                if (el) el.innerHTML = '<b>' + pad2(idx + 1) + '</b><i>/</i>' + pad2(total);
            }
            paintCount();

            function coverOf(i) { return slides[i].getAttribute('data-bg') || ''; }
            function setBg(i) {
                var url = coverOf(i);
                if (!url || !layers.length) return;
                var target = layers[1 - layerIdx];
                var activate = function (u) {
                    var instant = !firstShown;
                    target.style.backgroundImage = 'url("' + u.replace(/"/g, '\\"') + '")';
                    if (instant) { firstShown = true; target.style.transition = 'none'; }
                    target.classList.add('is-active');
                    if (layers[layerIdx]) layers[layerIdx].classList.remove('is-active');
                    layerIdx = 1 - layerIdx;
                    if (instant) requestAnimationFrame(function () { target.style.transition = ''; });
                };
                var img = cache[url];
                if (img && img.complete && img.naturalWidth) { activate(url); return; }
                img = new Image();
                cache[url] = img;
                img.onload = function () { activate(url); };
                img.src = url;
            }
            function preload(i) {
                var url = coverOf(i);
                if (!url || cache[url]) return;
                cache[url] = new Image();
                cache[url].src = url;
            }
            function resetRing() {
                if (!next) return;
                next.classList.remove('is-running');
                void next.offsetWidth;
                next.classList.add('is-running');
            }
            function goTo(n) {
                if (total < 2) return;
                var target = ((n % total) + total) % total;
                if (target === idx) return;
                slides[idx].classList.remove('is-active');
                slides[target].classList.add('is-active');
                if (dots[idx]) dots[idx].classList.remove('is-active');
                if (dots[target]) dots[target].classList.add('is-active');
                idx = target;
                paintCount();
                setBg(idx);
                preload((idx + 1) % total);
                resetRing();
            }
            function stop() {
                if (timer) { clearInterval(timer); timer = null; }
                hero.classList.add('is-paused');
            }
            function play() {
                stop();
                hero.classList.remove('is-paused');
                resetRing();
                if (total < 2) return;
                timer = setInterval(function () {
                    if (document.hidden || !document.body.contains(feature)) { stop(); return; }
                    goTo(idx + 1);
                }, 5000);
            }
            function restart() { play(); }

            if (prev) prev.addEventListener('click', function () { goTo(idx - 1); restart(); });
            if (next) next.addEventListener('click', function () { goTo(idx + 1); restart(); });
            hero.addEventListener('mouseenter', stop);
            hero.addEventListener('mouseleave', play);
            hero.addEventListener('focusin', stop);
            var tx = 0, ty = 0;
            hero.addEventListener('touchstart', function (e) {
                tx = e.touches[0].clientX; ty = e.touches[0].clientY;
            }, { passive: true });
            hero.addEventListener('touchend', function (e) {
                var t = e.changedTouches[0];
                var dx = t.clientX - tx, dy = t.clientY - ty;
                if (Math.abs(dx) > 46 && Math.abs(dx) > Math.abs(dy) * 1.4) {
                    goTo(idx + (dx < 0 ? 1 : -1));
                    restart();
                }
            }, { passive: true });

            // 首张背景已在模板中直出，预加载下一张并开始轮播
            preload((idx + 1) % total);
            play();
        })();

        /* ── 融合增强：排序标签（首页文章卡片重新排序） ── */
        var sortTabs = $('#sortTabs');
        if (sortTabs) {
            var firstCard = $('.post-card');
            var cardsWrap = firstCard ? firstCard.parentNode : null;
            if (cardsWrap) {
                var allCards = $$('.post-card[data-views]', cardsWrap);
                var origOrder = allCards.slice();
                sortTabs.addEventListener('click', function (e) {
                    var tab = e.target.closest('.sort-tab');
                    if (!tab) return;
                    $$('.sort-tab', sortTabs).forEach(function (t) { t.classList.remove('active'); });
                    tab.classList.add('active');
                    var key = tab.getAttribute('data-sort');
                    var list = origOrder.slice();
                    if (key === 'views' || key === 'comments') {
                        list.sort(function (a, b) {
                            return parseInt(b.getAttribute('data-' + key), 10) - parseInt(a.getAttribute('data-' + key), 10);
                        });
                    }
                    var pager = $('.page-navigator', cardsWrap);
                    list.forEach(function (card) {
                        cardsWrap.insertBefore(card, pager || null);
                        if (card.classList.contains('ar-reveal')) {
                            card.classList.remove('is-visible');
                            void card.offsetWidth;
                            card.classList.add('is-visible');
                        }
                    });
                });
            }
        }

        /* ── 评论 IP 归属地：本站国内接口代理，异步填充不阻塞页面 ── */
        (function () {
            var nodes = $$('.comment-from[data-ip]');
            if (!nodes.length) return;
            var proxy = (window.AR_THEME_URL || '') + '/ipinfo.php';
            var byIp = Object.create(null);
            var pending = [];
            nodes.forEach(function (node) {
                var ip = (node.getAttribute('data-ip') || '').trim();
                if (!ip) return;
                if (!byIp[ip]) {
                    byIp[ip] = { ip: ip, nodes: [] };
                    pending.push(byIp[ip]);
                }
                byIp[ip].nodes.push(node); // 同一 IP 的多条评论共用一个结果
            });

            function fill(item, data) {
                var text = data && data.ok && data.text ? data.text : '未知地区';
                item.nodes.forEach(function (node) {
                    var label = $('.cf-text', node);
                    if (label) label.textContent = text;
                    node.setAttribute('data-state', data && data.ok ? 'ok' : 'fail');
                    node.setAttribute('title', text + ' · 仅根据评论 IP 解析到省市');
                });
            }

            var index = 0;
            function next() {
                if (index >= pending.length) return;
                var item = pending[index++];
                var ctrl = typeof AbortController !== 'undefined' ? new AbortController() : null;
                var timer = setTimeout(function () { if (ctrl) ctrl.abort(); }, 7000);
                fetch(proxy + '?ip=' + encodeURIComponent(item.ip), ctrl ? { signal: ctrl.signal } : {})
                    .then(function (r) { if (!r.ok) throw new Error('status ' + r.status); return r.json(); })
                    .then(function (d) { clearTimeout(timer); fill(item, d); })
                    .catch(function () { clearTimeout(timer); fill(item, { ok: false }); })
                    .then(next);
            }
            for (var i = 0; i < Math.min(3, pending.length); i++) next();
        })();

        /* ── 独立页面：友链分类 / 归档年份 胶囊切换 ── */
        [
            { tabs: 'linkTabs', panels: '.link-group-panel', attr: 'data-group', all: 'all' },
            { tabs: 'archiveTabs', panels: '.archive-year-panel', attr: 'data-year', all: '' },
            { tabs: 'categoryTabs', panels: '.cat-panel', attr: 'data-cat', all: 'all' }
        ].forEach(function (cfg) {
            var bar = document.getElementById(cfg.tabs);
            if (!bar) return;
            var buttons = $$('.tab-pill', bar);
            var thumb = $('.pill-thumb', bar);
            var panels = $$(cfg.panels);

            function moveThumb(btn) {
                if (!thumb || !btn) return;
                thumb.style.width = btn.offsetWidth + 'px';
                thumb.style.height = btn.offsetHeight + 'px';
                thumb.style.transform = 'translate(' + btn.offsetLeft + 'px,' + btn.offsetTop + 'px)';
            }

            function select(btn) {
                var key = btn.getAttribute(cfg.attr) || '';
                buttons.forEach(function (b) { b.classList.toggle('is-active', b === btn); });
                moveThumb(btn);
                panels.forEach(function (panel) {
                    var pk = panel.getAttribute(cfg.attr) || '';
                    panel.hidden = !(key === cfg.all || pk === key);
                });
            }

            buttons.forEach(function (btn) {
                btn.addEventListener('click', function () { select(btn); });
            });
            var initial = $('.tab-pill.is-active', bar) || buttons[0];
            moveThumb(initial);
            if (window.addEventListener) {
                window.addEventListener('resize', function () {
                    moveThumb($('.tab-pill.is-active', bar) || buttons[0]);
                });
            }
            if (document.fonts && document.fonts.ready) {
                document.fonts.ready.then(function () { moveThumb($('.tab-pill.is-active', bar) || buttons[0]); });
            }
        });
    });

    /* ── 友链申请：弹窗 + 复制 + AJAX 提交（走 Typecho 评论接口） ── */
    ready(function () {
        var modal = $('#arApplyModal');
        var form = $('#arApplyForm');
        if (!modal || !form) return;

        var bodyLocked = false;
        function openModal() {
            modal.classList.remove('closing');
            modal.classList.add('active');
            modal.setAttribute('aria-hidden', 'false');
            if (!bodyLocked) { document.body.style.overflow = 'hidden'; bodyLocked = true; }
            setTimeout(function () { var el = $('#applyName'); if (el) el.focus(); }, 260);
        }
        function closeModal() {
            if (!modal.classList.contains('active')) return;
            modal.classList.add('closing');
            modal.setAttribute('aria-hidden', 'true');
            setTimeout(function () {
                modal.classList.remove('active', 'closing');
                if (bodyLocked) { document.body.style.overflow = ''; bodyLocked = false; }
            }, 200);
        }

        $$('[data-ar-apply-open]').forEach(function (btn) { btn.addEventListener('click', openModal); });
        $$('[data-ar-apply-close]', modal).forEach(function (btn) { btn.addEventListener('click', closeModal); });
        document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeModal(); });

        // 复制本站信息
        $$('.lk-copy').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var text = btn.getAttribute('data-copy') || '';
                function done() {
                    btn.classList.add('is-done');
                    setTimeout(function () { btn.classList.remove('is-done'); }, 1400);
                }
                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(text).then(done, function () {});
                } else {
                    var ta = document.createElement('textarea');
                    ta.value = text; ta.style.position = 'fixed'; ta.style.opacity = '0';
                    document.body.appendChild(ta); ta.select();
                    try { document.execCommand('copy'); done(); } catch (e) {}
                    document.body.removeChild(ta);
                }
            });
        });

        // 提交申请：组装为站内评论，AJAX POST，成功后展示「申请已提交」
        var submit = $('#arApplySubmit');
        var errBox = $('#arApplyErr');
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            var name = ($('#applyName').value || '').trim();
            var email = ($('#applyEmail').value || '').trim();
            var site = ($('#applySite').value || '').trim();
            var avatar = ($('#applyAvatar').value || '').trim();
            var desc = ($('#applyDesc').value || '').trim();
            if (!name || !email || !site || !desc) return;

            var text = '【友链申请】' + name + '\n站点地址：' + site
                + '\n头像地址：' + (avatar || '（留空）') + '\n联系邮箱：' + email
                + '\n站点简介：' + desc;
            $('#applyAuthor').value = name;
            $('#applyMail').value = email;
            $('#applyUrl').value = site;
            $('#applyText').value = text;

            if (errBox) errBox.hidden = true;
            if (submit) { submit.disabled = true; submit.textContent = '提交中…'; }

            // 填入页面内真实的评论表单（与文章/留言板评论走完全相同的字段与路径）
            var realForm = $('#arApplyRealRespond #comment-form');
            if (!realForm) { if (errBox) { errBox.textContent = '评论表单未就绪，请刷新后重试。'; errBox.hidden = false; } return; }
            $('input[name="author"]', realForm).value = name;
            $('input[name="mail"]', realForm).value = email;
            $('input[name="url"]', realForm).value = site;
            $('textarea[name="text"]', realForm).value = text;
            var data = new FormData(realForm);

            fetch(realForm.action, {
                method: 'POST',
                body: data,
                credentials: 'same-origin'
            }).then(function (r) {
                if (!r.ok) throw new Error('http ' + r.status);
                return r.text();
            }).then(function () {
                form.hidden = true;
                var done = $('#arApplyDone');
                if (done) done.hidden = false;
            }).catch(function (err) {
                if (errBox) { errBox.textContent = '提交失败，请稍后重试或联系站长。'; errBox.hidden = false; }
            }).finally(function () {
                if (submit) { submit.disabled = false; submit.innerHTML = '提交申请<svg class="ico"><use href="#i-arrow-right"></use></svg>'; }
            });
        });
    });
})();
