<script setup>

import {Head} from '@inertiajs/vue3'

</script>
<template>

    <Head>
        <title>Административная панель</title>
        <meta name="description" content="Административная панель<">
    </Head>

    <notifications position="top right"/>


    <header
        v-if="needMenu"
        class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
        <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6 cursor-pointer align-items-center d-flex"
           data-bs-toggle="modal" data-bs-target="#selected-company-bot-info">CashMan:
            <span v-if="bot" style="font-size:12px;margin-left:10px;"><a :href="'https://t.me/'+(bot.bot_domain||'botfather')"
                                target="_blank">{{ bot.bot_domain || 'Без имени' }}</a> </span>

        </a>
        <button class="navbar-toggler position-absolute d-md-none collapsed"
                type="button"
                data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu"
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="navbar-nav d-flex justify-content-center align-items-center flex-row">

            <div class="nav-item text-nowrap">
                <a class="nav-link px-3"
                   @click="active=0"
                   v-bind:class="{'border-bottom-active active':active==0}"
                   href="/company-page"> <i class="fa-solid fa-mug-hot"></i> Клиенты</a>
            </div>
            <div class="nav-item text-nowrap">
                <a class="nav-link px-3"
                   @click="active=1"
                   v-bind:class="{'border-bottom-active active':active==1}"
                   href="/bot-page"> <i class="fa-solid fa-robot"></i> Боты</a>
            </div>
            <div class="nav-item text-nowrap">
                <a class="nav-link px-3"
                   @click="active=6"
                   v-bind:class="{'border-bottom-active active':active==6}"
                   href="/script-page"> <i class="fa-solid fa-scroll"></i> Скрипты</a>
            </div>
        </div>
        <div class="navbar-nav d-none d-md-block">

            <div class="nav-item text-nowrap">
                <a class="nav-link px-3" href="/logout">Выход</a>
            </div>
        </div>
    </header>

    <div class="container-fluid">
        <div class="row">

            <main class="col-md-12 ms-sm-auto col-lg-12 px-md-4">
                <div class="pt-3 pb-2 mb-3">
                    <slot/>
                </div>
            </main>
        </div>
    </div>

    <div class="modal fade"
         id="selected-company-bot-info" tabindex="-1" aria-labelledby="open-construct-label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="open-construct-label">У вас выбрано</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="card border-info mb-2" v-if="company">
                        <div class="card-body">
                            <p>У Вас выбран клиент:</p>
                            <div class="d-flex justify-content-between w-100">
                                <span>{{ company.title || 'Без имени' }} </span>
                                <span @click="resetCompany"><i class="fa-solid fa-xmark"></i></span>
                            </div>

                        </div>
                    </div>

                    <div class="card border-info" v-if="bot">
                        <div class="card-body">
                            <p>У Вас выбран бот:</p>
                            <div class="d-flex justify-content-between w-100">
                                <span><a :href="'https://t.me/'+(bot.bot_domain||'botfather')"
                                         target="_blank">{{ bot.bot_domain || 'Без имени' }}</a> </span>
                                <span @click="resetBot"><i class="fa-solid fa-xmark"></i></span>
                            </div>

                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>

    <div class="theme-switcher">
        <button id="switch-theme"
                data-bs-toggle="modal" data-bs-target="#theme-switch-modal"
                class="btn btn-primary">
            <i class="fa-solid fa-palette"></i>
        </button>
    </div>

    <div class="modal"
         id="theme-switch-modal"
         tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Выбор темы оформления</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="list-group">
                        <button type="button"
                                @click="switchTheme(index)"
                                v-for="(theme, index) in themes"
                                v-bind:class="{'active':currentTheme.indexOf(theme.href)!=-1}"
                                class="list-group-item list-group-item-action " aria-current="true">
                            {{theme.title || '-'}}
                        </button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import {mapGetters} from "vuex";

