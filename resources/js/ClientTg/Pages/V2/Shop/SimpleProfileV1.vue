<template>
    <div
        class="container py-3"
        v-if="botUser"><!---->

        <div class="row">
            <div class="col-12">
                <form
                    v-on:submit.prevent="submit" class="row mb-0">

                    <div class="col-12 mb-2">
                        <div class="form-floating">
                            <input type="text" class="form-control text-center"
                                   placeholder="Петров Петр Семенович"
                                   aria-label="vipForm-name"
                                   v-model="vipForm.name"
                                   aria-describedby="vipForm-name" required>
                            <label for="vipForm-name">Ф.И.О.</label>
                        </div>
                    </div>

                    <div class="col-12" >
                        <div class="form-floating">
                            <input type="text" class="form-control text-center"
                                   v-mask="['+7(###)###-##-##']"
                                   v-model="vipForm.phone"
                                   placeholder="+7(000)000-00-00"
                                   aria-label="vipForm-phone" aria-describedby="vipForm-phone" required>
                            <label for="vipForm-phone">Номер телефона</label>
                        </div>
                    </div>

                    <div class="col-12">
                        <p class="mb-3"><em>Теперь, прежде чем закончить, пожалуйста, прочитайте условия
                            использования и дайте свое согласие на их принятие.</em></p>

                        <p>Перед отправкой данных нужно ознакомиться с <a
                            href="https://telegra.ph/Politika-konfidencialnosti-12-29-5" target="_blank">политикой конфиденциальности</a>.</p>

                        <div class="d-flex mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input"
                                       v-model="confirm"
                                       type="checkbox" role="switch" id="toggle-id-1">
                                <label class="form-check-label" for="toggle-id-1">
                                    С правилами ознакомлен
                                </label>
                            </div>
                        </div>

                        <button type="submit"
                                :disabled="!confirm||load"
                                class="btn btn-primary p-3 w-100">
                            Отправить анкету
                        </button>
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
            load: false,
            confirm: false,
            botUser: null,
            vipForm: {
                name: null,
                phone: null,
                page_id:null,

            }
        }
    },
    watch: {
        'getSelf': function () {
            this.botUser = this.getSelf

            this.vipForm.name = this.botUser.name || this.botUser.fio_from_telegram || null
            this.vipForm.phone = this.botUser.phone || null
        },
    },
    mounted() {
        const urlParams = new URLSearchParams(window.location.search);
        this.vipForm.page_id = JSON.parse(urlParams.get('page_id')) || null

    },

    computed: {
        ...mapGetters(['getSelf']),
        tg() {
            return window.Telegram.WebApp;
        },

        currentBot() {
            return window.currentBot
        }
    },
    methods: {

        submit() {
            this.loading = true;

            this.$store.dispatch("saveSimpleProfileFormData", {
                dataObject: this.vipForm
            }).then((resp) => {
                this.loading = false

                this.$notify({
                    title: 'Отлично!',
                    text: "Вы успешно заполнили форму и стали наши VIP-пользователем!",
                    type: "success"
                })

                this.tg.close()
            }).catch(() => {
                this.loading = false

                this.$notify({
                    title: 'Упс!',
                    text: "Ошибка заполнения формы!",
                    type: "error"
                })
            })
        }
    }
}
</script>
<style lang="scss" scoped>
.img-avatar {
    width: 200px;
    height: 200px;
    padding: 10px;

    img {
        object-fit: cover;
        width: 100%;
    }

}

.theme-dark {
    input {
        border-color: white;
    }
}
</style>
