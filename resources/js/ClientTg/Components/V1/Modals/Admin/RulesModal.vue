<template>


    <div :id="'rules-selector'" class="menu menu-box-bottom menu-box-detached rounded-m"
         data-menu-height="400" data-menu-effect="menu-over"
         style="height: 400px;display: block;">
        <h1 class="text-center mt-3 text-uppercase font-700">Выбор правила</h1>

        <div class="list-group list-custom-small px-2">
            <a  v-for="rule in rules"
                @click="addRules(rule)"
                href="javascript:void(0)">
                <i class="fa font-13 fa-check color-green1-dark"></i>
                <span>{{ rule.title || 'Не указан' }}</span>
            </a>

        </div>

    </div>


</template>
<script>
export default {
    data(){
      return {
          rules: [
              {
                  id: 1,
                  title: "Является администратором",
                  rules_block: 'bot_user',
                  rule: {
                      is_admin: true,
                  }
              },
              {
                  id: 2,
                  title: "Является VIP-пользователем",
                  rules_block: 'bot_user',
                  rule: {
                      is_vip: true,
                  }
              },
              {
                  id: 3,
                  title: "Находится в заведении",
                  rules_block: 'bot_user',
                  rule: {
                      user_in_location: true,
                  }
              },
              {
                  id: 4,
                  title: "За работой",
                  rules_block: 'bot_user',
                  rule: {
                      is_work: true,
                  }
              },
              {
                  id: 5,
                  title: "Возраст от ...",
                  rules_block: 'bot_user',
                  rule: {
                      age: 18,
                  }
              },
              {
                  id: 6,
                  title: "Находится в городе ...",
                  rules_block: 'bot_user',
                  rule: {
                      city: 'Краснодар',
                  }
              },
              {
                  id: 7,
                  title: "Пол",
                  rules_block: 'bot_user',
                  rule: {
                      sex: true,
                  }
              },
              {
                  id: 8,
                  title: "Состоит в канале",
                  rules_block: 'channels',
                  rule: {
                      channel: 1,
                  }
              },
              {
                  id: 9,
                  title: "Является Менеджером",
                  rules_block: 'bot_user',
                  rule: {
                      is_manager: true,
                  }
              },

          ],
      }
    },
    mounted() {

        window.addEventListener("open-rules-modal", (e) => {
            $('#rules-selector').showMenu();
        } );
    },
    methods:{
        duplicatePage() {
            this.loading = true
            this.$store.dispatch("duplicatePage", {
                dataObject: {
                    pageId: this.id
                },
            }).then(resp => {
                this.loading = false
                $('#menu-page-editor').hideMenu();
                this.$botPages.reloadPageList()
            }).catch(() => {
                this.loading = false
            })
        },
        removePage() {
            this.loading = true
            this.$store.dispatch("removePage", {
                dataObject: {
                    pageId: this.id
                },
            }).then(resp => {
                this.loading = false
                $('#menu-page-editor').hideMenu();
                this.$botPages.reloadPageList()
            }).catch(() => {
                this.loading = false
            })
        },
        addRules(item) {
            this.$botPages.selectRule(item)
            $('#rules-selector').hideMenu();
        },
    }
}
</script>

