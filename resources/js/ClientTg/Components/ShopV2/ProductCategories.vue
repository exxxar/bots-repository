
<template>
    <Carousel
        v-if="categories"
        :items-to-show="2" :wrap-around="true" :snap-alig="'center'">
        <Slide v-for="(item, index) in categories" :key="item.id">
            <div class="carousel__item selected-category-item"
                 @click="select(item)"
                 v-bind:class="{'bg-primary rounded-5 ':selected_category===item.id}">
                {{ item.title || 'Не указано' }} <span class="font-weight-bold small sub-badge-count">({{ item.count || 0 }})</span>
            </div>
        </Slide>

        <template #addons>
            <Navigation />
        </template>
    </Carousel>

</template>
<script>
import {mapGetters} from "vuex";
import 'vue3-carousel/dist/carousel.css'
import { Carousel, Slide, Pagination, Navigation } from 'vue3-carousel'

export default {
    components: {
        Carousel,
        Slide,
        Pagination,
        Navigation,
    },
    data() {
        return {
            selected_category:null,
            categories: null,
        }
    },
    computed: {
        ...mapGetters(['getCategories']),

    },
    mounted() {
        this.loadCategories()
    },
    methods: {
        nextCategories(index) {
            this.loadCategories(index)
        },
        select(item) {
            this.selected_category = item.id
            return this.$emit("select", item)
        },
        inCategory(id) {
            let index = this.selected.findIndex(item => item.id === id)
            return index >= 0
        },
        loadCategories(page = 0) {
            return this.$store.dispatch("loadCategories", {
                page: page,
                size: 100
            }).then(() => {
                this.categories = this.getCategories
            })
        },
    }
}
</script>
<style>
.bordered {
    border: 1px transparent solid !important;
}
</style>
