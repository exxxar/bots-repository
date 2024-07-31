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
         v-if="loaded"
         @click="launchWheel">
        <Wheel
            :gift="gift"
            :imgParams="logo"
            @done="done"
            ref="wheel"
            v-model="settings"
        />
    </div>

    <div class="card" v-if="form.win">
        <div class="card-body">
            <h6 class="text-center fw-bold">Ваш выигрыш</h6>
            <h6 class="mb-2 text-center"> {{ form.win.value || form.win.id || '-' }} (#{{ form.win.id }})</h6>
            <p class="mb-2 fst-italic">{{ form.win.description || 'не указно' }}</p>
            <p class="mb-0">Вы сможете получить приз: <span
                class="fw-bold text-primary">{{ form.win.mark || 'не указано' }}</span></p>
            <hr class="mb-2 p-0">
            <p class="mb-0"><span class="fw-bold">Внимание!</span> Приз возможно получить только в день выигрыша:
                <span class="fw-bold text-primary">{{ $filters.current(new Date()) }}</span>
            </p>
        </div>
    </div>

</template>

<script lang="ts">
import {Wheel} from "vue3-fortune-wheel";

export default {
    props: ["modelValue"],
    data() {
        return {
            loaded: true,
            gift: 2,
            selected_prize: null,
            started: false,
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
            this.loaded = true
        })
    },
    methods: {
        selectPrize(index) {
           this.selected_prize = this.settings[index]
        },
        done(r) {
            this.form.win = r
        },

        launchWheel() {
            /* if (this.started)
                 return*/

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

    div#wheel svg {
        font-size: 32px;
    }

}
</style>
