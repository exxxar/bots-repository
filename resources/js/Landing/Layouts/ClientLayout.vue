<script setup>

import {Head} from '@inertiajs/vue3'

</script>
<template>

    <Head>
        <title>CashMan - система твоего бизнеса внутри</title>
        <meta name="description" content="CashMan - система твоего бизнеса внутри"/>
    </Head>

    <notifications position="top right"/>
    <!-- Navigation-->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top shadow-sm" id="mainNav">
        <div class="container px-5 ">
            <a class="bg-light px-3 text-gradient fs-3 fw-lighter" href="#page-top">
                <!--                <img
                                    style="width:140px;"
                                    src="/landing/assets/img/logo-2.png" alt="">-->

                Next IT
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive"
                    aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                Menu
                <i class="bi-list"></i>
            </button>


            <div class="collapse navbar-collapse " id="navbarResponsive">


                <ul class="navbar-nav ms-auto me-4 my-3 my-lg-0">




                    <li class="nav-item"><a class="nav-link me-lg-3" href="#constructor">Конструктор</a></li>
                    <li class="nav-item"><a class="nav-link me-lg-3" href="#prices">Тарифы</a></li>
                    <li class="nav-item"><a class="nav-link me-lg-3" href="#employes">Сотрудничество</a></li>

                    <li class="nav-item" style="border-left:2px #efefef solid">
                        <a class="nav-link me-lg-3 text-gradient ml-2" href="#!"><i class="fa-brands fa-vk"></i></a>
                    </li>

                    <li class="nav-item" style="border-right:2px #efefef solid">
                        <a class="nav-link me-lg-3 text-gradient" href="#!"><i class="fa-brands fa-instagram"></i></a>

                    </li>
                </ul>
                <button class="btn btn-primary rounded-pill px-3 mb-2 mb-lg-0" data-bs-toggle="modal"
                        data-bs-target="#feedbackModal">
                        <span class="d-flex align-items-center">
                            <i class="bi-chat-text-fill me-2"></i>
                            <span class="small">Перезвонить</span>
                        </span>
                </button>
            </div>
        </div>
    </nav>

    <slot/>

</template>
<script>


export default {
    props: ["active"],
    data() {
        return {
            load: false,
            bot: null,
            company: null
        }
    },
    computed: {

    },


    mounted() {

    },

    methods: {

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
</style>
