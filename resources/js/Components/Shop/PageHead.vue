<template>
    <div class="page-title page-title-large" v-if="self">
        <h2 :data-username="(self.fio_from_telegram || 'Пользователь')+'!'" class="greeting-text"></h2>
        <a href="#" data-menu="menu-main" class="bg-fade-gray1-dark shadow-xl preload-img"
           v-lazy="logo"></a>
    </div>
    <div class="card header-card shape-rounded" data-card-height="140">
        <div class="card-overlay bg-highlight opacity-95"></div>
        <div class="card-overlay dark-mode-tint"></div>
        <div class="card-bg preload-img" data-src="/shop/images/pictures/20s.jpg"></div>
    </div>

</template>
<script>
import baseJS from '@/modules/custom.js'
import {mapGetters} from "vuex";
export default {
    data() {
        return {
            self: null,
        }
    },
    computed: {
        ...mapGetters(['getSelf']),
        currentBot() {
            return window.currentBot
        },
        logo(){
            return `/images-by-bot-id/${this.currentBot.id}/${this.currentBot.image}`
        },
    },
    mounted() {
        this.watchStore()
        this.self = this.getSelf
        baseJS.handler()
    },
    methods: {
        watchStore(){
            this.$store.watch(
                () => this.$store.getters.getSelf,
                data => {
                    this.self = this.$store.getters.getSelf
                    baseJS.handler()
                }
            )
        },
    }
}
</script>

