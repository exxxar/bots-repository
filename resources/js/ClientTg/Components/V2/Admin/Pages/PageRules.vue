<script setup>
import TelegramChannelHelper from "@/AdminPanel/Components/Constructor/Helpers/TelegramChannelHelper.vue";
import PagesList from "@/ClientTg/Components/V2/Admin/Pages/PageList.vue";
</script>
<template>

    <div class="alert alert-success" role="alert">
        Проверка правил идет по методу "И" - все правила должны выполняться для перехода на данную страницу,
        иначе будет вызвана страница из блока "иначе". Действия для "иначе" добавлять не обязательно.
    </div>
    <h6 class="my-2 fw-bold">Блок условий (если)</h6>
    <template v-if="rulesForm.rules_if">
        <div class="alert-light alert mb-2" id="bot-domain">
            Напишите текст, который будет отображен пользователю вместе с основным контентом в случае если
            условие будет выполнено. Не обязательно
        </div>

        <div class="form-floating mb-2">
               <textarea type="text" class="form-control"
                         placeholder="Текст сообщения"
                         aria-label="Текст сообщения"
                         v-model="rulesForm.rules_if_message"
                         maxlength="255"
                         id="rules-if-message"
                         aria-describedby="rules-if-message">
            </textarea>
            <label for="rules-if-message">Текст сообщения</label>
        </div>

    </template>


    <div class="dropdown">
        <button class="btn btn-outline-primary w-100" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            Доступные для выбора условия
        </button>
        <ul class="dropdown-menu w-100">
            <li
                @click="addRulesFromEvent(rule)"
                v-for="rule in rules"><a class="dropdown-item" href="javascript:void(0)">
                {{ rule.title || 'Не указан' }}
            </a>
            </li>

        </ul>
    </div>

    <h6 v-if="rulesForm.rules_if" class="my-2 ">Выбранные условия</h6>

    <ul class="list-group" v-if="rulesForm.rules_if">
        <li class="list-group-item  d-flex justify-content-between align-items-center"
            v-if="hasRuleKey('is_vip')">

            <div class="form-check">
                <input class="form-check-input"
                       v-model="rulesForm.rules_if.bot_user.is_vip"
                       type="checkbox" id="bot-user-is-vip">
                <label class="form-check-label" for="bot-user-is-vip">
                    <span v-if="rulesForm.rules_if.bot_user.is_vip">Является VIP-пользователем</span>
                    <span v-else>Не является VIP-пользователем</span>
                </label>
            </div>

            <button
                type="button"
                class="btn btn-outline-info" @click="removeRule('is_vip')"><i class="fa-solid fa-trash-can"></i>
            </button>

        </li>
        <li class="list-group-item  d-flex justify-content-between align-items-center"
            v-if="hasRuleKey('is_admin')">

            <div class="form-check">
                <input class="form-check-input"
                       v-model="rulesForm.rules_if.bot_user.is_admin"
                       type="checkbox" id="bot-user-is-admin">
                <label class="form-check-label" for="bot-user-is-admin">
                    <span v-if="rulesForm.rules_if.bot_user.is_admin">Является администратором</span>
                    <span v-else>Не является администратором</span>
                </label>
            </div>

            <button
                type="button"
                class="btn btn-outline-info" @click="removeRule('is_admin')"><i
                class="fa-solid fa-trash-can"></i></button>

        </li>
        <li class="list-group-item  d-flex justify-content-between align-items-center"
            v-if="hasRuleKey('is_deliveryman')">

            <div class="form-check">
                <input class="form-check-input"
                       v-model="rulesForm.rules_if.bot_user.is_deliveryman"
                       type="checkbox" id="bot-user-is-deliveryman">
                <label class="form-check-label" for="bot-user-is-deliveryman">
                    <span v-if="rulesForm.rules_if.bot_user.is_deliveryman">Является доставщиком</span>
                    <span v-else>Не является доставщиком</span>
                </label>
            </div>

            <button
                type="button"
                class="btn btn-outline-info" @click="removeRule('is_deliveryman')"><i
                class="fa-solid fa-trash-can"></i></button>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center"
            v-if="hasRuleKey('is_work')">
            <div class="form-check">
                <input class="form-check-input"
                       v-model="rulesForm.rules_if.bot_user.is_work"
                       type="checkbox" id="bot-user-is-work">
                <label class="form-check-label" for="bot-user-is-work">
                    <span v-if="rulesForm.rules_if.bot_user.is_work">За работой</span>
                    <span v-else>Не работает</span>
                </label>
            </div>

            <button
                type="button"
                class="btn btn-outline-info" @click="removeRule('is_work')"><i
                class="fa-solid fa-trash-can"></i></button>
        </li>
        <li class="list-group-item  d-flex justify-content-between align-items-center"
            v-if="hasRuleKey('user_in_location')">

            <div class="form-check">
                <input class="form-check-input"
                       v-model="rulesForm.rules_if.bot_user.user_in_location"
                       type="checkbox" id="bot-user-in-location">
                <label class="form-check-label" for="bot-user-in-location">
                    <span v-if="rulesForm.rules_if.bot_user.user_in_location">Находится в заведении</span>
                    <span v-else>Не находится в заведении</span>
                </label>
            </div>

            <button
                type="button"
                class="btn btn-outline-info" @click="removeRule('user_in_location')"><i
                class="fa-solid fa-trash-can"></i></button>

        </li>
        <li class="list-group-item  d-flex justify-content-between align-items-center"
            v-if="hasRuleKey('age')">

            <div>
                <label class="form-label" id="bot-domain">
                    Возраст от

                </label>
                <input type="text" class="form-control"
                       placeholder="Возраст от"
                       aria-label="Возраст от"
                       v-model="rulesForm.rules_if.bot_user.age"
                       maxlength="255"
                       aria-describedby="bot-user-age">
            </div>
            <button
                type="button"
                class="btn btn-outline-info" @click="removeRule('age')"><i class="fa-solid fa-trash-can"></i>
            </button>
        </li>
        <li class="list-group-item  d-flex justify-content-between align-items-center"
            v-if="hasRuleKey('city')">

            <div>
                <label class="form-label" id="bot-domain">
                    Только для города

                </label>
                <input type="text" class="form-control"
                       placeholder="Город"
                       aria-label="Город"
                       v-model=" rulesForm.rules_if.bot_user.city "
                       maxlength="255"
                       aria-describedby="bot-user-city">

            </div>

            <button
                type="button"
                class="btn btn-outline-info" @click="removeRule('city')"><i class="fa-solid fa-trash-can"></i>
            </button>
        </li>
        <li class="list-group-item  d-flex justify-content-between align-items-center"
            v-if="hasRuleKey('sex')">

            <div class="form-check">
                <input class="form-check-input"
                       v-model="rulesForm.rules_if.bot_user.sex"
                       type="checkbox" id="bot-user-sex">
                <label class="form-check-label" for="bot-user-sex">
                    <span v-if="rulesForm.rules_if.bot_user.sex">Только для мужчин</span>
                    <span v-else>Только для женщин</span>
                </label>
            </div>


            <button
                type="button"
                class="btn btn-outline-info" @click="removeRule('sex')"><i class="fa-solid fa-trash-can"></i>
            </button>
        </li>
        <li class="list-group-item"
            v-if="rulesForm.rules_if.channels.length>0">Проверка нахождения в канале \ группе:

            <div class="mb-3" v-for="(channel, index) in rulesForm.rules_if.channels">
                <div class="d-flex justify-content-between align-items-center">
                    <label class="form-label d-flex w-100 align-items-center"
                           id="bot-order-channel">

                        <TelegramChannelHelper
                            :token="bot.bot_token"
                            :param="`${index}`"
                            v-on:callback="modifyChannel"
                        />
                        Канал для проверки


                    </label>

                    <button
                        type="button"
                        class="btn btn-link text-danger" @click="removeChannelFromRule(index)"><i
                        class="fa-solid fa-trash-can"></i></button>
                </div>

                <div class="form-floating">
                    <input type="text" class="form-control"
                           placeholder="id канала"
                           aria-label="id канала"
                           v-model="rulesForm.rules_if.channels[index]"
                           maxlength="255"
                           aria-describedby="bot-order-channel">
                    <label for="">id канала</label>
                </div>

            </div>

        </li>
    </ul>

    <h6 class="my-3 fw-bold" v-if="rulesForm.rules_if">Иначе</h6>
    <p class="d-flex justify-content-between"
       v-if="rulesForm.rules_else_page_id">Вы выбрали страницу #{{ rulesForm.rules_else_page_id }} в качестве условия
        "иначе"
        <a href="javascript:void(0)" @click="rulesForm.rules_else_page_id = null">Очистить</a>
    </p>
    <PagesList
        v-if="rulesForm.rules_if"
        :current="rulesForm.id"
        v-on:callback="attachPageToRule"
        :editor="false"/>

    <template
        v-if="rulesForm.rules_if">

        <div class="alert-light alert mb-2" id="bot-domain">
            Напишите текст, который будет отображен пользователю вместе с основным контентом в случае если
            условие не будет выполнено и будет вызван блок "иначе"
        </div>

        <div class="form-floating mb-2">
              <textarea type="text" class="form-control"
                        placeholder="Текст сообщения"
                        aria-label="Текст сообщения"
                        v-model="rulesForm.rules_else_message"
                        maxlength="255"
                        id="rules-else-message"
                        aria-describedby="rules-else-message">
            </textarea>
            <label for="rules-else-message">Текст сообщения</label>
        </div>

    </template>

