<script setup>
import CallbackForm from "ClientTg@/Components/Shop/CallbackForm.vue";
import PlayerForm from "ClientTg@/Components/Shop/PlayerForm.vue";
import ReturnToBot from "ClientTg@/Components/Shop/Helpers/ReturnToBot.vue";
import ProjectInfoCard from "ClientTg@/Components/Shop/Helpers/ProjectInfoCard.vue";
</script>
<template>
    <div v-if="botUser">
        <div class="card card-style p-3">
            <form
                v-on:submit.prevent="submit" class="row mb-0">
                <div class="col-12">
                    <p class="mb-3">На вашем счету накопилось {{ botUser.cashBack.amount || 0 }} руб.
                    </p>
                    <h6 class="text-center">Какую сумму желаете вывести?</h6>
                    <div class="input-style input-style-2">

                        <input type="text" class="form-control text-center font-14 p-3 rounded-s border-theme"
                               placeholder="1000 руб."
                               aria-label="withDrawMoneyForm-amount"
                               v-model="withDrawMoneyForm.amount"
                               aria-describedby="withDrawMoneyForm-amount" required>
                    </div>
                </div>
                <div class="col-12">
                    <div class="d-flex mb-2">
                        <div class="pt-1">
                            <h5 data-activate="toggle-id-1" class="font-500 font-13">
                                <span v-if="need_phone">На карту</span>
                                <span v-else>По номеру телефона</span>
                            </h5>
                        </div>
                        <div class="ml-auto mr-4 pr-2">
                            <div class="custom-control ios-switch">
                                <input
                                    v-model="need_phone"
                                    type="checkbox" class="ios-input" id="toggle-id-2">
                                <label class="custom-control-label" for="toggle-id-2"></label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12" v-if="need_phone">

                    <h6 class="text-center" >Введите номер телефона</h6>
                    <div class="input-style input-style-2">
                        <input type="text" class="form-control text-center font-14 p-3 rounded-s border-theme"
                               v-mask="'+7(###)###-##-##'"
                               v-model="withDrawMoneyForm.card"
                               placeholder="+7(000)000-00-00"
                               aria-label="withDrawMoneyForm-card" aria-describedby="withDrawMoneyForm-card" required>

                    </div>
                </div>

                <div class="col-12" v-if="!need_phone">

                    <h6 class="text-center" >Введите номер карты</h6>
                    <div class="input-style input-style-2">
                        <input type="text" class="form-control text-center font-14 p-3 rounded-s border-theme"
                               v-mask="'#### #### #### ####'"
                               v-model="withDrawMoneyForm.card"
                               placeholder="0000 0000 0000 0000"
                               aria-label="withDrawMoneyForm-card" aria-describedby="withDrawMoneyForm-card" required>

                    </div>
                </div>

                <div class="col-12">
                    <h6 class="text-center">Укажите инициалы получателя и название банка</h6>
                    <div class="input-style input-style-2">

                        <input type="text" class="form-control text-center font-14 p-3 rounded-s border-theme"
                               placeholder="Иванов И.И., сбер"
                               aria-label="withDrawMoneyForm-info"
                               v-model="withDrawMoneyForm.info"
                               aria-describedby="withDrawMoneyForm-info" required>
                    </div>
                </div>


                <button type="submit"
                        :disabled="!confirm||load"
                        class="btn btn-m btn-full mb-2 rounded-s text-uppercase font-900 shadow-s bg-highlight w-100">
                    Отправить заявку
                </button>


            </form>

        </div>

        <ReturnToBot class="mb-2"/>
    </div>


    <ProjectInfoCard/>
</template>
<script>
import {mapGetters} from "vuex";

export default {
    data() {
        return {
            load: false,
            botUser: null,
            need_phone:false,
            withDrawMoneyForm: {
                amount: null,
                card: null,
                info: null,
            }
        }
    },
    watch: {
        'getSelf': function () {
            this.botUser = this.getSelf
            this.withDrawMoneyForm.amount = this.botUser.cashBack.amount || 0
        },
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

            this.$store.dispatch("withDrawMoney", {
                ...this.withDrawMoneyForm
            }).then((resp) => {
                this.loading = false
                this.tg.close()
            }).catch(() => {
                this.loading = false
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
