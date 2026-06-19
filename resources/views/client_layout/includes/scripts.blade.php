<script src="//cdn.shopify.com/s/files/1/0603/7530/2276/t/1/assets/options_selection.js" type="text/javascript"></script>
<script src="//cdn.shopify.com/s/files/1/0603/7530/2276/t/1/assets/globo_checkout.js" type="text/javascript"></script>
<script src="{{asset('js/jquery.min.js')}}"  type="text/javascript"></script>
    <script>window.performance && window.performance.mark && window.performance.mark('shopify.content_for_header.start');</script>
    {{-- <script async="async" src="/checkouts/internal/preloads.js?locale=en-WS"></script> --}}
    {{-- <script id="shopify-features"
        type="application/json">{"accessToken":"afc6e708737957516cf68eba2b07676b","betas":["rich-media-storefront-analytics"],"domain":"new-99.myshopify.com","predictiveSearch":true,"shopId":2709520502,"locale":"en"}</script> --}}
   
    <script type="module">!function (o) { (o.Shopify = o.Shopify || {}).modules = !0 }(window);</script>
    <script>!function (o) { function n() { var o = []; function n() { o.push(Array.prototype.slice.apply(arguments)) } return n.q = o, n } var t = o.Shopify = o.Shopify || {}; t.loadFeatures = n(), t.autoloadFeatures = n() }(window);</script>
    <script>
        window.Shopify = window.Shopify || {};
        window.Shopify.SignInWithShop = window.Shopify.SignInWithShop || {};
        window.Shopify.SignInWithShop.assetMetrics = { sampleRate: 0.01 };
        window.Shopify.SignInWithShop.eligible = true;
    </script>
    <script id="shop-js-analytics" type="application/json">{"pageType":"index"}</script>
    {{-- <script defer="defer" async type="module"
        src="//new-99.myshopify.com/cdn/shopifycloud/shop-js/modules/v2/loader.init-shop-cart-sync.en.esm.js"></script>
    <script type="module">
        await import("//new-99.myshopify.com/cdn/shopifycloud/shop-js/modules/v2/loader.init-shop-cart-sync.en.esm.js");

        window.Shopify.SignInWithShop?.initShopCartSync?.({ "fedCMEnabled": true, "windoidEnabled": true });

    </script> --}}

<script>
  window.Shopify = window.Shopify || {};
  if (!window.Shopify.featureAssets) window.Shopify.featureAssets = {};
  window.Shopify.featureAssets['shop-js'] = { "shop-toast-manager": ["modules/v2/loader.shop-toast-manager.en.esm.js"], "shop-cash-offers": ["modules/v2/loader.shop-cash-offers.en.esm.js"], "init-windoid": ["modules/v2/loader.init-windoid.en.esm.js"], "init-shop-user-recognition": ["modules/v2/loader.init-shop-user-recognition.en.esm.js"], "listener": ["modules/v2/loader.listener.en.esm.js"], "init-shop-email-lookup-coordinator": ["modules/v2/loader.init-shop-email-lookup-coordinator.en.esm.js"], "init-fed-cm": ["modules/v2/loader.init-fed-cm.en.esm.js"], "init-shop-cart-sync": ["modules/v2/loader.init-shop-cart-sync.en.esm.js"], "shop-button": ["modules/v2/loader.shop-button.en.esm.js"], "pay-button": ["modules/v2/loader.pay-button.en.esm.js"], "init-customer-accounts-sign-up": ["modules/v2/loader.init-customer-accounts-sign-up.en.esm.js"], "avatar": ["modules/v2/loader.avatar.en.esm.js"], "checkout-modal": ["modules/v2/loader.checkout-modal.en.esm.js"], "init-shop-for-new-customer-accounts": ["modules/v2/loader.init-shop-for-new-customer-accounts.en.esm.js"], "shop-user-recognition": ["modules/v2/loader.shop-user-recognition.en.esm.js"], "shop-login": ["modules/v2/loader.shop-login.en.esm.js"], "shop-follow-button": ["modules/v2/loader.shop-follow-button.en.esm.js"], "shop-login-button": ["modules/v2/loader.shop-login-button.en.esm.js"], "shop-cart-sync": ["modules/v2/loader.shop-cart-sync.en.esm.js"], "init-customer-accounts": ["modules/v2/loader.init-customer-accounts.en.esm.js"], "lead-capture": ["modules/v2/loader.lead-capture.en.esm.js"], "payment-terms": ["modules/v2/loader.payment-terms.en.esm.js"] };
</script>
<script id="hot-reload-client" src="{{asset('client/shopifycloud/theme-hot-reload/theme-hot-reload.js')}}" defer
  crossorigin></script>
<script
  id="__st">var __st = { "a": 2709520502, "offset": -14400, "reqid": "a3906b97-b533-4f55-b6bf-a32fd56e4507-1780057523", "pageurl": "new-99.myshopify.com\/", "t": "prospect", "u": "f2f31bac464a", "p": "home" };</script>
