<template>

    <div class="container pt-5">
        <h6>Ваши доступные слоты</h6>

        <div class="row">
            <div class="col-md-6">
                <div class="progress" role="progressbar" aria-label="Success striped example" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                    <div class="progress-bar progress-bar-striped bg-success" v-bind:style="{'width': slotsPercent+'%'}"></div>
                </div>
            </div>
            <div class="col-md-6 d-flex justify-content-between">
                <p>У вас <strong>{{getSelf.manager.max_bot_slot_count}}</strong> свободных слотов из <strong>{{summarySlotCount}}</strong> доступных</p>

                <a href="#" @click="callback(12)">Пополнить слоты</a>
            </div>
        </div>




    </div>

    <div class="container pb-5" v-if="bots.length>0">
        <h6>Ваши созданные боты</h6>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <div class="col" v-for="bot in bots">

                <div class="card h-100">
                    <img
                        class="card-img-top"
                        v-lazy="'/images-by-bot-id/'+bot.id+'/'+bot.image">

                    <div class="card-body">
                        <h5 class="card-title text-center">{{ bot.title || bot.id }}</h5>
                        <p class="card-text text-center">
                            {{ bot.short_description || 'Без описания' }}
                        </p>
                    </div>
                    <div class="card-footer "
                         v-bind:class="{'bg-danger':(bot.bot_token||'').length<40,'bg-success':(bot.bot_token||'').length>=40}"
                    >
                        <button
                            type="button"
                            @click="gotoBot(bot)"
                            class="btn btn-link text-white w-100">Редактировать</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container" v-else>
        <div class="alert alert-info" role="alert">
           <p>У вас еще нет созданных ботов, попробуйте с чего-то простого;)</p>

        </div>

        <div class="d-flex justify-content-center">
            <button class="btn btn-primary" @click="callback(0)">Поехали создавать</button>
        </div>
    </div>


</template>
<script>
import {mapGetters} from "vuex";

export default {

    data() {
        return {
            bots: []
        }
    }, computed: {
        ...mapGetters(['getBots']),
        getSelf(){
            return window.profile || null
        },
        slotsPercent(){
          return  (this.bots.length / this.summarySlotCount) * 100
        },
        summarySlotCount(){
            return this.getSelf.manager.max_bot_slot_count + this.bots.length
        }
    },
    mounted() {
        this.loadBots()
    },
    methods: {
        loadBots() {
            this.$store.dispatch("loadBots").then(() => {
                this.bots = this.getBots || []
            })
        },
        loadCurrentBot(bot = null) {
            this.$store.dispatch("updateCurrentBot", {
                bot: bot
            }).then(() => {
                this.bot = this.getCurrentBot
            })
        },
        gotoBot(bot){
            this.loadCurrentBot(bot)
            localStorage.setItem("cashman_set_botform_step_index", 0)
            localStorage.setItem("cashman_set_botpage_step_index", 2)
            window.location.href = '/bot-page'

        },
        callback(index){
            this.$emit("callback", index)
        }
    }
}
</script>
<style lang="scss">

.slot-list {
    display: flex;
    justify-content: start;
    align-items: start;
    flex-wrap: wrap;


    .slot-wrapper {
        padding: 5px;

        .slot {
            width: 50px;
            height: 50px;
            background-color: white;
            border-radius: 5px;


            position: relative;

            &.opened {
                box-shadow: 2px 2px 3px 0px #a7a4a4;
                border: 1px #185018 solid;

            }

            &.closed {
                box-shadow: 2px 2px 3px 0px #a7a4a4 inset;
                display: flex;
                justify-content: center;
                align-items: center;
                background: url('../images/cashman.jpg');
                background-size: cover;
                background-blend-mode: color;
                border: 1px #d8d8d8 solid;
            }

        }
    }
}
</style>
