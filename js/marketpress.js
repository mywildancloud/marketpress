let MarketPress, MarketPress_Product, MarketPress_Basket, MarketPress_Cart, MarketPress_Checkout, MarketPress_Cart_Customer, MarketPress_Thanks;

MarketPress = {
    init: function() {
        this.showHideSearch();
        this.showHidePrimaryMenu();
        this.showHideSecondaryMenu();
        this.showHideSubMenu();
        this.openCloseToc();
        this.shortProduct();
        this.resizeThumbnail();
        this.changeThumbnail();
        this.slideCategory();
        this.storeSlideCategory();
        this.countDown();
        this.basketIcon();
        this.copyLink();
        this.galerySmall();
        this.storeSearchBox();
        MarketPress_Product.init();
        MarketPress_Basket.init();
        MarketPress_Checkout.init();
        MarketPress_Thanks.init();
    },
    storeSlideCategory: function() {
        let $box = document.getElementById('slide-category-store');
        if (typeof($box) != 'undefined' && $box != null) {
            let $canvas = document.querySelector('.slide-category-store-canvas');
            document.body.addEventListener('click', function(event) {
                console.log(event);
                if (event.target.className == 'seemore') {
                    $canvas.style.right = '0px';
                } else {
                    $canvas.style.right = '-360px';
                }
            });

        }
    },
    storeSearchBox: function() {
        let $box = document.getElementById('store-searchbox');
        if (typeof($box) != 'undefined' && $box != null) {
            let $input = $box.querySelector('input[name="s"'),
                $result = $box.querySelector('.result');

            $input.addEventListener('keyup', function() {
                let $i = $box.querySelector('i');
                if (this.value) {
                    $i.classList.remove('lni-search-alt');
                    $i.classList.add('lni-close');

                    $i.addEventListener('click', function() {
                        $input.value = '';

                        $i.classList.add('lni-search-alt');
                        $i.classList.remove('lni-close');

                        $result.querySelector('.resultbox').innerHTML = '';
                        $result.style.display = 'none';
                    });

                    $data = {
                        nonce: marketpress.nonce,
                        s: this.value
                    }

                    fetch(marketpress.ajax_url + '?action=search_product', {
                            method: 'POST',
                            body: JSON.stringify($data)
                        }).then((respons) => respons.json())
                        .then(function(json) {
                            let data = json.data,
                                template = '';

                            for (let i = 0; data.length > i; i++) {

                                template += '<a href="' + data[i].permalink + '"><div class="resultin"><div class="thumbnailer"><img src="' + data[i].thumbnail_url + '" /></div><div class="contener"><div class="titler">' + data[i].title + '</div><div class="categorier">' + data[i].category + '</div></div></div></a>';

                            }

                            $result.querySelector('.resultbox').innerHTML = template;
                            $result.querySelector('label').innerHTML = json.message;
                            $result.style.display = 'block';
                        })
                        .catch(function(error) {
                            console.log(error);
                        });


                } else {
                    $i.classList.add('lni-search-alt');
                    $i.classList.remove('lni-close');

                    $result.querySelector('.resultbox').innerHTML = '';
                    $result.style.display = 'none';
                }
            })
        }

    },
    galerySmall: function() {
        let $box = document.getElementById('galery-scroll');
        if (typeof($box) != 'undefined' && $box != null) {
            let $scrollbox = $box.querySelector('.galerysmall'),
                $left = $box.querySelector('.arrow-left'),
                $right = $box.querySelector('.arrow-right'),
                $scroll_width = $scrollbox.scrollWidth,
                $scroll_left = $scrollbox.scrollLeft;

            $right.addEventListener('click', function() {
                $scrollbox.scrollTo({
                    top: 0,
                    left: $scroll_left += 210,
                    behavior: 'smooth',
                });
                if ($scroll_left >= $scroll_width) {
                    $scroll_left = $scroll_width;
                    this.style.display = 'none';
                } else {
                    this.style.display = 'block';
                }
                $left.style.display = 'block';

            });

            $left.addEventListener('click', function() {
                $scrollbox.scrollTo({
                    top: 0,
                    left: $scroll_left -= 200,
                    behavior: 'smooth',
                });
                if ($scroll_left <= 0) {
                    $scroll_left = 0;
                    this.style.display = 'none';
                } else {
                    this.style.display = 'block';
                }
                $right.style.display = 'block';
            });

        }
    },
    copyLink: function() {
        let $links = document.querySelectorAll('.copylink');
        for (let i = 0; i < $links.length; i++) {
            $links[i].onclick = function(e) {
                let link = this.href;
                let input = document.createElement('input');
                input.setAttribute('value', link);
                document.body.appendChild(input);
                input.select();
                document.execCommand('copy');
                document.body.removeChild(input);
                e.preventDefault();
            }
        }
    },
    basketIcon: function() {
        let $box = document.querySelector('#basket-menu-toggle');

        if (typeof($box) != 'undefined' && $box != null) {
            let $items = MarketPress_Cart_Items.get();

            if ($items.length > 0) {
                let $count = 0;
                for (let i = 0; i < $items.length; i++) {
                    $count = $count + parseInt($items[i].quantity);
                }

                $box.querySelector('.basket-counter').innerHTML = $count;

                $box.onclick = function() {
                    location.href = marketpress.site_url + '/checkout/';
                }
            }

        }
    },
    countDown: function() {
        let $countdowns = document.querySelectorAll('.countdown');

        for (let i = 0; i < $countdowns.length; i++) {

            const dataEndTime = $countdowns[i].getAttribute('data-end');
            if (dataEndTime) {
                const end = new Date(dataEndTime);

                initializeClock($countdowns[i], end);
            }
        }

        function getTimeRemaining(endtime) {
            const total = Date.parse(endtime) - Date.parse(new Date());
            const seconds = Math.floor((total / 1000) % 60);
            const minutes = Math.floor((total / 1000 / 60) % 60);
            const hours = Math.floor((total / (1000 * 60 * 60)) % 24);
            const days = Math.floor(total / (1000 * 60 * 60 * 24));

            return {
                total,
                days,
                hours,
                minutes,
                seconds
            };
        }

        function initializeClock(clock, endtime) {
            const daysSpan = clock.querySelector('.days');
            const hoursSpan = clock.querySelector('.hours');
            const minutesSpan = clock.querySelector('.minutes');
            const secondsSpan = clock.querySelector('.seconds');

            function updateClock() {
                const t = getTimeRemaining(endtime);

                const h = ('0' + t.hours).slice(-2);
                const m = ('0' + t.minutes).slice(-2);
                const s = ('0' + t.seconds).slice(-2);

                daysSpan.innerHTML = t.days + ' : ';
                hoursSpan.innerHTML = h;
                minutesSpan.innerHTML = m;
                secondsSpan.innerHTML = s;

                if (t.total <= 0) {
                    clearInterval(timeinterval);
                }

                if (t.days <= 0) {
                    daysSpan.style.display = 'none';
                }

                let d = new Date;
                let value = h + ':' + m + ':' + s;

                d.setTime(d.getTime() + 24 * 60 * 60 * 1000 * 30);
                document.cookie = "marketpress_evergreen_scarcity=" + value + ";path=/;expires=" + d.toGMTString();
            }

            updateClock();
            const timeinterval = setInterval(updateClock, 1000);
        }
    },
    slideCategory: function() {
        let $box = document.getElementById('category-slide');
        if (typeof($box) != 'undefined' && $box != null) {
            let $scrollbox = $box.querySelector('ul'),
                $scroller = $box.querySelector('.scrollbar'),
                $scroller_inner_width = 0,
                $left = $box.querySelector('.arrow-left'),
                $right = $box.querySelector('.arrow-right'),
                $scroll_width = $scrollbox.scrollWidth,
                $scroll_left = $scrollbox.scrollLeft;


            $scroller_inner_width = (parseInt($scrollbox.clientWidth) * 100) / parseInt($scrollbox.scrollWidth);

            $scroller.querySelector('.scrollbarinner').style.width = $scroller_inner_width + '%';

            $scrollbox.addEventListener('scroll', function(ev) {
                let $scrollLeft = (parseInt(ev.target.scrollLeft) * 100) / parseInt($scrollbox.scrollWidth);
                $scroller.querySelector('.scrollbarinner').style.marginLeft = $scrollLeft + '%';
            });

            if ($scroll_width <= $scrollbox.clientWidth) {
                $right.style.display = 'none';
            }

            $scroll_width = parseInt($scroll_width) - parseInt($scrollbox.clientWidth);

            $right.addEventListener('click', function() {
                $scrollbox.scrollTo({
                    top: 0,
                    left: $scroll_left += 210,
                    behavior: 'smooth',
                });
                if ($scroll_left >= $scroll_width) {
                    $scroll_left = $scroll_width;
                    this.style.display = 'none';
                } else {
                    this.style.display = 'block';
                }
                $left.style.display = 'block';

            });

            $left.addEventListener('click', function() {
                $scrollbox.scrollTo({
                    top: 0,
                    left: $scroll_left -= 200,
                    behavior: 'smooth',
                });
                if ($scroll_left <= 0) {
                    $scroll_left = 0;
                    this.style.display = 'none';
                } else {
                    this.style.display = 'block';
                }
                $right.style.display = 'block';
            });

        }
    },
    resizeThumbnail: function() {
        let $images = document.querySelectorAll('.image-thumbnail');
        for (var i = 0, length = $images.length; i < length; i++) {
            let iheight = $images[i].offsetWidth / 2;
            $images[i].style.height = iheight + 'px';
        }
    },
    changeThumbnail: function() {
        let $galeries = document.querySelectorAll('.galerysmallbox');
        for (var i = 0; i < $galeries.length; i++) {
            $galeries[i].onclick = function() {
                let $image_url = this.getAttribute('data-image');
                document.getElementById('biggalery').style.backgroundImage = 'url(' + $image_url + ')';
                return;
            }
        }
    },
    shortProduct: function() {
        let $select = document.getElementById('product-short');
        if (typeof($select) != 'undefined' && $select !== null) {
            $select.onchange = function() {
                window.location.replace(this.value);
            }
        }
    },
    openCloseToc: function() {
        let tocToggle = document.getElementById('toc-toggle');
        if (typeof(tocToggle) != 'undefined' && tocToggle !== null) {
            let parent = tocToggle.parentNode.parentNode,
                tocList = parent.querySelector('#toc-list');
            tocToggle.onclick = function() {
                if (-1 !== tocToggle.className.indexOf('close')) {
                    tocToggle.classList.remove('close');
                    tocList.classList.remove('hide');
                    tocToggle.innerHTML = 'Close';
                } else {
                    tocToggle.classList.add('close');
                    tocToggle.innerHTML = 'Open';
                    tocList.classList.add('hide');
                }
            }
        }
    },
    showHideSearch: function() {
        let $icon = document.getElementById('search-toggle'),
            $search = document.querySelector('.search-form');

        if (typeof($icon) != 'undefined' && $icon != null) {
            $icon.onclick = function(event) {
                if (-1 !== $search.className.indexOf('show')) {
                    $search.classList.remove('show');
                } else {
                    $search.classList.add('show');
                }
                event.stopPropagation();
            }
        }

        if (typeof($search) != 'undefined' && $search != null) {
            let $closeIcon = $search.querySelector('.close');
            $closeIcon.onclick = function(event) {
                if (-1 !== $search.className.indexOf('show')) {
                    $search.classList.remove('show');
                } else {
                    $search.classList.add('show');
                }
                event.stopPropagation();
            }
        }
    },
    showHideSecondaryMenu: function() {
        let $icon = document.getElementById('secondary-menu-toggle'),
            $menu = document.querySelector('.secondary-menu');
        if (typeof($icon) != 'undefined' && $icon != null) {
            $icon.onclick = function(event) {
                if (-1 !== $menu.className.indexOf('show')) {
                    $menu.classList.remove('show');
                } else {
                    $menu.classList.add('show');
                }
                event.stopPropagation();
            }
        }
    },
    showHidePrimaryMenu: function() {
        let $icon = document.getElementById('primary-menu-toggle'),
            $menu = document.querySelector('.primary-menu');
        if (typeof($icon) != 'undefined' && $icon != null) {
            $icon.onclick = function(event) {
                if (-1 !== $menu.className.indexOf('show')) {
                    $menu.classList.remove('show');
                    $icon.classList.remove('is-open');
                } else {
                    $menu.classList.add('show');
                    $icon.classList.add('is-open');
                }
                event.stopPropagation();
            }
        }
    },
    showHideSubMenu: function() {
        let navMenuLiHasChild = document.querySelectorAll('.menu-item-has-children');
        for (var i = 0, length = navMenuLiHasChild.length; i < length; i++) {
            let children = navMenuLiHasChild[i].querySelector('.sub-menu');
            navMenuLiHasChild[i].addEventListener(
                'click',
                function(event) {
                    if (-1 !== children.className.indexOf('show')) {
                        children.classList.remove('show');
                    } else {
                        children.classList.add('show');
                    }
                    event.stopPropagation();
                },
                false
            );
        }
    }
}

