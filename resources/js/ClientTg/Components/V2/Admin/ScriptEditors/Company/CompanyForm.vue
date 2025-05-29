<script setup>

</script>

<template>
    <form v-on:submit.prevent="submit">

        <p class="alert alert-light mb-2" v-if="!companyForm.law_params.offer_link">
            Внимание! Обязательно разместите ссылку на договор оферты!
        </p>
        <!-- Ссылка на договор оферты -->
        <div class="form-floating mb-2">
            <input
                v-model="companyForm.law_params.offer_link"
                type="url" class="form-control"
                required
                id="offerLink" placeholder="https://example.com/offer">
            <label for="offerLink">Ссылка на договор оферты</label>
        </div>

        <!-- Выбор типа организации -->
        <div class="form-floating mb-2">
            <select class="form-select"
                    v-model="companyForm.law_params.selected_type"
                    id="orgType" required>
                <option :value="null" selected>Выберите тип</option>
                <option :value="item.id" v-for="item in business_form_categories">{{ item.title || '-' }}</option>
            </select>
            <label for="orgType">Тип организации</label>
        </div>

        <!-- Поля для ИП -->
        <div id="ipFields" class="org-fields" v-if="companyForm.law_params.selected_type === 2">
            <div class="form-floating mb-2">
                <input type="text" class="form-control" id="ipFullName" placeholder="Иванов Иван Иванович"
                       v-model="companyForm.law_params.full_name">
                <label for="ipFullName">ФИО ИП</label>
            </div>
            <div class="form-floating mb-2">
                <input type="text"
                       v-mask="['############']"
                       class="form-control" id="ipInn" placeholder="123456789012" maxlength="12"
                       v-model="companyForm.law_params.inn">
                <label for="ipInn">ИНН</label>
            </div>
            <div class="form-floating mb-2">
                <input type="text"
                       v-mask="['###############']"
                       class="form-control" id="ipOgrnip" placeholder="123456789012345" maxlength="15"
                       v-model="companyForm.law_params.ogrnip">
                <label for="ipOgrnip">ОГРНИП</label>
            </div>
        </div>

        <!-- Поля для ООО -->
        <div id="oooFields" class="org-fields" v-if="companyForm.law_params.selected_type === 3">
            <div class="form-floating mb-2">
                <input type="text" class="form-control" id="oooName" placeholder="ООО Ромашка"
                       v-model="companyForm.law_params.name">
                <label for="oooName">Наименование ООО</label>
            </div>
            <div class="form-floating mb-2">
                <input type="text" class="form-control" id="oooName"
                       placeholder="23055, г. Краснодар, ул. Красная, д. 1, офис 234"
                       v-model="companyForm.law_params.phisical_adress">
                <label for="oooName">Юридический адрес</label>
            </div>
            <div class="form-floating mb-2">
                <input type="text"
                       v-mask="['##########']"
                       class="form-control" id="oooInn" placeholder="1234567890" maxlength="10"
                       v-model="companyForm.law_params.inn" required>
                <label for="oooInn">ИНН</label>
            </div>
            <div class="form-floating mb-2">
                <input type="text"
                       v-mask="['#########']"
                       class="form-control" id="oooKpp" placeholder="123456789" maxlength="9"
                       v-model="companyForm.law_params.kpp" required>
                <label for="oooKpp">КПП</label>
            </div>
            <div class="form-floating mb-2">
                <input type="text"
                       v-mask="['#############']"
                       class="form-control" id="oooOgrn" placeholder="1234567890123" maxlength="13"
                       v-model="companyForm.law_params.ogrn" required>
                <label for="oooOgrn">ОГРН</label>
            </div>
        </div>

        <!-- Поля для самозанятого -->
        <div id="selfFields" class="org-fields"
             v-if="companyForm.law_params.selected_type === 1 || companyForm.law_params.selected_type === 0">
            <div class="form-floating mb-2">
                <input type="text" class="form-control" id="selfFullName" placeholder="Иванов Иван Иванович"
                       v-model="companyForm.law_params.full_name">
                <label for="selfFullName">ФИО</label>
            </div>
            <div class="form-floating mb-2">
                <input type="text"
                       v-mask="['############']"
                       class="form-control" id="selfInn" placeholder="123456789012" maxlength="12"
                       v-model="companyForm.law_params.inn">
                <label for="selfInn">ИНН</label>
            </div>
            <div class="form-floating mb-2">
                <input type="text"
                       v-mask="['#### ######']"
                       class="form-control" id="selfPassportNumber" placeholder="1234 567890" maxlength="10"
                       v-model="companyForm.law_params.passport_number">
                <label for="selfPassportNumber">Серия и номер паспорта</label>
            </div>
            <div class="form-floating mb-2">
                <input type="date"
                       class="form-control" id="selfPassportDate"
                       v-model="companyForm.law_params.passport_date">
                <label for="selfPassportDate">Дата выдачи паспорта</label>
            </div>
            <div class="form-floating mb-2">
                <input type="text" class="form-control" id="selfPassportIssuer" placeholder="Отделением УФМС России"
                       v-model="companyForm.law_params.passport_issuer">
                <label for="selfPassportIssuer">Кем выдан</label>
            </div>
        </div>

        <div class="form-check mb-2">
            <input class="form-check-input" type="checkbox" id="agreementCheck"
                   v-model="companyForm.law_params.agreement">
            <label class="form-check-label" for="agreementCheck">
                Я согласен с <a
                target="_blank"
                href="https://telegra.ph/PUBLICHNAYA-OFERTA-na-ispolzovanie-platformy-CashMan-05-24">условиями</a>
            </label>
        </div>
        <button
            style="z-index: 100;"
            type="submit" class="btn btn-primary w-100 p-3 mb-3 position-sticky bottom-0">Сохранить изменения
        </button>
    </form>
