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

            <span class="font-12"> #{{item.id}} {{ item.command || 'Нет команды' }}</span>
            <strong>{{ item.comment || 'Нет описания' }}</strong>
            <u class="color-green1-dark" v-if="item.is_global">Глобальный</u>
            <i class="fa-solid fa-globe color-green1-dark" v-if="item.is_global"></i>

            <u class="color-blue2-dark" v-if="!item.is_global">Локальный</u>
            <i class="fa-solid fa-thumbtack color-blue2-dark" v-if="!item.is_global"></i>

        </a>
    </div>



    <div class="alert alert-danger" role="alert" v-else>
        У вас еще нет добавленных скриптов! Воспользуйтесь разделом "Скрипты" для добавления.
    </div>

</template>
<script>
import {mapGetters} from "vuex";

export default {
    props:["global"],
    data() {
        return {
            load: false,
            search: null,
            slugs: [],
        }
    },
    computed: {
        ...mapGetters([ 'getSlugs']),
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
        selectSlug(item) {
            this.$emit("callback", {
                id: item.id || null,
                slug: item.slug,
                comment: item.comment,
                command: item.command,

            })

            this.$botNotification.notification("Скрипты","Вы выбрали скрипт из списка!");
        },
        loadSlugs() {
            this.$store.dispatch("loadSlugs", {
                isGlobal: this.global || false
            }).then(resp => {
                this.slugs = this.getSlugs


            })
        },

    }
}
</script>