MarketPress_Product = {
    storage: 'marketpress_product',
    el: document.querySelector('.product'),
    init: function() {
        let $product = null;
        if (localStorage.getItem(this.storage)) {
            $product = JSON.parse(localStorage.getItem(this.storage));
        }

        if ($product !== null) {
            this.variation1Changer();
            this.variation2Changer();
            this.quantityChanger();
            this.addToCart();
            this.singleAddToCart();
            this.galerybig();
        }
    },
    galerybig: function() {
        let $el = this.el.querySelector('.galerybig');

        if (typeof($el) != 'undefined' && $el != null) {
            let $width = $el.offsetWidth;
            $el.style.height = $width + 'px';
        }

    },
    update: function($key, $val) {
        let $product = JSON.parse(localStorage.getItem(this.storage)),
            $product_key = '',
            $variation1 = '',
            $variation2 = '';

        $product_key = $product.id;

        if ($key == 'quantity') {
            $product.quantity = $val;
        }
        if ($key == 'variation1') {
            $product.variation_1_value = $val;
        }
        if ($key == 'variation2') {
            $product.variation_2_value = $val;
        }
        if ($key == 'note') {
            $product.note = $val;
        }
        if ($key == 'price') {
            $product.price = $val;
        }
        $variation1 = $product.variation_1_value;
        $variation2 = $product.variation_2_value;
        if ($variation1) {
            $variation1 = $variation1.toLowerCase();
            $variation1 = $variation1.replace(/ /g, '-');
            $product_key += $variation1;
        }

        if ($variation2) {
            $variation2 = $variation2.toLowerCase();
            $variation2 = $variation2.replace(/ /g, '-');
            $product_key += $variation2;
        }

        $product.key = $product_key;
        localStorage.setItem(this.storage, JSON.stringify($product));
    },
    variation1Changer: function() {
        let $options = this.el.querySelectorAll('.variation1 .variation-radio');

        for (var i = 0, length = $options.length; i < length; i++) {
            $options[i].onclick = function() {
                let $value = this.getAttribute('data-value');
                MarketPress_Product.update('variation1', $value);
            }
        }
    },
    variation2Changer: function() {
        let $options = this.el.querySelectorAll('.variation2 .variation-radio');

        for (var i = 0; i < $options.length; i++) {
            $options[i].onclick = function() {
                let $value = this.getAttribute('data-value'),
                    $price = this.getAttribute('data-price');
                MarketPress_Product.update('variation2', $value);
                MarketPress_Product.update('price', parseInt($price));
                MarketPress_Product.showPrice();
                MarketPress_Product.countTotal();
                MarketPress_Cart_Items.countSubTotal();
            }
        }
    },
    quantityChanger: function() {
        let $el = this.el.querySelector('.quantity-changer'),
            $input = $el.querySelector('input'),
            $plus = $el.querySelector('.plus'),
            $minus = $el.querySelector('.minus');

        $minus.onclick = function() {
            if ($input.value > 1) {
                $input.value--;
                MarketPress_Product.update('quantity', $input.value);
                MarketPress_Product.countTotal();
            }
        }
        $plus.onclick = function() {
            $input.value++;
            MarketPress_Product.update('quantity', $input.value);
            MarketPress_Product.countTotal();
            MarketPress_Cart_Items.countSubTotal();
        }
    },
    countTotal: function() {
        let $product = JSON.parse(localStorage.getItem(this.storage)),
            $price = 0;

        $price = parseInt($product.price);
        $product.total = $price * parseInt($product.quantity);
        localStorage.setItem(this.storage, JSON.stringify($product));
    },
    addToCart: function() {
        let $button = this.el.querySelector('.atc');
        $button.onclick = function() {
            let $product = JSON.parse(localStorage.getItem(MarketPress_Product.storage));
            MarketPress_Cart_Items.insert($product);
            MarketPress_Basket.init();
            MarketPress.basketIcon();
            // document.querySelector('#basket').scrollIntoView({
            //     behavior: 'smooth'
            // });
            MarketPress_Product.showAlert();
        }
    },
    showAlert: function() {
        let $el = this.el.querySelector('#atc-success');

        if (typeof($el) != 'undefined' && $el != null) {
            $el.style.display = 'block';
            setTimeout(function() {
                $el.style.display = 'none';
            }, 800)
        }

    },
    singleAddToCart: function() {
        let $button = this.el.querySelector('.buynow');
        $button.onclick = function() {
            let $product = JSON.parse(localStorage.getItem(MarketPress_Product.storage));
            //MarketPress_Cart_Items.empty();
            MarketPress_Cart_Items.insert($product);
            window.location.href = marketpress.site_url + '/checkout/';
        }
    },
    showPrice: function() {
        let $product = JSON.parse(localStorage.getItem(this.storage));
        this.el.querySelector('.price').innerHTML = marketpress.currency.format($product.price);
    }
}
MarketPress_Basket = {
    el: document.querySelector('#basket'),
    init: function() {
        let $items = MarketPress_Cart_Items.get();

        if (typeof(this.el) != 'undefined' && this.el != null) {

            if ($items.length > 0) {
                this.show($items);
            } else {
                this.hide();
            }
        }
    },
    show: function($items) {

        let $count = 0;
        let $total = 0;
        for (let i = 0; i < $items.length; i++) {
            $count = $count + parseInt($items[i].quantity);
            let price = parseInt($items[i].quantity) * parseInt($items[i].price);
            $total = $total + price;
        }

        this.el.querySelector('.sumary-total').innerHTML = marketpress.currency.format($total);
        this.el.querySelector('.sumary-item-total').innerHTML = $count + ' item dalam keranjang';

        this.el.style.display = 'block';
        this.el.querySelector('.basket-button').onclick = function() {
            location.href = marketpress.site_url + '/checkout/';
        }
        if (screen.width <= 414) {
            document.body.style.paddingBottom = '90px';
        }
    },
    hide: function() {
        // this.el.querySelector('.basket-counter').innerHTML = 0;
        // this.el.style.display = 'hide';
    }
}
MarketPress_Cart = {

}
MarketPress_Checkout = {
    el: document.querySelector('#checkout'),
    init: function() {
        if (typeof(this.el) != 'undefined' && this.el != null) {
            this.loadItems();
        }
    },
    loadItems: function() {
        let $templateItem = document.getElementById('checkout-item-template').innerHTML,
            $items = [],
            $template = '',
            $checkout_items = '',
            $variations = '',
            $total_price_stik = 0;

        $items = MarketPress_Cart_Items.get();
        if ($items.length > 0) {
            $items.forEach((item, i, object) => {
                $template = $templateItem;
                $variations = ' ';
                $total_price_stik = parseInt(item.price_stik) * item.quantity;

                if (item.variation_1_label && item.variation_1_value) {
                    $variations += item.variation_1_value;
                }
                if (item.variation_2_label && item.variation_2_value) {
                    $variations += ' - ' + item.variation_2_value;
                }

                $quantity = item.quantity;
                $total_item_weight = parseInt(item.quantity) * parseInt(item.weight);
                $quantity += ' - ' + $total_item_weight + 'gr';

                //$variations += ', x' + item.quantity;

                $template = $template.replace('{{id}}', item.key);
                $template = $template.replace('{{image}}', item.image);
                $template = $template.replace('{{name}}', item.name);
                $template = $template.replace('{{variation}}', $variations);
                $template = $template.replace('{{quantity}}', $quantity);
                $template = $template.replace('{{note}}', item.note);
                $template = $template.replace('{{price}}', marketpress.currency.format(item.total));
                if (parseInt($total_price_stik) > 0) {
                    $template = $template.replace('{{price_stik}}', marketpress.currency.format($total_price_stik));
                } else {
                    $template = $template.replace('{{price_stik}}', '');
                }
                $checkout_items += $template;
            });

            this.loadDetail();
            this.loadCustomerField();
            this.loadCouponCheck();
            this.loadPayment();
            this.loadSubmit();

        } else {
            let $templateEmptyItem = document.getElementById('checkout-empty-item-template').innerHTML;
            $checkout_items = $templateEmptyItem;
            let $hideEll = document.querySelectorAll('.hide-if-empty-item');
            for (let i = 0; $hideEll.length > i; i++) {
                $hideEll[i].style.display = 'none';
            }
        }

        this.el.querySelector('.checkout-items').innerHTML = $checkout_items;
        this.itemAction();
    },
    itemAction: function() {
        let $els = this.el.querySelectorAll('.item'),
            $id = null;

        for (let i = 0; $els.length > i; i++) {
            // $els[i].querySelector('.minus').onclick = function() {
            //     $id = this.parentNode.parentNode.parentNode.parentNode.getAttribute('data-id');
            //     if (this.parentNode.querySelector('input').value > 1) {
            //         this.parentNode.querySelector('input').value--;

            //         MarketPress_Cart_Items.update($id, 'quantity', this.parentNode.querySelector('input').value);
            //         MarketPress_Checkout.loadItems();
            //         MarketPress_Checkout.loadPayment();
            //     }
            // }
            // $els[i].querySelector('.plus').onclick = function() {

            //     $id = this.parentNode.parentNode.parentNode.parentNode.getAttribute('data-id');
            //     this.parentNode.querySelector('input').value++;
            //     MarketPress_Cart_Items.update($id, 'quantity', this.parentNode.querySelector('input').value);

            //     MarketPress_Checkout.loadItems();
            //     MarketPress_Checkout.loadPayment();
            // }

            $els[i].querySelector('.item-att-delete').onclick = function() {
                $id = this.parentNode.parentNode.getAttribute('data-id');
                MarketPress_Cart_Items.delete($id);

                MarketPress_Checkout.loadItems();
                MarketPress_Checkout.loadPayment();
            }
        }
    },
    loadCouponCheck: function() {
        let $el = this.el.querySelector('.checkout-coupon'),
            $code = '',
            $data = {};

        this.couponCheck();

        if (typeof($el) != 'undefined' && $el != null) {
            $el.querySelector('button').onclick = function() {
                $code = $el.querySelector('input').value;
                if ($code) {
                    this.innerHTML = 'Checking ....';
                    this.disabled = true;
                    $el.querySelector('input').disabled = true;
                    MarketPress_Cart_Detail.set('discount_code', $code);
                    MarketPress_Checkout.couponCheck();
                } else {
                    alert('masukan kode promo terlebih dulu');
                }
            }
        }
    },
    couponCheck: function() {
        let $el = this.el.querySelector('.checkout-coupon'),
            $code = MarketPress_Cart_Detail.get('discount_code'),
            $data = {};

        $data.nonce = marketpress.nonce;
        $data.code = $code;
        $data.cart_items = MarketPress_Cart_Items.get();

        if ($code) {
            fetch(
                    marketpress.ajax_url + '?action=apply_coupon', {
                        method: 'POST',
                        body: JSON.stringify($data)
                    })
                .then((respons) => respons.json())
                .then(function(json) {

                    if ('status' in json && json.status == 'valid') {
                        $el.querySelector('.couponbox-notice').innerHTML = json.message;
                        $el.querySelector('.couponbox-notice').style.display = 'block';
                        MarketPress_Cart_Detail.set('discount_amount', json.discount);
                        MarketPress_Cart_Detail.set('discount_code', $data.code);
                        $el.querySelector('.couponbox-notice').classList.remove('error');
                        $el.querySelector('input').value = $data.code;
                    } else {
                        MarketPress_Cart_Detail.set('discount_amount', '');
                        MarketPress_Cart_Detail.set('discount_code', '');
                        let $message = 'Error, please Contact this site owner';
                        if ('message' in json) {
                            $message = json.message;
                        }
                        $el.querySelector('.couponbox-notice').innerHTML = $message;
                        $el.querySelector('.couponbox-notice').classList.add('error');
                    }
                    MarketPress_Cart_Items.countTotal();
                    MarketPress_Checkout.loadDetail();

                    $el.querySelector('button').disabled = false;
                    $el.querySelector('input').disabled = false;
                    $el.querySelector('button').innerHTML = 'Terapkan';
                });
        }
    },
    loadDetail: function() {
        MarketPress_Cart_Items.countTotal();

        let $subtotal = MarketPress_Cart_Detail.get('subtotal'),
            $total = MarketPress_Cart_Detail.get('total'),
            $ongkir = MarketPress_Cart_Detail.get('shipping_cost'),
            $discount = MarketPress_Cart_Detail.get('discount_amount'),
            $payment_cod_cost = MarketPress_Cart_Detail.get('payment_cod_cost');

        $templ = '<table>';
        $templ += '<tr><td>Sub Total</td><td class="value">' + marketpress.currency.format($subtotal) + '</td></tr>';
        if ($ongkir) {
            $templ += '<tr><td style="color:#97A6AB">Ongkir</td><td class="value">' + marketpress.currency.format($ongkir) + '</td></tr>';
        }
        if ($payment_cod_cost > 0) {
            $templ += '<tr><td style="color:#97A6AB">Biaya COD ' + marketpress.payment_cod_cost + '%</td><td class="value">' + marketpress.currency.format($payment_cod_cost) + '</td></tr>';
        } else {}

        if ($discount) {
            $templ += '<tr><td style="color:#97A6AB">Diskon</td><td class="value" style="color:green">-' + marketpress.currency.format($discount) + '</td></tr>';
        }
        $templ += '<tr><td style="font-weight: bold;padding-top:10px">Total</td><td class="value" style="font-weight: bold;padding-top:10px;">' + marketpress.currency.format($total) + '</td></tr>';
        $templ += '</table>';

        this.el.querySelector('.checkout-detail').innerHTML = $templ;
    },
    loadCustomerField: function() {
        let $customer_field = this.el.querySelector('.checkout-customer-field'),
            $inputs = $customer_field.querySelectorAll('input'),
            $textareas = $customer_field.querySelectorAll('textarea');

        for (let i = 0; i < $inputs.length; i++) {
            let $key = $inputs[i].name;
            if ($key) {
                let $input_value = MarketPress_Cart_Customer.get($key);
                if ($input_value) {
                    $inputs[i].value = $input_value;
                }
            }
            $inputs[i].oninput = function() {
                if (this.name) {
                    MarketPress_Cart_Customer.set(this.name, this.value);
                }
            }
        }
        for (let i = 0; i < $textareas.length; i++) {
            let $key = $textareas[i].name;
            if ($key) {
                let $textarea_value = MarketPress_Cart_Customer.get($key);
                if ($textarea_value) {
                    $textareas[i].value = $textarea_value;
                }
            }
            $textareas[i].oninput = function() {
                if (this.name) {
                    MarketPress_Cart_Customer.set(this.name, this.value);
                }
            }
        }

        let loadSelectSubDistrict = async function() {
            if (marketpress.shipping_zone_subdistrict_id == 99999) {} else {
                MarketPress_Cart_Customer.set('subdistrict_id', marketpress.shipping_zone_subdistrict_id);
                MarketPress_Cart_Customer.set('subdistrict_name', marketpress.shipping_zone_subdistrict_name);
            }

            let $el = MarketPress_Checkout.el.querySelector('#subdistrictSelect'),
                $current_district_id = MarketPress_Cart_Customer.get('district_id'),
                $current_subdistrict_id = MarketPress_Cart_Customer.get('subdistrict_id'),
                $current_value = 0,
                $el_shipping = MarketPress_Checkout.el.querySelector('.checkout-shipping');


            const source = await fetch(marketpress.regions_source + 'subdistrict.json');
            const data = await source.json();

            const ddata = data.filter(function(d) {
                return d.city_id == $current_district_id;
            });

            $el.options.length = 0;
            for (let i = 0; ddata.length > i; i++) {
                $el.options.add(new Option(ddata[i].subdistrict_name, ddata[i].subdistrict_id));
                if (ddata[i].subdistrict_id == $current_subdistrict_id) {
                    $current_value = i;
                }
            }

            $el.selectedIndex = $current_value;

            if (marketpress.shipping_zone_subdistrict_id == 99999) {
                $el.removeAttribute('disabled');
            } else {
                $el.setAttribute('disabled', true);
            }

            MarketPress_Cart_Customer.set('subdistrict_id', ddata[$current_value].subdistrict_id);
            MarketPress_Cart_Customer.set('subdistrict_name', ddata[$current_value].subdistrict_name);

            if (typeof($el_shipping) != 'undefined' && $el_shipping !== null) {
                MarketPress_Checkout.loadShipping();
            }

            $el.addEventListener('change', (e) => {

                MarketPress_Cart_Customer.set('subdistrict_id', ddata[$el.selectedIndex].subdistrict_id);
                MarketPress_Cart_Customer.set('subdistrict_name', ddata[$el.selectedIndex].subdistrict_name);

                if (typeof($el_shipping) != 'undefined' && $el_shipping !== null) {
                    MarketPress_Checkout.loadFlatshipping();
                    MarketPress_Checkout.loadRajaongkir();
                }
            })

        }


        let loadSelectDistrict = async function() {

            if (marketpress.shipping_zone_district_id == 99999) {} else {
                MarketPress_Cart_Customer.set('district_id', marketpress.shipping_zone_district_id);
                MarketPress_Cart_Customer.set('district_name', marketpress.shipping_zone_district_name);
                MarketPress_Cart_Customer.set('district_type', marketpress.shipping_zone_district_type);
            }

            let $el = MarketPress_Checkout.el.querySelector('#districtSelect'),
                $current_province_id = MarketPress_Cart_Customer.get('province_id', 6),
                $current_district_id = MarketPress_Cart_Customer.get('district_id'),
                $current_value = 0;


            const source = await fetch(marketpress.regions_source + 'district.json');
            const data = await source.json();

            const ddata = data.filter(function(d) {
                return d.province_id == $current_province_id;
            });

            $el.options.length = 0;
            for (let i = 0; ddata.length > i; i++) {
                $el.options.add(new Option(ddata[i].type + ' ' + ddata[i].city_name, ddata[i].city_id));
                if (ddata[i].city_id == $current_district_id) {
                    $current_value = i;
                }
            }

            $el.selectedIndex = $current_value;

            if (marketpress.shipping_zone_district_id == 99999) {
                $el.removeAttribute('disabled');
            } else {
                $el.setAttribute('disabled', true);
            }

            MarketPress_Cart_Customer.set('district_id', ddata[$current_value].city_id);
            MarketPress_Cart_Customer.set('district_name', ddata[$current_value].city_name);
            MarketPress_Cart_Customer.set('district_type', ddata[$current_value].type);

            loadSelectSubDistrict();

            $el.addEventListener('change', (e) => {

                MarketPress_Cart_Customer.set('district_id', ddata[e.target.selectedIndex].city_id);
                MarketPress_Cart_Customer.set('district_name', ddata[e.target.selectedIndex].city_name);
                MarketPress_Cart_Customer.set('district_type', ddata[e.target.selectedIndex].type);

                loadSelectSubDistrict();
            })
        }


        let loadSelectProvince = async function() {

            if (marketpress.shipping_zone_province_id == 99999) {} else {
                MarketPress_Cart_Customer.set('province_id', marketpress.shipping_zone_province_id);
                MarketPress_Cart_Customer.set('province_name', marketpress.shipping_zone_province_name);
            }

            let $el = MarketPress_Checkout.el.querySelector('#provinceSelect'),
                $current_province_id = MarketPress_Cart_Customer.get('province_id', 6),
                $current_value = 0;


            const source = await fetch(marketpress.regions_source + 'province.json');
            const data = await source.json();

            for (let i = 0; data.length > i; i++) {
                $el.options.add(new Option(data[i].province, data[i].province_id));
                if (data[i].province_id == $current_province_id) {
                    $current_value = i;
                }
            }

            $el.selectedIndex = $current_value;

            if (marketpress.shipping_zone_province_id == 99999) {
                $el.removeAttribute('disabled');
            } else {
                $el.setAttribute('disabled', true);
            }

            MarketPress_Cart_Customer.set('province_id', data[$current_value].province_id);
            MarketPress_Cart_Customer.set('province_name', data[$current_value].province);

            await loadSelectDistrict();

            $el.addEventListener('change', (e) => {

                MarketPress_Cart_Customer.set('province_id', data[$el.selectedIndex].province_id);
                MarketPress_Cart_Customer.set('province_name', data[$el.selectedIndex].province);

                loadSelectDistrict();
            })
        }

        loadSelectProvince();
    },
    loadShipping: function() {
        let $el = this.el.querySelector('.checkout-shipping');

        if (typeof($el) != 'undefined' && $el !== null) {
            this.loadFlatshipping();
            this.loadRajaongkir();
        }
    },
    showHideRajaongkir: function() {
        let $els = this.el.querySelectorAll('.rajaongkir');

        for (let i = 0; $els.length > i; i++) {
            $els[i].querySelector('.rajaongkir-detail').onclick = function() {
                for (let io = 0; $els.length > io; io++) {
                    if (io != i) {
                        $els[io].querySelector('.rajaongkir-services').classList.remove('active');
                    }
                }
                $els[i].querySelector('.rajaongkir-services').classList.toggle('active');
            }
        }

        let $box = this.el.querySelector('.shipping-rajaongkir'),
            $services = $box.querySelectorAll('.rajaongkir-services-service');

        for (let ii = 0; $services.length > ii; ii++) {
            $services[ii].onclick = function() {
                let $radios = $box.querySelectorAll('.radios');
                for (let iii = 0; $radios.length > iii; iii++) {
                    $radios[iii].classList.remove('checked');
                }
                this.querySelector('.radios').classList.add('checked');

                let $shipping_name = this.getAttribute('data-name'),
                    $shipping_cost = this.getAttribute('data-cost'),
                    $shipping_etd = this.getAttribute('data-etd');

                MarketPress_Cart_Detail.set('shipping_cost', $shipping_cost);
                MarketPress_Cart_Detail.set('shipping_etd', $shipping_etd);
                MarketPress_Cart_Detail.set('shipping_name', $shipping_name);

                MarketPress_Cart_Items.countTotal();
                MarketPress_Checkout.loadDetail();
            }
        }
    },
    loadRajaongkir: function() {

        let $el = this.el.querySelector('.shipping-rajaongkir'),
            $district_id = MarketPress_Cart_Customer.get('district_id'),
            $subdistrict_id = MarketPress_Cart_Customer.get('subdistrict_id'),
            $region = $subdistrict_id + '-' + $district_id,
            $weight = MarketPress_Cart_Detail.get('weight'),
            $url = marketpress.ajax_url + '?action=get_ongkir&nonce=' + marketpress.nonce + '&destination=' + $region + '&weight=' + $weight,
            $rajaongkirTemplate = document.getElementById('checkout-shipping-rajaongkir').innerHTML,
            $rajaongkirServiceTemplate = document.getElementById('checkout-shipping-rajaongkir-service').innerHTML,
            $shippings = '',
            $loader = document.getElementById('checkout-loader').innerHTML;

        if (typeof($el) != 'undefined' && $el !== null) {

            MarketPress_Cart_Detail.set('shipping_cost', '');
            MarketPress_Cart_Detail.set('shipping_name', '');
            MarketPress_Cart_Detail.set('shipping_etd', '');

            $el.innerHTML = $loader;

            if ($district_id && $subdistrict_id) {
                fetch($url)
                    .then((respons) => respons.json())
                    .then(function(json) {
                        if ('code' in json) {
                            alert('Raja ongkir API Error : ' + json.code + ' | ' + json.message);
                        } else {

                            for (let i = 0; i < json.length; i++) {
                                let $templ = $rajaongkirTemplate,
                                    $shippingsServices = '';

                                $templ = $templ.replace('{{courier-icon}}', json[i].icon);
                                $templ = $templ.replace('{{courier-name}}', json[i].name);
                                if (i == 0) {
                                    $templ = $templ.replace('{{classes}}', ' active');
                                } else {
                                    $templ = $templ.replace('{{classes}}', '');
                                }

                                for (let ii = 0; json[i].costs.length > ii; ii++) {
                                    $templService = $rajaongkirServiceTemplate;

                                    let $etd = '';
                                    if (json[i].costs[ii].etd) {
                                        let str = json[i].costs[ii].etd;

                                        if (str.includes('hari') == false && str.includes('Hari') == false) {
                                            $etd = str + ' Hari';
                                        }
                                    }

                                    $templService = $templService.replace('{{service-name}}', json[i].costs[ii].service);
                                    $templService = $templService.replace('{{service-etd}}', $etd);
                                    $templService = $templService.replace('{{service-cost}}', marketpress.currency.format(json[i].costs[ii].value));

                                    $templService = $templService.replace('{{data-name}}', json[i].name + ' - ' + json[i].costs[ii].service);
                                    $templService = $templService.replace('{{data-etd}}', $etd);
                                    $templService = $templService.replace('{{data-cost}}', json[i].costs[ii].value);

                                    if (i == 0 && ii == 0) {
                                        $templService = $templService.replace('{{classes}}', ' checked');

                                        MarketPress_Cart_Detail.set('shipping_cost', json[i].costs[ii].value);
                                        MarketPress_Cart_Detail.set('shipping_etd', $etd);
                                        MarketPress_Cart_Detail.set('shipping_name', json[i].name + ' - ' + json[i].costs[ii].service);

                                        MarketPress_Cart_Items.countTotal();
                                        MarketPress_Checkout.loadDetail();

                                    } else {
                                        $templService = $templService.replace('{{classes}}', '');
                                    }

                                    $shippingsServices += $templService;
                                }

                                $templ = $templ.replace('{{services}}', $shippingsServices);

                                $shippings += $templ;
                            }

                            $el.innerHTML = $shippings;
                            MarketPress_Checkout.showHideRajaongkir();
                        }
                    })
                    .catch(function(error) {
                        alert(error);
                    });
            }

        }
    },
    loadFlatshipping: function() {
        let $el = this.el.querySelector('.shipping-flatshipping'),
            $shipping_name = MarketPress_Cart_Detail.get('shipping_name'),
            $shipping_etd = '',
            $shipping_cost = MarketPress_Cart_Detail.get('shipping_cost');

        if (typeof($el) != 'undefined' && $el !== null) {
            let $options = $el.querySelectorAll('.flatshipping');

            if (!$shipping_name) {
                $shipping_name = $options[0].getAttribute('data-name');
                $shipping_cost = $options[0].getAttribute('data-cost');

                MarketPress_Cart_Detail.set('shipping_cost', $shipping_cost);
                MarketPress_Cart_Detail.set('shipping_name', $shipping_name);
                MarketPress_Cart_Detail.set('shipping_etd', $shipping_etd);
            }

            $shipping_name = MarketPress_Cart_Detail.get('shipping_name');

            for (let i = 0; $options.length > i; i++) {

                $this_shipping_name = $options[i].getAttribute('data-name');

                if ($this_shipping_name == $shipping_name) {
                    $options[i].querySelector('.radios').classList.add('checked');
                }

                $options[i].onclick = function() {

                    for (var ii = 0; ii < $options.length; ii++) {
                        $options[ii].querySelector('.radios').classList.remove('checked');
                    }
                    $shipping_name = $options[i].getAttribute('data-name');
                    $shipping_cost = $options[i].getAttribute('data-cost');

                    MarketPress_Cart_Detail.set('shipping_cost', $shipping_cost);
                    MarketPress_Cart_Detail.set('shipping_name', $shipping_name);
                    MarketPress_Cart_Detail.set('shipping_etd', $shipping_etd);

                    $options[i].querySelector('.radios').classList.add('checked');

                    MarketPress_Cart_Items.countTotal();
                    MarketPress_Checkout.loadDetail();
                }
            }
        }
    },
    loadPayment: function() {
        let $el = this.el.querySelector('.checkout-payment'),
            $subtotal = MarketPress_Cart_Detail.get('subtotal'),
            $payment_cod_cost = 0;

        $payment_cod_cost = (parseInt(marketpress.payment_cod_cost) * $subtotal) / 100;
        MarketPress_Cart_Detail.set('payment_method', '');

        if (typeof($el) != 'undefined' && $el !== null) {
            $paymentboxs = $el.querySelectorAll('.payment');

            for (let i = 0; $paymentboxs.length > i; i++) {

                $paymentboxs[i].querySelector('.payment-detail').onclick = function() {
                    for (let io = 0; $paymentboxs.length > io; io++) {
                        if (io != i) {
                            $paymentboxs[io].querySelector('.payment-lists').classList.remove('active');
                        }
                    }
                    $paymentboxs[i].querySelector('.payment-lists').classList.toggle('active');
                }
            }

            let $payments = $el.querySelectorAll('.payment-lists-box');

            for (let ii = 0; $payments.length > ii; ii++) {
                $payments[ii].onclick = function() {
                    let $radios = $el.querySelectorAll('.radios');
                    for (let iii = 0; $radios.length > iii; iii++) {
                        $radios[iii].classList.remove('checked');
                    }
                    this.querySelector('.radios').classList.add('checked');

                    let $payment_name = this.getAttribute('data-name'),
                        $payment_method_key = this.getAttribute('data-key');

                    MarketPress_Cart_Detail.set('payment_method', $payment_name);
                    if ($payment_method_key == 'cod') {
                        MarketPress_Cart_Detail.set('payment_cod_cost', $payment_cod_cost);
                    } else {
                        MarketPress_Cart_Detail.set('payment_cod_cost', 0);
                    }

                    MarketPress_Cart_Items.countTotal();
                    MarketPress_Checkout.loadDetail();
                }
            }
        }
    },
    loadSubmit: function() {
        let $button = this.el.querySelector('#order');

        $button.onclick = function() {
            let $invalid = [],
                $customer = [
                    { key: 'name', message: 'Mohon masukan nama Anda' },
                    { key: 'phone', message: 'Mohon masukan nomor hp Anda' },
                    { key: 'address', message: 'Mohon masukan alamat Anda' },
                    { key: 'province_id', message: 'Mohon pilih nama provinsi' },
                    { key: 'district_id', message: 'Mohon pilih nama Kabupaten/Kota' },
                    { key: 'subdistrict_id', message: 'Mohon pilih kecamatan Anda' },
                    { key: 'postal', message: 'Mohon Input kode pos Anda' }
                ],
                $detail = [];

            if (marketpress.payment_enable == 'yes') {
                $detail.push({ key: 'payment_method', message: 'Mohon pilih metode pembayaran' })
            }

            if (marketpress.shipping_enable == 'yes') {
                $detail.push({ key: 'shipping_cost', message: 'Mohon pilih kurir pengiriman' })
            }

            $customer.forEach(function(val, key) {
                if (!MarketPress_Cart_Customer.get(val.key)) {
                    $invalid.push(val.message);
                }
            });

            $detail.forEach(function(val, key) {
                if (!MarketPress_Cart_Detail.get(val.key)) {
                    $invalid.push(val.message);
                }
            });

            if ($invalid.length > 0) {
                MarketPress_Checkout.errorField($invalid[0]);
            } else {
                this.innerHTML = 'Processing ....';
                this.onclick = false;
                MarketPress_Checkout.insertOrder();
            }
        }
    },
    errorField: function($message) {
        let $templ = document.getElementById('error-field-template').content.cloneNode(true);
        document.body.appendChild($templ);

        let $el = document.querySelector('.error-field');

        $el.innerHTML = $message;

        setTimeout(function() {
            $el.style.top = '110px';
        }, 100);

        setTimeout(function() {
            $el.style.top = '-110px';
        }, 1500);

        setTimeout(function() {
            $el.parentNode.removeChild($el);
        }, 1600);
    },
    insertOrder: function() {
        let $cartItems = MarketPress_Cart_Items.get();

        if ($cartItems.length > 0) {
            let $data = {};

            $data.nonce = marketpress.nonce;
            $data.items = $cartItems;
            $data.detail = MarketPress_Cart_Detail.get('all');
            $data.customer = MarketPress_Cart_Customer.get('all');

            fetch(marketpress.ajax_url + '?action=order_create', {
                    method: 'POST',
                    body: JSON.stringify($data)
                }).then((respons) => respons.json())
                .then(function(json) {

                    if (marketpress.thanks_page == 'yes') {
                        MarketPress_Cart_Items.empty();
                        window.location.href = marketpress.site_url + '/thanks/' + json.data.key;
                    } else {

                        if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
                            marketpress.link_wa = marketpress.link_wa.replace('https://web.whatsapp.com/', 'whatsapp://')
                        }

                        let $items = MarketPress_Cart_Items.get(),
                            $detail = MarketPress_Cart_Detail.get('all'),
                            $customer = MarketPress_Cart_Customer.get('all'),
                            $order = '';

                        $items.forEach(function(item, i) {
                            $number = i + 1;
                            $number = $number + '. ';

                            $price = parseInt(item.price);

                            $order += '%0A*' + $number + item.name + '* %0A';

                            if (item.variation_1_value) {
                                $order += '  ' + item.variation_1_label + ': ' + item.variation_1_value + ' %0A';
                            }

                            if (item.variation_2_value) {
                                $order += '  ' + item.variation_2_label + ': ' + item.variation_2_value + ' %0A';
                            }

                            $order += '  Quantity: ' + item.quantity + ' %0A';
                            $order += '  Harga (@): ' + marketpress.currency.format($price) + ' %0A';
                            $order += '  Total Harga: ' + marketpress.currency.format(item.total) + ' %0A';
                        });

                        $order += '%0ASub Total : *' + marketpress.currency.format($detail.subtotal) + '*%0A';

                        if (marketpress.shipping_enable == 'yes') {
                            $order += 'Ongkir : *' + marketpress.currency.format($detail.shipping_cost) + '* (' + $detail.shipping_name + ')%0A';
                        }
                        if ($detail.payment_cod_cost) {
                            $order += 'Biaya COD ' + marketpress.payment_cod_cost + '% : *' + marketpress.currency.format($detail.payment_cod_cost) + '* %0A';
                        }
                        if ($detail.discount_amount) {
                            $order += 'Diskon : *-' + marketpress.currency.format($detail.discount_amount) + '* (' + $detail.discount_code + ')%0A';
                        }
                        $order += 'Total : *' + marketpress.currency.format($detail.total) + '*%0A%0A';

                        $order += 'Metode Pembayaran : *' + $detail.payment_method + '* %0A';

                        let url = marketpress.link_wa + 'text=Haloo .%0A' + $order + '--------------------------------%0A*Nama :*%0A' + $customer.name + ' ( ' + $customer.phone + ' ) %0A%0A*Alamat :*%0A' + $customer.address.replace(/(\r\n|\n|\r)/gm, '%0A') + '%0AKecamatan. ' + $customer.subdistrict_name + '%0A' + $customer.district_type + '. ' + $customer.district_name + ', ' + $customer.province_name + ' ' + $customer.postal + '%0A%0A' + 'Via ' + location.href;

                        let w = 960,
                            h = 540,
                            left = Number((screen.width / 2) - (w / 2)),
                            top = Number((screen.height / 2) - (h / 2)),
                            popupWindow = window.open(url, '', 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=1, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

                        popupWindow.focus();

                        MarketPress_Cart_Items.empty();
                        window.location.href = marketpress.shop_now_page;

                    }
                })
                .catch(function(error) {
                    console.log(error);
                });
        }
    }
}

