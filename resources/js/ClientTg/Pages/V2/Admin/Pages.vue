<script setup>

import PageList from "@/ClientTg/Components/V2/Admin/Pages/PageList.vue";
import Page from "@/ClientTg/Components/V2/Admin/Pages/Page.vue"


</script>
<template>

    <div class="container py-3">
        <div class="row">
            <div class="col-12">
                <PageList
                    v-if="!loadPageList"
                    :editor="true"
                    v-on:callback="pageListCallback"/>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="page-form-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Редактор</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body mb-5">
                    <Page
                        v-if="!loadPage"
                        :page="page"
                        v-on:callback="pageCallback"/>
                    <div
                        v-else
                        class="alert alert-light d-flex flex-column align-items-center justify-content-center">
                        Подготавливаем данные...
                        <div class="spinner-border text-primary my-3" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
            const pageFormModal = new bootstrap.Modal('#page-form-modal', {})


            this.$nextTick(() => {
                this.loadPage = false
                this.page = page
                pageFormModal.show()

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
