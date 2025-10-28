<template>
    <div class="card">
        <div class="card-body">
            <h3 class="fw-bold text-primary text-center my-3">
                Регистрация участника
            </h3>

            <form v-on:submit.prevent="submit">
                <div class="row" v-if="botUser">
                    <div class="col-12 mb-2">
                        <div class="form-floating">
                            <input type="text" class="form-control text-center"
                                   placeholder="Петров Петр Семенович"
                                   aria-label="form-name"
                                   v-model="form.name"
                                   aria-describedby="form-name" required>
                            <label for="form-name">Ф.И.О.</label>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-floating">
                            <input type="text" class="form-control text-center"
                                   v-mask="['+7(###)###-##-##']"
                                   v-model="form.phone"
                                   placeholder="+7(000)000-00-00"
                                   aria-label="form-phone" aria-describedby="form-phone" required>
                            <label for="form-phone">Номер телефона</label>
                        </div>
                    </div>

                    <div class="col-12">
                        <p class="mb-3"><em>Теперь, прежде чем закончить, пожалуйста, прочитайте условия
                            использования и дайте свое согласие на их принятие.</em></p>

                        <p>Перед отправкой данных нужно ознакомиться с <a
                            href="https://telegra.ph/Politika-konfidencialnosti-12-29-5" target="_blank">политикой
                            конфиденциальности</a>.</p>

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


                    </div>
                </div>


                <button type="submit"
                        :disabled="!confirm||load"
                        class="btn btn-primary w-100 p-3">Отправить
                </button>
            </form>
        </div>
    </div>
</template>
<script>
import {mapGetters} from "vuex";

export default {
    data() {
        return {
            load: false,
            botUser: null,
            confirm: false,
            form: {
                name: null,
                phone: null,

            }
        }
    },
    mounted() {
        this.botUser = this.getSelf
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
    watch: {
        'getSelf': function () {
            this.botUser = this.getSelf

            this.form.name = this.botUser.name || this.botUser.fio_from_telegram || null
            this.form.phone = this.botUser.phone || null
        },
    },
    methods: {
        submit() {
            this.$preloader.show()
            this.$emit("callback", this.form)
        },

    }
}
</script>
<style>

.section-divider {
    height: 0;
    border-top: 1px solid #DDD;
    text-align: center;
    margin-top: 40px;
    margin-bottom: 40px;
}

.section-divider > span {
    color: #3498db;
    display: inline-block;
    position: relative;
    top: -11px;
    font-size: 15px;
    width: 20px;
    margin-left: 5px;
    height: 20px;
    background: #ff9941;
    border-radius: 50%;
}

.section-divider > span:nth-of-type(1),
.section-divider > span:nth-of-type(3) {
    width: 15px;
    height: 15px;
    top: -13px;
}

.upload-area {
    display: flex;
    position: relative;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    border: 2px dashed #6c757d;
    border-radius: 10px;
    padding: 40px;
    text-align: center;
    cursor: pointer;
    transition: border-color 0.3s ease, background-color 0.3s ease;
}

.upload-area:hover {
    border-color: #0d6efd;
    background-color: #f8f9fa;
}

.upload-area img {
    width: 100%;
    object-fit: cover;
}

.hide-input {
    position: absolute;
    opacity: 0;
    pointer-events: none;
}

.img-option {
    display: inline-block;
    border: 2px solid transparent;
    border-radius: 10px;
    padding: 10px;
    cursor: pointer;
    transition: all 0.25s ease;
    width: 90px;
    height: 90px;
}

.img-option img {
    width: 100%;
    object-fit: cover;
    height: auto;
    display: block;
}

.img-option:hover {
    border-color: #adb5bd;
    background-color: #f8f9fa;
}

/* Активное состояние (выбранный радио-батон) */
.hide-input:checked + .img-option {
    border-color: #0d6efd;
    box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.25);
    background-color: #e9f3ff;
}
</style>
