<script setup>
import Layout from "@/AdminPanel/Layouts/ManagerLayout.vue";
import BotSection from "@/AdminPanel/Components/Constructor/Bot/BotSection.vue";
import CompanyList from "@/AdminPanel/Components/Constructor/Company/CompanyList.vue";
import BotList from "@/AdminPanel/Components/Constructor/Bot/BotList.vue";
import BotForm from "@/AdminPanel/Components/Constructor/Bot/BotForm.vue";
import BotSearchModal from "@/AdminPanel/Components/Constructor/Bot/BotSearchModal.vue";

const props = defineProps({
    botUser: {
        type: String,
    },
});

window.profile = props.botUser
</script>

<template>
    <Layout>
        <template #default>
            <div class="container">

                <BotSection
                    v-on:callback="botSectionCallback"
                    v-if="bot&&!load"
                    :bot="bot"
                />

                <BotForm v-if="!load&&bot===null"
                         :bot="bot"
                         v-on:callback="loadCurrentBot"
                />

            </div>
        </template>
    </Layout>
</template>
<script>
import {mapGetters} from "vuex";
import 'vue3-carousel/dist/carousel.css'
import {Carousel, Slide, Pagination, Navigation} from 'vue3-carousel'

export default {
    components: {
        Carousel,
        Slide,
        Pagination,
        Navigation,
    },
    data() {
        return {
            templates: [],
            load: false,
            step: 0,
            bot: null,

        }
    },
    computed: {
        ...mapGetters(['getCurrentBot', 'getCurrentCompany',]),
        profile() {
            return window.profile
        },

        isManagerVerified() {

            if (!this.profile)
                return false;

            if (!this.profile.manager)
                return false;

            return true
        }
    },
    mounted() {

      //  this.loadTemplates();

        this.loadCurrentBot()

        this.setStep(localStorage.getItem("cashman_set_botpage_step_index") || 0)

        window.addEventListener('change-current_bot-from-navbar-event', (event) => {
            this.bot = this.getCurrentBot
            localStorage.setItem("cashman_set_botform_step_index", 4)
            localStorage.setItem("cashman_set_botpage_step_index", 2)
            this.botSectionCallback()
        });

        window.addEventListener('reset-current_bot-from-navbar-event', (event) => {
            this.bot = null
            this.botSectionCallback()
        });

    },
    methods: {
        openForEdit() {

        },
        duplicateAndEdit(index, botId) {
            /* if (!this.getCurrentCompany) {
                 this.$notify("У Вас не выбран клиент!");
                 return;
             }*/

            this.templates[index].in_use = true
            this.$store.dispatch("duplicateBot", {
                dataObject: {
                    bot_id: botId,
                    company_id: this.getCurrentCompany ? this.getCurrentCompany.id : null
                }
            }).then(resp => {
                this.bot = resp.data
                this.load = true
                this.templates[index].in_use = false

                this.loadCurrentBot(this.bot)
                localStorage.setItem("cashman_set_botform_step_index", 0)

                if (!this.inBotFav(this.bot.id))
                    this.$store.dispatch("addBotToFavorites", this.bot.id)

                this.setStep(2)

                this.$nextTick(() => {
                    this.load = false
                })
                this.$notify("Указанный бот успешно продублирован");


            })
        },
        loadTemplates() {
            this.$store.dispatch("loadTemplates").then(resp => {
                this.templates = resp.data
            })
        },
        setStep(index) {
            this.step = parseInt(index)
            localStorage.setItem("cashman_set_botpage_step_index", index)
        },
        loadCurrentBot(bot = null) {
            this.$store.dispatch("updateCurrentBot", {
                bot: bot
            }).then(() => {
                this.bot = this.getCurrentBot
            })
        },
        botSectionCallback() {
            this.load = true

            this.$nextTick(() => {
                this.load = false
            })
        },
        botListCallback(bot) {
            this.load = true

            this.setStep(2)
            this.loadCurrentBot(bot)

            this.$nextTick(() => {
                this.load = false
            })
        }

    }
}
</script>

