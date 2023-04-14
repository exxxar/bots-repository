<script setup>

import Pagination from '@/Components/Pagination.vue';

defineProps({
    bot: {
        type: Object,
    },

});
</script>
<template>
   <div class="container pt-3 pb-3">
       <div class="row">
           <div class="col-12 mb-3">
               <div class="card border-success  ">
                   <div class="card-body">
                       <p>
                           Вы можете выбрать администратора из списка активных администраторов
                           и прислать ему запрос на начисление <strong>CashBack</strong>.
                           К запросу вы можете прикрепить текстовое сообщение, которое также получит выбранный администратор.
                       </p>
                   </div>
               </div>
           </div>
           <div class="col-12">
               <div v-if="admins.length>0">
                   <textarea type="text"
                          v-model="message"
                          placeholder="Сообщение администратору"
                          class="form-control w-100  mb-3"/>

               </div>
               <div
                   v-if="admins.length>0"
                   class="list-group">
                   <a href="#"
                      @click="sendRequest(item, index)"
                      :key="'admin'+index"
                      v-for="(item, index) in admins"
                      class="list-group-item list-group-item-action">
                       <div class="d-flex w-100 justify-content-between">
                           <h5 class="mb-1">{{item.user.fio_from_telegram||item.user.name || 'Не указано'}}</h5>
                           <small class="text-muted">{{ $filters.timeAgo(item.updated_at) }}</small>
                       </div>
                       <p class="mb-1">+{{item.user.phone||'Номер телефона не указан'}}</p>
                       <small class="text-muted">
                           <span class="badge text-bg-primary">Администратор</span>
                       </small>

                   </a>

               </div>
               <div
                   v-else
                   class="alert alert-warning" role="alert">
                  К сожалению активных администраторов на данный момент нет, как только они будут в сети вы сможете запросить у них начисление бонусных баллов!
               </div>
           </div>
           <div class="col-12" v-if="admins.length>0">
               <Pagination
                   class="mt-2"
                   v-on:pagination_page="nextAdminPage"
                   v-if="admins_paginate_object"
                   :pagination="admins_paginate_object"/>
           </div>
       </div>
   </div>
</template>
<script>
import {mapGetters} from "vuex";
export default {
    data() {
        return {

            loading: false,
            admins:[],
            admins_paginate_object: null,
            message:null,

        }
    },
    computed: {
        ...mapGetters(['getAdmins',
            'getAdminsPaginateObject']),
        tg() {
            return window.Telegram.WebApp;
        },
        tgUser(){
            const urlParams = new URLSearchParams(this.tg.initData);
            return JSON.parse(urlParams.get('user'));
        }
    },
    mounted() {
        this.loadAdmins()
    },
    methods: {
        nextAdminPage(index){
            this.loadAdmins(index)
        },
        sendRequest(botUser, index){
            this.loading = true
            this.$store.dispatch("requestAdmin", {
                dataObject: {
                    bot_id: botUser.bot_id,
                    admin_telegram_chat_id: botUser.telegram_chat_id,
                    user_telegram_chat_id: this.tgUser.id,
                    message:this.message
                },
            }).then(resp=> {
                this.loading = false
                this.tg.close()
            }).catch(() => {
                this.loading = false
            })
         // this.tg.close()
        },
        loadAdmins(page = 0){
            this.loading = true
            this.$store.dispatch("loadAdmins", {
                dataObject: {
                    bot_domain: this.bot.bot_domain
                },
                page:page
            }).then(resp=> {
                this.loading = false
                this.admins = this.getAdmins
                this.admins_paginate_object = this.getAdminsPaginateObject
            }).catch(() => {
                this.loading = false
            })
        },
    }
}
</script>
