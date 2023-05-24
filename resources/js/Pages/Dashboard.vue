<script setup>
import Constructor from "@/Components/Constructor/Constructor.vue";

import InitialStepper from "@/Components/Constructor/InitialStepper.vue";
import BotEditor from "@/Components/Constructor/BotEditor.vue";
import BotPageConstructor from "@/Components/Constructor/BotPageConstructor.vue";
import ImageMenuEditor from "@/Components/Constructor/ImageMenuEditor.vue";
import CompanyEditor from "@/Components/Constructor/CompanyEditor.vue";
import LocationEditor from "@/Components/Constructor/LocationEditor.vue";
import BotDialogGroupEditor from "@/Components/Constructor/BotDialogGroupEditor.vue";
import BotCreator from "@/Components/Constructor/BotCreator.vue";
import { Head } from '@inertiajs/vue3'
</script>
<template>

    <Head>
        <title>Административная панель</title>
        <meta name="description" content="Административная панель<">
    </Head>

    <notifications position="top right"/>

    <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
        <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6" href="#">CashMan</a>
        <button class="navbar-toggler position-absolute d-md-none collapsed"
                type="button"
                data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu"
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-nav d-none d-md-block">
            <div class="nav-item text-nowrap">
                <a class="nav-link px-3" href="/logout">Выход</a>
            </div>
        </div>
    </header>

    <div class="container-fluid">
        <div class="row">
            <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-body-tertiary sidebar" style="">
                <div class="position-sticky pt-3 sidebar-sticky">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link "
                               v-bind:class="{'active':tab===0}"
                               href="#new-client"
                               aria-current="page" @click="tab=0">
                                <i class="fa-solid fa-mug-hot"></i>
                                Создание нового клиента
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link "
                               v-bind:class="{'active':tab===7}"
                               href="#new-bot"
                               aria-current="page" @click="tab=7">
                                <i class="fa-brands fa-android"></i>
                                Создание нового бота
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"
                               href="#company-editor"
                               v-bind:class="{'active':tab===1}"
                               aria-current="page" @click="tab=1">
                                <i class="fa-regular fa-building"></i>
                                Редактирование компаний
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"
                               href="#location-editor"
                               v-bind:class="{'active':tab===2}"
                               aria-current="page" @click="tab=2">
                                <i class="fa-solid fa-map-location-dot"></i>
                                Редактирование локаций
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"
                               href="#bot-editor"
                               v-bind:class="{'active':tab===3}"
                               aria-current="page" @click="tab=3">
                                <i class="fa-solid fa-robot"></i>
                                Редактирование ботов
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"
                               href="#menu-editor"
                               v-bind:class="{'active':tab===4}"
                               aria-current="page" @click="tab=4">
                                <i class="fa-regular fa-images"></i>
                                Редактор "предложения и услуги" для ботов
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"
                               href="#page-construct"
                               v-bind:class="{'active':tab===5}"
                               aria-current="page" @click="tab=5">
                                <i class="fa-regular fa-file-lines"></i>
                                Конструктор страниц
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"
                               href="#dialog-editor"
                               v-bind:class="{'active':tab===6}"
                               aria-current="page" @click="tab=6">
                                <i class="fa-regular fa-comment-dots"></i>
                                Конструктор диалогов
                            </a>
                        </li>

                        <li class="nav-item p-2">
                            <div class="alert alert-warning" role="alert">
                                <strong>Важно!</strong> новые боты начнут работать только после того, как вы обновите зависимости!
                            </div>

                            <button
                                type="button"
                                :disabled="load"
                                class="nav-lin btn btn-outline-success w-100"
                                @click="reloadWebhooks">Обновить зависимости</button>
                        </li>

                        <li class="nav-item p-2">
                            <button
                                type="button"
                                :disabled="load"
                                class="nav-lin btn btn-outline-success w-100"
                                @click="stopAllDialogs">Остановить все диалоги</button>
                        </li>

                    </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4" v-if="tab===0">
                <div class="pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="mb-3 mt-3">Создание нового клиента</h1>
                    <InitialStepper/>
                </div>
            </main>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4" v-if="tab===1">
                <div class="pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="mb-3 mt-3">Редактор компаний</h1>
                    <div class="alert alert-success mb-2" role="alert">
                        Выберите компанию из списка!
                    </div>
                    <CompanyEditor/>
                </div>
            </main>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4" v-if="tab===2">
                <div class="pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="mb-3 mt-3">Редактор локаций</h1>
                    <div class="alert alert-success mb-2" role="alert">
                        Выберите компанию из списка!
                    </div>
                    <LocationEditor/>
                </div>
            </main>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4" v-if="tab===3">
                <div class="pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="mb-3 mt-3">Редактор ботов</h1>
                    <div class="alert alert-success mb-2" role="alert">
                        Выберите бота из списка!
                    </div>
                    <BotEditor/>
                </div>
            </main>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4" v-if="tab===7">
                <div class="pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="mb-3 mt-3">Создание бота</h1>
                    <div class="alert alert-success mb-2" role="alert">
                        Выберите компанию из списка!
                    </div>
                    <BotCreator/>
                </div>
            </main>


            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4" v-if="tab===4">
                <div class="pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="mb-3 mt-3">Редактор меню</h1>
                    <div class="alert alert-success mb-2" role="alert">
                        Выберите бота из списка!
                    </div>
                    <ImageMenuEditor/>
                </div>
            </main>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4" v-if="tab===5">
                <div class="pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="mb-3 mt-3">Конструктор страниц</h1>
                    <div class="alert alert-success mb-2" role="alert">
                        Выберите бота из списка!
                    </div>
                    <BotPageConstructor/>
                </div>
            </main>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4" v-if="tab===6">
                <div class="pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="mb-3 mt-3">Конструктор диалогов</h1>
                    <div class="alert alert-success mb-2" role="alert">
                        Выберите бота из списка!
                    </div>
                    <BotDialogGroupEditor/>
                </div>
            </main>
        </div>
    </div>

