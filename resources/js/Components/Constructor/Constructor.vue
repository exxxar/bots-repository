<script setup>
import Company from "@/Components/Constructor/Company.vue";
import CompanyList from "@/Components/Constructor/CompanyList.vue";
import Location from "@/Components/Constructor/Location.vue";
import Bot from "@/Components/Constructor/Bot.vue";
import ImageMenu from "@/Components/Constructor/ImageMenu.vue";
</script>
<template>

    <div class="container">
        <div class="row">
            <div class="card mb-3 p-0" v-if="step===0">
                <div class="card-header">
                    <h3>Шаг 1: найдите или создайте компанию</h3>
                </div>
                <div class="card-body" v-if="step===0">
                    <h5 class="mt-2 mb-2">Найдите существующую компанию</h5>
                    <CompanyList v-on:callback="companyCallback"/>
                    <h5 class="mb-2">или создайте новую компанию</h5>
                    <Company v-on:callback="companyCallback"/>
                </div>
                <div class="card-body" v-else>
                    <div class="alert alert-success" role="alert">
                        Отлично! Шаг создания компании пройден! Далее следует приступить к следующим шагам!
                    </div>
                </div>
            </div>

            <div class="card mb-3 p-0" v-if="step===1">
                <div class="card-header">
                    <h3>Шаг 2: Добавьте локации заведений (не объязательно)</h3>
                </div>
                <div class="card-body" v-if="step===1">
                    <div class="alert alert-success" role="alert">
                        Если нет необходимости в локациях, вы можете пропустить данный шаг
                        <button
                            type="button"
                            class="btn btn-primary"
                            @click="skip"
                        >Пропустить
                        </button>
                        или же вы можете вернуться на прошлый шаг
                        <button
                            type="button"
                            class="btn btn-primary"
                            @click="reset"
                        >Начать заново
                        </button>
                    </div>
                    <Location v-if="companyId"
                              :company-id="companyId"
                              v-on:callback="locationCallback"
                    />
                </div>
                <div class="card-body" v-if="step<1">
                    <div class="alert alert-warning" role="alert">
                        Внимание! Вы еще не справились с прошлыми шагами!
                    </div>
                </div>
                <div class="card-body" v-if="step>1">
                    <div class="alert alert-success" role="alert">
                        Отлично! Вы создалии локации в завдении! Приступаем к следующим шагам!
                    </div>
                </div>
            </div>

            <div class="card mb-3 p-0" v-if="step===2">
                <div class="card-header">
                    <h3>Шаг 3: Добавьте бота

                    </h3>
                </div>
                <div class="card-body" v-if="step===2">

                    <div class="alert alert-success" role="alert">
                        При необходимости вы можете начать по новой
                        <button
                            type="button"
                            class="btn btn-primary"
                            @click="reset"
                        >Начать заново
                        </button>
                    </div>

                    <Bot v-if="companyId"
                         :company-id="companyId"
                         v-on:callback="botCallback"
                    />
                </div>
                <div class="card-body" v-if="step<2">
                    <div class="alert alert-warning" role="alert">
                        Внимание! Вы еще не справились с прошлыми шагами!
                    </div>
                </div>
                <div class="card-body" v-if="step>2">
                    <div class="alert alert-success" role="alert">
                        Отлично! Бот создан!
                    </div>
                </div>
            </div>

            <div class="card mb-3 p-0" v-if="step===3">
                <div class="card-header">
                    <h3>Шаг 4: Добавьте в бота меню</h3>
                </div>
                <div class="card-body" v-if="step===3">
                    <ImageMenu
                        v-if="botId"
                        :bot-id="botId"
                        v-on:callback="imageMenuCallback"/>
                </div>
                <div class="card-body" v-if="step<3">
                    <div class="alert alert-warning" role="alert">
                        Внимание! Вы еще не справились с прошлыми шагами!
                    </div>
                </div>
                <div class="card-body" v-if="step>3">
                    <div class="alert alert-success" role="alert">
                        Отлично! Все шаги выполнены

                        <button
                            type="button"
                            class="btn btn-primary"
                            @click="reset"
                        >Начать заново
                        </button>

                    </div>
                </div>
            </div>

            <div class="card mb-3 p-0" v-if="step===4">
                <div class="card-header">
                    <h3>Шаг 5: Обновите зависимости веб-хуков</h3>
                </div>
                <div class="card-body" v-if="step===3">
                    <a
                        target="_blank"
                        class="btn btn-outline-success w-100"
                        href="/bot/register-webhooks">Обновить</a>
                </div>

            </div>

        </div>
    </div>

</template>
<script>
import {mapGetters} from "vuex";

export default {
    data() {
        return {
            step: 0,
            companyId: null,
            botId: null,
        }
    },
    computed: {
        ...mapGetters(['getErrors']),
    },
    watch: {
        getErrors: function (newVal, oldVal) {
            Object.keys(newVal).forEach(key => {
                this.$notify({
                    title: "Конструктор ботов",
                    text: newVal[key],
                    type: 'warn'
                });
            })

        }
    },
    methods: {
        reset() {
            this.step = 0
            this.companyId = null
            this.botId = null
        },
        skip() {
            this.step++
        },
        companyCallback(company) {
            console.log("company", company)
            this.companyId = company.id
            this.step++;
        },
        locationCallback(location) {
            console.log("location", location)
            this.step++;
        },
        botCallback(bot) {
            console.log("bot", bot)
            this.botId = bot.id
            this.step++;
        },
        imageMenuCallback(imageMenu) {
            this.step++
        }

    }
}
</script>
