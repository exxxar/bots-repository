<script setup>
import Pagination from '@/Components/Pagination.vue';
import DialogCommandCard from "@/Components/Constructor/DialogCommandCard.vue";
import BotList from "@/Components/Constructor/BotList.vue";
import BotDialogCommandForm from "@/Components/Constructor/BotDialogCommandForm.vue";

</script>
<template>

    <div class="row">
        <div class="input-group mb-3">
            <input type="search" class="form-control"
                   placeholder="Поиск группы"
                   aria-label="Поиск группы"
                   v-model="search"
                   aria-describedby="button-addon2">
            <button class="btn btn-outline-secondary"
                    @click="loadGroups"
                    type="button"
                    id="button-addon2">Найти
            </button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-12">
            <button type="button"
                    data-bs-toggle="modal" data-bs-target="#group-create-modal"
                    class="btn btn-outline-success mt-2 w-100 p-3 mb-2">
                <i class="fa-solid fa-layer-group" style="margin-right:10px;"></i>Создать новую группу
            </button>

        </div>

        <div class="col-12 col-md-6">
            <button
                type="button"
                data-bs-toggle="modal" data-bs-target="#dialog-create-modal"
                class="btn btn-outline-success mt-2 w-100 p-3 mb-2">
                <i class="fa-regular fa-comment-dots" style="margin-right:10px;"></i>Создать новый диалоговый скрипт
            </button>
        </div>
    </div>



    <div class="row" v-if="dialog_groups.length>0">
        <div class="col-12 mb-3">
            <div class="card mb-2" v-for="(group, index) in dialog_groups">
                <div class="card-header">
                    <h6> #{{ group.id }} - {{ group.title || 'Не указано ' }} ({{ group.slug || 'Не указано' }})
                        <button
                            @click="removeGroup(group.id)"
                            type="button" class="btn btn-outline-danger">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </h6>



                </div>
                <div class="card-body">
                    <div class="row" v-if="group.bot_dialog_commands.length>0">
                        <div class="col-md-12 col-lg-4 col-12 col-sm-12 mb-2"
                             v-for="(command, index) in group.bot_dialog_commands">
                            <DialogCommandCard
                                v-on:callback="loadGroups"
                                v-on:select="selectDialog"
                                v-on:swap="swapGroupInit"

                                v-on:link="tryLink"
                                :item="command"/>
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

    <div class="modal fade" id="link-modal"
         tabindex="-1"
    >
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">
                        Диалог связывания
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" v-if="link">
                    <p>Вы выбрали диалоговую команду #{{ link.id }}</p>
                    <h6>Связать её с:</h6>
                    <div class="row">
                        <div class="col-md-2 mb-1" v-for="(command, index) in filteredCommands">
                            <button type="button"
                                    @click="doCommandLink(command.id)"
                                    v-bind:class="{'btn-primary text-white':link.current_next_id == command.id}"
                                    class="btn btn-outline-primary w-100">#{{ command.id }}
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="group-modal"
         tabindex="-1"
    >
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">
                        Диалог перемещения между группами
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" v-if="group">
                    <p>Вы выбрали диалоговую команду #{{ group.command_id }}</p>
                    <h6>Переместить её в группу</h6>
                    <div class="row" v-if="!loading">
                        <div class="col-md-2 mb-1" v-for="(item, index) in dialog_groups">
                            <button type="button"
                                    @click="doSwapGroup(item.id )"
                                    v-bind:class="{'btn-primary text-white':item.id == group.group_id}"
                                    class="btn btn-outline-primary w-100">#{{ item.id }}
                            </button>
                        </div>
                    </div>
                    <div class="row" v-else>
                        <div class="col-12">
                            <h6>Перемещаем-с...</h6>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="group-create-modal"
         tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">
                        Диалог создания группы
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning" role="alert">
                        Группировка имеет исключительно логическое значение! Группа помогает Вам ориентироваться в
                        цепочках диалогов
                    </div>

                    <form v-on:submit.prevent="addGroup">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="floatingInput"
                                   maxlength="255"
                                   pattern="[a-zA-Z0-9_]+"
                                   v-model="groupForm.slug" required>
                            <label for="floatingInput">Мнемоническое имя (англ)</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="floatingInput"
                                   maxlength="255"
                                   v-model="groupForm.title" required>
                            <label for="floatingInput">Название группы</label>
                        </div>
                        <button type="submit" class="btn btn-outline-primary w-100 p-3">Создать</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="dialog-create-modal"
         tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">
                        Создание диалогового сообщения
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <BotDialogCommandForm :bot="bot"/>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                </div>
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
            groupForm: {
                slug: null,
                title: null,
                bot_id: null,

            },
            loading: true,
            dialog_groups: [],
            search: null,
            dialog_groups_paginate_object: null,
            link: null,
            group: null,
        }
    },
    computed: {
        ...mapGetters(['getDialogGroups', 'getDialogGroupsPaginateObject']),
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
        removeGroup(dialogGroupId){
            this.loading = true

            this.$store.dispatch("removeDialogGroup", {
                dataObject: {
                    dialogGroupId:dialogGroupId
                }
            }).then((response) => {
                this.loading = false
                this.$notify("Диалоговая группа успешно удалена");
                this.loadGroups();
            }).catch(err => {
                this.loading = false
            })

        },
        addGroup() {
            this.loading = true
            this.groupForm.bot_id = this.bot.id

            let data = new FormData();
            Object.keys(this.groupForm)
                .forEach(key => {
                    const item = this.groupForm[key] || ''
                    if (typeof item === 'object')
                        data.append(key, JSON.stringify(item))
                    else
                        data.append(key, item)
                });


            this.$store.dispatch("createDialogGroup", {
                dialogGroupForm: data
            }).then((response) => {
                // this.$emit("callback", response.data)
                this.loading = false
                this.$notify("Диалоговая группа успешно создана");

                this.groupForm = {
                    slug: null,
                    title: null,
                }

                this.loadGroups();
            }).catch(err => {
                this.loading = false
            })

        },
        doSwapGroup(groupId){
            this.loading = true

            this.$store.dispatch("swapDialogGroup", {
                swapForm: {
                    dialogCommandId:this.group.command_id,
                    dialogGroupId:groupId
                }
            }).then((response) => {
                this.loading = false

                this.$notify({
                    title: "Конструктор ботов",
                    text: "Диалоговая команда успешно перемещена в другую группу!",
                    type: 'success'
                });
                this.loadGroups();
                this.group.group_id = groupId
            }).catch(err => {
                this.loading = false
            })
        },
        doCommandLink(commandId){
            this.loading = true

            this.$store.dispatch("swapDialogCommand", {
                swapForm: {
                    dialogCommandFromId:commandId,
                    dialogCommandToId:this.link.id,
                }
            }).then((response) => {
                this.loading = false

                this.$notify({
                    title: "Конструктор ботов",
                    text: "Диалоговая команда успешно слинкована!",
                    type: 'success'
                });
                this.loadGroups();
                this.link.current_next_id = commandId

            }).catch(err => {
                this.loading = false
            })
        },
        swapGroupInit(command) {
            this.group = command

            const groups = new bootstrap.Modal('#group-modal', {})
            groups.show()

        },

        tryLink(command) {
            this.link = command

            const links = new bootstrap.Modal('#link-modal', {})
            links.show()
        },
        selectDialog(command) {
            this.$emit("select-dialog", command)
            this.$notify("Вы выбрали диалог из списка!");
        },
        selectGroup(group) {
            this.$emit("select-group", group)
            this.$notify("Вы выбрали группу из списка!");
        },
        nextGroups(index) {
            this.loadGroups(index)
        },
        loadGroups(page = 0) {
            this.loading = true
            this.$store.dispatch("loadDialogGroups", {
                dataObject: {
                    botId: this.bot.id || null,
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
