<script setup>

import BotUserList from "@/AdminPanel/Components/Constructor/BotUserList.vue";

</script>
<template>
    <div class="row py-3">
        <div class="col-12">
            <button
                @click="clearCommandForm"
                class="btn btn-primary">Новая команда</button>
        </div>
    </div>
    <form v-on:submit.prevent="submitForm">
        <div class="row">


            <div class="col-12 mb-3">

                <label class="form-label" id="command-title">
                    <Popper>
                        <i class="fa-regular fa-circle-question mr-1"></i>
                        <template #content>
                            <div>
                                Название команды
                            </div>
                        </template>
                    </Popper>
                    Название команды
                    <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>
                </label>
                <input type="text" class="form-control"
                       placeholder="Название команды"
                       aria-label="Название команды"
                       maxlength="255"
                       v-model="commandForm.title"
                       aria-describedby="command-title" required>


            </div>


            <div class="col-12 mb-3">

                <label class="form-label " id="event-on_after_appointment">
                    <Popper>
                        <i class="fa-regular fa-circle-question mr-1"></i>
                        <template #content>
                            <div>
                                Текст описания команды
                            </div>
                        </template>
                    </Popper>
                    Описание команды
                    <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>
                    <small class="text-gray-400 ml-3" style="font-size:10px;" v-if="commandForm.description">
                        Длина текста {{ commandForm.description.length }}/255</small>
                </label>
                <textarea type="text" class="form-control"
                          placeholder="Текст описания"
                          aria-label="Текст описания"
                          v-model="commandForm.description"
                          maxlength="255"
                          aria-describedby="command-text" required>
                    </textarea>

            </div>

            <div class="col-12 mb-3">
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-outline-info rounded-5" data-bs-toggle="modal" data-bs-target="#select-players">
                    Подобрать участников команды
                </button>
            </div>

            <div class="col-12 mb-3" v-if="commandForm.players.length>0">
                <h4>Пользователи в команде</h4>
                <ol class="list-group list-group-numbered">
                    <li class="list-group-item d-flex justify-content-between align-items-start" v-for="player in commandForm.players">
                        <div class="ms-2 me-auto">
                            <div class="fw-bold">#{{player.id}}</div>
                            {{player.fio_from_telegram || player.telegram_chat_id}}
                        </div>
                        <div class="dropdown" >
                            <button class="btn btn-outline-secondary" type="button" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                <i class="fa-solid fa-ellipsis"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item"
                                       @click="removePlayerFromCommand(player.id)"
                                       href="javascript:void(0)">Удалить</a></li>
                            </ul>
                        </div>
                    </li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <button
                    type="submit" class="btn btn-outline-success w-100 p-3">
                    <span v-if="commandForm.id==null">Сохранить команду</span>
                    <span v-else>Обновить команду</span>
                </button>
            </div>
        </div>
    </form>


    <!-- Modal -->
    <div class="modal fade" id="select-players" tabindex="-1" aria-labelledby="select-players-label" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="select-players-label">Подбор участников команды</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <BotUserList
                        :simple="true"
                        v-on:select="selectBotUser"
                    ></BotUserList>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>


export default {
    props: ["quizId", "bot", "command"],

    data() {
        return {
            step: 0,
            load: false,
            commandForm: {
                id: null,
                title: null,
                description: null,
                players: [],
            }
        }
    },
    watch: {
        commandForm: {
            handler(val) {
                this.need_reset = true
            },
            deep: true
        }
    },
    mounted() {

        if (this.command)
            this.$nextTick(() => {
                this.commandForm = {
                    id:  this.command.id || null,
                    title:  this.command.title || null,
                    description:  this.command.description || null,
                    players:  this.command.players || [],
                }

            })

    },
    methods: {
        removePlayerFromCommand(id){

        },
        selectBotUser(botUser) {

            let index = this.commandForm.players.findIndex(item=>item.id == botUser.id)

            if (index>=0)
            {
                this.$notify("Такой пользователь уже есть в списке");
                return;
            }
            this.$notify("Пользователь успешно добавлен");
            this.commandForm.players.push(botUser)
        },
        clearCommandForm(){
            this.commandForm = {
                id: null,
                title: null,
                description: null,
                players: [],
            }
        },
        submitForm() {
            let data = new FormData();
            Object.keys(this.commandForm)
                .forEach(key => {
                    const item = this.commandForm[key] || ''
                    if (typeof item === 'object')
                        data.append(key, JSON.stringify(item))
                    else
                        data.append(key, item)
                });

            data.append('bot_id', this.bot.id);
            data.append('quiz_id', this.quizId);

            this.$store.dispatch("storeQuizCommand", {
                quizCommandForm: data
            }).then((response) => {
                this.$emit("callback", response.data)
                this.$notify("Команда успешно создана");
                this.clearCommandForm()
            }).catch(err => {
                this.$notify("Ошибка создания команды");
            })

        },

    }
}
</script>


