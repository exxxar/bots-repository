
<template>
    <div class="container py-4">

        <h1 class="text-center mb-4">🧇 Гонконгские вафли</h1>

        <!-- ОСНОВА -->
        <h4 class="text-center mb-3">
            <span class="border-bottom border-danger pb-1">Выбираем основу</span>
        </h4>

        <div class="row g-3 mb-4">
            <div
                v-for="item in baseItems"
                :key="item.id"
                class="col-md-4 col-sm-6"
            >
                <div class="card p-3 h-100 text-center">
                    <h5>{{ item.title }}</h5>
                    <small class="text-muted">{{ item.description }}</small>

                    <div class="my-2">
                        <span class="badge bg-secondary me-1">{{ item.weight }} г</span>
                        <span class="badge bg-primary">{{ item.price }} ₽</span>
                    </div>

                    <div class="form-check d-flex justify-content-center mt-auto">
                        <input
                            class="form-check-input"
                            type="radio"
                            name="base"
                            :value="item.id"
                            v-model="baseId"
                        />
                    </div>
                </div>
            </div>
        </div>

        <!-- НАЧИНКА / ФРУКТЫ / СОУСЫ -->
        <div v-for="section in sections" :key="section.key" class="mb-4">

            <h4 class="text-center mb-3">
                <span class="border-bottom border-danger pb-1">{{ section.title }}</span>
            </h4>

            <div class="row g-3">
                <div
                    v-for="item in section.items"
                    :key="item.id"
                    class="col-md-6"
                >
                    <div class="card p-3 h-100">
                        <div class="form-check">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                :value="item.id"
                                v-model="fillings"
                            />
                            <label class="form-check-label w-100">
                                <div class="d-flex justify-content-between">
                                    <strong>{{ item.title }}</strong>
                                    <span>{{ item.price }} ₽</span>
                                </div>
                                <small class="text-muted">{{ item.weight }} г</small>
                            </label>
                        </div>

                        <div
                            v-if="count(item.id) > 0"
                            class="d-flex align-items-center gap-2 mt-2"
                        >
                            <button class="btn btn-outline-secondary btn-sm" @click="remove(item.id)">−</button>
                            <span>{{ count(item.id) }}</span>
                            <button class="btn btn-outline-secondary btn-sm" @click="add(item.id)">+</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ИТОГО -->
        <div class="card p-4 mx-auto" style="max-width: 420px">
            <h5 class="text-center mb-3">Сколько вафель?</h5>

            <div class="d-flex justify-content-center align-items-center gap-3 mb-3">
                <button class="btn btn-warning" @click="summaryCount--" :disabled="summaryCount === 0">−</button>
                <strong>{{ summaryCount }}</strong>
                <button class="btn btn-warning" @click="summaryCount++">+</button>
            </div>

            <ul class="list-unstyled text-center mb-3">
                <li>Вес: <strong>{{ totalWeight }} г</strong></li>
                <li>Цена: <strong>{{ totalPrice }} ₽</strong></li>
            </ul>

            <div class="d-flex gap-2">
                <button class="btn btn-success w-100" :disabled="summaryCount === 0">
                    В корзину
                </button>
                <button class="btn btn-outline-danger w-100" @click="clear">
                    Очистить
                </button>
            </div>
        </div>

    </div>
</template>

<script>
export default {
    name: 'HongKongWaffleCalculator',

    data() {
        return {
            baseId: null,
            fillings: [],
            summaryCount: 0,

            baseItems: [
                { id: 1501, title: 'Классическая', description: 'Ванильное тесто', weight: 120, price: 150 },
                { id: 1502, title: 'Шоколадная', description: 'С какао', weight: 130, price: 170 },
                { id: 1503, title: 'Матча', description: 'Зелёный чай', weight: 125, price: 180 }
            ],

            sections: [
                {
                    key: 'filling',
                    title: 'Начинка',
                    items: [
                        { id: 1601, title: 'Маршмеллоу', weight: 30, price: 40 },
                        { id: 1602, title: 'Шоколадные капли', weight: 20, price: 50 }
                    ]
                },
                {
                    key: 'fruits',
                    title: 'Фрукты',
                    items: [
                        { id: 1701, title: 'Клубника', weight: 40, price: 45 },
                        { id: 1702, title: 'Банан', weight: 50, price: 40 }
                    ]
                },
                {
                    key: 'sauce',
                    title: 'Топпинги и соусы',
                    items: [
                        { id: 1801, title: 'Шоколадный соус', weight: 25, price: 35 },
                        { id: 1802, title: 'Карамель', weight: 25, price: 35 }
                    ]
                }
            ]
        }
    },

    computed: {
        allItems() {
            return [
                ...this.baseItems,
                ...this.sections.flatMap(s => s.items)
            ]
        },
        totalWeight() {
            const base = this.baseId
                ? this.baseItems.find(i => i.id === this.baseId).weight
                : 0

            const extra = this.fillings.reduce((sum, id) => {
                const item = this.allItems.find(i => i.id === id)
                return sum + (item ? item.weight : 0)
            }, 0)

            return (base + extra) * this.summaryCount
        },
        totalPrice() {
            const base = this.baseId
                ? this.baseItems.find(i => i.id === this.baseId).price
                : 0

            const extra = this.fillings.reduce((sum, id) => {
                const item = this.allItems.find(i => i.id === id)
                return sum + (item ? item.price : 0)
            }, 0)

            return (base + extra) * this.summaryCount
        }
    },

    methods: {
        count(id) {
            return this.fillings.filter(f => f === id).length
        },
        add(id) {
            this.fillings.push(id)
        },
        remove(id) {
            const i = this.fillings.indexOf(id)
            if (i !== -1) this.fillings.splice(i, 1)
        },
        clear() {
            this.baseId = null
            this.fillings = []
            this.summaryCount = 0
        }
    }
}
</script>
