(function(w, d) {
    let b = d.getElementsByTagName('body')[0],
        e = d.documentElement,
        wWidth = w.innerWidth || e.clientWidth || b.clientWidth,
        wHeight = w.innerHeight || e.clientHeight || b.clientHeight;

    if (marketpress.font_uri !== 'false') {
        let font = d.createElement('link');
        font.async = true;
        font.type = 'text/css';
        font.rel = 'stylesheet';
        font.href = marketpress.font_uri;
        b.appendChild(font);
    }

    let icon = d.createElement('link');
    icon.async = true;
    icon.type = 'text/css';
    icon.rel = 'stylesheet';
    icon.href = 'https://cdn.lineicons.com/2.0/LineIcons.min.css';

    b.appendChild(icon);


    let lazyload = d.createElement('script'),
        lazyloadVersion = !('IntersectionObserver' in w) ? '8.17.0' : '10.19.0';
    lazyload.async = true;
    lazyload.src = 'https://cdn.jsdelivr.net/npm/vanilla-lazyload@' + lazyloadVersion + '/dist/lazyload.min.js';
    w.lazyLoadOptions = { elements_selector: '.lazy' };

    b.appendChild(lazyload);

    let sliderjs = d.createElement('script');
    sliderjs.async = true;
    sliderjs.src = 'https://cdn.jsdelivr.net/npm/@splidejs/splide@3.2.7/dist/js/splide.min.js';
    b.appendChild(sliderjs);

    sliderjs.onload = function() {
        let slider = d.querySelector('#splide1');
        if (typeof(slider) != 'undefined' && slider != null) {
            let splide1 = new Splide('#splide1', {
                arrows: false,
                type: 'loop',
                autoplay: true,
                pauseOnHover: true,
                pauseOnFocus: true,
                lazyLoad: 'sequential',
                interval: 3000,
            }).mount();
        }
    }

    let masonryjs = d.createElement('script');
    masonryjs.async = true;
    masonryjs.src = 'https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js';
    b.appendChild(masonryjs);

    masonryjs.onload = function() {
        let $containers = d.querySelectorAll('.masonry-container');
        for (let i = 0; i < $containers.length; i++) {

            let $container = $containers[i];

            let runMasonry = function() {
                let $product_images = $container.querySelectorAll('.masonry-product-thumbnail');
                let $post_images = $container.querySelectorAll('.masonry-post-thumbnail');

                for (let i = 0; i < $product_images.length; i++) {
                    $product_images[i].style.height = $product_images[i].offsetWidth + 'px';
                }

                for (let i = 0; i < $post_images.length; i++) {
                    let iheight = $post_images[i].offsetWidth / 2;
                    $post_images[i].style.height = iheight + 'px';
                }

                new LazyLoad({ elements_selector: ".lazy" });
                new Masonry($container, {
                    gutter: 10,
                    itemSelector: '.masonry-item',
                });
                let $loader = $container.parentNode.querySelector('.loader');

                if ($loader) {
                    $loader.style.display = 'none';
                }
            }

            function grider() {
                let $product_images = document.querySelectorAll('.masonry-product-thumbnail');

                for (let i = 0; i < $product_images.length; i++) {
                    $product_images[i].style.height = $product_images[i].offsetWidth + 'px';
                }

                new LazyLoad({ elements_selector: ".lazy" });

            }

            grider();

            setTimeout(function() {
                runMasonry();
            }, 1000);

            let navurl = $container.parentNode.querySelector('.loop-navigation .next');

            if (navurl) {

                let loading = false;
                d.getElementById('loadmore').parentNode.style.display = 'block';
                d.getElementById('loadmore').onclick = function() {
                    if (!loading) {
                        loading = true;

                        let url = navurl.getAttribute('href');
                        $container.parentNode.querySelector('.loader').style.display = 'block';
                        let ajax = new XMLHttpRequest();
                        ajax.open('GET', url, true);
                        ajax.onload = function() {
                            if (ajax.status === 200) {
                                let html = new DOMParser().parseFromString(ajax.responseText, 'text/html');
                                let productboxs = html.querySelectorAll('.masonry-container .productbox');
                                productboxs.forEach(function(productbox, key) {
                                    $container.appendChild(productbox);
                                });

                                runMasonry();

                                let nextel = html.querySelector('.loop-navigation .next');
                                $container.parentNode.querySelector('.loader').style.display = 'none';
                                if (nextel != undefined) {
                                    let nexturl = nextel.getAttribute('href');
                                    $container.parentNode.querySelector('.loop-navigation .next').setAttribute('href', nexturl);
                                    new LazyLoad({ elements_selector: ".lazy" });
                                } else {
                                    let navel = $container.parentNode.querySelector('.loop-navigation');
                                    navel.parentNode.removeChild(navel);
                                    d.getElementById('loadmore').style.display = 'none';
                                }
                                loading = false;
                            }
                        }
                        ajax.send();
                    }
                };

            }
        }
    }

    let main = d.createElement('script');
    main.async = true;
    main.src = marketpress.main_script;
    b.appendChild(main);

    let goTopBtn = document.querySelector('#back-to-top');
    if (typeof(goTopBtn) != 'undefined' && goTopBtn !== null) {

        window.addEventListener('scroll', function() {
            let scrolled = window.pageYOffset;
            let coords = document.documentElement.clientHeight;

            if (typeof(goTopBtn) != 'undefined' && goTopBtn !== null) {
                if (scrolled > coords) {
                    goTopBtn.style.display = 'block';
                }

                if (scrolled < coords) {
                    goTopBtn.style.display = 'none';
                }
            }
        });

        let backToTop = function() {
            if (window.pageYOffset > 0) {
                window.scrollBy(0, -80);
                setTimeout(backToTop, 0);
            }
        }

        if (typeof(goTopBtn) != 'undefined' && goTopBtn !== null) {
            goTopBtn.addEventListener('click', backToTop);
        }

    }

}(window, document));
window.addComment = function(a) {
    function b() { c(), g() }

    function c(a) { if (t && (m = j(r.cancelReplyId), n = j(r.commentFormId), m)) { m.addEventListener("touchstart", e), m.addEventListener("click", e); for (var b, c = d(a), g = 0, h = c.length; g < h; g++) b = c[g], b.addEventListener("touchstart", f), b.addEventListener("click", f) } }

    function d(a) { var b, c = r.commentReplyClass; return a && a.childNodes || (a = q), b = q.getElementsByClassName ? a.getElementsByClassName(c) : a.querySelectorAll("." + c) }

    function e(a) {
        var b = this,
            c = r.temporaryFormId,
            d = j(c);
        d && o && (j(r.parentIdFieldId).value = "0", d.parentNode.replaceChild(o, d), b.style.display = "none", a.preventDefault())
    }

    function f(b) {
        var c, d = this,
            e = i(d, "belowelement"),
            f = i(d, "commentid"),
            g = i(d, "respondelement"),
            h = i(d, "postid");
        e && f && g && h && (c = a.addComment.moveForm(e, f, g, h), !1 === c && b.preventDefault())
    }

    function g() {
        if (s) {
            var a = { childList: !0, subTree: !0 };
            p = new s(h), p.observe(q.body, a)
        }
    }

    function h(a) {
        for (var b = a.length; b--;)
            if (a[b].addedNodes.length) return void c()
    }

    function i(a, b) { return u ? a.dataset[b] : a.getAttribute("data-" + b) }

    function j(a) { return q.getElementById(a) }

    function k(b, c, d, e) {
        var f = j(b);
        o = j(d);
        var g, h, i, k = j(r.parentIdFieldId),
            p = j(r.postIdFieldId);
        if (f && o && k) {
            l(o), e && p && (p.value = e), k.value = c, m.style.display = "", f.parentNode.insertBefore(o, f.nextSibling), m.onclick = function() { return !1 };
            try {
                for (var s = 0; s < n.elements.length; s++)
                    if (g = n.elements[s], h = !1, "getComputedStyle" in a ? i = a.getComputedStyle(g) : q.documentElement.currentStyle && (i = g.currentStyle), (g.offsetWidth <= 0 && g.offsetHeight <= 0 || "hidden" === i.visibility) && (h = !0), "hidden" !== g.type && !g.disabled && !h) { g.focus(); break }
            } catch (t) {}
            return !1
        }
    }

    function l(a) {
        var b = r.temporaryFormId,
            c = j(b);
        c || (c = q.createElement("div"), c.id = b, c.style.display = "none", a.parentNode.insertBefore(c, a))
    }
    var m, n, o, p, q = a.document,
        r = { commentReplyClass: "comment-reply-link", cancelReplyId: "cancel-comment-reply-link", commentFormId: "commentform", temporaryFormId: "wp-temp-form-div", parentIdFieldId: "comment_parent", postIdFieldId: "comment_post_ID" },
        s = a.MutationObserver || a.WebKitMutationObserver || a.MozMutationObserver,
        t = "querySelector" in q && "addEventListener" in a,
        u = !!q.documentElement.dataset;
    return t && "loading" !== q.readyState ? b() : t && a.addEventListener("DOMContentLoaded", b, !1), { init: c, moveForm: k }
}(window);