<script setup>
import Pagination from '@/ClientTg/Components/V1/Pagination.vue';
import DialogCommandCard from "@/ClientTg/Components/V2/Admin/Dialogs/DialogCommandCard.vue";
import BotDialogCommandForm from "@/ClientTg/Components/V2/Admin/Dialogs/BotDialogCommandForm.vue";

</script>
<template>





    <template
        v-if="dialog_groups.length>0">

        <div v-for="(group, index) in dialog_groups">
            <ul class="list-group" v-if="group.bot_dialog_commands.length>0">
                <li class="list-group-item p-2"
                     v-for="(command, index) in group.bot_dialog_commands">
                    <DialogCommandCard
                        v-if="bot"
                        :bot="bot"
                        v-on:callback="loadGroups"
                        :item="command"/>
                </li>
            </ul>


            <p v-else>Диалоговых скриптов не найдено</p>


        </div>


    </template>
    <div class="row" v-else>
        <div class="col-12">
            <div class="alert alert-warning" role="alert">
                У выбранного бота нет созданных диалоговых групп
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="create-bot-dialog" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Редактор</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <BotDialogCommandForm
                        v-if="bot"
                        v-on:callback="loadGroups"
                        :bot="bot"/>
                </div>
            </div>
        </div>
    </div>

    <button
        style="z-index: 100;bottom:10px;"
        type="button"
        data-bs-toggle="modal" data-bs-target="#create-bot-dialog"
        class="btn btn-primary p-3 w-100 my-2  position-sticky">
        <i class="fa-regular fa-comment-dots mr-2"></i>Создать новый диалог
    </button>

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
