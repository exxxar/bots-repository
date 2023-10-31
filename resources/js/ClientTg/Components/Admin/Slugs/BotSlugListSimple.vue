<script setup>
import Pagination from '@/ClientTg/Components/Pagination.vue';

</script>
<template>
    <h6>Добавление нового скрипта в бота</h6>
    <div>
        <input type="text"
               class="form-control mt-1 mb-1"
               v-model="search"
               placeholder="Поиск нужного скрипта по описанию">
    </div>

    <div class="list-group list-boxes"
         v-if="filteredSlugs.length>0">
        <a href="javascript:void(0)"
           @click="selectSlug(item)"
           v-for="(item, index) in filteredSlugs"
           v-bind:class="{'border-green1-dark':item.is_global,'border-blue2-dark':!item.is_global}"
           class="border rounded-s shadow-xs">

            <span class="font-12 slug-span"> #{{item.id}} {{ item.command || 'Нет команды' }}</span>
            <strong class="slug-strong">{{ item.comment || 'Нет описания' }}</strong>
            <u class="color-green1-dark" v-if="item.is_global">Глобальный</u>
            <i class="fa-solid fa-globe color-green1-dark" v-if="item.is_global"></i>

            <u class="color-blue2-dark" v-if="!item.is_global">Локальный</u>
            <i class="fa-solid fa-thumbtack color-blue2-dark" v-if="!item.is_global"></i>

        </a>
    </div>



    <div class="alert alert-danger" role="alert" v-else>
        У Вас еще нет добавленных скриптов! Воспользуйтесь разделом "Скрипты" для добавления.
    </div>

    <div class="mb-3">
        <Pagination
            :simple="true"
            v-on:pagination_page="nextSlugs"
            v-if="paginate"
            :pagination="paginate"/>
    </div>


</template>
<script>
import {mapGetters} from "vuex";

export default {
    props:["global"],
    data() {
        return {
            load: false,
            paginate:null,
            search: null,
            slugs: [],
        }
    },
    computed: {
        ...mapGetters([ 'getSlugs','getSlugsPaginateObject']),
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

            this.$botNotification.notification("Скрипты","Вы выбрали скрипт из списка!");
        },
        loadSlugs(index) {
            this.$store.dispatch("loadSlugs", {
                dataObject:{
                    needGlobal: this.global || false
                },
                page:index
            }).then(resp => {
                this.slugs = this.getSlugs
                this.paginate = this.getSlugsPaginateObject


            })
        },

    }
}
</script>
<style>
.slug-span {
    margin-top: -15px !important;
}

.slug-strong {
    text-overflow: ellipsis;
    overflow: hidden;
    height: 36px;
    line-height: 100%;
    /* top: 12px; */
    width: 78%;
    margin-top: 22px  !important;
}
</style>
