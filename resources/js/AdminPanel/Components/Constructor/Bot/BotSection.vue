<script setup>
import KeyboardList from "@/AdminPanel/Components/Constructor/KeyboardList.vue";
import BotSlugList from "@/AdminPanel/Components/Constructor/Slugs/BotSlugTableList.vue";
import BotUserList from "@/AdminPanel/Components/Constructor/BotUserList.vue";
import BotForm from "@/AdminPanel/Components/Constructor/Bot/BotForm.vue";

import PagesList from "@/AdminPanel/Components/Constructor/Pages/PagesList.vue";
import Page from "@/AdminPanel/Components/Constructor/Pages/Page.vue"
import BotDialogGroupList from "@/AdminPanel/Components/Constructor/Dialogs/BotDialogGroupList.vue";
import Shop from "@/AdminPanel/Components/Constructor/Shop/Shop.vue";
import AmoForm from "@/AdminPanel/Components/Constructor/Amo/AmoForm.vue";
import YClientsForm from "@/AdminPanel/Components/Constructor/YClients/YClientsForm.vue";
import Mail from "@/AdminPanel/Components/Constructor/Mail/Mail.vue";
import BotFields from "@/AdminPanel/Components/Constructor/Bot/BotFields.vue";
import BotMediaTable from "@/AdminPanel/Components/Constructor/BotMediaTable.vue";

</script>
<template>

    <div class="dropdown custom-dropdown">
        <button
            style="min-width: 50px;min-height: 50px;"
            type="button"
            class="btn btn-outline-primary dropdown-toggle mb-3" href="#" role="button"
            id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fa-solid fa-screwdriver-wrench mr-2"></i> Инструменты бота
        </button>

        <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">

            <li><a class="dropdown-item" href="#bot-info" @click="setStep(0)">
                <i class="fa-solid fa-info mr-2"></i>Информация о боте</a></li>

            <li><a class="dropdown-item" href="#bot-pages" @click="setStep(4)">
                <i class="fa-solid fa-file mr-2"></i>Страницы</a></li>

            <li><a class="dropdown-item" href="#bot-custom-fields" @click="setStep(10)">
                <i class="fa-solid fa-code mr-2"></i>Настраиваемые поля</a></li>

            <li><a class="dropdown-item" href="#bot-menu-template" @click="setStep(1)"><i
                class="fa-solid fa-keyboard mr-2"></i>Все клавиатуры в боте</a></li>
            <li><a class="dropdown-item" href="#bot-slugs" @click="setStep(2)"><i
                class="fa-solid fa-scroll mr-2"></i>Все скрипты в боте</a></li>
            <li><a class="dropdown-item" href="#bot-dialogs" @click="setStep(6)"><i
                class="fa-solid fa-comment-dots mr-2"></i>Все диалоги в боте</a></li>
            <li><a class="dropdown-item" href="#bot-users" @click="setStep(3)"><i
                class="fa-solid fa-users mr-2"></i>Все пользователи в боте</a></li>
            <li><a class="dropdown-item" href="#bot-news" @click="setStep(9)"><i
                class="fa-regular fa-newspaper mr-2"></i> Новостной канал</a></li>
            <li><a class="dropdown-item" href="#bot-amo" @click="setStep(7)"><i
                class="fa-solid fa-list-check mr-2"></i> AMO CRM</a></li>
            <li><a class="dropdown-item" href="#bot-y-clients" @click="setStep(12)"><i
                class="fa-solid fa-list-check mr-2"></i> YClients</a></li>
            <li><a class="dropdown-item" href="#bot-shop" @click="setStep(8)"><i
                class="fa-brands fa-shopify mr-2"></i> Магазин</a></li>
            <li><a class="dropdown-item" href="#bot-media" @click="setStep(11)"><i
                class="fa-brands fa-shopify mr-2"></i> Медиа файлы бота</a></li>
        </ul>
    </div>

    <div class="row" v-if="company">
        <div class="col-12">
            <h6>Создаем бот к компании {{ company.title || 'Не установлен' }}</h6>
        </div>
    </div>


    <div v-if="step===0" class="pb-5 mb-5">
        <BotForm
            :bot="bot"
            v-if="!load"
        />
    </div>

    <div v-if="step===7" class="pb-5 mb-5">
        <AmoForm
            :data="bot.amo"
            v-if="!load"
        />
    </div>

    <div v-if="step===12" class="pb-5 mb-5">
        <YClientsForm
            :data="bot.y_clients"
            v-if="!load"
        />
    </div>

    <div v-if="step===11" class="pb-5 mb-5">
        <BotMediaTable
            v-if="!load"
        />
    </div>


    <div v-if="step===8" class="pb-5 mb-5">
        <Shop v-if="!load"/>
    </div>

    <div v-if="step===10" class="pb-5 mb-5">
        <BotFields v-if="!load"/>
    </div>

    <div v-if="step===1" class="pb-5 mb-5">
        <KeyboardList
            :select-mode="false"
            v-if="!load"/>
    </div>

    <div v-if="step===6" class="pb-5 mb-5">
        <BotDialogGroupList
            v-if="!load"/>
    </div>

    <div v-if="step===2" class="pb-5 mb-5">
        <BotSlugList
            v-if="!load"
        />
    </div>

    <div v-if="step===3" class="pb-5 mb-5">
        <BotUserList
            v-if="!load"/>
    </div>

    <div class="row pb-5 mb-5" v-if="step===4">
        <div class="col-12 col-md-12" v-if="!load">
            <Page
                v-if="!loadPage"
                :page="page"
                v-on:callback="pageCallback"/>
        </div>

<!--        <div class="col-12 col-md-4" v-if="!load">
            <PagesList
                :editor="true"
                v-on:callback="pageListCallback"/>

        </div>-->
    </div>

    <div v-if="step===9" class="pb-5 mb-5">
        <Mail/>
    </div>


</template>
<script>

import {mapGetters} from "vuex";

export default {
    props: ["company", "bot"],
    data() {
        return {
            page: null,
            step: 0,
            load: false,
            loadPage: false,
        }
    },
    mounted() {
        this.setStep(localStorage.getItem("cashman_set_botform_step_index") || 0)
    },
    methods: {
        setStep(index) {
            this.step = parseInt(index)
            localStorage.setItem("cashman_set_botform_step_index", index)
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
        }
    }
}
</script>

<style>
.custom-dropdown {
    display: inline-block;
    position: sticky;
    top: 50px;
    background: white;
    z-index:101;
}
</style>
