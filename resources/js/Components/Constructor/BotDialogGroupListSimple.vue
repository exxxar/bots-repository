<script setup>
import Pagination from '@/Components/Pagination.vue';
import DialogCommandCard from "@/Components/Constructor/DialogCommandCard.vue";
</script>
<template>
    <div class="row" v-if="dialog_groups.length>0">
        <div class="col-12 mb-3">
            <div class="card mb-2" v-for="(group, index) in dialog_groups">
                <div class="card-header">
                    <h6> #{{ group.id }} - {{ group.title || 'Не указано ' }} ({{ group.slug || 'Не указано' }})</h6>
                </div>
                <div class="card-body">
                    <div class="row" v-if="group.bot_dialog_commands.length>0">
                        <div class="col-md-6 col-lg-4 col-12 col-sm-6 mb-2 "
                             v-for="(command, index) in group.bot_dialog_commands">
                            <div v-bind:class="{'select-dialog':selected_dialog_id==command.id}">
                                <DialogCommandCard
                                    :simple="true"
                                    v-on:select="selectDialog"
                                    :item="command"/>
                            </div>

                        </div>
                    </div>
                    <div class="row" v-else>
                        <div class="col-12">
                            <p>Диалоговых скриптов не найдено</p>
                        </div>
                    </div>

                </div>

            </div>


        </div>


        <div class="col-12">
            <Pagination
                v-on:pagination_page="nextGroups"
                v-if="dialog_groups_paginate_object"
                :pagination="dialog_groups_paginate_object"/>
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
    props: ["botId"],

    data() {
        return {
            groupForm: {
                slug: null,
                title: null,
            },
            loading: true,
            dialog_groups: [],
            search: null,
            dialog_groups_paginate_object: null,
            link: null,
            selected_dialog_id:null,
            group: null,
        }
    },
    computed: {
        ...mapGetters(['getDialogGroups', 'getDialogGroupsPaginateObject']),
    },
    mounted() {
        this.loadGroups();
    },
    methods: {

        selectDialog(command){
            this.$emit("select-dialog", command)
            this.selected_dialog_id = command.id
            this.$notify("Вы выбрали диалог из списка!");
        },
        nextGroups(index) {
            this.loadGroups(index)
        },
        loadGroups(page = 0) {
            this.loading = true
            this.$store.dispatch("loadDialogGroups", {
                dataObject: {
                    botId: this.botId || null,
                    search: this.search
                },
                page: page
            }).then(resp => {
                this.loading = false
                this.dialog_groups = this.getDialogGroups
                this.dialog_groups_paginate_object = this.getDialogGroupsPaginateObject
            }).catch(() => {
                this.loading = false
            })
        }
    }
}
</script>
<style lang="scss">
.select-dialog {
    position:relative;
    &:after {
        content: '';
        position: absolute;
        top: 20px;
        right: 20px;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        background-color: darkgreen;
    }
}
</style>
