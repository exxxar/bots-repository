<script setup>
import CategoryItem from "@/ClientTg/Components/Shop/Categories/CategoryItem.vue";
import Pagination from "@/ClientTg/Components/Pagination.vue";
</script>
<template>
    <div v-if="categories">

        <a
            v-for="item in categories"
            href="javascript:void(0)"
            @click="select(item)"
            style="height:80px;"
            v-bind:class="{'border-blue2-dark':inCategory(item.id)}"
            class="card card-style m-0 mb-2 bordered">
            <div class="card-center">
                <h5 class="pl-3">{{ item.title || 'Не указано' }}</h5>
                <p class="pl-3 mt-n2 font-12 color-highlight mb-0"><span
                    class="font-weight-bold">{{ item.count || 0 }}</span> ед. товаров в категории</p>
            </div>
            <div class="card-center opacity-30">
                <i class="fa fa-arrow-right opacity-50 float-right color-theme pr-3"></i>
            </div>
        </a>
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
    props: ["size", "selected"],
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
        select(item) {
            return this.$emit("select", item)
        },
        inCategory(id) {
            let index = this.selected.findIndex(item => item.id === id)
            return index >= 0
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
<style>
.bordered {
    border: 1px transparent solid !important;
}
</style>
