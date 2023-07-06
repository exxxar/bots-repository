<script setup>
defineProps({
    bot: Object,
    wheels: Array,
    rules: String,
});
</script>
<template>
    <div class="row" v-if="action">
        <div class="col-12 mb-2 mt-2" v-if="rules">
            <div class="card">
                <div class="card-body">
                    <p v-html="rules"></p>
                </div>
            </div>
        </div>

        <div class="col-12 mb-2 mt-2">
            <p style="text-align: center;font-size: larger;">Ваши попытки: <strong>{{ action.current_attempts || 0 }}</strong> из <strong>{{
                    action.max_attempts || 1
                }}</strong></p>
        </div>
        <div class="col-12 d-flex justify-content-center align-items-center "
             v-if="!played">

            <Roulette
                ref="wheel"
                size="300"
                :key="rouletteKey"
                :items="items"
                centered-indicator
                indicator-position="top"
                display-shadow
                display-border
                base-display
                base-display-indicator
                base-background="orange"
                base-display-shadow
                easing="bounce"
                @wheel-start="wheelStartedCallback"
                @wheel-end="wheelEndedCallback"
                @click="launchWheel"
            >
                <template #baseContent>
                    <div>Поехали</div>
                </template>
            </Roulette>

        </div>

        <div class="col-12 p-5" v-if="!played&&winForm.win">

            <div  class="alert alert-success mb-2" role="alert">
                <p>Вы выиграли - {{ winForm.win.htmlContent }}.</p>
            </div>
            <form v-on:submit="submit">
                <h6 class="text-center">Укажите своё имя, как к Вам может обращаться менеджер?</h6>
                <div class="input-group mb-3">

                    <input type="text" class="form-control text-center p-3"
                           placeholder="Петров Петр Семенович"
                           aria-label="winForm-name"
                           v-model="winForm.name"
                           aria-describedby="winForm-name" required>
                </div>

                <div class="col-12">
                    <h6 class="text-center">Введите свой номер телефона чтобы наш менеджер мог связаться с
                        Вами!</h6>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control p-3 text-center"
                               v-mask="'+7(###)###-##-##'"
                               v-model="winForm.phone"
                               placeholder="+7(000)000-00-00"
                               aria-label="winForm-phone" aria-describedby="vipForm-phone" required>

                    </div>

                    <button class="btn btn-outline-primary p-3 w-100">
                        Получить выигрышь
                    </button>

                </div>
            </form>

        </div>

        <div class="col-12 p-5 mt-2">
            <button
                @click="closeWheel"
                type="button" class="btn btn-outline-primary p-3 w-100">
                Вернуться в бота
            </button>
        </div>
    </div>
    <div class="row" v-else>
        <div class="col-12">
            <img v-lazy="'/images/load.gif'" class="w-100" style="object-fit:cover;" alt="">
        </div>
    </div>
    <!--
    <button @click="$refs.wheel.reset">reset</button>
    <button @click="rouletteKey += 1">hard reset</button>
    -->

</template>
<script>
import {Roulette} from "vue3-roulette";

export default {
    name: "App",
    components: {
        Roulette,
    },
    data() {
        return {
            rouletteKey: 0,
            played: false,
            action: null,
            winForm: {
                win: null,
                name: null,
                phone: null,
            },
            items: [],
        };
    },
    computed: {
        tg() {
            return window.Telegram.WebApp;
        },
        tgUserId() {
            return JSON.parse(new URLSearchParams(window.Telegram.WebApp.initData).get("user")).id || null
        }
    },
    mounted() {

        this.prepare().then(() => {

            this.played = this.action.completed_at != null

            let index = 1;

            this.items = []
            this.wheels.forEach(item => {
                this.items.push({
                    id: index,
                    name: item.value,
                    htmlContent: item.value,
                    textColor: "",
                    background: "",
                })

                index++;
            })
        })
    },
    methods: {
        prepare() {
            return this.$store.dispatch("wheelOfFortunePrepare", {
                prepareForm: {
                    telegram_chat_id: this.tgUserId
                },
                bodDomain: this.bot.bot_domain
            }).then((response) => {
                this.action = response
            })
        },
        submit() {
            let data = new FormData();
            Object.keys(this.winForm)
                .forEach(key => {
                    const item = this.winForm[key] || ''
                    if (typeof item === 'object')
                        data.append(key, JSON.stringify(item))
                    else
                        data.append(key, item)
                });

            data.append("telegram_chat_id", this.tgUserId)

            this.$store.dispatch("wheelOfFortuneWin", {
                winForm: data,
                bodDomain: this.bot.bot_domain
            }).then((response) => {

                this.winForm = {
                    win: null,
                    name: null,
                    phone: null,
                }

                this.$notify({
                    title: "Колесо фортуны",
                    text: "Вы успешно приняли участие в розыгрыше! Наш менеджер свяжется с вами для дальнейших инструкций.",
                    type: 'success'
                });

            }).catch(err => {

            })

        },
        closeWheel() {
            this.tg.close()
        },
        launchWheel() {
            this.rouletteKey += 1;
            setTimeout(() => this.$refs.wheel.launchWheel(), 0);
        },
        wheelStartedCallback() {
            console.log("wheelStartedCallback");
        },
        wheelEndedCallback(evt) {
            console.log(evt);
            this.winForm.win = evt
        },
    },
};
</script>
<style>
.wheel-base-container .wheel-base-indicator {
    left: 45px !important;
}

.wheel .content {
    font-size: 20px;
    font-weight: 900;
}
</style>
