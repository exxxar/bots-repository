<script setup>
import CategoryItem from "@/ClientTg/Components/Shop/Categories/CategoryItem.vue";
import Pagination from "@/ClientTg/Components/Pagination.vue";
</script>
<template>
    <div v-if="categories" class="d-flex flex-wrap scrollable-area">

        <a
            v-for="item in categories"
            href="javascript:void(0)"
            @click="select(item)"
            v-bind:class="{'bg-green2-dark':inCategory(item.id),'bg-gray2-dark':active.indexOf(item.id)===-1&&!inCategory(item.id)}"
            class="m-0 mb-2 badge bg-red2-light mr-2 rounded-l font-14 category-badge p-3">

            {{ item.title || 'Не указано' }} <span class="   font-14">{{ item.count || 0 }}</span>


        </a>
    </div>

    <Pagination
        :simple="true"
        v-on:pagination_page="nextCategories"
        v-if="categories_paginate_object && categories_paginate_object.meta.total > size"
        :pagination="categories_paginate_object"/>
</template>
<script>
import {mapGetters} from "vuex";

export default {
    props: ["size", "selected","active"],
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

.category-badge {
    display: inline-flex;
    width: 100%;
    text-align: left;
    white-space: normal;
    justify-content: space-between;
}

.scrollable-area {
    max-height: 200px;
    overflow-y: scroll;
}
</style>
