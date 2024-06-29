<script setup>
import CallbackForm from "@/ClientTg/Components/Shop/CallbackForm.vue";
import PlayerForm from "@/ClientTg/Components/Shop/PlayerForm.vue";
import WheelSecond from "@/ClientTg/Components/Other/WheelSecond.vue";
import ReturnToBot from "@/ClientTg/Components/Shop/Helpers/ReturnToBot.vue";
</script>
<template>

    <div class="card card-style" v-if="rules">
        <div class="content">
            <h4>Правила данной игры</h4>
            <p v-html="rules">
            </p>

            <p v-if="canPlay&&action" class="mb-2">Ваши попытки: <strong>{{
                    action.current_attempts || 0
                }}</strong> из <strong>{{
                    action.max_attempts || 1
                }}</strong></p>
            <div
                @click="lose"
                v-else>

                <p style="font-weight:900; color:red;" class="mb-2">Вы израсходовали все ваши попытки</p>

            </div>

            <ul v-if="action" class="m-0 p-0">
                <li v-for="item in action.data" class="d-flex flex-column mb-2" v-if="action.data">
                    <span>Название приза <strong>{{ item.description || 'Отсутствует' }}</strong></span>
                    <span>Победитель  <strong>{{ item.name || 'Не указано' }}</strong></span>
                    <span>Телефон  <strong>{{ item.phone || 'Не указано' }}</strong></span>
                </li>
            </ul>

            <ReturnToBot class="my-2"></ReturnToBot>
        </div>
    </div>

    <div class="card card-style" v-if="canPlay&&wheelDataLoaded">
        <div class="content d-flex justify-content-center flex-wrap">


            <WheelSecond
                :items="items"
                v-on:callback="wheelEndedCallback">
                <template #baseContent>
                    Испытай удачу
                </template>
            </WheelSecond>



        </div>
    </div>


<!--
    <CallbackForm/>
-->


</template>
<script>


export default {
    name: "App",

    data() {
        return {
            winResultMessage: "Наш менеджер свяжется с вами для дальнейших инструкций.",
            rules: null,
            rouletteKey: 0,
            action: null,
            hasProfileData: false,
            wheelDataLoaded:false,
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
            return this.$store.dispatch("wheelOfFortuneCustomPrepare").then((response) => {
                this.action = response.action

            })
        },

        loadServiceData() {
            this.wheelDataLoaded = false
            return this.$store.dispatch("wheelOfFortuneCustomLoadData").then((response) => {
                let index = 0;

                const colors = [
                    "#645b00",
                    "#9a4000",
                    "#831814",
                    "#831131",
                    "#83118d",
                    "#7529eb",
                    "#0e2e75",
                    "#115965",
                    "#0f6b2b",
                    "#476c17",
                    "#756d21",
                    "#854a1c",
                ]

                this.rules = response.rules
                this.winResultMessage = response.callback_message
                const wheels = this.shuffle(response.wheels)

                this.items = []
                wheels.forEach(item => {
                    this.items.push({
                        text: item.value,
                        color: colors[index],
                    })

                    index = (index < colors.length) ? index + 1 : 0
                })

                /*if (this.items.length % 2 !==0)
                    this.items.push({
                        text: "Не выиграл",
                        color: colors[index],
                    })*/

                this.wheelDataLoaded = true
            })
        },
        shuffle(array) {
            let currentIndex = array.length, randomIndex;
            // While there remain elements to shuffle.
            while (currentIndex > 0) {
                // Pick a remaining element.
                randomIndex = Math.floor(Math.random() * currentIndex);
                currentIndex--;
                // And swap it with the current element.
                [array[currentIndex], array[randomIndex]] = [
                    array[randomIndex], array[currentIndex]];
            }
            return array;
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
            const winResult = this.winForm.win || null

            data.append("description", this.items[winResult].text || 'Без описания')

            this.$store.dispatch("wheelOfFortuneCustomWin", {
                winForm: data
            }).then((response) => {
                this.winForm.win = null
                this.winForm.name = null
                this.winForm.phone = null

                this.prepareUserData()

                this.tg.close();
            }).catch(err => {

            })


            this.$botNotification.success("Вы выиграли!", "Вы выиграли приз " + (winResult ? this.items[winResult].text : 'Что-то интересное...'))
        },


        callbackPlayerForm(form) {

            this.winForm = {...this.winForm, ...form}

            this.hasProfileData = true
        },
        wheelEndedCallback(evt) {
            this.wheelDataLoaded = false
            console.log("game event", evt)

            const win = evt

            setTimeout(() => {
                this.winForm.win = win
                this.submit()

            }, 2000)

          /*  if (!evt)
                return;
            */


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
