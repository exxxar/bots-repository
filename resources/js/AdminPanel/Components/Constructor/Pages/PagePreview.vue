<script setup>
import KeyboardCard from "@/AdminPanel/Components/Constructor/Cards/KeyboardCard.vue";
import BotMediaObject from "@/AdminPanel/Components/Constructor/BotMediaObject.vue";
</script>

<template>
    <div class="page-container">
        <div class="page-content">
            <div class="row">
                <div class="col"
                     v-if="page.images"
                     v-for="(image, index) in page.images">

                        <img
                            v-lazy="'/images-by-bot-id/'+page.bot_id+'/'+image">
                </div>

            </div>
            <div class="row">
                <div class="col"
                     v-if="page.videos"
                     v-for="(video, index) in page.videos">

                    <BotMediaObject :content="video" type="video"></BotMediaObject>
                </div>
            </div>
            <div class="row">
                <div class="col"
                     v-if="page.audios"
                     v-for="(audio, index) in page.audios">

                    <BotMediaObject :content="audio" type="audio"></BotMediaObject>
                </div>

        </div>

        <div class="row">
            <div class="col-12">
                <p v-html="page.content"></p>
            </div>
        </div>
    </div>

    <div class="row" v-if="page.inlineKeyboard">
        <div class="col-12">
            <KeyboardCard
                :simple="true"
                :select-mode="true"
                v-if="!page.inlineKeyboard.deleted_at"
                :keyboard="page.inlineKeyboard"/>

        </div>
    </div>

    <div class="row mt-2" v-if="page.replyKeyboard">
        <p class="mb-0"><strong>Нижнее меню</strong></p>
        <div class="col-12">
            <KeyboardCard
                :simple="true"
                :select-mode="true"
                v-if="!page.replyKeyboard.deleted_at"
                :keyboard="page.replyKeyboard"/>

        </div>
    </div>

    </div>


    <!--    {{page}}-->


</template>
<script>
export default {
    props: ["page"]
}
</script>
