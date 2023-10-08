<template>
    <div v-for="(partner, index) in friends">
        <div class="d-flex">
            <div class="w-35 border-right pr-3 border-highlight partner-avatar">
                <img v-if="partner.manager" v-lazy="partner.manager.image || null"
                     data-src="images/avatars/1s.png" width="80"
                     class="bg-highlight rounded-circle preload-img">
                <img v-else v-lazy="'/images/manager.png'" data-src="images/avatars/1s.png" width="80"
                     class="bg-highlight rounded-circle preload-img">
                <h6 class="font-14 font-600 mt-2 text-center">
                    {{ partner.fio_from_telegram || 'Не задано' }}</h6>
                <p class="color-blue2-dark mt-n3 font-9 font-400 text-center mb-0 pt-1">Партнер первой
                    линии</p>
            </div>
            <div class="w-65 pl-3 pt-2">
                <h5>Команда партнера</h5>
                <p class="color-highlight mt-n3 font-10 pt-1 mb-3">Всего партнеров:
                    {{ partner.child.length }}</p>


                <a href="javascript:void(0)"
                   v-if="partner.child.length>0"
                   @click="showPartner"
                   class="d-inline-block position-relative"
                   v-for="subPartner in partner.child.slice(0, 4)">
                    <img v-if="subPartner.manager" v-lazy="subPartner.manager.image || null"
                         data-src="images/avatars/1s.png" width="40"
                         class="rounded-circle preload-img">
                    <img v-else v-lazy="'/images/manager.png'" data-src="images/avatars/1s.png" width="40"
                         class="rounded-circle preload-img ">
                    <span class="sub-badge-count rounded-circle bg-highlight color-white"
                          v-if="subPartner.child.length>0">{{ subPartner.child.length }}</span>
                </a>

                <a href="javascript:void(0)" v-if="partner.child.length>4">
                                <span
                                    class="rounded-circle preload-img bg-highlight color-white sub-partner-badge">+{{
                                        partner.child.length - 4
                                    }}</span>
                </a>

                <a href="javascript:void(0)"
                   @click="showPartner"
                   v-if="partner.child.length===0">
                    У вашего друга нет собственных партнеров
                </a>

            </div>
        </div>

        <div class="divider mt-4"></div>
    </div>
</template>
<script>
import {mapGetters} from "vuex";

export default {
    data() {
        return {

            load: false,
            friends: [],

        }
    },
    watch: {
        'getSelf': function () {
            this.loadFriendsWeb()
        }

    },
    mounted() {

    },
    computed: {
        ...mapGetters(['getSelf']),
    },
    methods: {
        showPartner() {
            this.$botNotification.notification("Партнеры", "Просмотр информации о партнере еще недоступен")
        },
        loadFriendsWeb() {
            this.loading = true;
            this.$store.dispatch("loadFriendsWeb").then((resp) => {
                this.loading = false

                this.friends = resp
                //  console.log(resp)

            }).catch(() => {
                this.loading = false
            })
        },



    }
}
</script>
<style lang="scss" scoped>
.img-avatar {
    width: 200px;
    height: 200px;
    padding: 10px;

    img {
        object-fit: cover;
        width: 100%;
    }

}

.theme-dark {
    input {
        border-color: white;
    }
}

.input-style-2 a {
    position: absolute;
    right: 12px;
    top: 12px;
    font-size: 16px;
}

.partner-avatar {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    flex-direction: column;
    align-items: center;
    min-width: 150px;
}

.sub-partner-badge {
    width: 35px;
    height: 35px;
    display: inline-flex;
    justify-content: center;
    align-items: center;
}

.sub-badge-count {
    position: absolute;
    bottom: 0px;
    right: 0;
    width: 16px;
    height: 16px;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 10px;
}
</style>
