<template>
    <div class="row">
        <div class="col-12 mb-3">
            <div class="alert alert-warning" role="alert">
                Если вы боитесь последствий модификации команды, то продублируйте нужную и внесите коррективы!
                Работать будут обе команды как оригинал, так и дубль!
            </div>
        </div>
        <div class="col-12">
            <form v-on:submit.prevent="addSlug"
                  class="card mb-3"
                  v-if="allSlugs.length>0">

                <div class="card-body">
                    <h6>Добавление нового скрипта в бота</h6>
                    <div>
                        <input type="text"
                               class="form-control mt-1 mb-1"
                               v-model="search"
                               placeholder="Поиск нужного скрипта по описанию">
                    </div>
                    <label class="form-label" id="bot-level-2">
                        Выберите скрипт
                        <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>
                    </label>
                    <ul class="list-group" style="overflow-y: auto; height: 300px; padding:10px;">
                        <li class="list-group-item cursor-pointer"
                            v-bind:class="{'active':slugForm.slug === item.slug}"
                            @click="selectSlug(item)"
                            v-for="(item, index) in filteredAllSlugs">
                            <p> {{ item.command }} (<strong>{{ item.slug }}</strong>)</p>
                            <p v-if="item.page"><span class="badge bg-success">Привязано к странице</span></p>
                            <p>{{ item.comment || 'Пояснение не указано' }}</p>
                        </li>

                    </ul>

                    <div class="row">
                        <div class="col-md-12 col-12">
                            <div class="mb-3">
                                <label class="form-label" id="bot-domain">
                                    <Popper>
                                        <i class="fa-regular fa-circle-question mr-1"></i>
                                        <template #content>
                                            <div>Измените только текст команды,<br>
                                                если хотите чтоб скрипт вызывался по кнопке из меню. <br>
                                                Или оставьте как есть. <br>
                                                Текст скрипта нужно также указать в качестве пункта меню.
                                            </div>
                                        </template>
                                    </Popper>
                                    Команда
                                    <span class="badge rounded-pill text-bg-danger m-0">Нужно</span>
                                </label>
                                <input type="text" class="form-control"
                                       placeholder="Команда"
                                       aria-label="Команда"
                                       v-model="slugForm.command"
                                       maxlength="255"
                                       aria-describedby="bot-domain" required>
                            </div>

                        </div>


                    </div>
                    <button
                        class="btn btn-outline-success mt-2 mb-2 w-100">Добавить скрипт в бота
                    </button>
                </div>

            </form>
        </div>
        <div class="col-12 mb-3"
             v-if="slugs"
             v-for="(slug, index) in slugs">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <button
                                @click="duplicateSlug(index)"
                                type="button"
                                class="btn btn-outline-success mr-2"
                            >
                                Дублировать
                            </button>
                            <button
                                @click="removeSlug(index)"
                                type="button"
                                class="btn btn-outline-danger"
                            >
                                Удалить
                            </button>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="mb-3">
                                <label class="form-label" id="bot-domain">Команда   <span
                                    v-if="slugs[index].page"
                                    class="badge bg-success">Привязано к странице</span></label>
                                <input type="text" class="form-control"
                                       placeholder="Команда"
                                       aria-label="Команда"
                                       v-model="slugs[index].command"
                                       maxlength="255"
                                       aria-describedby="bot-domain" required>
                            </div>

                        </div>

                        <div class="col-md-6 col-12">
                            <div class="mb-3">
                                <label class="form-label" id="bot-domain">Мнемоническое имя</label>
                                <input type="text" class="form-control"
                                       :disabled="true"
                                       placeholder="Мнемоническое имя"
                                       aria-label="Мнемоническое имя"
                                       v-model="slugs[index].slug"
                                       maxlength="255"
                                       aria-describedby="bot-domain" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <p>{{ slugs[index].comment || 'Пояснение не указано' }}</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: ["slugs", "command"],
    data() {
        return {
            search:null,
            allSlugs: [],
            slugForm: {
                command: null,
                comment: null,
                slug: null,
            }
        }
    },
    computed:{
      filteredAllSlugs(){
          if (this.allSlugs.length===0)
              return [];

          if (this.search==null)
              return this.allSlugs

          return this.allSlugs.filter(item=>{
              let slug = item.slug || ''
              let command = item.command || ''
              let comment = item.comment || ''

              return command.toLowerCase().indexOf(this.search.toLowerCase())!==-1||
                  comment.toLowerCase().indexOf(this.search.toLowerCase())!==-1 ||
                  slug.toLowerCase().indexOf(this.search.toLowerCase())!==-1
              })

      }

    },
    mounted() {
        this.loadAllSlugs()

        if (this.command) {
            this.$nextTick(() => {
                this.slugForm.command = this.command
            })
        }
    },
    methods: {
        selectSlug(item) {
            this.slugForm.slug = item.slug
            this.slugForm.comment = item.comment
            this.slugForm.command = this.command || item.command
        },
        duplicateSlug(index) {
            this.$emit("duplicate", index)
        },
        removeSlug(index) {
            this.$emit("remove", index)
        },
        loadAllSlugs() {
            this.$store.dispatch("loadAllSlugs").then(resp => {
                console.log(resp)
                this.allSlugs = resp.data
            })
        },
        addSlug() {
            const slug = this.slugForm

            this.$emit("add", slug)

            this.slugForm.slug = null
            this.slugForm.comment = null
            this.slugForm.command = null
        }
    }
}
</script>
