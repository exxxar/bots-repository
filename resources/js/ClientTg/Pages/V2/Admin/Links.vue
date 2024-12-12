<script setup>

import PageList from "@/ClientTg/Components/V2/Admin/Pages/PageList.vue";
import Page from "@/ClientTg/Components/V2/Admin/Pages/Page.vue"
import TrafficStatistic from "@/ClientTg/Components/V2/Admin/Statistic/TrafficStatistic.vue";

</script>
<template>

    <div class="container py-3">
        <div class="alert alert-light">
            Данный раздел предназначен для быстрой генерации ссылок с UTM-метками, по которым в дальнейшем будут
            переходит новые пользователи. Результат - отображение статистики по наиболее популярным точкам восприятия
            рекламы пользователем.
        </div>
        <div class="row">
            <div class="col-12">
                <p>Для начала выберите страницу, для которой будет создана ссылка</p>
                <button
                    @click="openPageList"
                    class="btn btn-primary w-100">Выбрать страницу
                </button>
                <p v-if="page" class="text-center mt-3">
                    Вы выбрали страницу "<span class="fw-bold text-primary">{{ page.slug.command }}</span>"
                    (#{{ page.id }})

                </p>
            </div>
            <div class="col-12 p-3" v-if="page">
                <p class="text-center fw-bold">Ссылка на текущую страницу: <span
                    style="word-wrap:break-word;"
                    class="bg-secondary text-white font-bold cursor-pointer"
                    @click="copyToClipBoard(pageLink)">{{
                        pageLink
                    }}</span></p>

                <div class="alert alert-light mb-2">
                    <strong class="fw-bold">Внимание!</strong> Используйте метки <strong
                    class="fw-bold text-primary">на английском языке</strong> или <strong
                    class="fw-bold text-primary">число</strong>!
                </div>
                <div
                    style="position: sticky;bottom: 81px;"
                    class="form-floating mb-2">
                    <input type="text"
                           maxlength="100"
                           v-model="utm_source"
                           class="form-control" id="utm-source" placeholder="name@example.com">
                    <label for="utm-source">Укажите метку для статистики</label>
                </div>

                <div class="d-flex justify-content-center">
                    <img v-lazy="qr" style="width:200px;height:200px;">
                </div>


            </div>

            <div class="col-12" v-if="date">
                <h6 class="fw-bold text-center mt-5">Статистика по переходам</h6>
                <div class="w-100 overflow-x-scroll">
                    <TrafficStatistic
                        :date="date"></TrafficStatistic>
                </div>
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
                <div class="modal-body pb-5">
                    <PageList
                        v-if="!loadPageList"
                        :editor="true"
                        v-on:callback="pageListCallback"/>
                </div>
            </div>
        </div>
    </div>

    <nav class="navbar navbar-expand-sm fixed-bottom p-3 bg-transparent border-0"
         style="border-radius:10px 10px 0px 0px;">
        <button
            @click="copyLink"
            style="box-shadow: 1px 1px 6px 0px #0000004a;"
            :disabled="!page"
            class="btn btn-primary w-100 p-3 rounded-3 shadow-lg d-flex justify-content-center ">
            Скопировать ссылку в буфер
        </button>
    </nav>
</template>
<script>

import {mapGetters} from "vuex";
import {Base64} from "js-base64";

export default {
    data() {
        return {
            date:null,
            page: null,
            utm_source: null,
            loadPage: false,
            loadPageList: false,

            pageFormModal: null,
        }
    },
    computed: {
        bot() {
            return window.currentBot
        },
        qr() {
            return "https://api.qrserver.com/v1/create-qr-code/?size=450x450&qzone=2&data=" + this.pageLink
        },
        pageLink() {
            if (!this.page)
                return "https://t.me/next_service1_bot"

            let tmpId = "";
            for (let i = 0; i < 10 - ("" + this.page.id).length; i++)
                tmpId += "0"
            tmpId += this.page.id;

            return "https://t.me/" + this.bot.bot_domain + "?start="
                + Base64.encode("004" + tmpId + (this.utm_source != null ? "utm" + this.utm_source : ""))

        }
    },
    mounted() {
        this.pageFormModal = new bootstrap.Modal('#page-form-modal', {})
        this.loadBotAdminConfig();
        const currentDate = new Date();
        const startDate = new Date(new Date().setDate(currentDate.getDate() - 31));
        const endDate = new Date(new Date().setDate(currentDate.getDate() + 31));
        this.date = [startDate, endDate];
    },
    methods: {

        copyLink(){
          this.copyToClipBoard(this.pageLink)
        },
        copyToClipBoard(text) {
            navigator.clipboard.writeText(text).then(() => {
                this.$notify({
                    title: "Копирование",
                    text: "Ссылка скопирована в буфер"
                })
            }).catch((err) => {
                this.$notify({
                    title: "Копирование",
                    text: "Ошибка копирования",
                    type: "error"
                })
            });
        },
        loadBotAdminConfig() {
            this.$store.dispatch("loadBotAdminConfig").then((resp) => {
                this.bot = resp.data

            })
        },
        openPageList() {

            this.pageFormModal.show()
        },

        pageListCallback(page) {
            this.loadPage = true
            this.$nextTick(() => {
                this.page = page
                this.loadPage = false
                this.pageFormModal.hide()
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
