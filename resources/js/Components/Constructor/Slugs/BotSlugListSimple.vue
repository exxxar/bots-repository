<script setup>
import BotDialogGroupListSimple from "@/Components/Constructor/Dialogs/BotDialogGroupListSimple.vue";
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

                    <ul class="list-group"
                        v-if="filteredSlugs.length>0"
                        style="overflow-y: auto; height: 300px;">
                        <li class="list-group-item cursor-pointer"
                            @click="selectSlug(item)"
                            v-for="(item, index) in filteredSlugs">
                            <p> #{{item.id}} {{ item.command }} (<strong>{{ item.slug }}</strong>)  <span v-if="item.page" class="badge bg-success">Привязано к странице</span></p>
                            <p> <strong>пояснение:</strong>{{ item.comment || 'Пояснение не указано' }}</p>
                        </li>

                    </ul>


                </div>


        </div>

    </div>

</template>
<script>
export default {
    props: ["botId"],
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
                bot_dialog_command_id: item.bot_dialog_command_id,
            })

            this.$notify("Вы выбрали скрипт из списка!");
        },
        loadSlugs() {
            this.$store.dispatch("loadBotSlugs", {
                botId:this.botId
            }).then(resp => {
                this.slugs = resp
            })
        },

    }
}
</script>
