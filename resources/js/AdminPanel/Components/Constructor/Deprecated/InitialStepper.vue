<script setup>
import Company from "@/AdminPanel/Components/Constructor/Company/CompanyForm.vue";
import CompanyList from "@/AdminPanel/Components/Constructor/Company/CompanyList.vue";
import BotList from "@/AdminPanel/Components/Constructor/Bot/BotList.vue";
import Location from "@/AdminPanel/Components/Constructor/Location/Location.vue";
import Bot from "@/AdminPanel/Components/Constructor/Bot/BotForm.vue";
import ImageMenu from "@/AdminPanel/Components/Constructor/ImageMenu.vue";
</script>

<template>
    <div class="row">
        <div class="card mb-3 p-0" v-if="step===0">
            <div class="card-header">
                <h3>Шаг 1: найдите или создайте компанию</h3>
            </div>
            <div class="card-body" v-if="step===0">
                <h5 class="mt-2 mb-2">Найдите существующую компанию</h5>
                <CompanyList
                    v-if="!load"
                    v-on:callback="companyListCallback"/>
                <h5 class="mb-2">или создайте новую компанию</h5>
                <Company
                    v-if="!load"
                    :company="company"
                    v-on:callback="companyCallback"/>
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
                    Если нет необходимости в локациях, Вы можете пропустить данный шаг
                    <button
                        type="button"
                        class="btn btn-primary"
                        @click="skip"
                    >Пропустить
                    </button>
                    или же Вы можете вернуться на прошлый шаг
                    <button
                        type="button"
                        class="btn btn-primary"
                        @click="reset"
                    >Начать заново
                    </button>
                </div>
                <Location v-if="company"
                          :company-id="company.id"
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
                    При необходимости Вы можете начать по новой
                    <button
                        type="button"
                        class="btn btn-primary"
                        @click="reset"
                    >Начать заново
                    </button>
                </div>

                <h5 class="mt-2 mb-2">Найдите существующего бота</h5>
                <BotList
                    v-if="company"
                    :company-id="company.id"
                    v-on:callback="botListCallback"/>
                <h5 class="mb-2">или создайте нового бота</h5>

                <Bot v-if="bot&&company&&!load"
                     :bot="bot"
                     :company-id="company.id"
                     v-on:callback="botCallback"
                />

                <Bot v-else-if="bot==null&&company&&!load"
                     :company-id="company.id"
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

                <div class="alert alert-success" role="alert">
                    При необходимости Вы можете начать по новой
                    <button
                        type="button"
                        class="btn btn-primary"
                        @click="reset"
                    >Начать заново
                    </button>
                </div>

                <ImageMenu
                    v-if="bot"
                    :bot-id="bot.id"
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
            <div class="card-body">
                <div class="row">
                    <div class="alert alert-warning" role="alert">
                        <strong>Важно!</strong> новые боты начнут работать только после того, как Вы обновите зависимости!
                    </div>

                    <div class="col-12 mb-3">
                        <a
                            class="btn btn-outline-success w-100"
                            @click="reloadWebhooks">Обновить зависимости</a>
                    </div>
                    <div class="col-12">
                        <a
                            @click="reset"
                            class="btn btn-outline-primary w-100"
                        >Начать заново</a>
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
            step: 0,
            load: false,
            company: null,
            location: null,
            bot: null,
        }
    },

    methods: {
        reloadWebhooks(){
            axios.get("/bot/register-webhooks").then(()=>{
                this.$notify({
                    title: "Конструктор ботов",
                    text: "Зависимости успешно обновлены!",
                    type: 'success'
                });
            })
        },
        reset() {
            this.step = 0
            this.company = null
            this.bot = null
        },
        skip() {
            this.step++
        },
        companyListCallback(company){
            this.load = true
            this.company = company
            this.$nextTick(()=>{
                this.load = false
            })

        },
        companyCallback(company) {
            this.step++;
            this.load = true
            this.$nextTick(()=>{
                this.load = false
            })

            document.documentElement.scrollTop = 0;
        },
        locationCallback() {
            this.step++;
            this.load = false

            document.documentElement.scrollTop = 0;
        },
        botListCallback(bot){
            this.load = true
            this.bot = bot
            this.$nextTick(()=>{
                this.load = false
            })
        },
        botCallback(bot) {
            this.step++;
            this.bot = bot
            document.documentElement.scrollTop = 0;
        },
        imageMenuCallback(imageMenu) {
            this.step++

            document.documentElement.scrollTop = 0;
        }

    }
}
</script>