MarketPress_Cart_Items = {
    storage: 'marketpress_cart_items',
    get: function() {
        let $return = [];
        if (localStorage.getItem(this.storage)) {
            $return = JSON.parse(localStorage.getItem(this.storage));
        }

        return $return;
    },
    empty: function() {
        localStorage.removeItem(this.storage);
    },
    countSubTotal: function() {
        let $subtotal = 0,
            $items = [],
            $subsubtotal = 0,
            $price = 0,
            $weight = 0;

        $items = this.get();
        $items.forEach(function(item, i, object) {
            $price = parseInt(item.price);
            $subsubtotal = parseInt($price) * parseInt(item.quantity);
            $subtotal = $subtotal + $subsubtotal;

            let $subweight = parseInt(item.weight) * parseInt(item.quantity);
            $weight = $weight + $subweight;
        });

        MarketPress_Cart_Detail.set('subtotal', $subtotal);
        MarketPress_Cart_Detail.set('total', $subtotal);
        MarketPress_Cart_Detail.set('weight', $weight);


    },
    countTotal: function() {
        let $total = MarketPress_Cart_Detail.get('subtotal'),
            $discount = MarketPress_Cart_Detail.get('discount_amount'),
            $shipping = MarketPress_Cart_Detail.get('shipping_cost'),
            $cod_cost = MarketPress_Cart_Detail.get('payment_cod_cost');

        if ($shipping) {
            $total = parseInt($total) + parseInt($shipping);
        }
        if ($cod_cost) {
            $total = parseInt($total) + parseInt($cod_cost);
        }
        if ($discount) {
            $total = parseInt($total) - parseInt($discount);
        }
        MarketPress_Cart_Detail.set('total', $total);
    },
    update: function($item_key, $key = 'quantity', $value = 0) {
        let $cartItems = this.get();

        $cartItems.forEach(function(item, i) {
            if ($item_key == item.key) {
                if ($key == 'quantity') {
                    $cartItems[i].quantity = parseInt($value);
                    $cartItems[i].total = parseInt($value) * parseInt(item.price);
                }
                if ($key == 'note') {
                    $cartItems[i].note = $value
                }
            }
        });

        localStorage.setItem(this.storage, JSON.stringify($cartItems));
        this.countSubTotal();
    },
    insert: function($item) {
        let $cartItems = this.get(),
            $isItemExists = false;

        $cartItems.forEach(function(item, i) {
            if ($item.key == item.key) {
                $cartItems[i].quantity = parseInt($item.quantity) + parseInt(item.quantity);
                $cartItems[i].note = $item.note;
                $isItemExists = true;
            }
        });

        if ($isItemExists == false) {
            $cartItems.push($item);
        }

        localStorage.setItem(this.storage, JSON.stringify($cartItems));
        this.countSubTotal();
    },
    delete: function($cart_key) {
        let $cartItems = this.get();

        $cartItems.forEach(function(item, i, object) {
            if ($cart_key == item.key) {
                object.splice(i, 1);
            }
        });

        localStorage.setItem(this.storage, JSON.stringify($cartItems));
        this.countSubTotal();
    }
}

