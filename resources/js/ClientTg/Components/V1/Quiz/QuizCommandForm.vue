
<template>

    <form v-on:submit.prevent="submitForm">
        <div class="form-field form-name">
            <label class="contactNameField color-theme"
                   for="command-title">Название команды:<span>(обязательно)</span></label>
            <input type="text" name="contactNameField"    maxlength="255"
                   v-model="commandForm.title"
                   placeholder="Например 'Новые люди'"
                   class="contactField round-small requiredField"
                   id="command-title" required>
        </div>

        <div class="form-field form-text">
            <label class="contactMessageTextarea color-theme" for="command-text">Описание команды:  <small class="text-gray-400 ml-1" style="font-size:10px;" v-if="commandForm.description">
                {{ commandForm.description.length }}/255</small> <span>(обязательно)</span></label>
            <textarea name="contactMessageTextarea"
                      placeholder="Текст описания"
                      aria-label="Текст описания"
                      v-model="commandForm.description"
                      maxlength="255"
                      class="contactTextarea round-small requiredField mb-2"
                      id="command-text"></textarea>
        </div>

        <div class="mb-2" v-if="commandForm.players.length>0">
            <h4>Участники в команде</h4>
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
        <p class="mb-2">Вы можете создавать не более <strong>1 команды</strong> в одном квизе!</p>
        <button
            type="submit" class="btn btn-m btn-full mb-3 rounded-xs text-uppercase font-900 shadow-s bg-green2-dark w-100 p-3 mb-2">
            <span v-if="commandForm.id==null">Сохранить команду</span>
            <span v-else>Обновить команду</span>
        </button>
        <div class="divider divider-small my-3 bg-highlight "></div>
    </form>

</template>

<script>


export default {
    props: ["quizId", "command"],

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
                    captain_id:  this.command.captain_id || null,
                    creator_id:  this.command.creator_id || null,
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


tс
