<script setup>
import Preloader from "@/ClientTg/Components/V2/Shop/Other/PreloaderV1.vue";
</script>
<template>
    <div class="container py-3" v-if="profile">
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

        <h6 class="opacity-75 mb-3">Информация о профиле друга</h6>
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

                class="list-group-item d-flex justify-content-between"
                aria-current="true">
                <span>Телефон</span>
                <span
                    class="text-primary fw-bold cursor-pointer">
                    {{ profile.phone || 'номер не указан' }}
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
                class="list-group-item d-flex justify-content-between cursor-pointer"
                aria-current="true">
                <span>Приглашено друзей</span>
                <span class="text-primary fw-bold">{{ profile.friends_count || 0 }}</span>
            </li>
            <li class="list-group-item d-flex justify-content-between cursor-pointer"
                aria-current="true">
                <span>Количество заказов</span>
                <span class="text-primary fw-bold">{{ profile.order_count || 0 }}</span>
            </li>
            <li
                class="list-group-item d-flex justify-content-between cursor-pointer"
                aria-current="true">
                <span>Получено баллов</span>
                <span class="text-primary fw-bold">{{ profile.cashBack.amount || 0 }} ₽</span>
            </li>
        </ul>
    </div>
    <Preloader v-else></Preloader>
</template>
<script>
import {mapGetters} from "vuex";

export default {
    data() {
        return {
            photos: null,
            profile:null,
            friend_id:null,
        }
    },
    computed: {
        tg() {
            return window.Telegram.WebApp;
        },
        currentBot() {
            return window.currentBot
        },

    },

    mounted() {

        const urlParams = new URLSearchParams(window.location.search);
        this.friend_id = JSON.parse(urlParams.get('friend')) || null

        this.tg.BackButton.show()

        this.tg.BackButton.onClick(() => {
            document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(item => item.click())

            this.$router.back()
        })

        this.$nextTick(() => {
            if (this.friend_id) {
                this.loadUserPhotos()
            }

        })

    },
    methods: {

        loadUserPhotos() {
            this.$store.dispatch("getUserProfilePhotos",{
                bot_user_id: this.friend_id
            }).then(resp => {
                console.log(resp)
                this.photos = resp.photos.result.photos || null
                this.profile = resp.profile
            }).catch(()=>{
                this.$notify({
                    title:'Ошибочка',
                    text:'Пользователь не найден!',
                    type:'error'
                })
            })
        },
    }
}
</script>
