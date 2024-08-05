<script setup>
import WheelOfFortuneShopVariant from "@/ClientTg/Components/V2/Games/WheelOfFortuneShopVariant.vue";
</script>

<template>
    <div class="container py-3" v-if="loadScriptData&&action">
        <div class="row">
            <div class="col-12" v-if="(script_data.wheel_of_fortune||{rules:null}).rules">
                <div class="alert alert-light mb-2">
                    {{ (script_data.wheel_of_fortune || {rules: null}).rules || 'Правила игры' }}
                </div>
            </div>
            <div class="col-12">
                <WheelOfFortuneShopVariant
                    :can-play="canPlay"
                    :is-admin="false"
                    :action-data="action"
                    v-if="(script_data.wheel_of_fortune.items||[]).length>=3"
                    v-on:win="winHandler"
                    v-model="script_data.wheel_of_fortune.items"></WheelOfFortuneShopVariant>
            </div>
        </div>
    </div>
    <div class="container py-3" v-else>
        <div class="row">
            <div class="col-12">
                <div class="alert alert-light d-flex flex-column align-items-center justify-content-center">
                    Подготавливаем данные...
                    <div class="spinner-border text-primary my-3" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            loadScriptData: false,
            script_data: null,
            action: null,
        }
    },
    computed: {
        canPlay() {
            if (!this.action)
                return false

            return this.action.completed_at == null && this.action.current_attempts < this.action.max_attempts
        }
    },
    mounted() {
        this.loadScriptModuleData().then(() => {
            this.prepareUserData()
        })
    },
    methods: {
        loadScriptModuleData() {
            this.loadScriptData = false
            return this.$store.dispatch("loadShopModuleData").then((resp) => {
                this.script_data = []

                this.$nextTick(() => {
                    Object.keys(resp).forEach(item => {
                        this.script_data[item] = resp[item]
                    })

                    /*const items = this.shuffle(this.script_data.wheel_of_fortune.items)
                    this.script_data.wheel_of_fortune.items = items*/
                    this.loadScriptData = true

                })
            })
        },

        winHandler(winForm) {

            this.$store.dispatch("wheelOfFortuneV3Win", {
                winForm: winForm?.win || null
            }).then((response) => {
                this.prepareUserData()
            }).catch(err => {

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
        prepareUserData() {
            return this.$store.dispatch("wheelOfFortuneV3Prepare").then((response) => {
                this.action = response.action

            })
        },
    },
}
</script>
