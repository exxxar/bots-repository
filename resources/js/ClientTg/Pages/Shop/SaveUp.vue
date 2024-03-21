<script setup>
import CallbackForm from "@/ClientTg/Components/Shop/CallbackForm.vue";
import PlayerForm from "@/ClientTg/Components/Shop/PlayerForm.vue";
import ReturnToBot from "@/ClientTg/Components/Shop/Helpers/ReturnToBot.vue";
</script>
<template>
    <div class="card card-style" v-if="rules">
        <div class="content">
            <h4>Правила данной акции</h4>
            <p>
                {{ rules }}
            </p>
        </div>
    </div>

    <div class="card card-style" v-if="action">
        <div class="content ">
            <h6 class="text-center">Ваша личная накопительная карта</h6>
            <p></p>
            <ul class="d-flex justify-content-around flex-wrap save-up">
                <li v-bind:class="{'active': n <= action.current_attempts}"
                    @click="requestClick"
                    v-for="n in (action.max_attempts || 10)">
                    <span v-if="n <= action.current_attempts" v-html="icon" v-bind:style="{'color':icon_color}"></span>
                    <span v-else><i class="fa-solid fa-question"></i></span>
                </li>

            </ul>

            <ReturnToBot></ReturnToBot>
        </div>
    </div>

<!--    <CallbackForm/>-->

</template>
<script>

export default {
    name: "App",
    data() {
        return {
            rules: null,
            action: null,
            icon: null,
            icon_color: null,
        };
    },

    mounted() {
        this.prepareUserData()
    }
    ,
    methods: {
        requestClick() {
            this.$cashback.qr("002")
            //this.$botNotification.success("Запрос", "В этой ячейке уже есть отметка о кофе:)")
        },

        prepareUserData() {
            return this.$store.dispatch("bonusProductPrepare").then((response) => {
                this.action = response.action

                this.rules = response.rules
                this.icon = response.icon
                this.icon_color = response.icon_color
            })
        },

        submit() {
            this.$store.dispatch("wheelOfFortuneWin", this.productForm).then((response) => {

                this.winForm = {
                    win: null,
                    name: null,
                    phone: null,
                }

                this.$botNotification.success("Вы выиграли!", "Наш менеджер свяжется с вами для дальнейших инструкций.")


                this.prepareUserData()


            }).catch(err => {

            })

        },

    },
}
;
</script>
<style lang="scss">

.save-up {
    padding: 0;

    li {
        width: 130px;
        height: 130px;
        padding: 10px;
        list-style: none;

        &.active {
            span {
                border-color: #FF9800;

                i {
                    color: #FF9800;
                }
            }
        }

        span {
            width: 100%;
            height: 100%;
            /* background-color: red; */
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            border-width: 8px;
            border-style: double;
            box-sizing: border-box;

            i {
                color: gray;
                font-size: 36px;
            }
        }
    }
}

</style>
