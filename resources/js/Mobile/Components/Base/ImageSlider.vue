<template>

        <!-- carousel multiple -->
        <Splide :options="options||defaultSplideOptions" class="carousel-multiple">
            <SplideSlide v-for="(slide, index) in (slides||defaultSlides)" :key="index">
                <div class="card">
                    <img :src="slide.img"
                         class="slider-image" :alt="slide.alt"/>
                    <div class="card-body pt-2">
                        <h4 class="mb-0">{{ slide.title }}</h4>
                    </div>
                </div>
            </SplideSlide>
        </Splide>
        <!-- * carousel multiple -->

</template>

<script>
import {Splide, SplideSlide} from '@splidejs/vue-splide'
import '@splidejs/vue-splide/css';

export default {
    props:["options","slides"],
    components: {
        Splide,
        SplideSlide,
    },
    data() {
        return {
            defaultSplideOptions: {
                type: 'loop',
                perPage: 1,
                autoplay: true,
                interval: 3000,
                pauseOnHover: true,
                arrows: true,
                pagination: true,
            },
            defaultSlides: [
                {img: '/Image/lDjcOncYLW0.jpg', alt: 'image', title: 'Progressive web app ready'},
                {img: '/Image/lDjcOncYLW0.jpg', alt: 'image', title: 'Reusable components'},
                {img: '/Image/lDjcOncYLW0.jpg', alt: 'image', title: 'Great for phones & tablets'},
                {img: '/Image/lDjcOncYLW0.jpg', alt: 'image', title: 'Change the styles in sass'},
                {img: '/Image/lDjcOncYLW0.jpg', alt: 'image', title: 'Sketch source file included'},
                {img: '/Image/lDjcOncYLW0.jpg', alt: 'image', title: 'RTL (Right to Left) Support'},
                {img: '/Image/lDjcOncYLW0.jpg', alt: 'image', title: 'Written with a code structure'},
            ]
        }
    }
}

</script>

<style scoped>
.slider-image {
    width: 100%;
    height: auto;
    object-fit: cover;
    border-radius: 12px;
}
</style>
