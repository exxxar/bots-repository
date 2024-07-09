<template>
    <div class="container py-3" v-if="self">
        <div class="d-flex justify-content-center align-items-center" style="min-height:350px;">
            <div style="width:200px;height:200px;border-radius:50%;overflow:hidden;">
                <img
                    class="w-100 object-fit-cover"
                    v-lazy="'/file-by-file-id-and-bot-domain/'+photos[0][0].file_id+'/'+currentBot.bot_domain"
                     v-if="photos"/>


            </div>
        </div>
        <h6 class="opacity-75 mb-3">Информация о профиле</h6>
        <ul class="list-group" >
            <li class="list-group-item d-flex justify-content-between"
                aria-current="true">
                <span>Имя</span>
                <span class="text-primary fw-bold">{{ self.fio_from_telegram || self.name || 'не указано' }}</span>
            </li>
            <li class="list-group-item d-flex justify-content-between"
                aria-current="true">
                <span>ID</span>
                <span class="text-primary fw-bold">{{ self.telegram_chat_id || '-' }}</span>
            </li>
            <li

                class="list-group-item d-flex justify-content-between"
                aria-current="true">
                <span>Телефон</span>
                <span
                    @click="sendMyNumber"
                    class="text-primary fw-bold cursor-pointer">
                    {{ self.phone || 'отправить мой номер' }}
                </span>
            </li>

            <li
                class="list-group-item d-flex justify-content-between"
                aria-current="true">
                <span>Город</span>
                <span
                    class="text-primary fw-bold cursor-pointer">
                    {{ self.city || 'не указан' }}
                </span>
            </li>

            <li

                class="list-group-item d-flex justify-content-between"
                aria-current="true">
                <span>День рождения</span>
                <span
                    class="text-primary fw-bold cursor-pointer">
                    {{ self.birthday || '-' }}
                </span>
            </li>

            <li class="list-group-item d-flex justify-content-between"
                aria-current="true">
                <span>Приглашено друзей</span>
                <span class="text-primary fw-bold">10</span>
            </li>
            <li class="list-group-item d-flex justify-content-between"
                aria-current="true">
                <span>Количество заказов</span>
                <span class="text-primary fw-bold">10</span>
            </li>
            <li class="list-group-item d-flex justify-content-between"
                aria-current="true">
                <span>Получено CashBack</span>
                <span class="text-primary fw-bold">{{ self.cashBack.amount || 0 }} ₽</span>
            </li>
        </ul>

        <h6 class="opacity-75 my-3" v-if="self.cashBack">Специальные начисления</h6>

        <ul class="list-group" v-if="self.cashBack">
            <li class="list-group-item d-flex justify-content-between"
                v-for="sub in self.cashBack.subs"
                aria-current="true">
                <span>{{ sub.title || '-' }}</span>
                <span class="text-primary fw-bold">{{ sub.amount || 0 }} ₽</span>
            </li>
        </ul>

        <h6 class="opacity-75 my-3">Ваш QR-код</h6>

        <img v-lazy="qr" class="img-thumbnail" alt="...">
    </div>
</template>
<script>
import {mapGetters} from "vuex";

export default {
    data(){
      return {
          photos:null,
      }
    },
    computed: {
        ...mapGetters(['getSelf']),
        logo() {
            return `/images-by-bot-id/${this.currentBot.id}/${this.currentBot.image}`
        },
        self() {
            return this.getSelf
        },
        tg() {
            return window.Telegram.WebApp;
        },
        tgUser() {
            const urlParams = new URLSearchParams(this.tg.initData);
            return JSON.parse(urlParams.get('user'));
        },
        currentBot() {
            return window.currentBot
        },
        qr() {
            return "https://api.qrserver.com/v1/create-qr-code/?size=450x450&qzone=2&data=" + this.link
        },
        link() {
            return "https://t.me/" + this.currentBot.bot_domain + "?start=" + btoa("001" + this.self.telegram_chat_id);
        }
    },
    mounted() {
        this.tg.BackButton.hide()

        this.loadUserPhotos()
    },
    methods: {
        loadUserPhotos() {
            this.$store.dispatch("getUserProfilePhotos").then(resp => {
                console.log(resp)
                this.photos = resp.result.photos || null

            })
        },
        sendMyNumber() {
            this.tg.requestContact((resp) => {
                this.$notify({
                    title: "Профиль",
                    text: resp?"Ваш контакт успешно отправлен!":"Вы отменили отправку контакта",
                    type: resp?"success":'error'
                })
            })
        }
    }
}
</script>
