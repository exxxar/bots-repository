<template>

    <form v-on:submit.prevent="submit">
        <div class="form-floating mb-2">
            <input type="text"
                   v-model="iikoForm.api_login"
                   @change="changeApiLogin"
                   class="form-control" id="iiko-form-api_login"
                   placeholder="name@example.com">
            <label for="iiko-form-api_login">API логин</label>
        </div>

        <div class="alert alert-light mb-2">
            Выберите организацию, которую хотите подключить
        </div>

        <div class="dropdown mb-2">
            <button
                type="button"
                :disabled="!token"
                class="btn btn-outline-primary dropdown-toggle w-100 p-3"
                role="button"
                data-bs-toggle="dropdown"
                aria-expanded="false">
                <span v-if="selected_organization">{{ selected_organization.name || 'не указан' }}</span>
                <span v-else>Не выбрано организации</span>
            </button>

            <ul class="dropdown-menu w-100">
                <li><a class="dropdown-item"
                       @click="selectOrganization(null)"
                       href="javascript:void(0)">Не выбрано</a></li>
                <li>
                    <hr class="dropdown-divider p-0 m-0">
                </li>
                <li
                    v-for="item in organizations"><a
                    v-bind:class="{'bg-primary text-white':item.id==this.iikoForm.organization_id}"
                    @click="selectOrganization(item)"
                    class="dropdown-item" href="javascript:void(0)">
                    <p class="text-wrap m-0"> {{ item.name || '-' }} ({{ item.restaurantAddress || '-' }})</p>
                </a></li>
            </ul>
        </div>

        <div class="alert alert-light mb-2">
            Выберите терминал в организации, через который будут проходить операции
        </div>

        <div class="dropdown mb-2">
            <button
                type="button"
                :disabled="!iikoForm.organization_id"
                class="btn btn-outline-primary dropdown-toggle w-100 p-3" role="button"
                data-bs-toggle="dropdown"
                aria-expanded="false">
                <span v-if="selected_terminal">{{ selected_terminal.name || 'не указан' }}</span>
                <span v-else>Не выбрано терминал</span>
            </button>

            <ul class="dropdown-menu w-100">
                <li><a class="dropdown-item"
                       @click="selectTerminal(null)"
                       href="javascript:void(0)">Не выбрано</a></li>
                <li>
                    <hr class="dropdown-divider p-0 m-0">
                </li>
                <li

                    v-for="item in preparedTerminals"><a
                    @click="selectTerminal(item)"
                    v-bind:class="{'bg-primary text-white':item.id==this.iikoForm.terminal_group_id}"
                    class="dropdown-item" href="javascript:void(0)">
                    {{ item.name || '-' }}
                </a></li>
            </ul>
        </div>


        <button
            class="btn btn-primary p-3 w-100"
            :disabled="!isComplete">
            Сохранить настройки
        </button>
    </form>

</template>
<script>
import {mapGetters} from "vuex";

export default {
    props: ["modelValue"],
    computed: {
        isComplete() {
            return this.iikoForm.api_login &&
                this.iikoForm.organization_id &&
                this.iikoForm.terminal_group_id
        },
        preparedTerminals() {
            let tmp = []

            this.terminals.forEach(terminal => {
                terminal.items.forEach(item => {
                    tmp.push(item)
                })
            })

            return tmp
        }
    },
    data() {
        return {
            load: false,
            token: null,
            selected_organization: null,
            selected_terminal: null,
            organizations: [],
            terminals: [],
            iikoForm: {
                id: null,
                bot_id: null,
                api_login: null,
                organization_id: null,
                terminal_group_id: null,
            },
        }
    },
    watch: {
        'iikoForm': {
            handler: function (newValue) {
                this.$emit("update:modelValue", this.iikoForm)
            },
            deep: true
        },

    },
    mounted() {
        if (this.modelValue)
            this.$nextTick(() => {
                this.iikoForm.id = this.modelValue.id || null
                this.iikoForm.bot_id = this.modelValue.bot_id || null
                this.iikoForm.api_login = this.modelValue.api_login || null
                this.iikoForm.organization_id = this.modelValue.organization_id || null
                this.iikoForm.terminal_group_id = this.modelValue.terminal_group_id || null

                this.selected_terminal = {
                    name: this.iikoForm.terminal_group_id
                }

                this.selected_organization = {
                    name: this.iikoForm.organization_id
                }

                if (this.iikoForm.api_login)
                    this.getToken()
            })

    },
    methods: {
        selectOrganization(item) {
            if (!item) {
                this.selected_organization = null
                this.iikoForm.organization_id = null
                this.selected_terminal = null
                this.iikoForm.terminal_group_id = null
                return
            }
            this.selected_organization = item
            this.iikoForm.organization_id = item.id
            this.getTerminals()
        },
        selectTerminal(item) {
            if (!item) {
                this.selected_terminal = null
                this.iikoForm.terminal_group_id = null
                return
            }
            this.selected_terminal = item
            this.iikoForm.terminal_group_id = item.id
        },
        changeApiLogin() {
            this.token = null
            this.selectOrganization(null)
            this.selectTerminal(null)

            this.getToken()
        },
        getToken() {


            this.$store.dispatch("getIikoToken", {
                api_login: this.iikoForm.api_login
            }).then((response) => {
                this.token = response.token || null

                this.getOrganizations()
            }).catch(err => {
                this.$notify({
                    title: "Упс!",
                    text: "Ошибка получения токена",
                    type: "error"
                });
            })
        },
        getOrganizations() {
            if (!this.token) {
                return
            }

            this.$store.dispatch("getIikoOrganizations", {
                token: this.token
            }).then((response) => {
                this.organizations = response.organizations || []

            }).catch(err => {
                this.$notify({
                    title: "Упс!",
                    text: "Ошибка получения списка организаций",
                    type: "error"
                });
            })
        },
        getTerminals() {
            if (!this.token || !this.iikoForm.organization_id) {
                return
            }


            this.$store.dispatch("getIikoTerminals", {
                token: this.token,
                organization_id: this.iikoForm.organization_id
            }).then((response) => {
                this.terminals = response.terminals || []
            }).catch(err => {
                this.$notify({
                    title: "Упс!",
                    text: "Ошибка получения списка терминалов",
                    type: "error"
                });
            })
        },

        submit() {
            let data = new FormData();
            Object.keys(this.iikoForm)
                .forEach(key => {
                    const item = this.iikoForm[key] || ''
                    if (typeof item === 'object')
                        data.append(key, JSON.stringify(item))
                    else
                        data.append(key, item)
                });


            this.$store.dispatch("storeIiko", {
                iikoForm: data
            }).then((response) => {
                this.$notify({
                    title: "Отлично!",
                    text: "Параметры успешно сохранены",
                    type: "success"
                });
            }).catch(err => {
                this.$notify({
                    title: "Упс!",
                    text: "Ошибка сохранения параметров",
                    type: "error"
                });
            })


        }
    }
}
</script>
