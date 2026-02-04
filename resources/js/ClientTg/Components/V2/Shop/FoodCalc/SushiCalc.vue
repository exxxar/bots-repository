<template>
    <div class="container my-4">
        <h1 class="text-center mb-4">Собираем вкусный ролл</h1>

        <!-- ОСНОВА -->
        <h5 class="text-center mb-3">
            <span class="border-bottom border-danger pb-1">Основа ролла</span>
        </h5>

        <div class="row g-2 mb-4">
            <div
                class="col-12 col-md-6"
                v-for="fill in getFilling(11)"
                :key="fill.id"
            >
                <div class="form-check border rounded p-3 h-100">
                    <input
                        class="form-check-input"
                        type="checkbox"
                        :id="'base-' + fill.id"
                        :value="fill.id"
                        v-model="fillings"
                    />
                    <label class="form-check-label w-100" :for="'base-' + fill.id">
                        {{ fill.title }}
                        <span class="badge bg-secondary ms-2">{{ fill.weight }} г</span>
                        <span class="badge bg-light text-dark ms-1">{{ fill.price }} ₽</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- ПОКРЫТИЕ -->
        <h5 class="text-center mb-3">
            <span class="border-bottom border-danger pb-1">Покрытие ролла</span>
        </h5>

        <div class="row g-2 mb-4">
            <div
                class="col-12 col-md-6"
                v-for="fill in getFilling(9)"
                :key="fill.id"
            >
                <div class="form-check border rounded p-3 h-100">
                    <input
                        class="form-check-input"
                        type="radio"
                        name="roll_coating"
                        :id="'coating-' + fill.id"
                        :value="fill.id"
                        v-model="roll_coating"
                    />
                    <label class="form-check-label w-100" :for="'coating-' + fill.id">
                        {{ fill.title }}
                        <span class="badge bg-secondary ms-2">{{ fill.weight }} г</span>
                        <span class="badge bg-light text-dark ms-1">{{ fill.price }} ₽</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- НАЧИНКА -->
        <h5 class="text-center mb-3">
          <span class="border-bottom border-danger pb-1">
            Начинка (до 6 ингредиентов)
          </span>
        </h5>

        <div class="row g-2">
            <div
                class="col-12 col-md-6"
                v-for="fill in getFilling(10)"
                :key="fill.id"
            >



                <div
                    class="border rounded p-3 d-flex justify-content-center align-items-center flex-column"
                >
                    <div class="form-check">
                        <input
                            class="form-check-input"
                            type="checkbox"
                            :id="'fill-' + fill.id"
                            :value="fill.id"
                            v-model="fillings"
                            :disabled="fillings.length >= 6 && !fillings.includes(fill.id)"
                        />
                        <label class="form-check-label" :for="'fill-' + fill.id">
                            {{ fill.title }}
                            <span class="badge bg-secondary ms-2">{{ fill.weight }} г</span>
                            <span class="badge bg-light text-dark ms-1">{{ fill.price }} ₽</span>
                        </label>
                    </div>

                    <!-- СЧЁТЧИК -->
                    <div v-if="hasManyItems(fill.id) > 0" class="btn-group btn-group-sm mt-2">
                        <button class="btn btn-outline-danger" @click="removeItem(fill.id)">−</button>
                        <button class="btn btn-outline-secondary disabled">
                            {{ hasManyItems(fill.id) }}
                        </button>
                        <button class="btn btn-outline-danger" @click="addItem(fill.id)">+</button>
                    </div>
                </div>
            </div>
        </div>

        <hr class="my-4" />

        <!-- ИТОГ -->
        <div class="text-center mb-3">
            <small class="text-muted">
                Цена указана за одну порцию (8 роллов)
            </small>
        </div>

        <div class="d-flex justify-content-center align-items-center gap-3 mb-3 ">
            <button class="btn btn-outline-danger" @click="decrementSummary">−</button>
            <strong>{{ summary_count }}</strong>
            <button class="btn btn-outline-danger" @click="incrementSummary">+</button>
        </div>

        <ul class="list-group list-group-flush mb-3">
            <li class="list-group-item d-flex justify-content-between">
                <span>Вес</span>
                <strong>{{ summary_weight }} г</strong>
            </li>
            <li class="list-group-item d-flex justify-content-between">
                <span>Цена</span>
                <strong>{{ summary_price }} ₽</strong>
            </li>
        </ul>

        <div v-if="message" class="alert alert-success text-center">
            {{ message }}
        </div>

        <button class="btn btn-outline-secondary w-100 mb-2 p-3" @click="clearCalc">
            Очистить
        </button>

        <button
            class="btn btn-danger w-100 p-3"
            @click="addToCart"
            :disabled="fillings.length === 0"
        >
            В корзину
        </button>



    </div>
