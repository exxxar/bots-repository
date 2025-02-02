<script setup>

import PageList from "@/ClientTg/Components/V2/Admin/Pages/PageList.vue";
import Page from "@/ClientTg/Components/V2/Admin/Pages/Page.vue"


</script>
<template>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <Page
                    v-if="page"
                    :page="page"
                />
                <div
                    v-else
                    class="alert alert-light d-flex flex-column align-items-center justify-content-center">
                    Подготавливаем данные...
                    <div class="spinner-border text-primary my-3" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>
<script>
export default {
    data() {
        return {
            page: null,
            bot: null,

        }
    },

    mounted() {
        this.loadBotAdminConfig();
        this.loadPageById()
    },
    methods: {
        loadBotAdminConfig() {
            this.$store.dispatch("loadBotAdminConfig").then((resp) => {
                this.bot = resp.data

            })
        },
        loadPageById() {
            let pageId = this.$route.params.pageId

            this.$store.dispatch("loadPageById", {
                dataObject: {
                    page_id: pageId
                }
            }).then(resp => {
              this.page = resp.data
            })
        }
    }
}
</script>
