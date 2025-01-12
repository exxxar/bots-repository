<script setup>
import ProfileCard from "@/ClientTg/Components/V2/Shop/ProfileCard.vue";
</script>

<template>
    <div class="container py-3" v-if="self">

        <ProfileCard :data="self"/>

        <button
            type="button"
            data-bs-toggle="modal" data-bs-target="#edit-profile"
            class="btn btn-link w-100 py-3"><i class="fa-solid fa-pen-to-square mr-1"></i> Редактировать</button>

        <h6 class="opacity-75 my-3">Ваш QR-код</h6>

        <img v-lazy="qr" class="img-thumbnail" alt="...">

        <button type="button"
                @click="copyToClipboard"
                class="btn btn-outline-primary mt-2 w-100"><i class="fa-solid fa-link mr-2"></i>Ваша реферальная ссылка
        </button>

        <!-- Modal -->
        <div class="modal fade" id="edit-profile" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-fullscreen">
                <form class="modal-content"
                      v-if="getSelf"
                      v-on:submit.prevent="submitProfile">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Редактирование профиля</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">


                        <div class="form-floating mb-2">

                            <input type="text" class="form-control"
                                   v-model="botUserForm.name"
                                   placeholder="Иванов Иван Иванович" required>
                            <label for="">Ф.И.О пользователя</label>
                        </div>


                        <div class="form-floating mb-2">

                            <input type="text" class="form-control"
                                   v-model="botUserForm.phone"
                                   v-mask="'+7(###)###-##-##'"
                                   placeholder="+7(XXX) XXX-XX-XX" required>
                            <label for="">Телефон</label>
                        </div>

                        <div class="form-floating mb-2">
                            <input type="text" class="form-control"
                                   v-model="botUserForm.email"
                                   placeholder="example@gmail.com">
                            <label for="">Почта</label>
                        </div>

                        <div class="form-floating mb-2">

                            <input type="text" class="form-control"
                                   v-model="botUserForm.address"
                                   placeholder="ул. Петрова, 123, кв 45">
                            <label for="">Адрес</label>
                        </div>

                        <div class="form-floating mb-2">

                            <input type="date" class="form-control"
                                   v-model="botUserForm.birthday"
                                   placeholder="12/01/2023">
                            <label for="">Дата рождения</label>
                        </div>

                        <div class="form-floating mb-2">

                            <input type="text" class="form-control"
                                   v-model="botUserForm.country"
                                   placeholder="Россия">
                            <label for="">Страна</label>
                        </div>


                        <div class="form-floating mb-2">

                            <input type="text" class="form-control"
                                   v-model="botUserForm.city"
                                   placeholder="Краснодар">
                            <label for="">Город</label>
                        </div>

                        <div class="row mb-0">
                            <p class="col-12 my-2">Пол</p>
                            <div class="col-6">
                                <button
                                    type="button"
                                    @click="botUserForm.sex = true"
                                    v-bind:class="{'btn-info text-white':botUserForm.sex,'btn-outline-secondary':!botUserForm.sex}"
                                    class="w-100 btn">
                                    <i class="fa-solid fa-mars mr-1"></i> Муж
                                </button>
                            </div>
                            <div class="col-6">
                                <button
                                    type="button"
                                    @click="botUserForm.sex = false"
                                    v-bind:class="{'btn-info text-white':!botUserForm.sex,'btn-outline-secondary':botUserForm.sex}"
                                    class="w-100 btn">
                                    <i class="fa-solid fa-venus mr-1"></i> Жен
                                </button>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer p-2">
                        <button type="submit" class="btn btn-primary w-100">Сохранить изменения</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</template>
<script>
import {mapGetters} from "vuex";

export default {
    data() {
        return {
            photos: null,
            load_self: true,
            botUserForm: {
                id: null,
                name: null,
                phone: null,
                email: null,
                birthday: null,
                age: null,
                city: null,
                country: null,
                address: null,
                sex: null,

            }
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
    watch: {
        'getSelf': function () {
            this.botUserForm.id = this.getSelf.id
            this.botUserForm.name = this.getSelf.name || this.getSelf.username || this.getSelf.id
            this.botUserForm.phone = this.getSelf.phone
            this.botUserForm.email = this.getSelf.email
            this.botUserForm.birthday = this.getSelf.birthday || null
            this.botUserForm.city = this.getSelf.city || null
            this.botUserForm.country = this.getSelf.country || null
            this.botUserForm.address = this.getSelf.address || null
            this.botUserForm.sex = this.getSelf.sex || false
        }
    },

    mounted() {
        this.tg.BackButton.show()

        this.tg.BackButton.onClick(() => {
            document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(item => item.click())

            this.$router.back()
        })

        this.$nextTick(() => {
            this.loadUserPhotos()

            if (this.getSelf) {
                this.botUserForm.id = this.getSelf.id
                this.botUserForm.name = this.getSelf.name || this.getSelf.username || this.getSelf.id
                this.botUserForm.phone = this.getSelf.phone
                this.botUserForm.email = this.getSelf.email
                this.botUserForm.birthday = this.getSelf.birthday || null
                this.botUserForm.city = this.getSelf.city || null
                this.botUserForm.country = this.getSelf.country || null
                this.botUserForm.address = this.getSelf.address || null
                this.botUserForm.sex = this.getSelf.sex || false
            }

        })

    },
    methods: {
        copyToClipboard() {
            navigator.clipboard.writeText(this.link)
            this.$notify({
                title: "Реферальная ссылка",
                text: "Ваша ссылка успешно скопирована в буфер!",
            })
        },
        submitProfile() {
            this.$store.dispatch('updateProfile', {
                botUserForm: this.botUserForm
            }).then(() => {
                this.$notify({
                    title: "Редактирование данных",
                    text: "Данные успешно обновлены!",
                    type: "success"
                })
                window.location.reload()
            }).catch(() => {
                this.$notify({
                    title: "Редактирование данных",
                    text: "Ошибка обновления данных",
                    type: "error"
                })
            })
        },
        goToFriends() {
            this.$router.push({name: 'FriendsV2'})
        },
        goToCashback() {
            this.$router.push({name: 'CashBackV2'})
        },
        goToOrders() {
            this.$router.push({name: 'OrdersV2'})
        },
        loadUserPhotos() {
            this.$store.dispatch("getUserProfilePhotos").then(resp => {
                this.photos = resp.photos.result.photos || null

            })
        },
        sendMyNumber() {
            this.tg.requestContact((resp) => {
                this.$notify({
                    title: "Профиль",
                    text: resp ? "Ваш контакт успешно отправлен!" : "Вы отменили отправку контакта",
                    type: resp ? "success" : 'error'
                })
                this.$store.dispatch("loadSelf").then(() => {
                    this.load_self = false
                    this.$nextTick(() => {
                        this.load_self = true
                    })
                })
            })
        }
    }
}
</script>
