<script setup>
import TableTypeSelector from "@/ClientTg/Components/V2/Admin/ScriptEditors/Shop/Tables/TableTypeSelector.vue";
import SelectedTablesList from "@/ClientTg/Components/V2/Admin/ScriptEditors/Shop/Tables/SelectedTablesList.vue";
</script>

<template>
    <div class="hall-constructor" v-if="loaded_params">
        <!-- Кнопка для выбора столиков -->


        <div class="form-check form-switch mb-2">
            <input class="form-check-input"
                   type="checkbox"
                   v-model="form.need_table_list"
                   role="switch" id="script-settings-need_table_list">
            <label class="form-check-label" for="script-settings-need_table_list">Столики в заведении: <span
                v-bind:class="{'text-primary fw-bold':form.need_table_list}">вкл</span> \ <span
                v-bind:class="{'text-primary fw-bold':!form.need_table_list}">выкл</span></label>
        </div>

        <template v-if="form.need_table_list">
            <p class="alert alert-light mb-2">
                Укажите максимальное число столиков в заведении
            </p>
            <div
                class="form-floating mb-2">
                <input type="number"
                       min="0"
                       max="200"
                       v-model="form.max_tables"
                       class="form-control" id="modelValue-table-number"
                       placeholder="Номер столика">
                <label for="modelValue-table-number">Число столиков</label>
            </div>

            <a
                :href="'/bot-client/'+bot.bot_domain+'/tables-qr?count='+form.max_tables+'&script-id='+scriptId"
                target="_blank"
                class="btn btn-info w-100 p-3 mb-2"
            ><i class="fa-solid fa-qrcode"></i> Скачать QR-коды для столиков</a>
        </template>
        <div class="divider my-3">Настройка бронирования</div>

        <div class="form-check form-switch mb-2">
            <input class="form-check-input"
                   type="checkbox"
                   v-model="form.can_use_booking"
                   role="switch" id="script-settings-need_bonuses_section">
            <label class="form-check-label" for="script-settings-need_bonuses_section">Разрешить бронирование столиков:
                <span
                    v-bind:class="{'text-primary fw-bold':form.can_use_booking}">вкл</span> \ <span
                    v-bind:class="{'text-primary fw-bold':!form.can_use_booking}">выкл</span></label>
        </div>

        <template v-if="tab==='table-selector'">
            <button
                type="button"
                class="btn btn-primary mb-2"
                @click="tab='configurator'">Закрыть
            </button>

            <h6 class="fw-bold">Выберите тип столика</h6>

            <TableTypeSelector v-on:selection="changeTableFilter"></TableTypeSelector>
            <SelectedTablesList
                v-on:remove="removeFromSelectedTableList"
                :sorted-selected-tables="form.tables_variants"></SelectedTablesList>
            <h6 class="fw-bold">Выберите столик</h6>
            <div class="row" v-if="filteredTables.length>0">
                <div
                    v-for="table in filteredTables"
                    :key="table.id"
                    class="col-6 p-2"

                >
                    <div class="border border-light p-2">
                        <img v-lazy="'/images/tables/'+table.image" alt="table" class="table-img"/>
                        <p>Столик №{{ table.id }}</p>
                        <p>{{ table.description }}</p>

                        <a href="javascript:void(0)"
                           @click="selectTable(table)"
                           class="text-center p-0 m-0 w-100 btn btn-primary"
                           v-if="countTableInSelection(table.id)===0">Выбрать</a>
                        <a href="javascript:void(0)"
                           @click="selectTable(table)"

                           class="text-center p-0 m-0 w-100 btn btn-primary" v-else>
                            <strong class="fw-bold p-0">{{ countTableInSelection(table.id) }}</strong> ед.</a>
                    </div>

                </div>
            </div>

            <div class="alert alert-light" v-else>
                Подходящие варианты не найдены
            </div>

        </template>

        <template v-if="tab==='configurator'">
            <button
                type="button"
                class="btn btn-outline-primary mb-3 w-100 p-2"
                @click="tab='table-selector'">Конфигуратор столиков
            </button>
            <!-- Список выбранных столиков -->
            <div class="p-2" v-if="sortedSelectedTables.length>0">
                <h6 class="fw-bold">Выбранные столики</h6>
                <div
                    v-for="(table, index) in sortedSelectedTables"
                    :key="index"
                    class="row mb-2 border-light border py-2"
                >
                    <div class="col-4">
                        <img v-lazy="'/images/tables/'+table.image" alt="table" class="table-img-small"/>
                    </div>
                    <div class="col-8">
                        <p>Столик №{{ table.number }} — {{ table.description }}</p>

                        <div class="d-flex">
                            <a
                                style="font-size:12px; margin-right:10px;"
                                @click="removeFromSelectedTableList(table.id)"
                                href="javascript:void(0)">Удалить</a>,
                            <a
                                style="font-size:12px; margin-right:10px;"
                                @click="table.edit = !table.edit"
                                href="javascript:void(0)">Редактировать</a>
                        </div>
                    </div>

                    <div class="col-12" v-if="table.edit">
                        <div class="form-floating mb-2">
                            <input type="number"
                                   v-model.number="table.number"
                                   class="form-control" :id="'table-number'+table.id" placeholder="Password">
                            <label :for="'table-number'+table.id">Номер</label>
                        </div>
                        <div class="form-floating mb-2">
                            <input type="number"
                                   v-model.number="table.seats"
                                   class="form-control" :id="'seats-count'+table.id" placeholder="Password">
                            <label :for="'seats-count'+table.id">Мест</label>
                        </div>
                        <div class="form-floating">
                        <textarea class="form-control"
                                  v-model.number="table.description"
                                  placeholder="Leave a comment here" :id="'seats-description'+table.id"></textarea>
                            <label :for="'seats-description'+table.id">Описание</label>
                        </div>


                    </div>


                </div>
            </div>
            <p class="alert alert-light" v-else>
                Вы еще не добавили столики для бронирования
            </p>
        </template>

    </div>