MarketPress_Cart_Detail = {
    storage: 'marketpress_cart_detail',
    get: function($key) {
        let $detail = {},
            $return = false;

        if (localStorage.getItem(this.storage)) {
            $detail = JSON.parse(localStorage.getItem(this.storage));
        }

        if ($key == 'all') {
            $return = $detail;
        }

        if ($key == 'subtotal' && 'subtotal' in $detail) {
            $return = $detail.subtotal;
        }
        if ($key == 'weight' && 'weight' in $detail) {
            $return = $detail.weight;
        }
        if ($key == 'shipping_cost' && 'shipping_cost' in $detail) {
            $return = $detail.shipping_cost;
        }
        if ($key == 'shipping_name' && 'shipping_name' in $detail) {
            $return = $detail.shipping_name;
        }
        if ($key == 'shipping_etd' && 'shipping_etd' in $detail) {
            $return = $detail.shipping_etd;
        }
        if ($key == 'discount_amount' && 'discount_amount' in $detail) {
            $return = $detail.discount_amount;
        }
        if ($key == 'discount_code' && 'discount_code' in $detail) {
            $return = $detail.discount_code;
        }
        if ($key == 'payment_method' && 'payment_method' in $detail) {
            $return = $detail.payment_method;
        }
        if ($key == 'payment_cod_cost' && 'payment_cod_cost' in $detail) {
            $return = $detail.payment_cod_cost;
        }
        if ($key == 'total' && 'total' in $detail) {
            $return = $detail.total;
        }

        return $return;
    },
    set: function($key, $value) {
        let $detail = {};

        if (localStorage.getItem(this.storage)) {
            $detail = JSON.parse(localStorage.getItem(this.storage));
        }

        if ($key == 'subtotal') {
            $detail.subtotal = parseInt($value);
        }
        if ($key == 'weight') {
            $detail.weight = parseInt($value);
        }
        if ($key == 'shipping_cost') {
            $detail.shipping_cost = parseInt($value);
        }
        if ($key == 'shipping_name') {
            $detail.shipping_name = $value;
        }
        if ($key == 'shipping_etd') {
            $detail.shipping_etd = $value;
        }
        if ($key == 'discount_amount') {
            $detail.discount_amount = parseInt($value);
        }
        if ($key == 'discount_code') {
            $detail.discount_code = $value;
        }
        if ($key == 'payment_method') {
            $detail.payment_method = $value;
        }
        if ($key == 'payment_cod_cost') {
            $detail.payment_cod_cost = $value;
        }
        if ($key == 'total') {
            $detail.total = parseInt($value);
        }

        localStorage.setItem(this.storage, JSON.stringify($detail));
    }
}