</template>

<script>
export default {
    data() {
        return {
            step: 0,
            load: false,
            photo: null,

            removedImage: null,
            need_reset: false,
            vat_codes: [
                {
                    id: 1,
                    title: 'Общая система налогообложения'
                },
                {
                    id: 2,
                    title: 'Упрощенная (УСН, доходы)'
                },
                {
                    id: 3,
                    title: 'Упрощенная (УСН, доходы минус расходы)'
                },
                {
                    id: 4,
                    title: 'Единый налог на вмененный доход (ЕНВД)'
                },
                {
                    id: 5,
                    title: 'Единый сельскохозяйственный налог (ЕСН)'
                },
                {
                    id: 6,
                    title: 'Патентная система налогообложения'
                }
            ],

            business_form_categories: [
                {
                    id: 0,
                    title: "Физическое-лицо",
                },
                {
                    id: 1,
                    title: "Самозанятый",
                },

                {
                    id: 2,
                    title: "Индивидуальный предприниматель (ИП)",
                },
                {
                    id: 3,
                    title: "Общество с ограниченной ответственностью (ООО)",
                },

            ],
            companyForm: {
                id: null,
                law_params: {
                    selected_type: 2,
                    full_name: null,
                    inn: null,
                    ogrnip: null,
                    name: null,
                    kpp: null,
                    ogrn: null,
                    phisical_adress: null,
                    passport_number: null,
                    passport_date: null,
                    passport_issuer: null,
                    agreement: true,
                    offer_link: null,
                },
                vat_code: 1,
            }
        }
    },
    computed:{
        currentBot() {
            return window.currentBot
        },
    },
    watch: {
        companyForm: {
            handler(val) {
                this.need_reset = true
            },
            deep: true
        }
    },
    mounted() {
        if (this.currentBot.company)
            this.$nextTick(() => {
                const company = this.currentBot.company
                this.companyForm.id = company.id || null;
                this.companyForm.law_params.selected_type = company.law_params.selected_type || null;
                this.companyForm.law_params.full_name = company.law_params.full_name || null;
                this.companyForm.law_params.inn = company.law_params.inn || null;
                this.companyForm.law_params.ogrnip = company.law_params.ogrnip || null;
                this.companyForm.law_params.name = company.law_params.name || null;
                this.companyForm.law_params.kpp = company.law_params.kpp || null;
                this.companyForm.law_params.ogrn = company.law_params.ogrn || null;
                this.companyForm.law_params.phisical_adress = company.law_params.phisical_adress || null;
                this.companyForm.law_params.passport_number = company.law_params.passport_number || null;
                this.companyForm.law_params.passport_date = company.law_params.passport_date || null;
                this.companyForm.law_params.passport_issuer = company.law_params.passport_issuer || null;
                this.companyForm.law_params.agreement = company.law_params.agreement || null;
                this.companyForm.law_params.offer_link = company.law_params.offer_link || null;
                this.companyForm.vat_code = company.vat_code || null;

            })

    },
    methods: {
        resetForm() {
            this.companyForm.id = null;
            this.companyForm.law_params.selected_type = null;
            this.companyForm.law_params.full_name = null;
            this.companyForm.law_params.inn = null;
            this.companyForm.law_params.ogrnip = null;
            this.companyForm.law_params.name = null;
            this.companyForm.law_params.kpp = null;
            this.companyForm.law_params.ogrn = null;
            this.companyForm.law_params.phisical_adress = null;
            this.companyForm.law_params.passport_number = null;
            this.companyForm.law_params.passport_date = null;
            this.companyForm.law_params.passport_issuer = null;
            this.companyForm.law_params.agreement = null;
            this.companyForm.law_params.offer_link = null;
            this.companyForm.vat_code = null;


        },


        submit() {
            let data = new FormData();
            Object.keys(this.companyForm)
                .forEach(key => {
                    const item = this.companyForm[key] || ''
                    if (typeof item === 'object')
                        data.append(key, JSON.stringify(item))
                    else
                        data.append(key, item)
                });

            this.$store.dispatch("updateCompanyLawParams",
                {
                    companyForm: data
                }).then((response) => {
                this.$notify({
                    title: "Юридические параметры",
                    text: "Параметры успешно обновлены",
                    type: "success",
                });

            }).catch(err => {
                this.$notify({
                    title: "Юридические параметры",
                    text: "Ошибка обновления параметров",
                    type: "error",
                });
            })

        },

    }
}
</script>

<style>

</style>
