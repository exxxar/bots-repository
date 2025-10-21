<script setup>
import ProductCard from "@/ClientTg/Components/V2/Shop/ProductCard.vue";

import 'vue3-carousel/dist/carousel.css'
import {Carousel, Slide, Pagination, Navigation} from 'vue3-carousel'
import PartnerCard from "@/ClientTg/Components/V2/Shop/Partners/PartnerCard.vue";
const carouselConfig = {
    itemsToShow: 1.9,
    wrapAround: true
}
</script>
<template>

    <div class="my-3" v-if="(partners||[]).length>0">

            <Carousel v-bind="carouselConfig">
                <Slide :key="0">
                    <div class="carousel__item">
                        <PartnerCard :partner="bot"
                                     style="width:190px;"
                                     v-on:select="selectPartner"
                                     :key="'partner-0'"></PartnerCard>
                    </div>
                </Slide>

                <Slide v-for="partner in partners" :key="partner.id">
                    <div class="carousel__item">
                        <PartnerCard
                            v-on:select="selectPartner"
                            :partner="partner" style="width:190px;"></PartnerCard>
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
