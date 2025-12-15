<script setup>
import ProductCard from "@/ClientTg/Components/V2/Shop/ProductCard.vue";

import 'vue3-carousel/dist/carousel.css'
import {Carousel, Slide, Pagination, Navigation} from 'vue3-carousel'
import PartnerCard from "@/ClientTg/Components/V2/Shop/Partners/PartnerCard.vue";
const carouselConfig = {
    itemsToShow: 1.3,
    wrapAround: true
}
</script>
<template>

    <div class="my-3" v-if="(partners||[]).length>0">

            <Carousel v-bind="carouselConfig">
<!--                <Slide :key="0">
                    <div class="carousel__item">
                        {{bot}}
                    </div>
                </Slide>-->

                <Slide v-for="partner in partners" :key="partner.id">
                    <div class="carousel__item">
                       <div class="p-3">
                           <button
                               @click="selectPartner(partner)"
                               class="btn btn-outline-light p-3 text-primary" style="width:250px;">{{partner.title}}</button>
                       </div>
                    </div>
                </Slide>

                <template #addons>
                    <Navigation />
                    <Pagination />
                </template>
            </Carousel>

    </div>


</template>

<script>

export default {
    name: 'PartnerList',
    data() {
        return {};
    },
    computed: {
        bot() {
            return window.currentBot || null
        },
        partners() {
            return this.bot.partners || []
        }
    },
    mounted() {

    },
    methods: {
        selectPartner(partner){
            this.$emit('select', partner)
            this.$preloader.show()
        }
    },
};
</script>

<style lang="scss" scoped>
.carousel__item {
    padding: 5px;
}

</style>
