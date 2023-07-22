<script setup>
import CallbackForm from "@/Components/Shop/CallbackForm.vue";
import PlayerForm from "@/Components/Shop/PlayerForm.vue";
import ReturnToBot from "@/Components/Shop/Helpers/ReturnToBot.vue";
</script>
<template>
    <div class="card card-style" v-if="rules">
        <div class="content">
            <h4>Правила данной игры</h4>
            <p>
                {{ rules }}
            </p>

            <p v-if="canPlay">Ваши попытки: <strong>{{
                    action.current_attempts || 0
                }}</strong> из <strong>{{
                    action.max_attempts || 1
                }}</strong></p>
            <p style="font-weight:900; color:red;" v-else>Вы израсходовали все ваши попытки</p>
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
                v-on:callback="callbackPlayerForm"/>

    <CallbackForm/>


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
        prepareUserData() {
            return this.$store.dispatch("wheelOfFortunePrepare").then((response) => {
                this.action = response.action

                if (!this.canPlay)
                    this.$botNotification.warning("Упс!", "Вы израсходовали все попытки!")

            })
        },

        loadServiceData() {
            return this.$store.dispatch("wheelOfFortuneLoadData").then((response) => {
                let index = 1;

                this.rules = response.rules
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


            this.$store.dispatch("wheelOfFortuneWin", {
                winForm: data

            }).then((response) => {

                this.winForm = {
                    win: null,
                    name: null,
                    phone: null,
                }

                this.$botNotification.success("Колесо фортуны",
                    "Вы успешно приняли участие в розыгрыше! Наш менеджер свяжется с вами для дальнейших инструкций.",
                );

                this.prepareUserData()

            }).catch(err => {

            })

        },

        launchWheel() {
            this.rouletteKey += 1;
            setTimeout(() => this.$refs.wheel.launchWheel(), 0);
        },
        wheelStartedCallback() {

        },
        callbackPlayerForm(form){
            this.winForm = {...this.winForm, ...form}
            this.hasProfileData = true
        },
        wheelEndedCallback(evt) {
            if (!evt)
                return;
            this.winForm.win = evt.id
            this.submit()
            this.hasProfileData = false
            this.$botNotification.success("Победа!", "Вы выиграли приз <strong>" + (this.winForm.win || '-') + "</strong>. Для получения приза заполните анкету победителя!")
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
