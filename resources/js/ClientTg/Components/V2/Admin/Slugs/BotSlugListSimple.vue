<script setup>
import Pagination from '@/ClientTg/Components/V1/Pagination.vue';

</script>
<template>
    <h6>Добавление нового скрипта в бота</h6>
    <div class="form-floating">
        <input type="text"
               class="form-control mt-1 mb-1"
               v-model="search"
               placeholder="Поиск нужного скрипта по описанию">
        <label for="">Поиск скрипта</label>
    </div>

    <div
        v-if="filteredSlugs.length>0"
        @click="selectSlug(item)"
        v-for="(item, index) in filteredSlugs"
        class="input-group mb-0 align-items-start">

        <div class="input-group-text d-flex flex-column"
              style="font-size:12px;"
             v-bind:class="{'bg-primary text-white':selected==item.id}"
              id="basic-addon1">
            <span>
                #{{item.id}}
            </span>
        </div>
        <p class="form-control d-flex flex-column mb-2">
               <span class="fw-bold">{{ item.command || 'Нет команды' }}</span>
            <span class="fst-italic" style="font-size:12px;">{{ item.comment || 'Нет описания' }}</span>
        </p>

    </div>
    <div class="alert alert-light"
         v-else
         role="alert" >
        У Вас еще нет добавленных скриптов! Воспользуйтесь разделом "Скрипты" для добавления.
    </div>

    <div class="mb-3" >
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
    props:["global","selected"],
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

            this.$notify({
                title:'Скрипты',
                text: "Вы выбрали скрипт из списка",

            })

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
