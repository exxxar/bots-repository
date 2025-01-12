<template>
    <div class="d-flex justify-content-center align-items-center" style="min-height:350px;">
        <div
            v-if="(photos||[]).length>0"
            style="width:200px;height:200px;border-radius:50%;overflow:hidden;">
            <img
                class="w-100 object-fit-cover"
                v-lazy="'/file-by-file-id-and-bot-domain/'+photos[0][0].file_id+'/'+currentBot.bot_domain"
            />
        </div>
        <div v-else
             style="width:200px;height:200px;">
            <img
                class="w-100 object-fit-cover"
                v-lazy="'/images/shop-v2/profile.png'"
            />
        </div>
    </div>

    <h6 class="opacity-75 mb-3 d-flex justify-content-between align-items-center">Информация о профиле</h6>

    <template v-if="profile">
        <ul class="list-group">
            <li class="list-group-item d-flex justify-content-between"
                aria-current="true">
                <span>Имя</span>
                <span
                    style="font-size:12px;"
                    class="text-primary fw-bold"> {{ profile.fio_from_telegram || 'не указано' }}</span>
            </li>
            <li class="list-group-item d-flex justify-content-between"
                aria-current="true">
                <span>ID</span>
                <span class="text-primary fw-bold">{{ profile.telegram_chat_id || '-' }}</span>
            </li>
            <li
                v-if="load_data"
                class="list-group-item d-flex justify-content-between"
                aria-current="true">
                <span>Телефон</span>
                <span
                    @click="sendMyNumber"
                    class="text-primary fw-bold cursor-pointer">
                    {{ profile.phone || 'отправить мой номер' }}
                </span>
            </li>

            <li
                class="list-group-item d-flex justify-content-between"
                aria-current="true">
                <span>Город</span>
                <span
                    class="text-primary fw-bold">
                    {{ profile.city || 'не указан' }}
                </span>
            </li>

            <li

                class="list-group-item d-flex justify-content-between"
                aria-current="true">
                <span>День рождения</span>
                <span
                    class="text-primary fw-bold">
                    {{ profile.birthday || '-' }}
                </span>
            </li>

            <li
                @click="goToFriends"
                class="list-group-item d-flex justify-content-between cursor-pointer"
                aria-current="true">
                <span>Приглашено друзей</span>
                <span class="text-primary fw-bold">{{ profile.friends_count || 0 }}</span>
            </li>
            <li class="list-group-item d-flex justify-content-between cursor-pointer"
                @click="goToOrders"
                aria-current="true">
                <span>Количество заказов</span>
                <span class="text-primary fw-bold">{{ profile.order_count || 0 }}</span>
            </li>
            <li
                @click="goToCashback"
                class="list-group-item d-flex justify-content-between cursor-pointer"
                aria-current="true">
                <span>Получено баллов</span>
                <span class="text-primary fw-bold">{{ profile.cashBack.amount || 0 }} ₽</span>
            </li>
        </ul>

        <template v-if="profile.cashBack">
            <h6 class="opacity-75 my-3" v-if="(profile.cashBack.subs||[]).length>0">Специальные начисления</h6>

            <ul class="list-group" v-if="(profile.cashBack.subs||[]).length>0">
                <li class="list-group-item d-flex justify-content-between"
                    v-for="sub in profile.cashBack.subs"
                    aria-current="true">
                    <span>{{ sub.title || '-' }}</span>
                    <span class="text-primary fw-bold">{{ sub.amount || 0 }} ₽</span>
                </li>
            </ul>
        </template>

        <template v-if="data.id === profile.id && profile.phone == null ">
            <button
                type="button"
                class="btn btn-info w-100 p-3 my-3"
                @click="sendMyNumber">Отправить мой номер
            </button>
        </template>
    </template>
</template>
<script>
import {mapGetters} from "vuex";

export default {
    props: ["data"],
    data() {
        return {
            photos: null,
            profile: null,
            load_data: true,

        }
    },
    computed: {
        logo() {
            return `/images-by-bot-id/${this.currentBot.id}/${this.currentBot.image}`
        },

        currentBot() {
            return window.currentBot
        },

    },


    mounted() {
        this.loadUserPhotos()
    },
    methods: {
        goToFriends() {
            if (this.data.id !== this.profile.id)
                return;
            document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(item => item.click())
            this.$router.push({name: 'FriendsV2'})
        },
        goToCashback() {
            if (this.data.id !== this.profile.id)
                return;

            document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(item => item.click())

            this.$router.push({name: 'CashBackV2'})
        },
        goToOrders() {
            if (this.data.id !== this.profile.id)
                return;
            document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(item => item.click())
            this.$router.push({name: 'OrdersV2'})
        },
        loadUserPhotos() {

            this.$store.dispatch("getUserProfilePhotos", {
                bot_user_id: this.data.id
            }).then(resp => {
                this.photos = resp.photos.result.photos || null
                this.profile = resp.profile
            })
        },
        sendMyNumber() {
            this.tg.requestContact((resp) => {
                this.$notify({
                    title: "Профиль",
                    text: resp ? "Ваш контакт успешно отправлен!" : "Вы отменили отправку контакта",
                    type: resp ? "success" : 'error'
                })
                this.$store.dispatch("loaddata").then(() => {
                    this.load_data = false
                    this.$nextTick(() => {
                        this.load_data = true
                    })
                })
            })
        }
    }
}
</script>
