<template>
    <div class="container py-3" v-if="self">
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

        <h6 class="opacity-75 mb-3 d-flex justify-content-between align-items-center">Информация о профиле
            <button
                type="button"
                data-bs-toggle="modal" data-bs-target="#edit-profile"
                class="btn btn-link"><i class="fa-solid fa-pen-to-square mr-1"></i></button>
        </h6>
        <ul class="list-group">
            <li class="list-group-item d-flex justify-content-between"
                aria-current="true">
                <span>Имя</span>
                <span
                    style="font-size:12px;"
                    class="text-primary fw-bold"> {{ self.fio_from_telegram || 'не указано' }}</span>
            </li>
            <li class="list-group-item d-flex justify-content-between"
                aria-current="true">
                <span>ID</span>
                <span class="text-primary fw-bold">{{ self.telegram_chat_id || '-' }}</span>
            </li>
            <li
                v-if="load_self"
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
                    class="text-primary fw-bold">
                    {{ self.city || 'не указан' }}
                </span>
            </li>

            <li

                class="list-group-item d-flex justify-content-between"
                aria-current="true">
                <span>День рождения</span>
                <span
                    class="text-primary fw-bold">
                    {{ self.birthday || '-' }}
                </span>
            </li>

            <li class="list-group-item d-flex justify-content-between cursor-pointer"
                aria-current="true">
                <span>Приглашено друзей</span>
                <span class="text-primary fw-bold">{{ self.friends_count || 0 }}</span>
            </li>
            <li class="list-group-item d-flex justify-content-between cursor-pointer"
                @click="goToOrders"
                aria-current="true">
                <span>Количество заказов</span>
                <span class="text-primary fw-bold">{{ self.order_count || 0 }}</span>
            </li>
            <li
                @click="goToCashback"
                class="list-group-item d-flex justify-content-between cursor-pointer"
                aria-current="true">
                <span>Получено CashBack</span>
                <span class="text-primary fw-bold">{{ self.cashBack.amount || 0 }} ₽</span>
            </li>
        </ul>

        <template v-if="self.cashBack">
            <h6 class="opacity-75 my-3" v-if="(self.cashBack.subs||[]).length>0">Специальные начисления</h6>

            <ul class="list-group" v-if="(self.cashBack.subs||[]).length>0">
                <li class="list-group-item d-flex justify-content-between"
                    v-for="sub in self.cashBack.subs"
                    aria-current="true">
                    <span>{{ sub.title || '-' }}</span>
                    <span class="text-primary fw-bold">{{ sub.amount || 0 }} ₽</span>
                </li>
            </ul>
        </template>


        <h6 class="opacity-75 my-3">Ваш QR-код</h6>

        <img v-lazy="qr" class="img-thumbnail" alt="...">

        <button type="button"
                @click="copyToClipboard"
                class="btn btn-outline-primary mt-2 w-100"><i class="fa-solid fa-link mr-2"></i>Ваша реферальная ссылка</button>

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
        copyToClipboard(){
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
        goToCashback() {
            this.$router.push({name: 'CashBackV2'})
        },
        goToOrders() {
            this.$router.push({name: 'OrdersV2'})
        },
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
