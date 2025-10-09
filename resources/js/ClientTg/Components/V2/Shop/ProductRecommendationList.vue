<script setup>
import ProductCard from "@/ClientTg/Components/V2/Shop/ProductCard.vue";

import 'vue3-carousel/dist/carousel.css'
import { Carousel, Slide, Pagination, Navigation } from 'vue3-carousel'

const carouselConfig = {
    itemsToShow: 2.5,
    wrapAround: true
}
</script>
<template>

    <div class="row my-3">
        <div class="col-12">
            <Carousel v-bind="carouselConfig">
                <Slide   v-for="product in products" :key="product.id">
                    <div class="carousel__item">
                        <ProductCard
                            :item="product"
                        />
                    </div>
                </Slide>

                <template #addons>

                </template>
            </Carousel>

        </div>
    </div>



</template>

<script>

export default {
    name: 'ProductRecommendationList',
    data() {
        return {
            products:[]
        };
    },
    mounted() {
        this.loadRecommendedProducts()
    },
    methods: {
        loadRecommendedProducts() {
            return this.$store.dispatch("loadRecommendedProducts").then((response) => {
                this.products = response.data || []
            })
        },
    },
};
</script>

<style scoped>
.carousel__item {
    padding:5px;
}

</style>