MarketPress_Cart_Customer = {
    storage: 'marketpress_cart_customer',
    get: function($key, $default = false) {
        let $customer = {},
            $return = $default;

        if (localStorage.getItem(this.storage)) {
            $customer = JSON.parse(localStorage.getItem(this.storage));
        }

        if ($key == 'all') {
            $return = $customer;
        }

        if ($key == 'name' && 'name' in $customer) {
            $return = $customer.name
        }
        if ($key == 'phone' && 'phone' in $customer) {
            $return = $customer.phone;
        }
        if ($key == 'email' && 'email' in $customer) {
            $return = $customer.email;
        }
        if ($key == 'address' && 'address' in $customer) {
            $return = $customer.address;
        }
        if ($key == 'subdistrict_id' && 'subdistrict_id' in $customer) {
            $return = $customer.subdistrict_id;
        }
        if ($key == 'subdistrict_name' && 'subdistrict_name' in $customer) {
            $return = $customer.subdistrict_name;
        }
        if ($key == 'district_id' && 'district_id' in $customer) {
            $return = $customer.district_id;
        }
        if ($key == 'district_type' && 'district_type' in $customer) {
            $return = $customer.district_type;
        }
        if ($key == 'district_name' && 'district_name' in $customer) {
            $return = $customer.district_name;
        }
        if ($key == 'province_id' && 'province_id' in $customer) {
            $return = $customer.province_id;
        }
        if ($key == 'province_name' && 'province_name' in $customer) {
            $return = $customer.province_name;
        }
        if ($key == 'postal' && 'postal' in $customer) {
            $return = $customer.postal;
        }
        return $return;
    },
    set: function($key, $value) {
        let $customer = {};
        if (localStorage.getItem(this.storage)) {
            $customer = JSON.parse(localStorage.getItem(this.storage));
        }
        if ($key == 'name') {
            $customer.name = $value;
        }
        if ($key == 'phone') {
            $customer.phone = $value;
        }
        if ($key == 'email') {
            $customer.email = $value;
        }
        if ($key == 'address') {
            $customer.address = $value;
        }
        if ($key == 'subdistrict_name') {
            $customer.subdistrict_name = $value;
        }
        if ($key == 'subdistrict_id') {
            $customer.subdistrict_id = $value;
        }
        if ($key == 'district_name') {
            $customer.district_name = $value;
        }
        if ($key == 'district_id') {
            $customer.district_id = $value;
        }
        if ($key == 'district_type') {
            $customer.district_type = $value;
        }
        if ($key == 'province_name') {
            $customer.province_name = $value;
        }
        if ($key == 'province_id') {
            $customer.province_id = $value;
        }
        if ($key == 'postal') {
            $customer.postal = $value;
        }
        localStorage.setItem(this.storage, JSON.stringify($customer));
    }
}

