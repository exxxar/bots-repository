<template>
    <div id="event-item-info" class="menu menu-box-bottom menu-box-detached rounded-m d-block"
         style="height:50vh; display:block;"
         data-menu-effect="menu-over">

        <div class="w-100 p-3" v-if="item">
            <h4>Информирование пользователя</h4>
            <ul>
                <li> № события: {{ item.slug.id }}</li>
                <li> Название события: {{ item.slug.command }}</li>
                <li> Использовано попыток: {{ item.current_attempts }}</li>
                <li> Дата прохождения: {{ $filters.current(item.completed_at) }}</li>
                <li v-for="res in item.data">
                    <p class="mb-0" v-for="obj in Object.keys(res)">
                        {{ params[obj] }}:{{ res[obj] || 'Не установлено' }}
                    </p>
                </li>

            </ul>

            <form v-on:submit.prevent="sendApproveToUser">
                <div class="mb-3">
                    <label for="bill-info" class="form-label">Сообщение для пользователя</label>
                    <textarea class="form-control"
                              placeholder="Информация"
                              v-model="eventCallbackForm.info"
                              id="bill-info" rows="3" required></textarea>
                </div>

                <div class="mb-3">
                    <button
                        :disabled="loading"
                        type="submit"
                        class="btn btn-m btn-full mb-3 rounded-xs text-uppercase font-900 shadow-s bg-red1-light w-100">
                        Отправить
                    </button>
                </div>
            </form>

        </div>


    </div>
</template>
<script>
import {mapGetters} from "vuex";

export default {
    data() {
        return {
            params: {
                win: 'Номер выигрыша',
                name: 'Ф.И.О.',
                phone: 'Телефон',
                answered_by: 'Кто проверил',
                answered_at: 'Дата ответа',
            },
            item: null,
            eventCallbackForm: {
                info: null,
            },
            loading: false,
        }
    },
    mounted() {
        window.addEventListener("show-event-info", (e) => {
            this.item = e.detail.item || null

            this.$nextTick(() => {
                $('#event-item-info').showMenu();
            })
        });


    },
    methods: {
        sendApproveToUser() {
            this.loading = true;
            this.$store.dispatch("sendApproveToUser", {
                dataObject: {
                    user_telegram_chat_id: this.item.bot_user.telegram_chat_id,
                    action_id: this.item.id,
                    ...this.eventCallbackForm
                }
            }).then((resp) => {
                this.loading = false
                this.eventCallbackForm.info = null
                $('#event-item-info').hideMenu();
                this.$botNotification.success("Отлично!", "Оповещение успешно отправлено")
            }).catch(() => {
                this.loading = false
                $('#event-item-info').hideMenu();
                this.$botNotification.warning("Упс!", "Что-то пошло не так")
            })
        }
    }
}
</script>
<style>

</style>
