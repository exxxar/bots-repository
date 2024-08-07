<script setup>
import WheelCustomScriptEditor from "@/ClientTg/Components/V2/Admin/ScriptEditors/WheelCustom/WheelCustomScriptEditor.vue";
</script>
<template>

    <div class="container py-3" v-if="wheelDataLoaded">
        <div class="row">
            <div class="col-12" v-if="(getSelf||{is_admin:false}).is_admin">
                <button
                    type="button"
                    data-bs-toggle="modal" data-bs-target="#shop-wheel-form-modal"
                    class="btn btn-outline-light text-primary w-100 mb-2"
                    style="font-size:12px;">
                    <i class="fa-regular fa-pen-to-square "></i> Редактор скрипта
                </button>
            </div>

            <div class="col-12">
                <h4 v-if="rules">Правила данной игры</h4>
                <p v-if="rules" v-html="rules" class="mb-2"></p>

                <p v-if="canPlay&&action" class="mb-2">Ваши попытки:
                    <strong class="fw-bold text-primary">
                        {{ action?.current_attempts || 0 }}
                    </strong> из
                    <strong class="fw-bold text-primary">
                        {{ action?.max_attempts || 1 }}
                    </strong>
                </p>
                <div
                    class="alert-light alert mb-2"
                    @click="lose"
                    v-else>
                    <p class="mb-0 fw-bold text-danger">Вы израсходовали все ваши попытки</p>
                </div>

                <div v-if="action">
                    <div v-if="sortedActionData.length>0" class="alert-light alert mb-2">
                        <h6 class="text-center fw-bold">Результат розыгрыша</h6>
                        <p class="mb-2 d-flex justify-content-between">Название приза <strong
                            class="fw-bold text-primary">{{ sortedActionData[0].description || 'Отсутствует' }}</strong>
                        </p>
                        <p class="mb-2 d-flex justify-content-between">Победитель <strong class="fw-bold text-primary">{{
                                sortedActionData[0].name || 'Не указано'
                            }}</strong></p>
                        <p class="mb-2 d-flex justify-content-between">Телефон <strong
                            class="fw-bold text-primary">{{ sortedActionData[0].phone || 'Не указано' }}</strong></p>
                        <p class="mb-0 d-flex justify-content-between"
                           v-if="sortedActionData[0].played_at">
                            Дата розыгрыша <strong class="fw-bold text-primary">{{
                                $filters.currentFull(sortedActionData[0].played_at)
                            }}</strong>
                        </p>

                        <h6 class="mt-3 mb-2 text-center fw-bold">Как получить приз</h6>
                        <p class="mb-0 fst-italic" v-if="script_data.callback_message" v-html="script_data.callback_message"></p>
                    </div>
                </div>
                <div v-else
                    class="alert alert-light d-flex flex-column align-items-center justify-content-center">
                    Подготавливаем информацию по вашим розыгрышам...
                    <div class="spinner-border text-primary my-3" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>

                <div class=" w-100 py-2 alert alert-light">
                    <p v-for="(item, index) in items" v-if="!selected_prize"
                       @click="selectPrize(index)"
                       class="mb-0">{{ item.value }} - {{ item.description }}</p>
                </div>

                <div class="wrap">
                    <Wheel
                        :gift="gift"
                        :imgParams="logo"
                        @done="done"
                        ref="wheel"
                        v-model="items"
                    />
                    <div
                        v-if="canPlay"
                        class="start-panel">
                        <button
                            type="button"
                            @click="launchWheel"
                            class="btn btn-outline-primary bg-white border-5 text-black w-100 h-100 rounded-circle">
                            Нажми старт
                        </button>
                    </div>
                </div>


            </div>
            <div class="col-12" v-if="winForm.win">
                <div class="card"
                     id="result">
                    <div class="card-body">
                        <h6 class="text-center fw-bold">
                            Ваш текущий выигрыш
                        </h6>

                        <h6 class="mb-2 text-center"> {{ winForm.win.value || winForm.win.id || '-' }} (#{{
                                winForm.win.id
                            }})</h6>
                        <p class="mb-2 fst-italic">{{ winForm.win.description || 'не указно' }}</p>


                    </div>
                </div>
            </div>

            <div class="col-12" v-if="sortedActionData.length>0">
                <h6 class="my-3">История розыгрышей</h6>
                <ul class="list-group">
                    <li class="list-group-item p" v-for="item in sortedActionData"
                        v-if="action.data">
                        <p class="mb-2 d-flex justify-content-between">Название приза <strong
                            class="fw-bold text-primary text-right">{{ item.description || 'Отсутствует' }}</strong></p>
                        <p class="mb-2 d-flex justify-content-between">Победитель <strong class="fw-bold text-primary text-right">{{
                                item.name || 'Не указано'
                            }}</strong></p>
                        <p class="mb-2 d-flex justify-content-between">Телефон <strong
                            class="fw-bold text-primary text-right">{{ item.phone || 'Не указано' }}</strong></p>
                        <p class="mb-2 d-flex justify-content-between"
                           v-if="item.played_at">
                            Дата розыгрыша <strong class="fw-bold text-primary text-right">{{
                                $filters.currentFull(item.played_at)
                            }}</strong>
                        </p>
                    </li>
                </ul>
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

    <!-- Modal -->
    <div
        class="modal fade" id="shop-wheel-form-modal"
        tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Редактор</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <WheelCustomScriptEditor v-if="script_data" v-model="script_data"></WheelCustomScriptEditor>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import {Wheel} from "vue3-fortune-wheel";
