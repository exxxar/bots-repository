<script setup>
import Pagination from '@/ClientTg/Components/V1/Pagination.vue';

</script>
<template>

    <div v-if="dialogs.length>0" >
        <div v-for="(dialog, index) in dialogs" class="mb-2">
            <h6> #{{ dialog.id }} - {{ dialog.title || 'Не указано ' }} ({{ dialog.slug || 'Не указано' }})</h6>
            <div class="list-group list-boxes " v-if="dialog.bot_dialog_commands.length>0">
                <div
                    @click="selectDialog(command)"
                   v-for="(command, index) in dialog.bot_dialog_commands"
                   class="border border-green1-dark rounded-s shadow-xs p-3 mb-2">
                  #{{command.id}} {{ command.pre_text || '-' }}
                </div>

            </div>
        </div>
        <Pagination
            v-on:pagination_page="nextDialogs"
            v-if="dialogs_paginate_object"
            :pagination="dialogs_paginate_object"/>
    </div>

    <div v-else class="alert alert-warning" role="alert">
        У выбранного бота нет созданных диалоговых групп
    </div>


</template>
<script>
import {mapGetters} from "vuex";

export default {
    data() {
        return {
            loading: true,
            dialogs: [],
            search: null,
            dialogs_paginate_object: null,
        }
    },
    computed: {
        ...mapGetters(['getDialogs', 'getDialogsPaginateObject']),
    },
    mounted() {
        this.loadDialogs();
    },
    methods: {

        selectDialog(command){
            this.$emit("select-dialog", command)

            this.$botNotification.notification("Диалоги","Вы выбрали диалог из списка!");
        },
        nextDialogs(index) {
            this.loadDialogs(index)
        },
        loadDialogs(page = 0) {
            this.loading = true
            this.$store.dispatch("loadDialogs", {
                dataObject: {
                    search: this.search,
                },
                page: page
            }).then(resp => {
                this.loading = false
                this.dialogs = this.getDialogs
                this.dialogs_paginate_object = this.getDialogsPaginateObject
            }).catch(() => {
                this.loading = false
            })
        }
    }
}
</script>

