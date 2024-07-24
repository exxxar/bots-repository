<script setup>
import CallbackForm from "@/ClientTg/Components/V1/Shop/CallbackForm.vue";
import PlayerForm from "@/ClientTg/Components/V1/Shop/PlayerForm.vue";
import ReturnToBot from "@/ClientTg/Components/V1/Shop/Helpers/ReturnToBot.vue";
</script>
<template>
    <div class="card card-style" v-if="rules">
        <div class="content">
            <h4>Правила данной игры</h4>
            <p>
                {{ rules }}
            </p>

            <p v-if="canPlay" class="mb-2">Ваши попытки: <strong>{{
                    action.current_attempts || 0
                }}</strong> из <strong>{{
                    action.max_attempts || 1
                }}</strong></p>
            <div
                @click="lose"
                v-else>

                <p style="font-weight:900; color:red;" class="mb-2">Вы израсходовали все ваши попытки</p>

            </div>

            <ul v-if="action.data" class="m-0 p-0">
                <li v-for="item in action.data" class="d-flex flex-column mb-2">
                    <span>Приз <strong>№{{ item.win || 'Отсуствует' }}</strong></span>
                    <span>Описание <strong>{{ item.description || 'Отсуствует' }}</strong></span>
                    <span>Победитель  <strong>{{ item.name || 'Не указано' }}</strong></span>
                    <span>Телефон  <strong>{{ item.phone || 'Не указано' }}</strong></span>
                </li>
            </ul>
        </div>
    </div>

    <div class="card card-style" v-if="canPlay&&hasProfileData">
        <div class="content d-flex justify-content-center flex-wrap">


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
    </div>

    <PlayerForm v-if="canPlay&&!hasProfileData"
                v-on:callback="callbackPlayerForm">
        <template v-slot:head>
            <h3>Анкета участника акции</h3>

            <p>
                Для участия в конкурсе и дальнейшего получения приза необходимо заполнить данную анкету! Укажите своё
                имя и номер телефона чтоб менеджер
                мог выдать Вам приз по итогу.
            </p>
        </template>
    </PlayerForm>

<!--    <CallbackForm/>-->


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
            winResultMessage: "Наш менеджер свяжется с вами для дальнейших инструкций.",
            rules: null,
            rouletteKey: 0,
            action: null,
            hasProfileData: false,
            winForm: {
                win: null,
            },

            items: [],
        };
    },
    computed: {
        canPlay() {

            if (!this.action)
                return false

            return this.action.current_attempts < this.action.max_attempts
        },
    },
    mounted() {
        this.loadServiceData().then(() => {
            this.prepareUserData()
        })
    }
    ,
    methods: {
        lose() {
            this.$botNotification.warning("Упс!", "Вы израсходовали все попытки!")
        },
        prepareUserData() {
            return this.$store.dispatch("wheelOfFortunePrepare").then((response) => {
                this.action = response.action

            })
        },

        loadServiceData() {
            return this.$store.dispatch("wheelOfFortuneLoadData").then((response) => {
                let index = 1;

                this.rules = response.rules
                this.winResultMessage = response.callback_message
                const wheels = response.wheels

                this.items = []
                wheels.forEach(item => {
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
        }
        ,
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
            const winResult = this.winForm.win || 'Что-то интересное...'

            this.$store.dispatch("wheelOfFortuneWin", {
                winForm: data
            }).then((response) => {
                this.winForm.win = null
                this.winForm.name = null
                this.winForm.phone = null

                this.prepareUserData()
            }).catch(err => {

            })

            this.$botNotification.success("Вы выиграли!", winResult)
        },

        launchWheel() {
            this.rouletteKey += 1;
            setTimeout(() => this.$refs.wheel.launchWheel(), 0);
        },
        wheelStartedCallback() {

        },
        callbackPlayerForm(form) {

            this.winForm = {...this.winForm, ...form}

            this.hasProfileData = true
        },
        wheelEndedCallback(evt) {
            if (!evt)
                return;
            const win = evt.id
            setTimeout(() => {
                this.winForm.win = win
                this.submit()
                this.hasProfileData = false
            }, 2000)

            this.$botNotification.success("Победа!", "Вы выиграли приз <strong>" + (this.winForm.win || '-') + "</strong>.")
        },
    },
}
;
</script>
<style>
.wheel-base-container .wheel-base-indicator {
    left: 45px !important;
}

.wheel .content {
    font-size: 14px;
    font-weight: 900;
    margin: 0 !important;
}

</style>