export default {
    props: ["active","needMenu"],
    data() {
        return {
            load: false,
            bot: null,
            currentTheme:'',
            company: null,
            themes:[
                {
                    title:'Тема 1',
                    href: '/theme1.bootstrap.min.css',
                },
                {
                    title:'Тема 2',
                    href: '/theme2.bootstrap.min.css',
                },
                {
                    title:'Тема 3',
                    href: '/theme3.bootstrap.min.css',
                },
                {
                    title:'Тема 4',
                    href: '/theme4.bootstrap.min.css',
                },
                {
                    title:'Тема 5',
                    href: '/theme5.bootstrap.min.css',
                },
                {
                    title:'Тема 6',
                    href: '/theme6.bootstrap.min.css',
                },
                {
                    title:'Тема 7',
                    href: '/theme7.bootstrap.min.css',
                },
                {
                    title:'Тема 8',
                    href: '/theme8.bootstrap.min.css',
                },
                {
                    title:'Тема 9',
                    href: '/theme9.bootstrap.min.css',
                },
                {
                    title:'Тема 10',
                    href: '/theme10.bootstrap.min.css',
                }
                ,
                {
                    title:'Тема 11',
                    href: '/theme11.bootstrap.min.css',
                },
                {
                    title:'Тема 12',
                    href: '/theme12.bootstrap.min.css',
                },
                {
                    title:'Тема 13',
                    href: '/theme13.bootstrap.min.css',
                },
                {
                    title:'Тема 14',
                    href: '/theme14.bootstrap.min.css',
                },
                {
                    title:'Тема 15',
                    href: '/theme15.bootstrap.min.css',
                },
                {
                    title:'Тема 16',
                    href: '/theme16.bootstrap.min.css',
                }
            ]
        }
    },
    computed: {
        ...mapGetters(['getErrors', 'getCurrentBot', 'getCurrentCompany']),
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
    mounted() {
        this.loadCurrentCompany()
        this.loadCurrentBot()


        let theme =  localStorage.getItem("cashman_global_admin_theme") || null

        if (theme)
        {
            this.$nextTick(()=>{
                this.currentTheme = theme
            })
        }



        window.addEventListener('store_current_bot-change-event', (event) => {
            this.bot = this.getCurrentBot
        });

        window.addEventListener('store_current_company-change-event', (event) => {
            this.company = this.getCurrentCompany
        });
    },

    methods: {
        switchTheme(index){
            let changeTheme = document.querySelector("#theme")
            changeTheme.href = this.themes[index].href //`./theme${index}.bootstrap.min.css`
            localStorage.setItem("cashman_global_admin_theme", changeTheme.href)


            this.$nextTick(()=>{
                this.currentTheme = changeTheme.href
            })


        },
        loadCurrentCompany(company = null) {
            this.$store.dispatch("updateCurrentCompany", {
                company: company
            }).then(() => {
                this.company = this.getCurrentCompany
            })
        },
        loadCurrentBot(bot = null) {
            this.$store.dispatch("updateCurrentBot", {
                bot: bot
            }).then(() => {
                this.bot = this.getCurrentBot
            })
        },
        resetCompany() {
            this.$store.dispatch("resetCurrentCompany").then(() => {
                this.company = null

                window.dispatchEvent(new CustomEvent('store_current_company-change-event'));
            })
        },
        resetBot() {
            this.$store.dispatch("resetCurrentBot").then(() => {
                this.bot = null

                window.dispatchEvent(new CustomEvent('store_current_bot-change-event'));
            })
        },
        stopAllDialogs() {
            this.$store.dispatch("stopDialogs").then((response) => {
                this.$notify({
                    title: "Конструктор ботов",
                    text: "Все диалоги остановлены",
                    type: 'success'
                });

            }).catch(err => {
            })
        },
        reloadWebhooks() {
            this.load = true
            this.$notify({
                title: "Конструктор ботов",
                text: "Процедура обновления зависимостей началась",
            });
            axios.get("/bot/register-webhooks").then(() => {
                this.load = false
                this.$notify({
                    title: "Конструктор ботов",
                    text: "Зависимости успешно обновлены!",
                    type: 'success'
                });
            }).catch(() => {
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

<style lang="scss">
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


.bot-label {
    border-radius: 5px;
    border: 1px white solid;
    height: 40px;

    p {
        color: white;
        padding: 10px;
        box-sizing: border-box;
    }
}

.border-bottom-active {
    border-bottom:1px white solid;
}

.theme-switcher {
    position: fixed;
    bottom: 50px;
    right: 50px;
    background: white;
    /* border-radius: 50%; */
    padding: 7px;
    z-index: 1000;
    border: 1px #f7f7f7 solid;
    box-shadow: 0px 0px 1px 0px #dadada;
    border-radius: 10px;
}
</style>