<script>window.ShopifyPaypalV4VisibilityTracking = true;</script>
<script
  id="form-persister">!function () { 'use strict'; const t = 'contact', e = 'new_comment', n = [[t, t], ['blogs', e], ['comments', e], [t, 'customer']], o = 'password', r = 'form_key', c = ['recaptcha-v3-token', 'g-recaptcha-response', 'h-captcha-response', o], s = () => { try { return window.sessionStorage } catch { return } }, i = '__shopify_v', u = t => t.elements[r], a = function () { const t = [...n].map((([t, e]) => `form[action*='/${t}']:not([data-nocaptcha='true']) input[name='form_type'][value='${e}']`)).join(','); var e; return e = t, () => e ? [...document.querySelectorAll(e)].map((t => t.form)) : [] }(); function m(t) { const e = u(t); a().includes(t) && (!e || !e.value) && function (t) { try { if (!s()) return; !function (t) { const e = s(); if (!e) return; const n = u(t); if (!n) return; const o = n.value; o && e.removeItem(o) }(t); const e = Array.from(Array(32), (() => Math.random().toString(36)[2])).join(''); !function (t, e) { u(t) || t.append(Object.assign(document.createElement('input'), { type: 'hidden', name: r })), t.elements[r].value = e }(t, e), function (t, e) { const n = s(); if (!n) return; const r = [...t.querySelectorAll(`input[type='${o}']`)].map((({ name: t }) => t)), u = [...c, ...r], a = {}; for (const [o, c] of new FormData(t).entries()) u.includes(o) || (a[o] = c); n.setItem(e, JSON.stringify({ [i]: 1, action: t.action, data: a })) }(t, e) } catch (e) { console.error('failed to persist form', e) } }(t) } const f = t => { if ('true' === t.dataset.persistBound) return; const e = function (t, e) { const n = function (t) { return 'function' == typeof t.submit ? t.submit : HTMLFormElement.prototype.submit }(t).bind(t); return function () { let t; return () => { t || (t = !0, (() => { try { e(), n() } catch (t) { (t => { console.error('form submit failed', t) })(t) } })(), setTimeout((() => t = !1), 250)) } }() }(t, (() => { m(t) })); !function (t, e) { if ('function' == typeof t.submit && 'function' == typeof e) try { t.submit = e } catch { } }(t, e), t.addEventListener('submit', (t => { t.preventDefault(), e() })), t.dataset.persistBound = 'true' }; !function () { function t(t) { const e = (t => { const e = t.target; return e instanceof HTMLFormElement ? e : e && e.form })(t); e && m(e) } document.addEventListener('submit', t), document.addEventListener('DOMContentLoaded', (() => { const e = a(); for (const t of e) f(t); var n; n = document.body, new window.MutationObserver((t => { for (const e of t) if ('childList' === e.type && e.addedNodes.length) for (const t of e.addedNodes) 1 === t.nodeType && 'FORM' === t.tagName && a().includes(t) && f(t) })).observe(n, { childList: !0, subtree: !0, attributes: !1 }), document.removeEventListener('submit', t) })) }() }();</script>
{{-- <script integrity="sha256-JjoPp5ZfB1sSAs5SQaol1x1GgvveM+BgmRzyDexInEQ="
  data-source-attribution="shopify.loadfeatures" defer="defer"
  src="{{asset('client/shopifycloud/storefront/assets/storefront/load_feature-1bd60354.js')}}"
  crossorigin="anonymous"></script> --}}
{{-- <script data-source-attribution="shopify.dynamic_checkout.dynamic.init">var Shopify = Shopify || {}; Shopify.PaymentButton = Shopify.PaymentButton || { isStorefrontPortableWallets: !0, init: function () { window.Shopify.PaymentButton.init = function () { }; var t = document.createElement("script"); t.src = "{{asset('shopifycloud/portable-wallets/latest/portable-wallets.en.js", t.type')}} = "module", document.head.appendChild(t) } };
</script> --}}
{{-- <script data-source-attribution="shopify.dynamic_checkout.buyer_consent">
  function portableWalletsHideBuyerConsent(e) { var t = document.getElementById("shopify-buyer-consent"), n = document.getElementById("shopify-subscription-policy-button"); t && n && (t.classList.add("hidden"), t.setAttribute("aria-hidden", "true"), n.removeEventListener("click", e)) } function portableWalletsShowBuyerConsent(e) { var t = document.getElementById("shopify-buyer-consent"), n = document.getElementById("shopify-subscription-policy-button"); t && n && (t.classList.remove("hidden"), t.removeAttribute("aria-hidden"), n.addEventListener("click", e)) } window.Shopify?.PaymentButton && (window.Shopify.PaymentButton.hideBuyerConsent = portableWalletsHideBuyerConsent, window.Shopify.PaymentButton.showBuyerConsent = portableWalletsShowBuyerConsent);
</script> --}}
{{-- <script data-source-attribution="shopify.dynamic_checkout.cart.bootstrap">document.addEventListener("DOMContentLoaded", (function () { function t() { return document.querySelector("shopify-accelerated-checkout-cart, shopify-accelerated-checkout") } if (t()) Shopify.PaymentButton.init(); else { new MutationObserver((function (e, n) { t() && (Shopify.PaymentButton.init(), n.disconnect()) })).observe(document.body, { childList: !0, subtree: !0 }) } }));
</script> --}}
{{-- <script async="async" integrity="sha256-haKOhzT83GjvO3v6qzDZfOsoJVFdXid/w2WH/c5LJ+s="
  src="{{asset('shopifycloud/storefront/assets/storefront/origin_trials-0583672e.js')}}"
  crossorigin="anonymous"></script> --}}

{{-- <script
  id="shopify-cfh-end">window.performance && window.performance.mark && window.performance.mark('shopify.content_for_header.end');</script>
  <script src="{{asset('client/shop/t/89/assets/core.js')}}"></script>
    <script src="{{asset('client/shop/t/89/assets/the-final-countdown.js')}}"
        async="async"></script> --}}


 
  {{-- <script data-cfasync='false' class='jdgm-script'>
    !function (e) {
        window.jdgm = window.jdgm || {}, jdgm.CDN_HOST = "https://cdnwidget.judge.me/", jdgm.CDN_HOST_ALT = "https://cdn2.judge.me/cdn/widget_frontend/", jdgm.API_HOST = "https://api.judge.me/", jdgm.CDN_BASE_URL = "https://cdn.shopify.com/extensions/019e7346-c189-7f7a-92b8-27c96423875f/judgeme-543/assets/", jdgm.CDN_API_HOST = "https://cdn.judge.me/",
        jdgm.docReady = function (d) {
            (e.attachEvent ? "complete" === e.readyState : "loading" !== e.readyState) ?
            setTimeout(d, 0) : e.addEventListener("DOMContentLoaded", d)
        }, jdgm.loadCSS = function (d, t, o, a) {
            !o && jdgm.loadCSS.requestedUrls.indexOf(d) >= 0 || (jdgm.loadCSS.requestedUrls.push(d),
                (a = e.createElement("link")).rel = "stylesheet", a.class = "jdgm-stylesheet", a.media = "nope!",
                a.href = d, a.onload = function () { this.media = "all", t && setTimeout(t) }, e.body.appendChild(a))
        },
        jdgm.loadCSS.requestedUrls = [], jdgm.loadJS = function (e, d) {
            var t = new XMLHttpRequest;
            t.onreadystatechange = function () { 4 === t.readyState && (Function(t.response)(), d && d(t.response)) },
                t.open("GET", e), t.onerror = function () { if (e.indexOf(jdgm.CDN_HOST) === 0 && jdgm.CDN_HOST_ALT !== jdgm.CDN_HOST) { var f = e.replace(jdgm.CDN_HOST, jdgm.CDN_HOST_ALT); jdgm.loadJS(f, d) } }, t.send()
        }, jdgm.docReady((function () {
            (window.jdgmLoadCSS || e.querySelectorAll(
                ".jdgm-widget, .jdgm-all-reviews-page").length > 0) && (jdgmSettings.widget_load_with_code_splitting ?
                    parseFloat(jdgmSettings.widget_version) >= 3 ? jdgm.loadCSS(jdgm.CDN_BASE_URL + "widget_v3_base.css") :
                        jdgm.loadCSS(jdgm.CDN_BASE_URL + "widget_base.css") : jdgm.loadCSS(jdgm.CDN_BASE_URL + "shopify_v2.css")
            )
        }))
    }(document);
</script> --}}


 <!-- Necessary JS -->
<script src="{{asset('client/shop/t/89/assets/plugins.js')}}"></script>
    <script id="setmore_script" type="text/javascript"
        src="https://my.setmore.com/webapp/js/src/others/setmore_iframe.js" async="async"></script>
    <!-- Put all third-party JS files in the vendor.css file and minify the files -->
    <script src="{{asset('client/shop/t/89/assets/vendor.js?v=143671678363523141051701360555')}}"
        defer="defer"></script>
    <script src="{{asset('client/shop/t/89/assets/aos.min.js?v=87596359962816138981701360555')}}"
        defer="defer"></script>
    <script src="{{asset('client/shop/t/89/assets/wishlist.js?v=136666558000900808741701360555')}}"
        defer="defer"></script>
  
    <script src="{{asset('client/shop/t/89/assets/predictive-search.js?v=42609991257564171701360555')}}"
        defer="defer"></script>
    <script>
        $('body').on('click', function () {
            $('.predictive-search-item').attr('style', $('.predictive-search-item').attr('style') === 'display: block;' ? 'display: none' : 'display: none;');
            $('.predictive-search-input').click(function () { return false; });
            $('.predictive-search__list-item a').click(function () { return false; });
        });
    </script>
    <!-- Ajax Cart js -->
    <script src="{{asset('client/shopifycloud/storefront/assets/themes_support/option_selection-b017cd28.js')}}"
        defer="defer"></script>
    <script src="{{asset('client/shopifycloud/storefront/assets/themes_support/api.jquery-7ab1a3a4.js')}}"
        defer="defer"></script>
    <script src="{{asset('client/shop/t/89/assets/cart.api.js')}}"
        defer="defer"></script>
    <!-- Your main.js file upload this file -->
    <script src="{{asset('client/shop/t/89/assets/main.js')}}"
        defer="defer"></script>

    <script src="{{asset('client/shop/t/89/assets/fastclick.js')}}"
        defer="defer"></script>
    <script src="{{asset('client/shop/t/89/assets/global.js')}}"
        defer="defer"></script>


    <!-- BEGIN app snippet: theme_fix_tags -->
    <script>
      (function () {
          var jdgmThemeFixes = null;
          if (!jdgmThemeFixes) return;
          var thisThemeFix = jdgmThemeFixes[Shopify.theme.id];
          if (!thisThemeFix) return;

          if (thisThemeFix.html) {
              document.addEventListener("DOMContentLoaded", function () {
                  var htmlDiv = document.createElement('div');
                  htmlDiv.classList.add('jdgm-theme-fix-html');
                  htmlDiv.innerHTML = thisThemeFix.html;
                  document.body.append(htmlDiv);
              });
          };

          if (thisThemeFix.css) {
              var styleTag = document.createElement('style');
              styleTag.classList.add('jdgm-theme-fix-style');
              styleTag.innerHTML = thisThemeFix.css;
              document.head.append(styleTag);
          };

          if (thisThemeFix.js) {
              var scriptTag = document.createElement('script');
              scriptTag.classList.add('jdgm-theme-fix-script');
              scriptTag.innerHTML = thisThemeFix.js;
              document.head.append(scriptTag);
          };
      })();
  </script>
  <!-- END app snippet -->

  <!-- END app block -->
  {{-- <script src="https://cdn.shopify.com/extensions/019e7346-c189-7f7a-92b8-27c96423875f/judgeme-543/assets/loader.js"
  type="text/javascript" defer="defer" data-app-id="683015"></script> --}}
<meta property="og:image"
  content="{{asset('client/s/files/1/0027/0952/0502/files/logo.png?height=628&pad_color=fff&v=1666114323&width=1200')}}" />
<meta property="og:image:secure_url"
  content="{{asset('client/s/files/1/0027/0952/0502/files/logo.png?height=628&pad_color=fff&v=1666114323&width=1200')}}" />
<meta property="og:image:width" content="1200" />
<meta property="og:image:height" content="628" />
<link href="https://monorail-edge.shopifysvc.com" rel="dns-prefetch">
<script>(function () { if ("sendBeacon" in navigator && "performance" in window) { try { var session_token_from_headers = performance.getEntriesByType('navigation')[0].serverTiming.find(x => x.name == '_s').description; } catch { var session_token_from_headers = undefined; } var session_cookie_matches = document.cookie.match(/_shopify_s=([^;]*)/); var session_token_from_cookie = session_cookie_matches && session_cookie_matches.length === 2 ? session_cookie_matches[1] : ""; var session_token = session_token_from_headers || session_token_from_cookie || ""; function handle_abandonment_event(e) { var entries = performance.getEntries().filter(function (entry) { return /monorail-edge.shopifysvc.com/.test(entry.name); }); if (!window.abandonment_tracked && entries.length === 0) { window.abandonment_tracked = true; var currentMs = Date.now(); var navigation_start = performance.timing.navigationStart; var payload = { shop_id: 2709520502, url: window.location.href, navigation_start, duration: currentMs - navigation_start, session_token, page_type: "index" }; window.navigator.sendBeacon("https://monorail-edge.shopifysvc.com/v1/produce", JSON.stringify({ schema_id: "online_store_buyer_site_abandonment/1.1", payload: payload, metadata: { event_created_at_ms: currentMs, event_sent_at_ms: currentMs } })); } } window.addEventListener('pagehide', handle_abandonment_event); } }());</script>
<script>
  window.__TREKKIE_SHIM_QUEUE = window.__TREKKIE_SHIM_QUEUE || [];
</script>

 {{-- <script>
    jQuery('.addtocartqv').click(function(e) {
      e.preventDefault();
      Shopify.addItemFromFormStart('add-item-qv', jQuery(this).attr('id'));
    });
  </script>  --}}
{{-- <script
  id="web-pixels-manager-setup">(function () { var wpmLoader = function () { "use strict"; return function (e, d, r, n) { var o = arguments.length > 4 && void 0 !== arguments[4] ? arguments[4] : {}; if (!Boolean(null == (i = null == (a = window.Shopify) ? void 0 : a.analytics) ? void 0 : i.replayQueue)) { var a, i; window.Shopify = window.Shopify || {}; var t = window.Shopify; t.analytics = t.analytics || {}; var s = t.analytics; s.replayQueue = [], s.publish = function (e, d, r) { return s.replayQueue.push([e, d, r]), !0 }; try { self.performance.mark("wpm:start") } catch (e) { } var l, u, c, m, p, f, h, g, y, w, v, b, S, P = (u = (l = { modern: /Edge?\/(1{2}[4-9]|1[2-9]\d|[2-9]\d{2}|\d{4,})\.\d+(\.\d+|)|Firefox\/(1{2}[4-9]|1[2-9]\d|[2-9]\d{2}|\d{4,})\.\d+(\.\d+|)|Chrom(ium|e)\/(9{2}|\d{3,})\.\d+(\.\d+|)|(Maci|X1{2}).+ Version\/(15\.\d+|(1[6-9]|[2-9]\d|\d{3,})\.\d+)([,.]\d+|)( \(\w+\)|)( Mobile\/\w+|) Safari\/|Chrome.+OPR\/(9{2}|\d{3,})\.\d+\.\d+|(CPU[ +]OS|iPhone[ +]OS|CPU[ +]iPhone|CPU IPhone OS|CPU iPad OS)[ +]+(15[._]\d+|(1[6-9]|[2-9]\d|\d{3,})[._]\d+)([._]\d+|)|Android:?[ /-](13[3-9]|1[4-9]\d|[2-9]\d{2}|\d{4,})(\.\d+|)(\.\d+|)|Android.+Firefox\/(13[5-9]|1[4-9]\d|[2-9]\d{2}|\d{4,})\.\d+(\.\d+|)|Android.+Chrom(ium|e)\/(13[3-9]|1[4-9]\d|[2-9]\d{2}|\d{4,})\.\d+(\.\d+|)|SamsungBrowser\/([2-9]\d|\d{3,})\.\d+/, legacy: /Edge?\/(1[6-9]|[2-9]\d|\d{3,})\.\d+(\.\d+|)|Firefox\/(5[4-9]|[6-9]\d|\d{3,})\.\d+(\.\d+|)|Chrom(ium|e)\/(5[1-9]|[6-9]\d|\d{3,})\.\d+(\.\d+|)([\d.]+$|.*Safari\/(?![\d.]+ Edge\/[\d.]+$))|(Maci|X1{2}).+ Version\/(10\.\d+|(1[1-9]|[2-9]\d|\d{3,})\.\d+)([,.]\d+|)( \(\w+\)|)( Mobile\/\w+|) Safari\/|Chrome.+OPR\/(3[89]|[4-9]\d|\d{3,})\.\d+\.\d+|(CPU[ +]OS|iPhone[ +]OS|CPU[ +]iPhone|CPU IPhone OS|CPU iPad OS)[ +]+(10[._]\d+|(1[1-9]|[2-9]\d|\d{3,})[._]\d+)([._]\d+|)|Android:?[ /-](13[3-9]|1[4-9]\d|[2-9]\d{2}|\d{4,})(\.\d+|)(\.\d+|)|Mobile Safari.+OPR\/([89]\d|\d{3,})\.\d+\.\d+|Android.+Firefox\/(13[5-9]|1[4-9]\d|[2-9]\d{2}|\d{4,})\.\d+(\.\d+|)|Android.+Chrom(ium|e)\/(13[3-9]|1[4-9]\d|[2-9]\d{2}|\d{4,})\.\d+(\.\d+|)|Android.+(UC? ?Browser|UCWEB|U3)[ /]?(15\.([5-9]|\d{2,})|(1[6-9]|[2-9]\d|\d{3,})\.\d+)\.\d+|SamsungBrowser\/(5\.\d+|([6-9]|\d{2,})\.\d+)|Android.+MQ{2}Browser\/(14(\.(9|\d{2,})|)|(1[5-9]|[2-9]\d|\d{3,})(\.\d+|))(\.\d+|)|K[Aa][Ii]OS\/(3\.\d+|([4-9]|\d{2,})\.\d+)(\.\d+|)/ }).modern, c = l.legacy, (m = navigator.userAgent).match(u) ? "modern" : m.match(c) ? "legacy" : "unknown"), C = "modern" === P ? "modern" : "legacy", _ = (null != n ? n : { modern: "", legacy: "" })[C], O = [(p = { baseUrl: d, hashVersion: r, buildTarget: C }).baseUrl, "/wpm", "/b", p.hashVersion, "modern" === p.buildTarget ? "m" : "l", ".js"].join(""), U = (f = { version: r, bundleTarget: P, surface: e.surface, pageUrl: self.location.href, monorailEndpoint: e.monorailEndpoint }, h = f.version, g = f.bundleTarget, y = f.surface, w = f.pageUrl, v = f.monorailEndpoint, { emit: function (e) { var d = e.status, r = e.errorMsg, n = (new Date).getTime(), o = JSON.stringify({ metadata: { event_sent_at_ms: n }, events: [{ schema_id: "web_pixels_manager_load/3.1", payload: { version: h, bundle_target: g, page_url: w, status: d, surface: y, error_msg: r }, metadata: { event_created_at_ms: n } }] }); if (!v) return console && console.warn && console.warn("[Web Pixels Manager] No Monorail endpoint provided, skipping logging."), !1; try { return self.navigator.sendBeacon.bind(self.navigator)(v, o) } catch (e) { } var a = new XMLHttpRequest; try { return a.open("POST", v, !0), a.setRequestHeader("Content-Type", "text/plain"), a.send(o), !0 } catch (e) { return console && console.warn && console.warn("[Web Pixels Manager] Got an unhandled error while logging to Monorail."), !1 } } }); try { o.browserTarget = P, function (e) { var d = e.src, r = e.async, n = void 0 === r || r, o = e.onload, a = e.onerror, i = e.sri, t = e.scriptDataAttributes, s = void 0 === t ? {} : t, l = document.createElement("script"), u = document.querySelector("head"), c = document.querySelector("body"); if (l.async = n, l.src = d, i && (l.integrity = i, l.crossOrigin = "anonymous"), s) for (var m in s) if (Object.prototype.hasOwnProperty.call(s, m)) try { l.dataset[m] = s[m] } catch (e) { } if (o && l.addEventListener("load", o), a && l.addEventListener("error", a), u) u.appendChild(l); else { if (!c) throw new Error("Did not find a head or body element to append the script"); c.appendChild(l) } }({ src: O, async: !0, onload: function () { if (!function () { var e, d; return Boolean(null == (d = null == (e = window.Shopify) ? void 0 : e.analytics) ? void 0 : d.initialized) }()) { var d = window.webPixelsManager.init(e) || void 0; if (d) { var r = window.Shopify.analytics; r.replayQueue.forEach(function (e) { var r = e[0], n = e[1], o = e[2]; d.publishCustomEvent(r, n, o) }), r.replayQueue = [], r.publish = d.publishCustomEvent, r.visitor = d.visitor, r.initialized = !0 } } }, onerror: function () { return U.emit({ status: "failed", errorMsg: "".concat(O, " has failed to load") }) }, sri: (b = _, S = /^sha384-[A-Za-z0-9+/=]+$/, "string" == typeof b && S.test(b) ? _ : ""), scriptDataAttributes: o }), U.emit({ status: "loading" }) } catch (e) { U.emit({ status: "failed", errorMsg: (null == e ? void 0 : e.message) || "Unknown error" }) } } } }(); wpmLoader({ shopId: 2709520502, storefrontBaseUrl: "https://new-99.myshopify.com", extensionsBaseUrl: "https://extensions.shopifycdn.com/cdn/shopifycloud/web-pixels-manager", monorailEndpoint: "https://monorail-edge.shopifysvc.com/unstable/produce_batch", surface: "storefront-renderer", enabledBetaFlags: ["2dca8a86", "d5bdd5d0", "5acaffe6", "3b3c7daf", "6faea013"], webPixelsConfigList: [{ "id": "974815350", "configuration": "{\"webPixelName\":\"Judge.me\"}", "eventPayloadVersion": "v1", "runtimeContext": "STRICT", "scriptVersion": "34ad157958823915625854214640f0bf", "type": "APP", "apiClientId": 683015, "privacyPurposes": ["ANALYTICS"], "dataSharingAdjustments": { "protectedCustomerApprovalScopes": ["read_customer_email", "read_customer_name", "read_customer_personal_data", "read_customer_phone"], "dataSharingControls": ["share_all_events"] }, "dataSharingState": "unrestricted" }, { "id": "shopify-app-pixel", "configuration": "{}", "eventPayloadVersion": "v1", "runtimeContext": "STRICT", "scriptVersion": "0460", "apiClientId": "shopify-pixel", "type": "APP", "privacyPurposes": ["ANALYTICS", "MARKETING"] }, { "id": "shopify-custom-pixel", "eventPayloadVersion": "v1", "runtimeContext": "LAX", "scriptVersion": "0460", "apiClientId": "shopify-pixel", "type": "CUSTOM", "privacyPurposes": ["ANALYTICS", "MARKETING"] }], isMerchantRequest: true, initData: { "shop": { "name": "New99", "paymentSettings": { "currencyCode": "USD" }, "myshopifyDomain": "new-99.myshopify.com", "countryCode": "FR", "storefrontUrl": "https:\/\/new-99.myshopify.com" }, "customer": null, "cart": { "cost": { "totalAmount": { "amount": 822.0, "currencyCode": "WST" } }, "lines": [{ "cost": { "totalAmount": { "amount": 822.0, "currencyCode": "WST" } }, "merchandise": { "price": { "amount": 274.0, "currencyCode": "WST" }, "product": { "title": "L. u c e product", "vendor": "Vendor L", "id": "6690243313782", "untranslatedTitle": "L. u c e product", "url": "\/products\/12-unique-content-for-each-product-on-the-product-tab", "type": "Type L" }, "id": "39637215084662", "image": { "src": "\/\/new-99.myshopify.com\/cdn\/shop\/products\/handmade-product_21.png?v=1669049656" }, "sku": "111", "title": "red", "untranslatedTitle": "red" }, "quantity": 3 }], "totalQuantity": 3, "attributes": [], "id": "hWNBnngRQvUKU3dSO8RnoCqQ" }, "checkout": null, "productVariants": [], "products": null, "purchasingCompany": null, "page": null }, }, "https://new-99.myshopify.com/cdn", "b2423595wb67a1977p8b578d23m1ed5a501", { "modern": "", "legacy": "" }, { "trekkieShim": true, "pageType": "home", "shopId": "2709520502", "storefrontBaseUrl": "https:\/\/new-99.myshopify.com", "extensionBaseUrl": "https:\/\/extensions.shopifycdn.com\/cdn\/shopifycloud\/web-pixels-manager", "surface": "storefront-renderer", "enabledBetaFlags": "[\"2dca8a86\", \"d5bdd5d0\", \"5acaffe6\", \"3b3c7daf\", \"6faea013\"]", "isMerchantRequest": "true", "hashVersion": "b2423595wb67a1977p8b578d23m1ed5a501", "publish": "custom", "events": "[[\"page_viewed\",{}]]" }); })();</script> --}}
{{-- <script>
  window.ShopifyAnalytics = window.ShopifyAnalytics || {};
  window.ShopifyAnalytics.meta = window.ShopifyAnalytics.meta || {};
  window.ShopifyAnalytics.meta.currency = 'WST';
  var meta = { "page": { "pageType": "home", "requestId": "a3906b97-b533-4f55-b6bf-a32fd56e4507-1780057523" } };
  for (var attr in meta) {
      window.ShopifyAnalytics.meta[attr] = meta[attr];
  }
</script> --}}
{{-- <script class="analytics">
  (function () {
      var customDocumentWrite = function (content) {
          var jquery = null;

          if (window.jQuery) {
              jquery = window.jQuery;
          } else if (window.Checkout && window.Checkout.$) {
              jquery = window.Checkout.$;
          }

          if (jquery) {
              jquery('body').append(content);
          }
      };

      var hasLoggedConversion = function (token) {
          if (token) {
              return document.cookie.indexOf('loggedConversion=' + token) !== -1;
          }
          return false;
      }

      var setCookieIfConversion = function (token) {
          if (token) {
              var twoMonthsFromNow = new Date(Date.now());
              twoMonthsFromNow.setMonth(twoMonthsFromNow.getMonth() + 2);

              document.cookie = 'loggedConversion=' + token + '; expires=' + twoMonthsFromNow;
          }
      }

      var trekkie = window.ShopifyAnalytics.lib = window.trekkie = window.trekkie || [];
      window.ShopifyAnalytics.lib.trekkie = window.trekkie;
      if (trekkie.integrations) {
          return;
      }
      trekkie.methods = [
          'identify',
          'page',
          'ready',
          'track',
          'trackForm',
          'trackLink'
      ];
      trekkie.factory = function (method) {
          return function () {
              var args = Array.prototype.slice.call(arguments);
              args.unshift(method);
              trekkie.push(args);
              if (window.__TREKKIE_SHIM_QUEUE && (method == 'track' || method == 'page')) {
                  try {
                      window.__TREKKIE_SHIM_QUEUE.push({
                          from: 'trekkie-stub',
                          method: method,
                          args: args.slice(1)
                      });
                  } catch (e) {
                      // no-op
                  }
              }
              return trekkie;
          };
      };
      for (var i = 0; i < trekkie.methods.length; i++) {
          var key = trekkie.methods[i];
          trekkie[key] = trekkie.factory(key);
      }
      trekkie.load = function (config) {
          trekkie.config = config || {};
          trekkie.config.initialDocumentCookie = document.cookie;
          var first = document.getElementsByTagName('script')[0];
          var script = document.createElement('script');
          script.type = 'text/javascript';
          script.onerror = function (e) {
              var scriptFallback = document.createElement('script');
              scriptFallback.type = 'text/javascript';
              scriptFallback.onerror = function (error) {
                  var Monorail = {
                      produce: function produce(monorailDomain, schemaId, payload) {
                          var currentMs = new Date().getTime();
                          var event = {
                              schema_id: schemaId,
                              payload: payload,
                              metadata: {
                                  event_created_at_ms: currentMs,
                                  event_sent_at_ms: currentMs
                              }
                          };
                          return Monorail.sendRequest("https://" + monorailDomain + "/v1/produce", JSON.stringify(event));
                      },
                      sendRequest: function sendRequest(endpointUrl, payload) {
                          // Try the sendBeacon API
                          if (window && window.navigator && typeof window.navigator.sendBeacon === 'function' && typeof window.Blob === 'function' && !Monorail.isIos12()) {
                              var blobData = new window.Blob([payload], {
                                  type: 'text/plain'
                              });

                              if (window.navigator.sendBeacon(endpointUrl, blobData)) {
                                  return true;
                              } // sendBeacon was not successful

                          } // XHR beacon

                          var xhr = new XMLHttpRequest();

                          try {
                              xhr.open('POST', endpointUrl);
                              xhr.setRequestHeader('Content-Type', 'text/plain');
                              xhr.send(payload);
                          } catch (e) {
                              console.log(e);
                          }

                          return false;
                      },
                      isIos12: function isIos12() {
                          return window.navigator.userAgent.lastIndexOf('iPhone; CPU iPhone OS 12_') !== -1 || window.navigator.userAgent.lastIndexOf('iPad; CPU OS 12_') !== -1;
                      }
                  };
                  Monorail.produce('monorail-edge.shopifysvc.com',
                      'trekkie_storefront_load_errors/1.1',
                      {
                          shop_id: 2709520502,
                          theme_id: 123293991030,
                          app_name: "storefront",
                          context_url: window.location.href,
                          source_url: "{{asset('client/s/trekkie.storefront.f7140b8b25ae1195cf346a36a85e3e4bcf46adb3.min.js')}}"
                      });

              };
              scriptFallback.async = true;
              scriptFallback.src = '{{asset('client/s/trekkie.storefront.f7140b8b25ae1195cf346a36a85e3e4bcf46adb3.min.js')}}';
              first.parentNode.insertBefore(scriptFallback, first);
          };
          script.async = true;
          script.src = '{{asset('client/s/trekkie.storefront.f7140b8b25ae1195cf346a36a85e3e4bcf46adb3.min.js')}}';
          first.parentNode.insertBefore(script, first);

      };
      trekkie.load(
          { "Trekkie": { "appName": "storefront", "development": false, "defaultAttributes": { "shopId": 2709520502, "isMerchantRequest": null, "themeId": 123293991030, "themeCityHash": "13725321150530310603", "contentLanguage": "en", "currency": "WST", "eventMetadataId": "507a60b8-22d4-46d3-a7fd-6423edc2d1a2" }, "isServerSideCookieWritingEnabled": true, "monorailRegion": "shop_domain", "enabledBetaFlags": ["b5387b81", "d5bdd5d0"] }, "Session Attribution": {}, "S2S": { "facebookCapiEnabled": false, "source": "trekkie-storefront-renderer", "apiClientId": 580111 } }
      );

      var loaded = false;
      trekkie.ready(function () {
          if (loaded) return;
          loaded = true;

          window.ShopifyAnalytics.lib = window.trekkie;

          var originalDocumentWrite = document.write;
          document.write = customDocumentWrite;
          try { window.ShopifyAnalytics.merchantGoogleAnalytics.call(this); } catch (error) { };
          document.write = originalDocumentWrite;

          window.ShopifyAnalytics.lib.page(null, { "pageType": "home", "requestId": "a3906b97-b533-4f55-b6bf-a32fd56e4507-1780057523", "shopifyEmitted": true });

          var match = window.location.pathname.match(/checkouts\/(.+)\/(thank_you|post_purchase)/)
          var token = match ? match[1] : undefined;
          if (!hasLoggedConversion(token)) {
              setCookieIfConversion(token);

          }
      });

      var eventsListenerScript = document.createElement('script');
      eventsListenerScript.async = true;
      eventsListenerScript.src = "{{asset('client/shopifycloud/storefront/assets/shop_events_listener-4e26a9ce.js')}}";
      document.getElementsByTagName('head')[0].appendChild(eventsListenerScript);
  })();</script>
<script defer src="{{asset('shopifycloud/perf-kit/shopify-perf-kit-3.5.0.min.js')}}"
  data-application="storefront-renderer" data-shop-id="2709520502" data-render-region="gcp-us-east1"
  data-page-type="index" data-theme-instance-id="123293991030" data-theme-name="New99" data-theme-version="2.0.4"
  data-monorail-region="shop_domain" data-resource-timing-sampling-rate="10" data-shs="true"
  data-shs-beacon="true" data-shs-export-with-fetch="true" data-shs-logs-sample-rate="1"
  data-shs-beacon-endpoint="https://new-99.myshopify.com/api/collect"></script>


 --}}


<!-- END QUICKVIEW PRODUCT -->


<script defer="defer">
    $('[data-countdown].product-grid-countdown').each(function () {
        var $this = $(this), finalDate = $(this).data('countdown');
        $this.countdown(finalDate, function (event) {
            $this.html(event.strftime('<div class="single-countdown"><span class="single-countdown__time">%D</span><span class="single-countdown__text">Days</span></div><div class="single-countdown"><span class="single-countdown__time">%H</span><span class="single-countdown__text">Hours</span></div><div class="single-countdown"><span class="single-countdown__time">%M</span><span class="single-countdown__text">Mins</span></div><div class="single-countdown"><span class="single-countdown__time">%S</span><span class="single-countdown__text">Secs</span></div>'));
        });
    });
</script>


{{-- <script src="{{asset('client/s/javascripts/currencies.js')}}"></script> --}}
{{-- <script
    src="{{asset('client/shop/t/89/assets/jquery.currencies.min.js?v=35452912321688254271701360555')}}"></script> --}}


<script defer="defer">
    /* --------------------------------------------------------
              34. scrollUp active
          -------------------------------------------------------- */
    $.scrollUp({
        scrollText: '<i class="fa fa-angle-up"></i>',
        easingType: 'linear',
        scrollSpeed: 900,
        animation: 'fade'
    });
</script>


<script src="{{ asset('notiflix/notiflix-3.2.8.min.js') }}"></script>
<script src="{{asset('js/custom.js')}}"></script>
{{-- <script src="{{asset('client/s/files/1/0644/2975/2553/t/2/assets/arist.js')}}" type="text/javascript"></script> --}}

<script>
      Notiflix.Confirm.init({
            width: '500px',
            titleFontSize: '22px',
            messageFontSize: '15px',
            backOverlay: true,
            plainText: false,
            messageMaxLength: 255,
            titleMaxLength: 50,
            titleColor: '#071c1f',
            okButtonBackground: '#f8796c',
            cancelButtonBackground: '#071c1f',
            clickToClose: true,
            borderRadius: '5px',
        });

        Notiflix.Loading.init({
            svgColor: '#f8796c',
        
        });
</script>


<script>
   // ==================== MINI CART FUNCTIONS ====================



// Add to cart from anywhere (with mini cart update)
function addToCart(productId, quantity = 1) {
    Notiflix.Loading.pulse('Adding to cart...');
    
    $.ajax({
        url: '{{ route("shop.cart.add") }}',
        type: 'POST',
        data: {
            product_id: productId,
            quantity: quantity,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            Notiflix.Loading.remove();
            if (response.status === 'success') {
                Notiflix.Notify.success(response.message || 'Product added to cart!');
                // Update mini cart
                refreshMiniCart();
                updateCartCount();
            } else {
                Notiflix.Notify.warning(response.message || 'Failed to add to cart');
            }
        },
        error: function(xhr) {
            Notiflix.Loading.remove();
            if (xhr.status === 422) {
                var errors = xhr.responseJSON.errors;
                var firstError = Object.values(errors)[0];
                Notiflix.Notify.warning(firstError[0] || 'Validation error');
            } else if (xhr.status === 400) {
                Notiflix.Notify.warning(xhr.responseJSON.message || 'Product is not available');
            } else {
                Notiflix.Notify.failure('An error occurred while adding to cart');
            }
        }
    });
}

// Remove from cart (mini cart version)
function removeFromCartMini(productId, rowId) {
  
    showLoadingNotiflix('ltn__utilize-cart-menu', 'Removing item from cart...');            
            $.ajax({
                url: '{{ route("shop.cart.remove") }}',
                type: 'DELETE',
                data: {
                    product_id: productId,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    Notiflix.Loading.remove();
                    if (response.status === 'success') {
                        Notiflix.Notify.success('Item removed from cart');
                        // Remove item from mini cart UI
                        $('#mini-cart-item-' + rowId).fadeOut(300, function() {
                            $(this).remove();
                            // Check if cart is empty
                            checkMiniCartEmpty();
                        });
                        updateCartCount();
                        // Update subtotal
                        updateMiniCartTotal();
                        hideLoadingNotiflix('ltn__utilize-cart-menu');

                    } else {
                        Notiflix.Notify.warning(response.message || 'Failed to remove item');
                    }
                },
                error: function() {
                    hideLoadingNotiflix('ltn__utilize-cart-menu');
                    Notiflix.Notify.failure('An error occurred');
                }
            });
}

// Remove from cart (full page version)
function removeFromCart(productId) {
    Notiflix.Confirm.show(
        'Remove Item',
        'Are you sure you want to remove this item from cart?',
        'Yes',
        'Cancel',
        function() {
            Notiflix.Loading.pulse('Removing item...');
            
            $.ajax({
                url: '{{ route("shop.cart.remove") }}',
                type: 'DELETE',
                data: {
                    product_id: productId,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    Notiflix.Loading.remove();
                    if (response.status === 'success') {
                        Notiflix.Notify.success('Item removed from cart');
                        // Reload page to update cart display
                        location.reload();
                    } else {
                        Notiflix.Notify.warning(response.message || 'Failed to remove item');
                    }
                },
                error: function() {
                    Notiflix.Loading.remove();
                    Notiflix.Notify.failure('An error occurred');
                }
            });
        }
    );
}

// Refresh mini cart content
function refreshMiniCart() {
    $.ajax({
        url: '{{ route("shop.cart.items") }}',
        type: 'GET',
        success: function(response) {
            if (response.status === 'success' && response.items) {
                // Clear existing items
                $('#miniCartItems .mini-cart-item').remove();
                
                // Add new items
                var itemsHtml = '';
                $.each(response.items, function(index, item) {
                    itemsHtml += `
                        <div class="mini-cart-item clearfix" id="mini-cart-item-${item.rowId}">
                            <div class="mini-cart-img">
                                <a href="${item.options.product_slug ? '/shop/product/' + item.options.product_slug : '#'}">
                                    <img src="${item.options.image || '/images/default-product.jpg'}" alt="${item.name}">
                                </a>
                                <span onclick="removeFromCartMini('${item.id}', '${item.rowId}')" class="mini-cart-item-delete">
                                    <i class="icon-cancel"></i>
                                </span>
                            </div>
                            <div class="mini-cart-info">
                                <h6>
                                    <a href="${item.options.product_slug ? '/shop/product/' + item.options.product_slug : '#'}">
                                        ${item.name}
                                        ${item.options.variant ? ' - ' + item.options.variant : ''}
                                    </a>
                                </h6>
                                <span class="mini-cart-quantity">
                                    ${item.qty} x GH₵${parseFloat(item.price).toFixed(2)}
                                </span>
                            </div>
                        </div>
                    `;
                });
                
                $('#miniCartItems').append(itemsHtml);
                
                // Update totals
                $('#miniCartSubtotal').text('GH₵' + response.subtotal);
                
                // Check if cart is empty
                checkMiniCartEmpty();
            }
        }
    });
}

// Check if mini cart is empty
function checkMiniCartEmpty() {
    var itemCount = $('#miniCartItems .mini-cart-item').length;
    
    if (itemCount === 0) {
        $('#miniCartItems').hide();
        $('#miniCartFooter').hide();
        $('#miniCartEmpty').show();
    } else {
        $('#miniCartItems').show();
        $('#miniCartFooter').show();
        $('#miniCartEmpty').hide();
    }
}

// Update mini cart total
function updateMiniCartTotal() {
    $.ajax({
        url: '{{ route("shop.cart.total") }}',
        type: 'GET',
        success: function(response) {
            if (response.total !== undefined) {
                $('#miniCartSubtotal').text('GH₵' + parseFloat(response.total).toFixed(2));
            }
        }
    });
}

// Update cart count in header
function updateCartCount() {
    $.ajax({
        url: '{{ route("shop.cart.count") }}',
        type: 'GET',
        success: function(response) {
            if (response.count !== undefined) {

                
                document.querySelectorAll('[data-cart="count"]').forEach(element => {
                    element.textContent = response.count;
                });
               
            }
        }
    });
}

// Add to cart from quick view
function addToCartFromQuickView() {
    var productId = $('#add-item-qv').data('product-id');
    var quantity = parseInt($('#quickViewQuantity').val()) || 1;
    
    if (!productId) {
        Notiflix.Notify.warning('Product not found');
        return;
    }
    
    addToCart(productId, quantity);
    
    setTimeout(function() {
        $('#quickViewModal').modal('hide');
    }, 1000);
}

// Apply coupon (for mini cart or full page)
function applyCoupon() {
    var couponCode = $('#coupon_code').val();
    
    if (!couponCode) {
        Notiflix.Notify.warning('Please enter a coupon code');
        return;
    }
    
    Notiflix.Loading.pulse('Applying coupon...');
    
    $.ajax({
        url: '{{ route("shop.cart.coupon.apply") }}',
        type: 'POST',
        data: {
            code: couponCode,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            Notiflix.Loading.remove();
            if (response.status === 'success') {
                Notiflix.Notify.success('Coupon applied successfully!');
                location.reload();
            } else {
                Notiflix.Notify.warning(response.message || 'Failed to apply coupon');
            }
        },
        error: function(xhr) {
            Notiflix.Loading.remove();
            if (xhr.status === 404) {
                Notiflix.Notify.warning(xhr.responseJSON?.message || 'Invalid coupon code');
            } else {
                Notiflix.Notify.failure('An error occurred');
            }
        }
    });
}

// Remove coupon
function removeCoupon() {
    Notiflix.Confirm.show(
        'Remove Coupon',
        'Are you sure you want to remove this coupon?',
        'Yes',
        'Cancel',
        function() {
            Notiflix.Loading.pulse('Removing coupon...');
            
            $.ajax({
                url: '{{ route("shop.cart.coupon.remove") }}',
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    Notiflix.Loading.remove();
                    if (response.status === 'success') {
                        Notiflix.Notify.success('Coupon removed');
                        location.reload();
                    } else {
                        Notiflix.Notify.warning(response.message || 'Failed to remove coupon');
                    }
                },
                error: function() {
                    Notiflix.Loading.remove();
                    Notiflix.Notify.failure('An error occurred');
                }
            });
        }
    );
}

// ==================== MINI CART TOGGLE ====================

// Initialize mini cart toggle
$(document).ready(function() {
    // Toggle mini cart
    $('.ltn__utilize-toggle').on('click', function(e) {
        e.preventDefault();
        var target = $(this).attr('href');
        $(target).toggleClass('ltn__utilize-open');
        $('body').toggleClass('ltn__utilize-open');
    });
    
    // Close mini cart
    $('.ltn__utilize-close').on('click', function() {
        var target = $(this).closest('.ltn__utilize');
        target.removeClass('ltn__utilize-open');
        $('body').removeClass('ltn__utilize-open');
    });
    
    // Close mini cart on overlay click
    $(document).on('click', function(e) {
        if ($('.ltn__utilize-open').length > 0) {
            if (!$(e.target).closest('.ltn__utilize').length && !$(e.target).closest('.ltn__utilize-toggle').length) {
                $('.ltn__utilize').removeClass('ltn__utilize-open');
                $('body').removeClass('ltn__utilize-open');
            }
        }
    });
});

// ==================== ADDITIONAL CART ROUTES ====================

// Add this to your routes if not already present
// Route::get('/cart/total', [ShopCartController::class, 'getTotal'])->name('cart.total');

// Add this method to ShopCartController:
/*
public function getTotal()
{
    return response()->json([
        'total' => $this->getCartTotalAfterDiscount()
    ]);
}
*/ 
</script>