</template>

<script>
export default {
    name: 'SushiRollCalculator',

    data() {
        return {
            /* =====================
               STATE
            ===================== */
            message: '',
            roll_coating: null,
            summary_count: 1,

            fillings: [],

            /* =====================
               ТЕСТОВЫЕ ДАННЫЕ
            ===================== */
            ingredients: [
                // покрытие (type 9)
                { id: 77, type: 9, title: 'Кунжут', price: 30, weight: 10, disabled: false },
                { id: 78, type: 9, title: 'Икра масаго', price: 60, weight: 15, disabled: false },

                // начинка (type 10)
                { id: 1, type: 10, title: 'Лосось', price: 120, weight: 40, disabled: false },
                { id: 2, type: 10, title: 'Угорь', price: 140, weight: 40, disabled: false },
                { id: 3, type: 10, title: 'Сыр креметта', price: 60, weight: 30, disabled: false },
                { id: 4, type: 10, title: 'Огурец', price: 20, weight: 20, disabled: false },

                // основа (type 11)
                { id: 11, type: 11, title: 'Рис', price: 50, weight: 100, disabled: false },
                { id: 12, type: 11, title: 'Нори', price: 20, weight: 5, disabled: false },
            ]
        }
    },

    /* =====================
       COMPUTED
    ===================== */
    computed: {
        price() {
            return this.fillings.reduce((sum, id) => {
                const item = this.ingredients.find(i => i.id === id)
                return item ? sum + item.price : sum
            }, 0)
        },

        weight() {
            return this.fillings.reduce((sum, id) => {
                const item = this.ingredients.find(i => i.id === id)
                return item ? sum + item.weight : sum
            }, 0)
        },

        summary_price() {
            return this.price * this.summary_count
        },

        summary_weight() {
            return this.weight * this.summary_count
        }
    },

    /* =====================
       WATCH
    ===================== */
    watch: {
        roll_coating(newVal, oldVal) {
            if (oldVal) this.removeItem(oldVal)
            if (newVal) this.addItem(newVal)
        }
    },

    /* =====================
       METHODS
    ===================== */
    methods: {
        getFilling(type) {
            return this.ingredients.filter(i => i.type === type)
        },

        hasManyItems(id) {
            return this.fillings.filter(i => i === id).length
        },

        addItem(id) {
            this.fillings.push(id)
        },

        removeItem(id) {
            const index = this.fillings.indexOf(id)
            if (index !== -1) this.fillings.splice(index, 1)
        },

        incrementSummary() {
            this.summary_count++
        },

        decrementSummary() {
            if (this.summary_count > 1) this.summary_count--
        },

        clearCalc() {
            this.fillings = []
            this.roll_coating = null
            this.summary_count = 1
            this.message = 'Калькулятор очищен'
        },

        addToCart() {
            this.message = 'Ролл добавлен в корзину (тест)'
            console.log('ADD TO CART (mock)', {
                items: this.fillings,
                count: this.summary_count,
                price: this.summary_price,
                weight: this.summary_weight
            })
        }
    }
}
</script>
<style scoped>
.card {
    border-radius: 1rem;
}

.badge {
    font-weight: 500;
}

.form-check-label {
    cursor: pointer;
}

.list-group-item {
    font-size: 0.95rem;
}
</style>
