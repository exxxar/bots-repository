<template>
    <div id="menu-admin-main"
         class="menu menu-box-right menu-box-detached rounded-m "
         style="width: 260px;display:block;height: 100vh;overflow-y: scroll;">


        <div class="menu-header">


            <a href="#" data-toggle-theme="" class="border-right-0"><i
                class="fa font-12 color-yellow1-dark fa-lightbulb"></i></a>
            <a href="#" data-menu="menu-highlights" class="border-right-0"><i
                class="fa font-12 color-green1-dark fa-brush"></i></a>
            <a href="#" data-menu="menu-share" class="border-right-0">
                <i class="fa font-12 color-red2-dark fa-share-alt"></i></a>

            <router-link
                class="close-menu"
                :tag="'a'" :to="'/settings'">
                <i class="fa  color-blue2-dark fa-cog  font-12" ></i>
            </router-link>

            <a class="border-right-0 close-menu" ><i class="fa font-12 color-red2-dark fa-times"></i></a>
        </div>

        <div class="menu-logo text-center">
            <a href="#"><img class="rounded-circle bg-highlight" width="80" v-lazy="logo"></a>
            <h1 class="p-3 font-800 font-24 text-uppercase">Админ.панель</h1>
            <p class="font-11 mt-n2">{{currentBot.company.title || currentBot.bot_domain || 'CashMan:Shopify'}}</p>

        </div>

        <div class="menu-items">
            <h5 class="text-uppercase opacity-20 font-12 pl-3">Главное меню</h5>

            <router-link
                id="nav-home"
                :active-class="'nav-item-active'"
                :tag="'a'" :to="'/admin-main'">
                <i class="fa-solid fa-house-chimney" style="color:lightblue;"></i>
                <span>Главная</span>
                <i class="fa fa-circle"></i>
            </router-link>


            <router-link
                id="nav-home"
                :active-class="'nav-item-active'"
                :tag="'a'" :to="'/admin-work-status'">
                <i class="fa-solid fa-briefcase" style="color:saddlebrown;"></i>
                <span>Рабочий день</span>
                <i class="fa fa-circle"></i>
            </router-link>

            <router-link
                id="nav-basket"
                :active-class="'nav-item-active'"
                :tag="'a'" :to="'/admin-shop-manager'">
                <i class="fa-solid fa-basket-shopping"></i>
                <span>Менеджер магазина</span>
                <strong class="badge bg-highlight color-white" v-if="cartTotalCount>0">{{ cartTotalCount }}</strong>
                <i class="fa fa-circle"></i>
            </router-link>

            <router-link
                id="nav-products"
                :active-class="'nav-item-active'"
                :tag="'a'" :to="'/admin-statistic'">
                <i class="fa-solid fa-ranking-star"  style="color:orangered;"></i>
                <span>Статистика</span>
                <i class="fa fa-circle"></i>
            </router-link>

            <router-link
                id="nav-favorites"
                :active-class="'nav-item-active'"
                :tag="'a'" :to="'/admin-promotion'">
                <i class="fa-brands fa-adversal" style="color:orange;"></i>
                <span>Реклама</span>
                <strong class="badge bg-highlight color-white" v-if="favoritesCount>0">{{favoritesCount}}</strong>
                <i class="fa fa-circle"></i>
            </router-link>

            <router-link
                id="nav-out-team"
                :active-class="'nav-item-active'"
                :tag="'a'" :to="'/history'">
                <i class="fa-regular fa-clock" style="color:#842029;"></i>
                <span>История заказов</span>
                <strong class="badge bg-highlight color-white">3</strong>
                <i class="fa fa-circle"></i>
            </router-link>

            <router-link
                id="nav-out-team"
                :active-class="'nav-item-active'"
                :tag="'a'" :to="'/admin-actions'">
                <i class="fa-solid fa-bolt" style="color:#6f42c1;"></i>
                <span>События</span>
                <i class="fa fa-circle"></i>
            </router-link>



            <a href="#" class="close-menu">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"
                     class="feather feather-x" data-feather-line="3" data-feather-size="16"
                     data-feather-color="red2-dark" data-feather-bg="red2-fade-dark" style="width: 16px; height: 16px;">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
                <span>Закрыть</span>
                <i class="fa fa-circle"></i>
            </a>
        </div>

        <div class="text-center pt-2">
            <a href="#" class="icon icon-xs mr-1 rounded-s bg-facebook"><i class="fab fa-facebook"></i></a>
            <a href="#" class="icon icon-xs mr-1 rounded-s bg-twitter"><i class="fab fa-twitter"></i></a>
            <a href="#" class="icon icon-xs mr-1 rounded-s bg-instagram"><i class="fab fa-instagram"></i></a>
            <a href="#" class="icon icon-xs mr-1 rounded-s bg-linkedin"><i class="fab fa-linkedin-in"></i></a>
            <a href="#" class="icon icon-xs rounded-s bg-whatsapp"><i class="fab fa-whatsapp"></i></a>
            <p class="mb-0 pt-3 font-10 opacity-30">Copyright <span class="copyright-year"></span> Enabled. All rights
                reserved</p>
        </div>
    </div>
</template>
<script>
import {mapGetters} from "vuex";
import baseJS from '@/modules/custom.js'

export default {
    data() {
        return {
            isActive: false,
        }
    },
    computed: {
        ...mapGetters([ 'cartTotalCount' ,'favoritesCount']),

        logo(){
            return `/images-by-bot-id/${this.currentBot.id}/${this.currentBot.image}`
        },
        currentBot(){
            return window.currentBot
        }
    },
    mounted() {

    }
}
</script>
