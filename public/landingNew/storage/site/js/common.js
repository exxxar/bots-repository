'use strict';
const JSCCommon = {

    modalCall() {
        const link = '[data-fancybox="modal"], .link-modal-js';

        Fancybox.bind(link, {
            arrows: false,
            // infobar: false,
            touch: false,
            trapFocus: false,
            placeFocusBack: false,
            infinite: false,
            dragToClose: false,
            type: 'inline',
            autoFocus: false,
            groupAll: false,
            groupAttr: false,
            // showClass: "fancybox-throwOutUp",
            // hideClass: "fancybox-throwOutDown",
            l10n: {
                CLOSE: 'Закрыть',
                Escape: 'Закрыть',
                NEXT: 'Вперед',
                PREV: 'Назад',
                MODAL: 'Вы можете закрыть это модальное окно с помощью клавиши ESC.',
                ERROR: 'Что-то пошло не так. Пожалуйста, повторите попытку позже',
                IMAGE_ERROR: 'Изображение не найдено',
                ELEMENT_NOT_FOUND: 'HTML-элемент не найден',
                AJAX_NOT_FOUND: 'Ошибка при загрузке AJAX: не найдено',
                AJAX_FORBIDDEN: 'Ошибка при загрузке AJAX: запрещено',
                IFRAME_ERROR: 'Ошибка загрузки iframe',
            },
        });
        document.querySelectorAll('.modal-close-js').forEach((el) => {
            el.addEventListener('click', () => {
                Fancybox.close();
            });
        });
        Fancybox.bind('[data-fancybox]', {
            placeFocusBack: false,
        });
        document.addEventListener('click', (event) => {
            let element = event.target.closest(link);
            if (!element) return;
            let modal = document.querySelector('#' + element.dataset.src);
            const data = element.dataset;

            function setValue(val, elem) {
                if (elem && val) {
                    const el = modal.querySelector(elem);
                    el.tagName == 'INPUT' ? (el.value = val) : (el.innerHTML = val);
                    el.tagName == 'IMG' ? (el.src = val) : (el.innerHTML = val);
                    // console.log(modal.querySelector(elem).tagName)
                }
            }
            // setValue(data.order, '.order');
            // setValue(data.title, '.data-title');
            // setValue(data.text, '.after-headline');
            // setValue(data.btn, '.btn');
        });
    },
    // /modalCall
    toggleMenu() {
        const toggle = document.querySelectorAll('.toggle-menu-mobile--js');
        const menu = document.querySelector('.menu-mobile--js');
        toggle.forEach((el) => el.classList.toggle('on'));
        menu.classList.toggle('active');
        [document.body, document.querySelector('html')].forEach((el) => el.classList.toggle('fixed'));
    },
    closeMenu() {
        const toggle = document.querySelectorAll('.toggle-menu-mobile--js');
        const menu = document.querySelector('.menu-mobile--js');
        toggle.forEach((element) => element.classList.remove('on'));
        if (menu) {
            menu.classList.remove('active');
            [document.body, document.querySelector('html')].forEach((el) => el.classList.remove('fixed'));
        }
    },
    mobileMenu() {
        document.addEventListener(
            'click',
            (event) => {
                let container = event.target.closest('.menu-mobile--js'); // (1)
                let toggle = event.target.closest('.toggle-menu-mobile--js'); // (1)
                if (toggle) this.toggleMenu();
                if (!container && !toggle) this.closeMenu();
            }, {
                passive: true
            },
        );

        window.addEventListener(
            'resize',
            () => {
                if (window.matchMedia('(min-width: 992px)').matches) this.closeMenu();
            }, {
                passive: true
            },
        );
    },

    // tabs  .
    tabscostume(tab) {
        // const tabs = document.querySelectorAll(tab);
        // const indexOf = element => Array.from(element.parentNode.children).indexOf(element);
        // tabs.forEach(element => {
        // 	let tabs = element;
        // 	const tabsCaption = tabs.querySelector(".tabs__caption");
        // 	const tabsBtn = tabsCaption.querySelectorAll(".tabs__btn");
        // 	const tabsWrap = tabs.querySelector(".tabs__wrap");
        // 	const tabsContent = tabsWrap.querySelectorAll(".tabs__content");
        // 	const random = Math.trunc(Math.random() * 1000);
        // 	tabsBtn.forEach((el, index) => {
        // 		const data = `tab-content-${random}-${index}`;
        // 		el.dataset.tabBtn = data;
        // 		const content = tabsContent[index];
        // 		content.dataset.tabContent = data;
        // 		if (!content.dataset.tabContent == data) return;

        // 		const active = content.classList.contains('active') ? 'active' : '';
        // 		// console.log(el.innerHTML);
        // 		content.insertAdjacentHTML("beforebegin", `<div class="tabs__btn-accordion  btn btn-primary  mb-1 ${active}" data-tab-btn="${data}">${el.innerHTML}</div>`)
        // 	})

        // 	tabs.addEventListener('click', function (element) {
        // 		const btn = element.target.closest(`[data-tab-btn]:not(.active)`);
        // 		if (!btn) return;
        // 		const data = btn.dataset.tabBtn;
        // 		const tabsAllBtn = this.querySelectorAll(`[data-tab-btn`);
        // 		const content = this.querySelectorAll(`[data-tab-content]`);
        // 		tabsAllBtn.forEach(element => {
        // 			element.dataset.tabBtn == data
        // 				? element.classList.add('active')
        // 				: element.classList.remove('active')
        // 		});
        // 		content.forEach(element => {
        // 			element.dataset.tabContent == data
        // 				? (element.classList.add('active'), element.previousSibling.classList.add('active'))
        // 				: element.classList.remove('active')
        // 		});
        // 	})
        // })

        $('.' + tab + '__caption').on('click', '.' + tab + '__btn:not(.active)', function(e) {
            $(this)
                .addClass('active')
                .siblings()
                .removeClass('active')
                .closest('.' + tab)
                .find('.' + tab + '__content')
                .hide()
                .removeClass('active')
                .eq($(this).index())
                .fadeIn()
                .addClass('active');
        });
    },
    // /tabs

    inputMask() {
        // mask for input
        let InputTel = [].slice.call(document.querySelectorAll('input[type="tel"]'));
        // InputTel.forEach((element) =>
        //   element.setAttribute('pattern', '[+][0-9]{1}[(][0-9]{3}[)][0-9]{3}-[0-9]{2}-[0-9]{2}'),
        // );
        Inputmask({
            alias: "numeric",
            showMaskOnHover: false,
            allowPlus: false,
            allowMinus: false
        }).mask(InputTel);
    },
    // /inputMask
    sendForm() {
        var gets = (function() {
            var a = window.location.search;
            var b = new Object();
            var c;
            a = a.substring(1).split('&');
            for (var i = 0; i < a.length; i++) {
                c = a[i].split('=');
                b[c[0]] = c[1];
            }
            return b;
        })();
        // form
        $(document).on('submit', 'form', function(e) {
            e.preventDefault();
            const th = $(this);
            var data = th.serialize();
            th.find('.utm_source').val(decodeURIComponent(gets['utm_source'] || ''));
            th.find('.utm_term').val(decodeURIComponent(gets['utm_term'] || ''));
            th.find('.utm_medium').val(decodeURIComponent(gets['utm_medium'] || ''));
            th.find('.utm_campaign').val(decodeURIComponent(gets['utm_campaign'] || ''));
            $.ajax({
                    url: 'action.php',
                    type: 'POST',
                    data: data,
                })
                .done(function(data) {
                    Fancybox.close();
                    Fancybox.show([{
                        src: '#modal-thanks',
                        type: 'inline'
                    }]);
                    // window.location.replace("/thanks.html");
                    setTimeout(function() {
                        // Done Functions
                        th.trigger('reset');
                        // $.magnificPopup.close();
                        // ym(53383120, 'reachGoal', 'zakaz');
                        // yaCounter55828534.reachGoal('zakaz');
                    }, 4000);
                })
                .fail(function() {});
        });
    },
    heightwindow() {
        // First we get the viewport height and we multiple it by 1% to get a value for a vh unit
        let vh = window.innerHeight * 0.01;
        // Then we set the value in the --vh custom property to the root of the document
        document.documentElement.style.setProperty('--vh', `${vh}px`);

        // We listen to the resize event
        window.addEventListener(
            'resize',
            () => {
                // We execute the same script as before
                let vh = window.innerHeight * 0.01;
                document.documentElement.style.setProperty('--vh', `${vh}px`);
            }, {
                passive: true
            },
        );
    },
    animateScroll() {
        $(document).on('click', ' .nav-block__item, .scroll-link', function() {
            const elementClick = $(this).attr('href');
            if (!document.querySelector(elementClick)) {
                $(this).attr('href', '/' + elementClick);
            } else {
                let destination = $(elementClick).offset().top;
                $('html, body').animate({
                    scrollTop: destination - 80
                }, 0);
                return false;
            }
        });
    },
    getCurrentYear(el) {
        let now = new Date();
        let currentYear = document.querySelector(el);
        if (currentYear) currentYear.innerText = now.getFullYear();
    },
    toggleShow(toggle, drop) {
        let catalogDrop = drop;
        let catalogToggle = toggle;

        $(document).on('click', catalogToggle, function() {
            $(this)
                .toggleClass('active')
                .next()
                .fadeToggle('fast', function() {
                    $(this).toggleClass('active');
                });
        });

        document.addEventListener(
            'mouseup',
            (event) => {
                let container = event.target.closest(catalogDrop + '.active'); // (1)
                let link = event.target.closest(catalogToggle); // (1)
                if (!container || !catalogToggle) {
                    $(catalogDrop).removeClass('active').fadeOut();
                    $(catalogToggle).removeClass('active');
                }
            }, {
                passive: true
            },
        );
    },
    makeDDGroup() {
        $('.dd-head-js').on('click', function() {
            let clickedHead = this;
            $(this).parent().toggleClass('active');
            $(this)
                .next()
                .slideToggle(function() {
                    $(this).toggleClass('active');
                });

            // $(ChildHeads).each(function () {
            //   if (this === clickedHead) {
            //     //parent element gain toggle class, style head change via parent
            //   }
            //   else {
            //     $(this.parentElement).removeClass('active');
            //     $(this.parentElement).find('.dd-content-js').slideUp(function () {
            //       $(this).removeClass('active');
            //     });
            //   }
            // });
        });
    },
    imgToSVG() {
        const convertImages = (query, callback) => {
            const images = document.querySelectorAll(query);

            images.forEach((image) => {
                fetch(image.src)
                    .then((res) => res.text())
                    .then((data) => {
                        const parser = new DOMParser();
                        const svg = parser.parseFromString(data, 'image/svg+xml').querySelector('svg');

                        if (image.id) svg.id = image.id;
                        if (image.className) svg.classList = image.classList;

                        image.parentNode.replaceChild(svg, image);
                    })
                    .then(callback)
                    .catch((error) => console.error(error));
            });
        };

        convertImages('.img-svg-js');
    },
    scrollToTopOfPage(elems) {
        elems.forEach((elem) => {
            if (document.querySelector(elem)) {
                document.addEventListener('scroll', function() {
                    if (window.scrollY > 400) {
                        document.querySelector(elem).classList.add('active');
                    } else {
                        document.querySelector(elem).classList.remove('active');
                    }
                }, {
                    passive: true
                }, );
            }
            document.addEventListener('click', function(event) {
                let scrollTopBtn = event.target.closest(elem);
                if (scrollTopBtn) window.scrollTo(0, 0);
            });
        });
    },
    setActiveAnchor(navLiParam) {
        const navLi = document.querySelectorAll(navLiParam);

        const sections = document.querySelectorAll(`.hrefs-js [id]`);
        if (sections.length > 0 && navLi.length > 0) {
            document.addEventListener(
                'scroll',
                function() {
                    var current = '';

                    sections.forEach((section) => {
                        const sectionTop = section.offsetTop;
                        if (pageYOffset >= sectionTop - 80) {
                            current = section.getAttribute('id');
                        }
                    });
                    navLi.forEach((li) => {
                        li.classList.remove('active');
                        if (li.getAttribute('href') == `#${current}` && li.getAttribute('href') != `#`) {
                            li.classList.add('active');
                        }
                    });
                }, {
                    passive: true
                },
            );
        }
    },
};
const $ = jQuery;