</template>

<script>
export default {
    name: "HallConstructor",
    props: ["modelValue"],
    data() {
        return {
            loaded_params: false,
            tab: 'configurator',
            form: null,
            tableFilters: [],
            tablesTemplate: [
                {id: 1, image: "1.png", description: "Угловой стол на 4 места", seats: 4},
                {id: 2, image: "2.png", description: "Угловой стол на 4 места", seats: 4},

                {id: 3, image: "3.png", description: "Круглый стол на 8 мест", seats: 8},
                {id: 4, image: "4.png", description: "Овальный стол на 6 мест", seats: 6},

                {id: 5, image: "5.png", description: "Овальный стол на 8 мест", seats: 8},
                {id: 6, image: "6.png", description: "Овальный стол с диваном на 8 мест", seats: 8},

                {id: 7, image: "7.png", description: "Прямоугольный стол с диваном на 8 мест", seats: 8},
                {id: 8, image: "8.png", description: "Овальный стол с диваном на 6 мест", seats: 6},

                {id: 9, image: "9.png", description: "Прямоугольный стол с диваном на 6 мест", seats: 6},
                {id: 10, image: "10.png", description: "Овальный стол с диваном на 4 места", seats: 4},

                {id: 11, image: "11.png", description: "Прямоугольный стол с диваном на 4 мест", seats: 4},
                {id: 12, image: "12.png", description: "Прямоугольный стол на 4 места", seats: 4},

                {id: 13, image: "13.png", description: "Прямоугольный стол на 6 мест", seats: 6},
                {id: 14, image: "14.png", description: "Круглый стол на 6 мест", seats: 6},

                {id: 15, image: "15.png", description: "Круглый стол на 5 мест", seats: 5},
                {id: 16, image: "16.png", description: "Круглый стол на 2 места", seats: 2},

                {id: 17, image: "17.png", description: "Круглый стол на 4 места", seats: 4},
                {id: 18, image: "18.png", description: "Квадратный стол на 2 места", seats: 2},

                {id: 19, image: "19.png", description: "Квадратный стол на 4 места", seats: 4},
            ],

        };
    },
    watch: {
        'form': {
            handler: function (newValue) {
                this.$emit("update:modelValue", this.form)
            },
            deep: true
        },
    },
    mounted() {
        this.loaded_params = false
        this.$nextTick(() => {
            this.form = this.modelValue
            if (!this.modelValue.tables_variants)
                this.form.tables_variants = []
            this.loaded_params = true
        })
    },
    computed: {
        scriptId() {
            return window.currentScript || null
        },
        bot() {
            return window.currentBot || null
        },
        filteredTables() {
            // если фильтры пустые — вернуть все столики отсортированные
            if (this.tableFilters.length === 0) {
                return (this.tablesTemplate || []).slice().sort((a, b) => a.seats - b.seats);
            }

            // иначе — вернуть только те, у которых seats входит в tableFilters
            return this.tablesTemplate
                .filter(table => this.tableFilters.includes(table.seats))
                .sort((a, b) => a.seats - b.seats);
        },
        sortedSelectedTables() {
            if (!this.form.tables_variants)
                return []
            return this.form.tables_variants
                .sort((a, b) => a.seats - b.seats);
        }
    },
    methods: {
        countTableInSelection(id) {
            return (this.form.tables_variants.filter(i => i.id === id) || []).length
        },
        removeFromSelectedTableList(id) {
            console.log("removeFromSelectedTableList", id, this.form.tables_variants)
            let index = this.form.tables_variants.findIndex(i => i.id === id)

            if (index !== -1)
                this.form.tables_variants.splice(index, 1)
        },
        changeTableFilter(filters) {
            this.tableFilters = filters
        },
        selectTable(table) {
            table.edit = false
            table.number = table.id
            // Добавляем копию, чтобы seats можно было менять независимо
            this.form.tables_variants.push({...table});

            this.$notify({
                title: "Информация о столиках",
                text: "Столик добавлен в общий список!",
                type: "success"
            })
        },
    },
};
</script>

<style scoped>
.hall-constructor {

}

.table-img-small {
    width: 100%;
    object-fit: cover;
}

.table-img {
    width: 100%;
    object-fit: cover;
    height: 150px;
}

</style>
