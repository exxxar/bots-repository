<script setup>
import Layout from "@/AdminPanel/Layouts/MainAdminLayout.vue";
import BotSection from "@/AdminPanel/Components/Constructor/Bot/BotSection.vue";
import CompanyList from "@/AdminPanel/Components/Constructor/Company/CompanyList.vue";
import BotList from "@/AdminPanel/Components/Constructor/Bot/BotList.vue";
import BotForm from "@/AdminPanel/Components/Constructor/Bot/BotForm.vue";

</script>

<template>
    <Layout :active="2" :need-menu="true">
        <template #default>
            <div class="container">
                <div class="row mb-2">
                    <div class="col-12">
                        <div class="btn-group" role="group" aria-label="Basic outlined example">
                            <button type="button"
                                    @click="setStep(0)"
                                    v-bind:class="{'btn-primary':step===0,'btn-outline-primary':step!==0}"
                                    class="btn">Создание бота
                            </button>
                            <button type="button"
                                    @click="setStep(1)"
                                    v-bind:class="{'btn-primary':step===1,'btn-outline-primary':step!==1}"
                                    class="btn">Поиск бота
                            </button>
                            <button type="button"
                                    :disabled="!bot"
                                    @click="setStep(2)"
                                    v-bind:class="{'btn-primary':step===2,'btn-outline-primary':step!==2}"
                                    class="btn">Редактирование бота
                            </button>

                        </div>
                    </div>
                </div>

                <div class="row mt-3" v-if="step===0">

                    <div class="col-12">
                        <BotForm v-if="!load"
                                 v-on:callback="loadCurrentBot"
                        />
                    </div>
                </div>

                <div class="row" v-if="step===1">
                    <div class="col-12">
                        <h5>Создать бота из шаблона</h5>
                        <Carousel :itemsToShow="3.95" :wrapAround="true" :transition="500">
                            <Slide v-for="(template, index) in templates" :key="template.id">
                                <div class="carousel__item p-2 w-100">
                                    <div class="card w-100">
                                        <div class="card-body w-100">
                                            <h5 class="card-title">
                                                {{ template.title || template.bot_domain || 'Без названия' }}</h5>
                                            <p class="card-text">{{ template.template_description || '-' }}</p>
                                            <button
                                                :disabled="template.in_use||false"
                                                type="button"
                                               @click="duplicateAndEdit(index, template.id)"
                                               class="btn btn-primary">Создать из шаблона</button>
                                        </div>
                                    </div>
                                </div>
                            </Slide>

                            <template #addons>
                                <Navigation/>
                            </template>
                        </Carousel>
                    </div>

                    <BotList
                        v-if="!load"
                        :editor="true"
                        v-on:callback="botListCallback"/>
                </div>

                <div v-if="step===2">
                    <BotSection
                        v-if="bot&&!load"
                        :bot="bot"
                    />
                </div>
            </div>
        </template>
    </Layout>
</template>
<script>
import {mapGetters} from "vuex";
import 'vue3-carousel/dist/carousel.css'
import {Carousel, Slide, Pagination, Navigation} from 'vue3-carousel'

export default {
    components: {
        Carousel,
        Slide,
        Pagination,
        Navigation,
    },
    data() {
        return {
            templates: [],
            load: false,
            step: 0,
            bot: null,
            botUser: null,
        }
    },
    computed: {
        ...mapGetters(['getCurrentBot', 'getCurrentCompany', 'getBotFavorites', 'inBotFav']),
        getSelf() {
            return window.profile
        },
        isManagerVerified() {

            if (!this.botUser)
                return false;

            if (!this.botUser.manager)
                return false;

            /* if (!this.botUser.manager.verified_at)
                 return false*/

            return true
        }
    },
    mounted() {

        this.loadTemplates();

        this.loadCurrentBot()

        this.botUser = this.getSelf

        this.setStep(localStorage.getItem("cashman_set_botpage_step_index") || 0)

        window.addEventListener('store_current_bot-change-event', (event) => {
            this.bot = this.getCurrentBot
            this.step = this.bot ? 2 : 1;
        });


    },
    methods: {
        openForEdit() {

        },
        duplicateAndEdit(index, botId) {
            /* if (!this.getCurrentCompany) {
                 this.$notify("У Вас не выбран клиент!");
                 return;
             }*/

            this.templates[index].in_use = true
            this.$store.dispatch("duplicateBot", {
                dataObject: {
                    bot_id: botId,
                    company_id: this.getCurrentCompany ? this.getCurrentCompany.id : null
                }
            }).then(resp => {
                this.bot = resp.data
                this.load = true
                this.templates[index].in_use = false

                this.loadCurrentBot(this.bot)
                localStorage.setItem("cashman_set_botform_step_index", 0)

                if (!this.inBotFav(this.bot.id))
                    this.$store.dispatch("addBotToFavorites", this.bot.id)

                this.setStep(2)

                this.$nextTick(() => {
                    this.load = false
                })
                this.$notify("Указанный бот успешно продублирован");


            })
        },
        loadTemplates() {
            this.$store.dispatch("loadTemplates").then(resp => {
                this.templates = resp.data
                console.log(resp.data)
            })
        },
        setStep(index) {
            this.step = parseInt(index)
            localStorage.setItem("cashman_set_botpage_step_index", index)
        },
        loadCurrentBot(bot = null) {
            this.$store.dispatch("updateCurrentBot", {
                bot: bot
            }).then(() => {
                this.bot = this.getCurrentBot
            })
        },

        botListCallback(bot) {
            this.load = true

            this.setStep(2)
            this.loadCurrentBot(bot)

            this.$nextTick(() => {
                this.load = false
            })
        }

    }
}
</script>
<style scoped>
.carousel__slide {
    padding: 5px;
}

.carousel__viewport {
    perspective: 2000px;
}

.carousel__track {
    transform-style: preserve-3d;
}

.carousel__slide--sliding {
    transition: 0.5s;
}

.carousel__slide {
    opacity: 0.9;
    transform: rotateY(-20deg) scale(0.9);
}

.carousel__slide--active ~ .carousel__slide {
    transform: rotateY(20deg) scale(0.9);
}

.carousel__slide--prev {
    opacity: 1;
    transform: rotateY(-10deg) scale(0.95);
}

.carousel__slide--next {
    opacity: 1;
    transform: rotateY(10deg) scale(0.95);
}

.carousel__slide--active {
    opacity: 1;
    transform: rotateY(0) scale(1.1);
}
</style>
