s
<template>
    <div
        v-if="bot"
        class="row">


        <div class="col-12">
            <h3 class="my-3">Редактор сообщений в боте</h3>
        </div>


        <div class="mb-3 col-12 col-sm-12"
             v-if="messages&&!load">
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">Параметр</th>
                    <th scope="col">Текст</th>
                    <th scope="col">Действия</th>
                </tr>
                </thead>
                <tbody>
                <tr
                    v-for="(val, key) in messages">

                    <td> {{
                            key || 'ключа нет'
                        }}
                    </td>
                    <td>
                        <p v-html="val"></p>
                    </td>

                    <td>
                        <a
                            @click="selectMessage(key)"
                            title="Выбрать сообщение"
                            class="btn btn-link">
                            Редактировать
                        </a>
                    </td>
                </tr>

                </tbody>
            </table>
        </div>


    </div>

    <div class="modal fade" id="edit-table-message" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content" v-if="selected&&!load">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Редактирование сообщения
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="row" v-on:submit.prevent="submit">
                        <div class="col-12">
                            <div class="dropdown">
                                <button class="btn btn-outline-primary dropdown-toggle mb-2 w-100" type="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                    Выбрать параметр автоподстановки
                                </button>
                                <ul class="dropdown-menu">
                                    <li v-for="(val, key) in message_dictionary">
                                        <a class="dropdown-item"
                                           @click="useTemplate(key)"
                                           href="javascript:void(0)"> <strong>{{
                                                key || '-'
                                            }}</strong> - {{ val || '-' }}
                                        </a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating">
                                <textarea
                                    v-model="selected.value"
                                    class="form-control" placeholder="Leave a comment here" id="message-editor-textarea"
                                    style="min-height: 100px" required></textarea>
                                <label for="floatingTextarea2">Текст сообщения</label>
                            </div>

                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-primary w-100 p-3 mt-2">Сохранить</button>
                        </div>

                    </form>

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
    data() {
        return {
            bot: null,
            search: null,

            load: false,
            messages: [],
            message_dictionary: [],

            modal: null,
            selected: {
                key: null,
                value: null,
            }


        }
    },

    computed: {
        ...mapGetters(['getCurrentBot', 'getMessages', 'getMessageDictionary']),
    },

    mounted() {

        this.modal = new bootstrap.Modal(document.getElementById('edit-table-message'), {})
        this.loadCurrentBot().then(() => {
            this.loadMessages();
        })

    },
    methods: {

        useTemplate(param) {
            let fieldId = "#message-editor-textarea"
            let start = document.querySelector(fieldId)
            start = start.selectionStart || 0;


            let tmp = this.selected.value || ''

            let firstPart = tmp.slice(0, start)
            let secondPart = tmp.slice(start)

            this.selected.value = firstPart +
                "{{" + param + "}}" + secondPart;


        },
        loadMessages(page = 0) {

            this.load = true
            return this.$store.dispatch("loadMessages", {
                dataObject: {
                    botId: this.bot.id,
                    search: this.search || null
                },
                page: page
            }).then((resp) => {
                this.messages = this.getMessages
                this.message_dictionary = this.getMessageDictionary
                this.load = false

            }).catch(()=>{
                this.load = false
            })
        },
        loadCurrentBot(bot = null) {
            return this.$store.dispatch("updateCurrentBot", {
                bot: bot
            }).then(() => {
                this.bot = this.getCurrentBot
            })
        },

        submit() {
            let data = new FormData();
            data.append("key", this.selected.key)
            data.append("value", this.selected.value)
            data.append("bot_id", this.bot.id)


            this.$store.dispatch("updateMessage", {
                messageForm: data
            })
                .then((response) => {

                    this.loadMessages()

                    this.modal.hide()
                    this.$notify({
                        title: "Редактор сообщений",
                        text: "Данные успешно обновлены",
                        type: 'success'
                    });
                }).catch(() => {
                this.$notify({
                    title: "Редактор сообщений",
                    text: "Ошибка обновления данных",
                    type: 'error'
                });
            })
        },
        selectMessage(key) {


            this.load = true
            this.$nextTick(() => {
                this.selected.key = key
                this.selected.value = this.messages[key] || ''
                this.load = false
                this.modal.show()
            })

            this.$emit("select", item)
        },


    }
}
</script>
