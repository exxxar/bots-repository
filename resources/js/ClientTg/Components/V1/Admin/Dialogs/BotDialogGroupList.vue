<script setup>
import Pagination from '@/ClientTg/Components/V1/Pagination.vue';
import DialogCommandCard from "@/ClientTg/Components/V1/Admin/Dialogs/DialogCommandCard.vue";
import BotDialogCommandForm from "@/ClientTg/Components/V1/Admin/Dialogs/BotDialogCommandForm.vue";

</script>
<template>

<!--    <div class="mb-3">
        <input type="search" class="form-control mb-2"
               placeholder="Поиск группы"
               aria-label="Поиск группы"
               v-model="search"
               aria-describedby="button-addon2">
        <button class="btn btn-m btn-full mb-2 rounded-xs text-uppercase font-900 shadow-s bg-blue2-dark w-100"
                @click="loadGroups"
                type="button"
                id="button-addon2">Найти
        </button>
    </div>

    <div class="divider divider-small my-3 bg-highlight "></div>-->

    <button
        type="button"
        @click="needCreate=!needCreate"

        class="btn btn-m btn-full mb-2 rounded-xs text-uppercase font-900 shadow-s bg-green2-dark w-100">

        <span v-if="!needCreate">
            <i class="fa-regular fa-comment-dots mr-2"></i>Создать новый диалог
        </span>
        <span v-else>
            <i class="fa-solid fa-chevron-up mr-2"></i>Скрыть создание диалога
        </span>

    </button>

    <BotDialogCommandForm
        v-if="bot&&needCreate"
        v-on:callback="loadGroups"
        :bot="bot"/>

    <div class="divider divider-small my-3 bg-highlight "></div>

    <div v-if="dialog_groups.length>0">

        <div class="mb-2" v-for="(group, index) in dialog_groups">
            <div v-if="group.bot_dialog_commands.length>0">
                <div class="mb-2"
                     v-for="(command, index) in group.bot_dialog_commands">
                    <DialogCommandCard
                        v-if="bot"
                        :bot="bot"
                        v-on:callback="loadGroups"
                        :item="command"/>
                </div>
            </div>


            <p v-else>Диалоговых скриптов не найдено</p>


        </div>


    </div>
    <div class="row" v-else>
        <div class="col-12">
            <div class="alert alert-warning" role="alert">
                У выбранного бота нет созданных диалоговых групп
            </div>
        </div>
    </div>


</template>
<script>
import {mapGetters} from "vuex";

export default {

    props: ["bot"],
    data() {
        return {
            needCreate: false,
            loading: true,
            dialog_groups: [],
            search: null,
            link: null,
            group: null,
        }
    },
    computed: {
        ...mapGetters(['getDialogs', 'getDialogsPaginateObject']),
        filteredCommands() {
            if (!this.link)
                return [];

            let group = this.dialog_groups.find(group => group.id === this.link.group_id)

            return group.bot_dialog_commands.filter(command => command.id != this.link.id)

        }
    },
    mounted() {
        this.loadGroups();

    },
    methods: {


        loadGroups(page = 0) {
            this.loading = true
            this.$store.dispatch("loadDialogs", {
                dataObject: {
                    search: this.search
                },
                page: page
            }).then(resp => {
                this.loading = false
                this.dialog_groups = this.getDialogs
                this.dialog_groups_paginate_object = this.getDialogsPaginateObject
            }).catch(() => {
                this.loading = false
            })
        }
    }
}
</script>