import {mapGetters} from "vuex";

export default {
    components: {
        Wheel,
    },
    name: "App",

    data() {
        return {
            smiles: ["🥤", "🥗", "🍔", "🍗", "🍟", "🥓", "🌯", "🍱", "🍜", "🍲", "🍧", "🍨", "🧁", "🥞",
                "💎", "🤖", "🎲", "🎯", "🏆", "😊", "😎", "🌻", "👽", "💌", "📚", "🐶", "👻", "🏀", "👓", "🎓"],
            rules: null,
            action: null,
            script_data:null,
            selected_prize: null,
            wheelDataLoaded: false,
            winForm: {
                win: null,
            },
            gift: 0,
            logo: {
                src: "/wheel.png",
                width: 120,
                height: 120,
            },
            items: [
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
            ],
        };
    },
    computed: {
        ...mapGetters(['getSelf']),
        sortedActionData() {
            if (!this.action || (this.action?.data || []).length === 0)
                return []

            return this.action.data.sort((a, b) => {
                return new Date(b.played_at) - new Date(a.played_at);
            });
        },
        canPlay() {

            if (!this.action)
                return false

            return this.action.current_attempts < this.action.max_attempts
        },
    },
    mounted() {
        this.wheelDataLoaded = false
        this.loadServiceData().then(() => {
            this.prepareUserData().then(()=>{
                this.wheelDataLoaded = true
            })
        })
    }
    ,
    methods: {
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
        launchWheel() {
            if (!this.canPlay) {
                this.$notify({
                    title: 'Колесо фортуны',
                    text: "Вы израсходовали все попытки!",
                    type: 'error'
                })

                return
            }


            this.gift = Math.floor(Math.random() * this.items.length) + 1
            let wheel = this.$refs.wheel
            this.started = true
            wheel.spin();

        },
        done(r) {
            this.winForm.win = r

            this.$notify({
                title: 'Колесо фортуны',
                text: 'Поздравляем! Вы выиграли!',
                type: 'success'
            })

            this.action.current_attempts++

            this.$nextTick(() => {
                document.querySelector("#result").scrollIntoView();

                setTimeout(() => {
                    this.submit()
                }, 2000)
            })
        },
        lose() {

            this.$notify({
                title: 'Колесо фортуны',
                text: "Вы израсходовали все попытки!",
                type: 'error'
            })
        },
        prepareUserData() {
            return this.$store.dispatch("wheelOfFortuneCustomPrepare").then((response) => {
                this.action = response.action

            })
        },
        selectPrize(index) {
            this.selected_prize = this.items[index]
        },
        loadServiceData() {

            return this.$store.dispatch("wheelOfFortuneCustomLoadData").then((response) => {
                this.script_data = response
                this.rules = response.rules
                const wheels = this.shuffle(response.wheels)

                let index = 0
                this.items = []
                let tmpValues = []

                let getRandomInt = (min, max) => {
                    min = Math.ceil(min);
                    max = Math.floor(max);
                    return Math.floor(Math.random() * (max - min + 1)) + min;
                }

                wheels.forEach(item => {

                    let success = false
                    let value = index+1
                    while (!success) {
                        value = this.smiles[getRandomInt(0,this.smiles.length - 1)]
                        if (tmpValues.indexOf(value) === -1) {
                            tmpValues.push(value)
                            success = true
                        }
                    }

                    this.items.push({
                        id: index + 1,
                        value: value,
                        bgColor: index % 2 === 0 ? "#9a1717" : "#ffffff",
                        color: "#9a1717",
                        description: item.value
                    })

                    index++
                })


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

            data.append("description", this.winForm.win.description || 'Без описания')

            this.$store.dispatch("wheelOfFortuneCustomWin", {
                winForm: data
            }).then((response) => {
                this.winForm.win = null

                this.prepareUserData()

                this.tg.close();
            }).catch(err => {

            })

            this.$notify({
                title: 'Колесо фортуны',
                text: "Вы выиграли приз " + (this.winForm.win.description || 'Что-то интересное...'),
                type: 'success'
            })

        },


    },
}
;
</script>
<style lang="scss">
.wrap {
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    flex-direction: column;

    div#wheel svg {
        font-size: 26px;
    }
}
</style>
