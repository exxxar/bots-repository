<script setup>
import Slug from '@/AdminPanel/Components/Constructor/Slugs/Slug.vue'
import Pagination from '@/AdminPanel/Components/Pagination.vue';
</script>
<template>
    <div class="row">
        <div class="col-12" >

                    <h6>Добавление нового скрипта в бота</h6>
                    <div>
                        <input type="text"
                               class="form-control mt-1 mb-1"
                               v-model="search"
                               @keyup="loadSlugs"
                               placeholder="Поиск нужного скрипта по описанию">
                    </div>

                    <div
                        v-if="slugs.length>0"
                        class="row">
                        <div class="col-md-6 col-lg-4 col-sm-6 col-12 mb-2" v-for="(item, index) in slugs">
                            <Slug
                                :is-active="(selected||[]).indexOf(item.id)!=-1"
                                :item="item"
                                :bot="bot"
                                v-on:callback="callbackSlugs"
                                v-on:select="selectSlug"/>
                        </div>

                        <Pagination

                            v-on:pagination_page="nextSlugs"
                            v-if="paginate"
                            :pagination="paginate"/>
                    </div>
                    <div class="row" v-else>
                        <div class="col-12">
                            <div class="alert alert-danger" role="alert">
                               У Вас еще нет добавленных скриптов! Воспользуйтесь разделом "Скрипты" для добавления.
                            </div>
                        </div>
                    </div>





        </div>

    </div>

</template>
<script>
import {mapGetters} from "vuex";

export default {
    props: ["bot","global","selected"],
    data() {
        return {
            load: false,
            search: null,
            slugs: [],
            paginate: [],
        }
    },
    computed: {
        ...mapGetters(['getSlugs','getSlugsPaginateObject']),
        filteredSlugs() {
            if (!this.slugs)
                return [];

            if (this.slugs.length === 0)
                return [];

            if (this.search == null)
                return this.slugs

            return this.slugs.filter(item => {
                let slug = item.slug || ''
                let command = item.command || ''
                let comment = item.comment || ''

                return command.toLowerCase().indexOf(this.search.toLowerCase()) !== -1 ||
                    comment.toLowerCase().indexOf(this.search.toLowerCase()) !== -1 ||
                    slug.toLowerCase().indexOf(this.search.toLowerCase()) !== -1
            })

        }

    },
    mounted() {
      //  this.search = (this.selected||[]).length > 0 ? this.selected[0] : null
        this.loadSlugs()
    },
    methods: {
        nextSlugs(index) {
            this.loadSlugs(index)
        },
        selectSlug(item) {
            this.$emit("callback", {
                id: item.id || null,
                slug: item.slug,
                comment: item.comment,
                command: item.command,

            })

            this.$notify("Вы выбрали скрипт из списка!");
        },
        loadSlugs(page = 0) {
            this.$store.dispatch("loadSlugs", {
                dataObject:{
                    botId: this.bot.id,
                    search: this.search  || null,
                    needGlobal: this.global
                },
                page:page
            }).then(resp => {
                this.slugs = this.getSlugs
                this.paginate = this.getSlugsPaginateObject
            })
        },
        callbackSlugs(){

        }

    }
}
</script>
