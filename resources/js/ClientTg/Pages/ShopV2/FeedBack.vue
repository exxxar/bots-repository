<script setup>

import ReturnToBot from "ClientTg@/Components/Shop/Helpers/ReturnToBot.vue";
</script>
<template>

        <div class="container my-3 g-2" v-if="self">
            <h3 v-if="!type">Обратная связь</h3>
            <h3 v-if="type==='booking'">Бронирование столика</h3>

            <p v-if="!type">
                Вы можете оставить нам сообщение с пожеланиями по улучшению сервиса!
            </p>


            <p v-if="type==='booking'">
                Укажите в текстовом сообщение критерии бронирования столика, или же оставьте сообщение без изменений!
            </p>

            <form v-on:submit.prevent="submitCallback">

                <div class="form-floating mb-2">
                    <input type="text"
                           v-model="callbackForm.name"
                           class="form-control"
                           id="callbackForm-name"
                           placeholder="name@example.com" required>
                    <label for="callbackForm-name">Ваше имя</label>
                </div>


                <div class="form-floating mb-2">
                    <input type="text"
                           v-mask="'+7(###)###-##-##'"
                           v-model="callbackForm.phone"
                           class="form-control"
                           id="callbackForm-phone"
                           placeholder="name@example.com" required>
                    <label for="callbackForm-phone">Номер телефона</label>
                </div>


                <div class="form-floating">
                    <textarea class="form-control"
                              style="height:200px;line-height:150%;"
                              v-model="callbackForm.message"
                              placeholder="Leave a comment here"
                              id="callbackForm-message"></textarea>
                    <label for="callbackForm-message" required>Текст сообщения</label>
                </div>


                <button type="submit"
                        :disabled="sending"
                        class="btn btn-primary mt-2 p-3 w-100">
                    Отправить сообщение
                </button>
            </form>


        </div>

</template>
<script>


import {mapGetters} from "vuex";

export default {
    props: ["type"],
    data() {
        return {
            sending: false,
            callbackForm: {
                name: null,
                phone: null,
                message: null,
            },
        }
    },
    watch:{
        'getSelf': {
            handler: function (newValue) {

                   this.callbackForm.name = this.self.fio_from_telegram || this.self.name
                   this.callbackForm.phone = this.self.phone || null


            },
            deep: true
        },

    },
    computed:{
        ...mapGetters(['getSelf']),
        self(){
            return this.getSelf
        }
    },
    mounted() {

        if (this.self) {
            this.callbackForm.name = this.self.fio_from_telegram || this.self.name
            this.callbackForm.phone = this.self.phone || null
        }


        if (this.type === 'booking')
            this.callbackForm.message = 'Добрый день! Я хочу забронировать столик! Перезвоните мне.'
    },
    methods: {
        submitCallback() {
            let data = new FormData();

            this.sending = true
            Object.keys(this.callbackForm)
                .forEach(key => {
                    const item = this.callbackForm[key] || ''
                    if (typeof item === 'object')
                        data.append(key, JSON.stringify(item))
                    else
                        data.append(key, item)
                });

            if (this.type)
                data.append("type", this.type)

            this.$store.dispatch("callbackForm", {
                callbackForm: data

            }).then((response) => {

                this.callbackForm = {
                    message: null,
                    name: null,
                    phone: null,
                }

                this.$notify(  {
                        title:"Обратная связь",
                        text:"Спасибо за сообщение",
                    },
                );

                this.sending = false
            }).catch(err => {
                this.sending = false
            })
        },
    }
}
</script>
