<script setup>
import Pagination from "@/ClientTg/Components/Pagination.vue";
</script>
<template>

    <div class="list-group" v-if="categories">
        <a
            href="javascript:void(0)"
            @click="select(null)"
            v-bind:class="{'active':inCategory(null)}"
            class="list-group-item list-group-item-action d-flex justify-content-between p-3" aria-current="true">
            Все категории товаров
        </a>

        <a
            href="javascript:void(0)"
            @click="select(item)"
            v-for="item in categories"
            v-bind:class="{'active':inCategory(item.id)}"
            class="list-group-item list-group-item-action d-flex justify-content-between p-3 align-items-center font-weight-bold" aria-current="true">
            {{ item.title || 'Не указано' }}<span class="badge text-bg-primary">{{ item.count || 0 }}</span>
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
    props: ["selected", "active"],
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
            if (!this.selected)
                return false;

            let index = this.selected.findIndex(item => (item || {id: null}).id === id)
            return index >= 0
        },
        loadCategories(page = 0) {
            return this.$store.dispatch("loadCategories", {
                page: page,
                size: 20
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
