<script setup>
import CategoryItem from "@/ClientTg/Components/Shop/Categories/CategoryItem.vue";
import Pagination from "@/ClientTg/Components/Pagination.vue";
</script>
<template>
    <div v-if="categories">
        <CategoryItem v-for="item in categories" :item="item"/>
    </div>

    <Pagination
        :simple="true"
        v-on:pagination_page="nextCategories"
        v-if="categories_paginate_object"
        :pagination="categories_paginate_object"/>
</template>
<script>
import {mapGetters} from "vuex";

export default {
    props: ["size"],
    data() {
        return {
            categories: null,
            categories_paginate_object: null,
        }
    },
    computed: {
        ...mapGetters(['getCategories', 'getCategoriesPaginateObject']),
    },
    mounted() {
        this.loadCategories()
    },
    methods: {
        nextCategories(index) {
            this.loadCategories(index)
        },

        loadCategories(page = 0) {
            return this.$store.dispatch("loadCategories", {
                page: page,
                size: this.size || 5
            }).then(() => {
                this.categories = this.getCategories
                this.categories_paginate_object = this.getCategoriesPaginateObject
            })
        },
    }
}
</script>
