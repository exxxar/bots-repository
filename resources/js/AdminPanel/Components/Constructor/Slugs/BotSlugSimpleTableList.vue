<script setup>;
import Pagination from '@/AdminPanel/Components/Pagination.vue';
</script>
<template>
    <div
        v-if="bot"
        class="row">

        <div class="col-12">
            <div class="form-floating mb-3">
                <input type="search"
                       v-model="ownSearch"
                       class="form-control" id="floatingInput" placeholder="name@example.com">
                <label for="floatingInput">Быстрый поиск команды</label>
            </div>
        </div>

        <div class="col-12 mb-2 ">
            <div class="form-check">
                <input class="form-check-input"
                       v-model="need_global"
                       type="checkbox"
                       id="need_global">
                <label class="form-check-label" for="need_global">
                    <span v-if="need_global">Глобальные</span>
                    <span v-else>Локальные</span>
                </label>
            </div>

            <div class="form-check">
                <input class="form-check-input"
                       v-model="need_show_deleted"
                       type="checkbox"
                       id="need_show_deleted">
                <label class="form-check-label" for="need_show_deleted">
                    Удаленные
                </label>
            </div>
        </div>

        <div class="mb-3 col-12 col-sm-12"
             v-if="slugs">
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Команда</th>
                    <th scope="col">Пояснение</th>
                </tr>
                </thead>
                <tbody>
                <tr

                    v-bind:class="{'border-info':item.deleted_at==null,'border-danger':item.deleted_at!=null}"
                    v-for="(item, index) in slugs">
                    <th scope="row" v-bind:class="{'text-danger':item.deleted_at!=null}">
                        {{ item.id || 'Нет идентификатора' }}
                    </th>
                    <td
                        @click="selectSlug(item)"
                        v-bind:class="{'text-danger':item.deleted_at!=null}"> {{ item.command || 'Нет команды' }}
                    </td>
                    <td v-bind:class="{'text-danger':item.deleted_at!=null}"> {{
                            item.comment || 'Пояснение не указано'
                        }}
                    </td>

                </tr>

                </tbody>
            </table>
        </div>


        <Pagination
            v-on:pagination_page="nextSlugs"
            v-if="paginate"
            :pagination="paginate"/>
        <div class="mb-3 col-md-12" v-if="slugs.length===0">

            <div class="alert alert-danger" role="alert">
                У Вас еще нет добавленных скриптов!
            </div>

        </div>
    </div>


</template>
<script>
import {mapGetters} from "vuex";

export default {
    props: ["command", "canSelect"],
    data() {
        return {
            bot: null,
            load: false,
            need_global: true,
            need_show_deleted: true,
            slugs: [],
            ownSearch:null,
            paginate: [],

        }
    },
    watch: {
        'ownSearch': function (oldV, newV) {
            this.loadSlugs()
        },
        'need_global': function (oldV, newV) {
            this.loadSlugs()

        },
        'need_show_deleted': function (oldV, newV) {
            this.loadSlugs()

        }
    },
    computed: {
        ...mapGetters(['getCurrentBot', 'getSlugs', 'getSlugsPaginateObject']),
    },

    mounted() {
        this.loadCurrentBot().then(() => {
            this.loadSlugs();
        })

    },
    methods: {
        nextSlugs(index) {
            this.loadSlugs(index)
        },
        loadSlugs(page = 0) {
            return this.$store.dispatch("loadSlugs", {
                dataObject: {
                    botId: this.bot.id,
                    needGlobal: this.need_global,
                    needDeleted: this.need_show_deleted,
                    search: this.ownSearch
                },
                page: page
            }).then((resp) => {
                this.slugs = this.getSlugs
                this.paginate = this.getSlugsPaginateObject

            })
        },
        loadCurrentBot(bot = null) {
            return this.$store.dispatch("updateCurrentBot", {
                bot: bot
            }).then(() => {
                this.bot = this.getCurrentBot
            })
        },
        callbackSlugs() {
            this.loadSlugs()
        },
        selectSlug(item) {
            this.$emit("select", item)
        },

    }
}
</script>
