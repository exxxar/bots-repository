<template>
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


</template>

<script lang="ts">
import {Wheel} from "vue3-fortune-wheel";

export default {
    props: ["modelValue"],
    data() {
        return {
            loaded:true,
            gift: 2,
            started:false,
            form: {},
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
        done(r) {
            console.log("done", r);
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
