<template>


    <div class="d-flex justify-content-center flex-wrap">
        <template v-for="(item, index) in settings" v-if="!selected_prize">
            <div class="item-wrap p-1">
                <span
                    @click="selectPrize(index)"
                    class="btn btn-outline-light">{{ item.value }}</span>
            </div>

        </template>

        <div class="card w-100" v-else @click="selected_prize=null">
            <div class="card-body">
                <h6 class="mb-2 text-center"> {{ selected_prize.value || '-' }} (#{{ selected_prize.id }})</h6>
                <p class="mb-2 fst-italic">{{ selected_prize.description || 'не указно' }}</p>
                <p class="mb-0">Способ получения: <span
                    class="fw-bold text-primary">{{ selected_prize.mark || 'не указано' }}</span></p>
            </div>
        </div>
    </div>

    <div class="wrap"
         v-if="loaded">
        <Wheel
            :gift="gift"
            :imgParams="logo"
            @done="done"
            ref="wheel"
            v-model="settings"
        />
        <div
            v-if="enabledToPlay"
            class="start-panel">
            <button
                type="button"
                @click="launchWheel"
                class="btn btn-outline-primary bg-white border-5 text-black w-100 h-100 rounded-circle">Нажми старт
            </button>
        </div>
    </div>

    <div class="card"
         id="result"
         v-if="form.win">
        <div class="card-body">
            <h6 class="text-center fw-bold">
                <span v-if="completed_at">Ваш текущий выигрыш</span>
                <span v-else>Ваш прошлый выигрыш</span>

            </h6>
            <h6 class="mb-2 text-center"> {{ form.win.value || form.win.id || '-' }} (#{{ form.win.id }})</h6>
            <p class="mb-0 fst-italic">{{ form.win.description || 'не указно' }}</p>
            <p v-if="completed_at"
               class="mb-0 mt-2">Вы сможете получить приз: <span
                class="fw-bold text-primary">{{ form.win.mark || 'не указано' }}</span></p>
            <hr class="mb-2 p-0" v-if="completed_at">
            <p class="mb-0" v-if="completed_at"><span class="fw-bold">Внимание!</span> Приз возможно получить только
                течении 24часов с момента выигрыша:
                <span class="fw-bold text-primary">{{ $filters.currentFull(completed_at) }}</span>
            </p>
        </div>
    </div>

</template>

<script lang="ts">
import {Wheel} from "vue3-fortune-wheel";
import img from '/public/images/bg-wheel-1.png'

export default {
    props: ["modelValue", "canPlay", "actionData", "isAdmin"],
    computed: {

        enabledToPlay() {
            if (this.isAdmin)
                return true

            return !this.started && this.canPlay
        }
    },
    data() {
        return {
            loaded: true,
            selected_prize: null,
            started: false,
            completed_at: null,
            gift: 0,
            form: {
                win: null,
            },
            logo: {
                src: "/wheel.png",
                width: 120,
                height: 120,
            },
            settings: [
                {
                    id: 1,
                    value: "🍅",
                    bgColor: "#fac600",
                    color: "#ffffff",
                },
                {
                    id: 2,
                    value: "🍲",
                    bgColor: "#ffffff",
                    color: "#000000",
                },
                {
                    id: 3,
                    value: "🍦",
                    bgColor: "#ff2e55",
                    color: "#ffffff",
                },
                {
                    id: 4,
                    value: "😍",
                    bgColor: "#a1043a",
                    color: "#ffffff",
                },
                {
                    id: 5,
                    value: "☕",
                    bgColor: "#ffffff",
                    color: "#000000",
                },
                {
                    id: 6,
                    value: "🍕",
                    bgColor: "#c92729",
                    color: "#ffffff",
                },
                {
                    id: 7,
                    value: "📲",
                    bgColor: "#ffffff",
                    color: "#000000",
                },
                {
                    id: 8,
                    value: "📌",
                    bgColor: "#c92729",
                    color: "#ffffff",
                },
                {
                    id: 9,
                    value: "🚀",
                    bgColor: "#c92729",
                    color: "#ffffff",
                },
            ]
        }
    },

    mounted() {
        this.loaded = false
        this.$nextTick(() => {
            this.settings = this.modelValue
            if (this.actionData) {
                this.form.win = (this.actionData.data || []).length > 0 ? this.actionData.data[this.actionData.data.length - 1] || null : null
                this.completed_at = this.actionData.completed_at || null
            }

            this.loaded = true
        })
    },
    methods: {
        selectPrize(index) {
            this.selected_prize = this.settings[index]
        },
        done(r) {
            this.form.win = r

            if (!this.isAdmin)
                this.$emit("win", this.form)

            this.$notify({
                title: 'Колесо фортуны',
                text: 'Поздравляем! Вы выиграли!',
                type: 'success'
            })
            this.completed_at = new Date()

            this.$nextTick(() => {
                document.querySelector("#result").scrollIntoView();
            })
        },

        launchWheel() {
            if (!this.enabledToPlay)
                return

            this.gift = Math.floor(Math.random() * this.settings.length) + 1
            let wheel = this.$refs.wheel
            this.started = true
            wheel.spin();

        }
    },
    components: {
        Wheel,
    },


}

</script>

<style lang="scss">
.wrap {
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;

    .start-panel {
        position: absolute;
        z-index: 1000;
        height: 110px;
        width: 110px;
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 50%;
        box-shadow: 0px 0px 5px 1px black;
        //background: url('/images/bg-wheel-1.png');
        //background-size: contain;
        // background-repeat: no-repeat;
        // background-position-y: 43px;
    }

    div#wheel svg {
        font-size: 32px;
    }

}
</style>
