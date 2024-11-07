'use strict';
class Stories {
    // ====================================================================================================

    progressBar;
    modalSwiper;

    static getAutoplay() {
        return 5000;
    }
    static getSlides() {

        return document.querySelectorAll(".modal-slider__slide");
    }
    static settings(index) {
        return {
            slidesPerView: 1,
            observer: true,
            initialSlide: index,
            navigation: {
                nextEl: '.modal-slider__arrow-wrap .swiper-button-next',
                prevEl: '.modal-slider__arrow-wrap .swiper-button-prev',
            },
            autoplay: {
                delay: Stories.getAutoplay(),
                stopOnLastSlide: true,
                disableOnInteraction: false,
            },
            on: {
                slideChangeTransitionEnd(swiper) {
                    Stories.setAnimate(swiper.activeIndex + 1, 300);

                    $(`.headerBlock__inner-row  .col-auto:nth-child(${swiper.activeIndex}) .headerBlock__stories-item--js`).removeClass('active');
                    // setTimeout(() => {
                    // 	}, 3000)
                },
                reachEnd(swiper) {

                    setTimeout(() => {
                        console.log(swiper.progress);
                        if (swiper.progress == 1) {

                            $(`.headerBlock__inner-row  .col-auto:nth-child(${swiper.activeIndex + 1}) .headerBlock__stories-item--js`).removeClass('active');
                            Fancybox.close();
                        }
                    }, Stories.getAutoplay() + 300);
                }
            },

        }
    }
    // let modalSwiper = new Swiper('.modal-slider__slider--js', settings);

    static setAnimate(index, speed = 0) {
        this.progressBar = document.querySelector('.modal-slider__status-line-wrap span');
        let currentIndex = 0;
        this.progressBar.animate(
            [{
                    transform: "scaleX(0)"
                },
                {
                    transform: "scaleX(1)"
                },
            ], {
                duration: this.getAutoplay() + speed,
                iterations: 1,
                fill: "both"
            }
        ).finished.then(() => {
            // if (index == 3 && currentIndex <= index) {
            // 		Fancybox.close();
            // 	currentIndex = index;
            // 	}
        })
    }

    // modalSwiper.disable();


    static showModal() {
        $(document).on('click', '[data-src="modal-stories"]', function() {
            let index = $(this).parent().index();
            Stories.modalSwiper = new Swiper('.modal-slider__slider--js', Stories.settings(index));
            Stories.setAnimate();
            // modalSwiper.enable()
            // Stories.modalSwiper.slideTo(index);


        });

        Fancybox.bind('[data-src="modal-stories"]', {
            on: {
                destroy: () => {
                    console.log('close');
                    // this.animateBlock.pause();
                    Stories.modalSwiper.destroy();
                    // modalSwiper = new Swiper('.modal-slider__slider--js', settings)
                },
            },
            src: "#modal-stories",
            type: "inline",
            arrows: false,
            // infobar: false,
            touch: false,
            trapFocus: false,
            placeFocusBack: false,
            infinite: false,
            dragToClose: false,
            autoFocus: false,
            groupAll: false,
            groupAttr: false,
        })
    }

    static init() {
        this.showModal();
    }
    // ===========================================================================
}
if (document.readyState !== 'loading') {
    Stories.init();
} else {
    document.addEventListener('DOMContentLoaded', Stories.init());
}