function eventHandler() {
    JSCCommon.modalCall();
    // JSCCommon.tabscostume('tabs');
    JSCCommon.mobileMenu();
    JSCCommon.inputMask();
    // JSCCommon.sendForm();
    JSCCommon.heightwindow();
    JSCCommon.makeDDGroup();
    JSCCommon.getCurrentYear('.footer__bottom-row p span');
    JSCCommon.getCurrentYear('.sEngVersionFooter p span');
    JSCCommon.scrollToTopOfPage(['.footer__scrollTop--js', '.scrolToTop']);
    // JSCCommon.scrollToTopOfPage('.scrolToTop');

    JSCCommon.setActiveAnchor('.nav-block__item');
    // JSCCommon.toggleShow(".catalog-block__toggle--desctop", '.catalog-block__dropdown');
    JSCCommon.animateScroll();

    // JSCCommon.CustomInputFile();
    var x = window.location.host;
    let screenName;
    screenName = 'screen/' + document.body.dataset.bg;
    if (screenName && x.includes('localhost:30')) {
        document.body.insertAdjacentHTML(
            'beforeend',
            `<div class="pixel-perfect" style="background-image: url(${screenName});"></div>`,
        );
    }

    function setFixedNav() {
        let topNav = document.querySelector('.top-nav');
        if (!topNav) return;
        window.scrollY > 0 ? topNav.classList.add('fixed') : topNav.classList.remove('fixed');
    }

    function whenResize() {
        setFixedNav();
    }

    window.addEventListener(
        'scroll',
        () => {
            setFixedNav();
        }, {
            passive: true
        },
    );
    window.addEventListener(
        'resize',
        () => {
            whenResize();
        }, {
            passive: true
        },
    );

    whenResize();

    let defaultSl = {
        spaceBetween: 0,
        lazy: {
            loadPrevNext: true,
        },
        watchOverflow: true,
        loop: true,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        pagination: {
            el: ' .swiper-pagination',
            type: 'bullets',
            clickable: true,
            // renderBullet: function (index, className) {
            // 	return '<span class="' + className + '">' + (index + 1) + '</span>';
            // }
        },
    };

    const swiperbreadcrumb = new Swiper('.breadcrumb-slider--js', {
        slidesPerView: 'auto',
        freeMode: true,
        watchOverflow: true,
    });

    const swiper4 = new Swiper('.sBanners__slider--js', {
        // slidesPerView: 5,
        ...defaultSl,
        slidesPerView: 'auto',
        freeMode: true,
        loopFillGroupWithBlank: true,
        touchRatio: 0.2,
        slideToClickedSlide: true,
        freeModeMomentum: true,
    });

    // modal window

    document.addEventListener('click', function(event) {
        let dropdownBtnTarget = event.target.closest('.dropdownBtn--js');
        let dropdownContainer = event.target.closest('.menu-dropdown--js');
        let dropdownWraps = document.querySelectorAll('.menu-dropdown--js');
        if (window.innerWidth > 992) {
            if (dropdownBtnTarget)
                dropdownBtnTarget.closest('.menu-dropdown--js').classList.toggle('active');
            if (!dropdownBtnTarget && !dropdownContainer) {
                dropdownWraps.forEach((dropdownWrap) => {
                    dropdownWrap.classList.remove('active');
                });
            }
        } else {
            if (dropdownBtnTarget) {
                $(dropdownBtnTarget.nextElementSibling).slideToggle();
                $(dropdownBtnTarget.closest('.menu-dropdown--js')).toggleClass('active');
            }
            if (!dropdownBtnTarget && !dropdownContainer) {
                // $(dropdownBtnTarget.nextElementSibling).slideUp();
                dropdownWraps.forEach((dropdownWrap) => {
                    dropdownWrap.classList.remove('active');
                    $(dropdownWrap.querySelector('.dropdown-container')).slideUp();
                });
            }
        }
    });

    // var sc = 0;
    // window.addEventListener('scroll', function () {
    //   if (sc == 0) {
    //     sc = 1;
    //   }
    // });
    let animateBlocks = document.querySelectorAll('[data-json]');
    if (animateBlocks) {
        for (const animateBlock of animateBlocks) {
            lottie.loadAnimation({
                container: animateBlock, // the dom element that will contain the animation
                renderer: 'canvas',
                loop: true,
                autoplay: true,
                path: animateBlock.dataset.json, // the path to the animation json
            });
        }
    }
    // window.onload = function () {
    //   window.scrollTo(window.scrollX, window.scrollY - 1);
    //   window.scrollTo(window.scrollX, window.scrollY + 1);
    // };

    document.addEventListener('click', (event) => {
        let tfootWrapTarget = event.target.closest('.collapse-js tfoot td');
        if (tfootWrapTarget) {
            // $('tbody tr').toggle();
            $('.collapse-js tfoot td').toggleClass('active');
            // $('.collapse-js tbody tr').toggleClass('active');
            $('.collapse-js tbody tr.active').slideUp(function() {
                $(this).removeClass('active');
            });
            $('.collapse-js tbody tr:hidden')
                .slideToggle({
                    start: function() {
                        $(this).css({
                            display: "flex"
                        })
                    }
                })
                .addClass('active');
        }
    });

    const defaultSlider = new Swiper('.breadcrumb-slider--js', {
        slidesPerView: 'auto',
    });

    let defaultSliders = document.querySelectorAll('.defaultSwiper--js');
    if (defaultSliders) {
        for (const defaultSlider of defaultSliders) {
            const defaultSwiper = new Swiper(defaultSlider, {
                slidesPerView: 'auto',
                navigation: {
                    nextEl: defaultSlider.querySelector('.swiper-button-next'),
                    prevEl: defaultSlider.querySelector('.swiper-button-prev'),
                },
                pagination: {
                    el: defaultSlider.querySelector(' .swiper-pagination'),
                    type: 'bullets',
                    clickable: true,
                },
            });
        }
    }

    let cookies = document.querySelector('.cookies');
    if (cookies) {
        cookies.querySelector('.cookies__close').addEventListener('click', function() {
            cookies.classList.add('hide');
        });
    }

    let passInputs = document.querySelectorAll('.form-wrap__input-wrap--pass');
    if (passInputs) {
        passInputs.forEach((passInput) => {
            passInput.querySelector('.form-wrap__pass-btn').addEventListener('click', function() {
                this.classList.toggle('active');
                passInput.querySelector('input').type =
                    passInput.querySelector('input').type == 'password' ? 'text' : 'password';
            });
        });
    }

    var telInputs = document.querySelectorAll('.tel-js');
    if (telInputs) {
        telInputs.forEach((telInput) => {
            intlTelInput(telInput, {
                separateDialCode: true,
                hiddenInput: "full_phone",
                utilsScript: "/storage/site/libs/intl-tel-input/build/js/utils.js?1690975972744",
                initialCountry: 'ru',
                preferredCountries: ['ru', 'by', 'kz'],
            });
        });
    }
    let stickyElem = document.querySelector('.sticky-js');
    if (stickyElem) {
        var Sticky = new hcSticky(stickyElem, {
            stickTo: '.sticky-content-js',
            mobileFirst: true,
            disable: true,
            responsive: {
                992: {
                    disable: false,
                    top: 100,
                },
            },
        });
    }

    let searchBlock = document.querySelector('.search-block');
    if (searchBlock) {
        let searchBlockInput = searchBlock.querySelector('.search-block input');
        let searchBlockBtn = searchBlock.querySelector('.search-block button');
        let searchBlockWrap = searchBlock.querySelector('.search-block__wrap');
        let isFocused = false;
        let isInput = false;
        let isEscaped = false;

        function focusSearchBlock(isFocusedParam, isInputParam) {
            if (isFocusedParam && isInputParam) {
                searchBlockWrap.classList.add('active');
            } else {
                searchBlockWrap.classList.remove('active');
            }
            if (isFocusedParam) {
                searchBlockInput.focus();
                if (isInputParam) {
                    searchBlockBtn.classList.add('active');
                } else {
                    searchBlockBtn.classList.remove('active');
                }
            } else {
                searchBlockInput.blur();
                if (isEscaped) {
                    searchBlockBtn.classList.remove('active');
                    isEscaped = false;
                    searchBlockInput.value = '';
                }
            }
        }
        searchBlockInput.addEventListener('input', () => {
            searchBlockInput.value.length > 0 ? (isInput = true) : (isInput = false);
        });
        document.addEventListener('keyup', function(event) {
            if (event.code === 'Slash') {
                isFocused = true;
            }
            if (event.code === 'Escape') {
                isFocused = false;
                isInput = false;
                isEscaped = true;
            }
            focusSearchBlock(isFocused, isInput);
        });
        document.addEventListener('click', function(event) {
            let searchWrapTarget = event.target.closest('.search-block');
            let searchBtntarget = event.target.closest('.search-block__btn');

            searchWrapTarget && !searchBtntarget ? (isFocused = true) : (isFocused = false);
            searchBlockInput.value.length > 0 ? (isInput = true) : (isInput = false);

            focusSearchBlock(isFocused, isInput);

            if (searchWrapTarget) {
                document.querySelector('body').classList.add('fixed2');
            } else {
                document.querySelector('body').classList.remove('fixed2');
            }
        });
        searchBlockBtn.addEventListener('click', (event) => {
            event.preventDefault();
            event.stopPropagation();

            isFocused = false;
            isEscaped = true;

            focusSearchBlock(isFocused, isInput);
            document.querySelector('body').classList.remove('fixed2');
        });
    };

    let sConnectedBtn = document.querySelector('.sConnected__btn--js');
    if (sConnectedBtn) {
        sConnectedBtn.addEventListener('click', (e) => {
            $(sConnectedBtn).toggleClass('active');
            e.preventDefault();
            $('.sConnected__col.active').slideUp(function() {
                $(this).removeClass('active');
            });
            $('.sConnected__col:hidden').slideDown(function() {
                $(this).addClass('active');
            });
        });
    }
}
if (document.readyState !== 'loading') {
    eventHandler();
} else {
    document.addEventListener('DOMContentLoaded', eventHandler);
}

// window.onload = function () {
// 	document.body.classList.add('loaded_hiding');
// 	window.setTimeout(function () {
// 		document.body.classList.add('loaded');
// 		document.body.classList.remove('loaded_hiding');
// 	}, 500);
// }


$('body').on('change', '.flexSwitchCheckDefault', function() {
    if ($(this).is(':checked')) {
        $('.priceTarif').each(function() {
            $(this).html($(this).attr('data-price-year'));
        })
    } else {
        $('.priceTarif').each(function() {
            $(this).html($(this).attr('data-price-month'));
        })
    }
})