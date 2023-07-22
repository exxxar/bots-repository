
<script setup>

import ReturnToBot from "@/Components/Shop/Helpers/ReturnToBot.vue";
</script>
<template>
    <div class="card card-style">
        <div class="content mb-0">
            <h3>Обратная связь</h3>

            <p>
                Вы можете оставить нам сообщение с пожеланиями по улучшению сервиса!
            </p>

            <form v-on:submit.prevent="submitCallback">
                <div class="input-style input-style-2 has-icon input-required">
                    <i class="input-icon fa fa-user"></i>
                    <span class="color-highlight">Ф.И.О.</span>
                    <em>(нужно)</em>
                    <input class="form-control"
                           v-model="callbackForm.name"
                           type="text" placeholder="Иванов Иван Иванович" required>
                </div>

                <div class="input-style input-style-2 has-icon input-required">
                    <i class="input-icon fa-solid fa-phone"></i>
                    <span class="color-highlight">Телефон</span>
                    <em>(нужно)</em>
                    <input class="form-control"
                           type="text"
                           v-mask="'+7(###)###-##-##'"
                           v-model="callbackForm.phone"
                           placeholder="+7(000)000-00-00"
                           required>
                </div>

                <div class="input-style input-style-2 has-icon input-required">
                    <span class="input-style-1-active input-style-1-inactive">Ваше сообщение</span>
                    <i class="input-icon fa-solid fa-envelope-open-text"></i>
                    <em>(нужно)</em>
                    <textarea class="form-control"
                              style="height:200px;line-height:250%;padding:35px;"
                              v-model="callbackForm.message"
                              type="text" placeholder="" required></textarea>
                </div>

                <button type="submit"
                        :disabled="sending"
                        class="btn btn-full btn-m shadow-l rounded-s text-uppercase font-900 bg-green1-dark my-4 w-100">
                    Отправить сообщение
                </button>

                <ReturnToBot class="mb-3"/>
            </form>


        </div>
    </div>
</template>
<script>
export default {
    data(){
        return {
            sending:false,
            callbackForm: {
                name: null,
                phone: null,
                message: null,
            },
        }
    },
    methods:{
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

            this.$store.dispatch("callbackForm", {
                callbackForm: data

            }).then((response) => {

                this.callbackForm = {
                    message: null,
                    name: null,
                    phone: null,
                }

                this.$botNotification.success("Обратная связь",
                    "Вы оставли сообщение для нас! Наш менеджер свяжется с вами для дальнейших уточнений.",
                );

                this.sending = false
            }).catch(err => {
                this.sending = false
            })
        },
    }
}
</script>