MarketPress_Thanks = {
    el: document.querySelector('#thanks'),
    init: function() {
        if (typeof(this.el) != 'undefined' && this.el != null) {
            this.loadConfirmButton();
            this.copyBankNumber();
        }
    },
    copyBankNumber: function() {
        let $button = this.el.querySelector('#copyBankNumber');
        if (typeof($button) != 'undefined' && $button != null) {
            let $parent = $button.parentNode,
                $span = $parent.querySelector('span'),
                $input = $parent.querySelector('input');

            $input.style.width = (($input.value.length + 7) * 8) + 'px';

            $button.onclick = function() {

                console.log();

                $input.select();
                document.execCommand("copy");
            }
        }

    },
    loadConfirmButton: function() {
        let $button = this.el.querySelector('#confirm'),
            $order = '';

        $button.onclick = function() {
            $items = marketpress_thanks.items;
            $detail = marketpress_thanks.detail;
            $customer = marketpress_thanks.customer;

            $items.forEach(function(item, i) {
                $number = i + 1;
                $number = $number + '. ';

                $price = parseInt(item.price);

                $order += '%0A*' + $number + item.name + '* %0A';

                if (item.variation_1_value) {
                    $order += '  ' + item.variation_1_label + ': ' + item.variation_1_value + ' %0A';
                }

                if (item.variation_2_value) {
                    $order += '  ' + item.variation_2_label + ': ' + item.variation_2_value + ' %0A';
                }

                $order += '  Quantity: ' + item.quantity + ' %0A';
                $order += '  Harga (@): ' + marketpress.currency.format($price) + ' %0A';
                $order += '  Total Harga: ' + marketpress.currency.format(item.total) + ' %0A';
            });

            $order += '%0ASub Total : *' + marketpress.currency.format($detail.subtotal) + '*%0A';

            if (marketpress.shipping_enable == 'yes') {
                $order += 'Ongkir : *' + marketpress.currency.format($detail.shipping_cost) + '* (' + $detail.shipping_name + ')%0A';
            }
            if ($detail.payment_cod_cost) {
                $order += 'Biaya COD ' + marketpress.payment_cod_cost + '% : *' + marketpress.currency.format($detail.payment_cod_cost) + '* %0A';
            }
            if ($detail.discount_amount) {
                $order += 'Diskon : *-' + marketpress.currency.format($detail.discount_amount) + '* (' + $detail.discount_code + ')%0A';
            }
            $order += 'Total : *' + marketpress.currency.format($detail.total) + '*%0A%0A';

            $order += 'Metode Pembayaran : *' + $detail.payment_method + '* %0A';

            if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
                marketpress_thanks.link_wa = marketpress_thanks.link_wa.replace('https://web.whatsapp.com/', 'whatsapp://')
            }

            let url = marketpress_thanks.link_wa + 'text=Haloo .%0A' + $order + '--------------------------------%0A*Nama :*%0A' + $customer.name + ' ( ' + $customer.phone + ' ) %0A%0A*Alamat :*%0A' + $customer.address.replace(/(\r\n|\n|\r)/gm, '%0A') + '%0AKecamatan. ' + $customer.subdistrict_name + '%0A' + $customer.district_type + '. ' + $customer.district_name + ', ' + $customer.province_name + ' ' + $customer.postal + '%0A%0A' + 'Via ' + location.href;

            let w = 960,
                h = 540,
                left = Number((screen.width / 2) - (w / 2)),
                top = Number((screen.height / 2) - (h / 2)),
                popupWindow = window.open(url, '', 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=1, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

            popupWindow.focus();
        }
    }
}

MarketPress.init();