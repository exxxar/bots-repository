<script setup>
import OrderList from "@/ClientTg/Components/V2/Admin/Orders/OrderList.vue";
import UserInfo from '@/ClientTg/Components/V2/Admin/Clients/UserInfo.vue';
import Pagination from '@/ClientTg/Components/V1/Pagination.vue'

import MessageToUser from "@/ClientTg/Components/V2/Admin/Clients/Modules/MessageToUser.vue";
import RemoveCashBack from "@/ClientTg/Components/V2/Admin/Clients/Modules/RemoveCashBack.vue";
import AddCashBack from "@/ClientTg/Components/V2/Admin/Clients/Modules/AddCashBack.vue"
import ChangeAdminStatus from "@/ClientTg/Components/V2/Admin/Clients/Modules/ChangeAdminStatus.vue";
import RefreshUserMenu from "@/ClientTg/Components/V2/Admin/Clients/Modules/RefreshUserMenu.vue";
import RequestUserProfile from "@/ClientTg/Components/V2/Admin/Clients/Modules/RequestUserProfile.vue";
import RequestInvoice from "@/ClientTg/Components/V2/Admin/Clients/Modules/RequestInvoice.vue";
import CashBackList from "@/ClientTg/Components/V2/CashBack/CashBackList.vue";
import RequestReview from "@/ClientTg/Components/V2/Admin/Clients/Modules/RequestReview.vue";
import LastOrderHandler from "@/ClientTg/Components/V2/Admin/Clients/Modules/LastOrderHandler.vue";
</script>
<template v-if="botUser">
    <div class="btn-group w-100 my-3 px-3"

         style="overflow-x:auto;"
         role="group" aria-label="Basic example">
        <button type="button"
                v-bind:class="{'btn-primary':tab===0,'btn-outline-primary':tab!==0}"
                @click="tab=0"
                class="btn">Информация
        </button>
        <button type="button"
                @click="tab=2"
                v-bind:class="{'btn-primary':tab===2,'btn-outline-primary':tab!==2}"
                class="btn">Управление
        </button>
        <button type="button"
                @click="tab=1"
                v-bind:class="{'btn-primary':tab===1,'btn-outline-primary':tab!==1}"
                class="btn">CashBack
        </button>
        <button type="button"
                @click="tab=3"
                v-bind:class="{'btn-primary':tab===3,'btn-outline-primary':tab!==3}"
                class="btn">Заказы
        </button>
    </div>

    <div v-if="tab===0">
        <UserInfo
            v-on:update="updateUserInfo"
            v-if="botUser&&!reloadUsers"
            :bot-user="botUser"></UserInfo>
    </div>

    <div v-if="tab===1">
        <CashBackList
            v-if="botUser"
            :bot-user="botUser"></CashBackList>
    </div>

    <div v-if="tab===3">
        <OrderList
            v-if="botUser"
            :bot-user="botUser"></OrderList>
    </div>

    <div v-if="botUser&&tab===2">
        <template v-if="work_with_orders">

            <LastOrderHandler class="mb-2"
                              :order-id="order_id"
                              :bot-user="botUser"></LastOrderHandler>
        </template>

        <div class="divider">Сервисы</div>
        <MessageToUser
            class="mb-2"
            :bot-user="botUser"></MessageToUser>

        <RequestReview
            class="mb-2"
            :bot-user="botUser"></RequestReview>

        <RemoveCashBack class="mb-2"
                        :bot-user="botUser"></RemoveCashBack>

        <AddCashBack class="mb-2"
                     :bot-user="botUser"></AddCashBack>

        <ChangeAdminStatus class="mb-2"
                           :bot-user="botUser"></ChangeAdminStatus>

        <RefreshUserMenu class="mb-2"
                         :bot-user="botUser"></RefreshUserMenu>

        <RequestUserProfile class="mb-2"
                            :bot-user="botUser"></RequestUserProfile>

        <RequestInvoice class="mb-2"
                        :bot-user="botUser"></RequestInvoice>

    </div>
</template>
<script>

import {mapGetters} from "vuex";

export default {
    props: ["modelValue"],
    data() {
        return {
            work_with_orders:false,
            loading: false,
            reloadUsers: false,
            order_id:null,
            tab: 0,
            botUser:null,
            request_telegram_chat_id: null,

        }
    },
    computed: {
        ...mapGetters(['getSelf']),
        currentBot() {
            return window.currentBot
        }
    },
    mounted() {

        const urlParams = new URLSearchParams(window.location.search);
        this.order_id = JSON.parse(urlParams.get('order_id'));

        if (this.order_id) {
            this.work_with_orders = true
        }


        if (this.modelValue)
            this.$nextTick(()=>{
                this.botUser = this.modelValue
            })
    },
    methods: {
        updateUserInfo() {
            this.reloadUsers = true
            this.botUser = null
            this.request_telegram_chat_id = null
            this.$nextTick(() => {
                this.reloadUsers = false
            })
        },

    }
}
</script>

