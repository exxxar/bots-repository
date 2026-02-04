<template>
    <div class="container my-4">
        <h1 class="text-center mb-4">Собираем вкусную пиццу</h1>

        <!-- ОСНОВА -->
        <h5 class="text-center mb-3">
            <span class="border-bottom border-danger pb-1">Основа пиццы</span>
        </h5>

        <div class="row g-2 mb-4">
            <div
                class="col-12 col-md-6"
                v-for="fill in getFilling(8)"
                :key="fill.id"
            >
                <div class="form-check border rounded p-3">
                    <input
                        class="form-check-input"
                        type="radio"
                        name="pizza_base"
                        :id="'base-' + fill.id"
                        :value="fill.id"
                        v-model="pizza_base"
                    />
                    <label class="form-check-label w-100" :for="'base-' + fill.id">
                        {{ fill.title }}
                        <span class="badge bg-secondary ms-2">{{ fill.weight }} г</span>
                        <span class="badge bg-light text-dark ms-1">{{ fill.price }} ₽</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- НАЧИНКИ ПО КАТЕГОРИЯМ -->
        <div v-for="category in categories" :key="category.id" class="mb-4">
            <h5 class="mb-3">
            <span class="border-bottom border-danger pb-1">
              {{ category.title }}
            </span>
            </h5>

            <div class="row g-2">
                <div
                    class="col-12 col-md-6"
                    v-for="fill in getFilling(category.id)"
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
                            />
                            <label class="form-check-label" :for="'fill-' + fill.id">
                                {{ fill.title }}
                                <span class="badge bg-secondary ms-2">{{ fill.weight }} г</span>
                                <span class="badge bg-light text-dark ms-1">{{ fill.price }} ₽</span>
                            </label>
                        </div>

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
        </div>

        <hr class="my-4" />

        <!-- ИТОГ -->
        <h5 class="text-center mb-3">Сколько таких пицц сделать?</h5>

        <div class="d-flex justify-content-center align-items-center gap-3 mb-3">
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

        <button class="btn btn-outline-secondary w-100 p-3 mb-2" @click="clearCalc">
            Очистить
        </button>

        <button class="btn btn-danger w-100 p-3" @click="addToCart">
            В корзину
        </button>

    </div>
</template>
<script>
export default {
    name: 'PizzaCalculator',

    data() {
        return {
            message: '',
            pizza_base: null,
            summary_count: 1,
            fillings: [],

            categories: [
                { id: 1, title: 'Соусы' },
                { id: 2, title: 'Сыры' },
                { id: 3, title: 'Мясо' },
                { id: 4, title: 'Овощи' }
            ],

            ingredients: [
                // основа
                { id: 59, type: 8, title: 'Тонкое тесто', price: 200, weight: 200 },
                { id: 60, type: 8, title: 'Толстое тесто', price: 220, weight: 250 },

                // соусы
                { id: 1, type: 1, title: 'Томатный соус', price: 30, weight: 30 },
                { id: 2, type: 1, title: 'Сливочный соус', price: 40, weight: 30 },

                // сыры
                { id: 3, type: 2, title: 'Моцарелла', price: 80, weight: 50 },
                { id: 4, type: 2, title: 'Чеддер', price: 70, weight: 40 },

                // мясо
                { id: 5, type: 3, title: 'Пепперони', price: 120, weight: 50 },
                { id: 6, type: 3, title: 'Ветчина', price: 100, weight: 50 },

                // овощи
                { id: 7, type: 4, title: 'Грибы', price: 40, weight: 30 },
                { id: 8, type: 4, title: 'Оливки', price: 35, weight: 20 }
            ]
        }
    },

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

    watch: {
        pizza_base(newVal, oldVal) {
            if (oldVal) this.removeItem(oldVal)
            if (newVal) this.addItem(newVal)
        }
    },

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
            const i = this.fillings.indexOf(id)
            if (i !== -1) this.fillings.splice(i, 1)
        },
        incrementSummary() {
            this.summary_count++
        },
        decrementSummary() {
            if (this.summary_count > 1) this.summary_count--
        },
        clearCalc() {
            this.fillings = []
            this.pizza_base = null
            this.summary_count = 1
            this.message = 'Калькулятор очищен'
        },
        addToCart() {
            this.message = 'Пицца добавлена в корзину (тест)'
            console.log('PIZZA ADD TO CART', {
                items: this.fillings,
                count: this.summary_count,
                price: this.summary_price
            })
        }
    }
}
</script>
<style scoped>
.card {
    border-radius: 1rem;
}
.form-check-label {
    cursor: pointer;
}
</style>
