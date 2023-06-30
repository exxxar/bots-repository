<script setup>
defineProps({
    texts: Array,
});
</script>
<template>
<div class="row">
    <div class="col-12 d-flex justify-content-center align-items-center " style="padding-top: 100px;">
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
        <p v-if="win">Вы выиграли - {{win.htmlContent}}</p>
    </div>
</div>
<!--
<button @click="$refs.wheel.reset">reset</button>
<button @click="rouletteKey += 1">hard reset</button>
-->

</template>
<script>
import { Roulette } from "vue3-roulette";
export default {
    name: "App",
    components: {
        Roulette,
    },
    data() {
        return {
            rouletteKey: 0,
            win:null,
            items: [
                {
                    id: 1,
                    name: "1",
                    htmlContent: "жопа бобра<br>с кусочками<br>лайма",
                    textColor: "",
                    background: "",
                },
                {
                    id: 2,
                    name: "10",
                    htmlContent: "<i class='fa-brands fa-instagram'></i>",
                    textColor: "",
                    background: "",
                },
                {
                    id: 3,
                    name: "Banana",
                    htmlContent:
                        "<img src='https://prv3.lori-images.net/koleso-ruletki-na-belom-fone-0004118371-preview.jpg' style='width:50px;'>",
                    textColor: "",
                    background: "white",
                },
                {
                    id: 4,
                    name: "1000",
                    htmlContent: "1000",
                    textColor: "",
                    background: "",
                },
                {
                    id: 7,
                    name: "1000",
                    htmlContent: "1000",
                    textColor: "",
                    background: "",
                },
                {
                    id: 4,
                    name: "1000",
                    htmlContent: "1000",
                    textColor: "",
                    background: "",
                },
                {
                    id: 4,
                    name: "1000",
                    htmlContent: "1000",
                    textColor: "",
                    background: "",
                },
                {
                    id: 41,
                    name: "10000",
                    htmlContent: "10000",
                },
                {
                    id: 5,
                    name: "1000000",
                    htmlContent: "1000000",
                },
                {
                    id: 6,
                    name: "10000000",
                    htmlContent: "10 000 000",
                },
                {
                    id: 7,
                    name: "0",
                    htmlContent: "0",
                },
            ],
        };
    },
    mounted() {
        //
        console.log(this.texts)
        let index = 1;
        this.items = []
        this.texts.forEach(item=>{
            this.items.push(  {
                id: index,
                name: item.value,
                htmlContent:  item.value,
                textColor: "",
                background: "",
            })

            index++;
        })
    },
    methods: {
        launchWheel() {
            this.rouletteKey += 1;
            setTimeout(() => this.$refs.wheel.launchWheel(), 0);
        },
        wheelStartedCallback() {
            console.log("wheelStartedCallback");
        },
        wheelEndedCallback(evt) {
            console.log(evt);
            this.win = evt
        },
    },
};
</script>
<style>
.wheel-base-container .wheel-base-indicator {
    left: 45px !important;
}
</style>
