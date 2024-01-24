<script setup>
import Pagination from "ClientTg@/Components/Pagination.vue";
import BotMediaObject from "@/ClientTg/Components/BotMediaObject.vue";
import ReturnToBot from "ClientTg@/Components/Shop/Helpers/ReturnToBot.vue";
</script>
<template>
    <div class="card card-style">
        <div class="content " v-if="history.length>0">
            <div v-for="message in history" class="mt-5 pt-3">
                <div class="speech-bubble speech-right color-black" v-if="message.from_bot_user_id == botUserId">
                    {{message.text || 'Текст сообщения'}}
                </div>
                <div class="clearfix"></div>
                <div class="speech-bubble speech-left bg-highlight" v-if="message.from_bot_user_id != botUserId">
                    {{message.text || 'Текст сообщения'}}
                </div>

                <div class="speech-bubble speach-image speech-left bg-highlight" v-if="message.media_content">
                    <BotMediaObject
                        :content="message.media_content"
                        :type="message.content_type"></BotMediaObject>
                </div>

            </div>

<!--            <div class="speech-bubble speech-right color-black" >
                These are chat bubbles, right? They look awesome don't they?
            </div>
            <div class="clearfix"></div>
            <div class="speech-bubble speech-left bg-highlight">
                Yeap!
            </div>
            <div class="clearfix"></div>
            <div class="speech-bubble speech-left bg-highlight">
                They also expand to a certain point, just like the ones that Mobile Chat apps have!
            </div>
            <div class="clearfix"></div>
            <div class="speech-bubble speech-right color-black">
                Awesome! Images too?
            </div>
            <div class="clearfix"></div>
            <p class="text-center mb-0 font-11">Yesterday, 1:45 AM</p>
            <div class="speech-bubble speach-image speech-left bg-highlight">
                <img class="img-fluid preload-img" src="images/pictures/8w.jpg" data-src="images/pictures/8w.jpg"
                     alt="img">
            </div>
            <div class="clearfix"></div>
            <div class="speech-bubble speech-left bg-highlight">
                Images can be used here as well, very easy! Just add an image tag!
            </div>
            <div class="clearfix"></div>
            <div class="speech-bubble speech-right color-black">
                WOW! Videos?!
            </div>
            <div class="clearfix"></div>
            <div class="speech-bubble speech-right color-black">
                Can I Embed videos or wait, actually, can I add maps?
            </div>
            <div class="clearfix"></div>
            <div class="speech-bubble speach-image speech-left">
                <iframe src="https://www.youtube.com/embed/c9MnSeYYtYY" frameborder="0" allowfullscreen=""></iframe>
            </div>
            <div class="clearfix"></div>
            <div class="speech-bubble speech-left bg-highlight">
                Yep! Just embed your stuff here. It's super simple. You just copy the embed code in this place.
            </div>
            <div class="clearfix"></div>
            <p class="text-center mb-0 font-11">25 Minutes Ago</p>
            <div class="speech-bubble speech-right color-black">
                Is this an actual chat system? Can i send messages already?
            </div>
            <div class="clearfix"></div>
            <div class="speech-bubble speech-last speech-left bg-highlight">
                It's just a chat template, but it's ready and able to be coded into a full chat system. Great huh?
            </div>
            <div class="clearfix"></div>
            <em class="speech-read mb-3">Delivered &amp; Read - 07:18 PM</em>-->
            <div class="divider my-5"></div>


            <Pagination
                class="mt-4"
                :simple="true"
                v-on:pagination_page="nextChatHistory"
                v-if="paginate_object"
                :pagination="paginate_object"/>

            <ReturnToBot/>

        </div>
        <div class="content" v-else>
            <p>Переписка с данным пользователем еще не сохранена</p>
        </div>
    </div>

</template>
<script>
import {mapGetters} from "vuex";

export default {
    data() {
        return {
            botUserId:null,
            loading: false,
            history: null,
            paginate_object: null
        }
    },
    computed: {
        ...mapGetters(['getChatHistory',
            'getChatHistoryPaginateObject']),
    },
    mounted() {
        this.botUserId = this.$route.params.botUserId
        this.loadChatHistory()
    },
    methods: {
        nextChatHistory(index) {
            this.loadChatHistory(index)
        },
        loadChatHistory(page = 0) {
            this.loading = true
            this.$store.dispatch("loadChatHistory", {
                dataObject: {
                    bot_user_id: this.botUserId || null
                },
                page: page
            }).then(resp => {
                this.history = this.getChatHistory || []
                this.paginate_object = this.getChatHistoryPaginateObject || null
                this.loading = false
            }).catch(() => {
                this.loading = false
            })
        }
    }
}
</script>
