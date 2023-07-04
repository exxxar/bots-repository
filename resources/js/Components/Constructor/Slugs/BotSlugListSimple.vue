<script setup>
import Slug from '@/Components/Constructor/Slugs/Slug.vue'
</script>
<template>
    <div class="row">
        <div class="col-12" >
                <div class="card-body">
                    <h6>Добавление нового скрипта в бота</h6>
                    <div>
                        <input type="text"
                               class="form-control mt-1 mb-1"
                               v-model="search"
                               placeholder="Поиск нужного скрипта по описанию">
                    </div>

                    <div
                        v-if="filteredSlugs.length>0"
                        class="row">
                        <div class="col-md-6">
                            <Slug
                                v-for="(item, index) in filteredSlugs"
                                :item="item"
                                :bot="bot"
                                v-on:callback="callbackSlugs"
                                v-on:select="selectSlug"/>
                        </div>
                    </div>


                </div>


        </div>

    </div>

</template>
<script>
export default {
    props: ["bot","global"],
    data() {
        return {
            load: false,
            search: null,
            slugs: [],
        }
    },
    computed: {
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

            this.$notify("Вы выбрали скрипт из списка!");
        },
        loadSlugs() {
            this.$store.dispatch("loadBotSlugs", {
                botId:this.bot.id,
                isGlobal: this.global || false
            }).then(resp => {
                this.slugs = resp
            })
        },

    }
}
</script>