</template>
<script>
import {mapGetters} from "vuex";

export default {

    data() {
        return {
            tab: 0,
            load: false
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
        stopAllDialogs(){
            this.$store.dispatch("stopDialogs").then((response) => {

                this.$notify({
                    title: "Конструктор ботов",
                    text: "Все диалоги остановлены",
                    type: 'success'
                });


            }).catch(err => {

            })
        },
        reloadWebhooks(){
            this.load = true
            this.$notify({
                title: "Конструктор ботов",
                text: "Процедура обновления зависимостей началась",
            });
            axios.get("/bot/register-webhooks").then(()=>{
                this.load = false
                this.$notify({
                    title: "Конструктор ботов",
                    text: "Зависимости успешно обновлены!",
                    type: 'success'
                });
            }).catch(()=>{
                this.load = false

                this.$notify({
                    title: "Конструктор ботов",
                    text: "Неудалось обновить зависимости",
                    type: 'error'
                });
            })
        },
    }
}
</script>

<style>
.bg-dots-darker {
    background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(0,0,0,0.07)'/%3E%3C/svg%3E");
}

@media (prefers-color-scheme: dark) {
    .dark\:bg-dots-lighter {
        background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(255,255,255,0.07)'/%3E%3C/svg%3E");
    }
}


body {
    font-size: .875rem;
}

.feather {
    width: 16px;
    height: 16px;
}

/*
 * Sidebar
 */

.sidebar {
    position: fixed;
    top: 0;
    /* rtl:raw:
    right: 0;
    */
    bottom: 0;
    /* rtl:remove */
    left: 0;
    z-index: 100; /* Behind the navbar */
    padding: 48px 0 0; /* Height of navbar */
    box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
}

@media (max-width: 767.98px) {
    .sidebar {
        top: 5rem;
    }
}

.sidebar-sticky {
    height: calc(100vh - 48px);
    overflow-x: hidden;
    overflow-y: auto; /* Scrollable contents if viewport is shorter than content. */
}

.sidebar .nav-link {
    font-weight: 500;
    color: #333;
}

.sidebar .nav-link .feather {
    margin-right: 4px;
    color: #727272;
}

.sidebar .nav-link.active {
    color: #2470dc;
}

.sidebar .nav-link:hover .feather,
.sidebar .nav-link.active .feather {
    color: inherit;
}

.sidebar-heading {
    font-size: .75rem;
}

/*
 * Navbar
 */

.navbar-brand {
    padding-top: .75rem;
    padding-bottom: .75rem;
    background-color: rgba(0, 0, 0, .25);
    box-shadow: inset -1px 0 0 rgba(0, 0, 0, .25);
}

.navbar .navbar-toggler {
    top: .25rem;
    right: 1rem;
}

.navbar .form-control {
    padding: .75rem 1rem;
}

.form-control-dark {
    color: #fff;
    background-color: rgba(255, 255, 255, .1);
    border-color: rgba(255, 255, 255, .1);
}

.form-control-dark:focus {
    border-color: transparent;
    box-shadow: 0 0 0 3px rgba(255, 255, 255, .25);
}

.cursor-pointer {
    cursor: pointer;
}

.ml-2 {
    margin-left: 10px;
}

.mr-2 {
    margin-right: 10px;
}
</style>
