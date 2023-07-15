<template>
    <div id="menu-main"
         class="menu menu-box-right menu-box-detached rounded-m "
         style="width: 260px;display:block;"
         data-menu-active="nav-features"
         data-menu-effect="menu-over">


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
            <h1 class="p-3 font-800 font-24 text-uppercase">{{currentBot.company.title || currentBot.bot_domain || 'CashMan:Shopify'}}</h1>
            <p class="font-11 mt-n2"><i class="fa-solid fa-location-dot mr-2 text-danger"></i>{{currentBot.company.address || 'Без описания'}}</p>

        </div>

        <div class="menu-items">
            <h5 class="text-uppercase opacity-20 font-12 pl-3">Главное меню</h5>

            <router-link
                id="nav-home"
                :active-class="'nav-item-active'"
                :tag="'a'" :to="'/home'">
                <i class="fa-solid fa-house-chimney" style="color:lightblue;"></i>
                <span>Главная</span>
                <i class="fa fa-circle"></i>
            </router-link>

            <router-link
                id="nav-basket"
                :active-class="'nav-item-active'"
                :tag="'a'" :to="'/basket'">
                <i class="fa-solid fa-basket-shopping"></i>
                <span>Корзина</span>
                <strong class="badge bg-highlight color-white" v-if="cartTotalCount>0">{{ cartTotalCount }}</strong>
                <i class="fa fa-circle"></i>
            </router-link>

            <router-link
                id="nav-products"
                :active-class="'nav-item-active'"
                :tag="'a'" :to="'/products'">
                <i class="fa-solid fa-pizza-slice" style="color:orangered;"></i>
                <span>Продукция</span>
                <i class="fa fa-circle"></i>
            </router-link>

            <router-link
                id="nav-favorites"
                :active-class="'nav-item-active'"
                :tag="'a'" :to="'/favorites'">
                <i class="fa-solid fa-star" style="color:orange;"></i>
                <span>Избранное</span>
                <strong class="badge bg-highlight color-white">3</strong>
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
                :tag="'a'" :to="'/our-team'">
                <i class="fa-solid fa-people-group" style="color:#6f42c1;"></i>
                <span>Наша команда</span>
                <i class="fa fa-circle"></i>
            </router-link>

            <router-link
                id="nav-contact-us"
                :active-class="'nav-item-active'"
                :tag="'a'" :to="'/contact-us'">
                <i class="fa-solid fa-star" style="color:orange;"></i>
                <span>О нас</span>
                <i class="fa fa-circle"></i>
            </router-link>

            <router-link
                id="nav-terms"
                :active-class="'nav-item-active'"
                :tag="'a'" :to="'/terms'">
                <i class="fa-regular fa-circle-question" style="color:green;"></i>
                <span>Правила сервиса</span>
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

export default {
    data() {
        return {
            isActive: false,
        }
    },
    computed: {
        ...mapGetters(['cartTotalCount']),
        logo(){
            return `/images-by-bot-id/${this.currentBot.id}/${this.currentBot.image}`
        },
        currentBot(){
            return window.currentBot
        }
    }
}
</script>