</template>
<script>

export default {
    props:["bot","rulesForm"],
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
        window.addEventListener("select-rule-event", (e) => {
            this.addRulesFromEvent(e.detail.rule)
        } );
    },
    methods: {
        hasRuleKey(key) {
            if (this.rulesForm.rules_if == null)
                return false

            return Object.keys(this.rulesForm.rules_if.bot_user).find(item => item === key) != null
        },
        modifyChannel(params) {
            this.rulesForm.rules_if.channels[params.param] = params.text
        },
        removeChannelFromRule(index) {
            this.rulesForm.rules_if.channels.splice(index, 1)
            this.checkRules()
        },
        checkRules(){
            const propBotUsers = Object.getOwnPropertyNames(this.rulesForm.rules_if.bot_user);
            const channels = this.rulesForm.rules_if.channels;
            if (propBotUsers.length===0&&channels.length===0)
                this.rulesForm.rules_if = null
        },
        removeRule(rule) {
            delete this.rulesForm.rules_if.bot_user[rule]
            this.checkRules()
        },
        addRulesFromEvent(item) {

            if (!this.rulesForm.rules_if)
                this.rulesForm.rules_if = {
                    bot_user: {},
                    channels: [],
                }

            if (item.rules_block === "bot_user")
                this.rulesForm.rules_if.bot_user = {...this.rulesForm.rules_if.bot_user, ...item.rule}
            if (item.rules_block === "channels")
                this.rulesForm.rules_if.channels.push(item.rule.channel)

            // this.rulesForm.rules_if[item.rules_block] = [...this.rulesForm.rules_if[item.rules_block], ...item.rule]
        },
        attachPageToRule(item){
            if (item.id != this.rulesForm.id)
                this.rulesForm.rules_else_page_id = item.id
            else
                this.$notify({
                    title:'Правила страниц',
                    text: "Вы не можете связать данную страницу с собой!",
                    type:'error'
                })


        },
        openRulesModal(){
            this.$botPages.rules()
        }
    }
}
</script>
