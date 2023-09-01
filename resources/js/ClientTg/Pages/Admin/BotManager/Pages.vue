<script setup>

import PagesList from "@/ClientTg/Components/Admin/Pages/PagesList.vue";
import Page from "@/ClientTg/Components/Admin/Pages/Page.vue"


</script>
<template>

        <div class="card card-style">
            <div class="content mb-0">
                <PagesList
                    v-if="!loadPageList"
                    :editor="true"
                    v-on:callback="pageListCallback"/>
            </div>
        </div>

        <Page
            v-if="!loadPage"
            :page="page"
            v-on:callback="pageCallback"/>


</template>
<script>

import {mapGetters} from "vuex";

export default {
    data() {
        return {
            page: null,

            loadPage: false,
            loadPageList: false,

            bot: null,

        }
    },

    mounted() {

        this.loadBotAdminConfig();

    },
    methods: {
        loadBotAdminConfig() {
            this.$store.dispatch("loadBotAdminConfig").then((resp) => {
                this.bot = resp.data

            })
        },

        pageListCallback(page) {
            this.loadPage = true
            this.page = page
            this.$nextTick(() => {
                this.loadPage = false

            });
        },

        pageCallback(page) {
            this.loadPageList = true
            this.$nextTick(() => {
                this.loadPageList = false
            });
        },

    }
}
</script>
<style lang="scss">

</style>
