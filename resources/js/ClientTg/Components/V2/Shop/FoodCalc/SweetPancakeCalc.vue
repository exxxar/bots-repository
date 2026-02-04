<template>
    <div class="container py-4">

        <h1 class="text-center mb-4">🥞 Сладкие блинчики</h1>

        <!-- ОСНОВА -->
        <h4 class="text-center mb-3">
            <span class="border-bottom border-danger pb-1">Основа блинчика</span>
        </h4>

        <div class="row g-1 mb-4">
            <div
                v-for="item in baseFillings"
                :key="item.id"
                class="col-md-6"
            >
                <div class="card p-3">
                    <div class="form-check">
                        <input
                            class="form-check-input"
                            type="checkbox"
                            :id="'base-' + item.id"
                            :value="item.id"
                            v-model="fillings"
                            :disabled="item.disabled"
                        />
                        <label class="form-check-label w-100" :for="'base-' + item.id">
                            <div class="d-flex justify-content-between">
                                <strong>{{ item.title }}</strong>
                                <span>{{ item.price }} ₽</span>
                            </div>
                            <small class="text-muted">{{ item.weight }} г</small>
                        </label>
                    </div>

                    <div v-if="count(item.id) > 0" class="d-flex align-items-center gap-2 mt-2">
                        <button class="btn btn-outline-secondary btn-sm" @click="remove(item.id)">−</button>
                        <span>{{ count(item.id) }}</span>
                        <button class="btn btn-outline-secondary btn-sm" @click="add(item.id)">+</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- ДОБАВКИ -->
        <h4 class="text-center mb-3">
            <span class="border-bottom border-danger pb-1">Сладкие добавки</span>
        </h4>

        <div class="row g-3 mb-4">
            <div
                v-for="item in sweetFillings"
                :key="item.id"
                class="col-md-6"
            >
                <div class="card p-3">
                    <div class="form-check">
                        <input
                            class="form-check-input"
                            type="checkbox"
                            :id="'sweet-' + item.id"
                            :value="item.id"
                            v-model="fillings"
                            :disabled="item.disabled"
                        />
                        <label class="form-check-label w-100" :for="'sweet-' + item.id">
                            <div class="d-flex justify-content-between">
                                <strong>{{ item.title }}</strong>
                                <span>{{ item.price }} ₽</span>
                            </div>
                            <small class="text-muted">{{ item.weight }} г</small>
                        </label>
                    </div>

                    <div v-if="count(item.id) > 0" class="d-flex align-items-center gap-2 mt-2">
                        <button class="btn btn-outline-secondary btn-sm" @click="remove(item.id)">−</button>
                        <span>{{ count(item.id) }}</span>
                        <button class="btn btn-outline-secondary btn-sm" @click="add(item.id)">+</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- ИТОГО -->
        <div class="card p-4 mx-auto" style="max-width: 420px">
            <h5 class="text-center mb-3">Сколько блинчиков?</h5>

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
    name: 'SweetPancakeCalculator',

    data() {
        return {
            fillings: [],
            summaryCount: 0,

            items: [
                { id: 501, type: 'base', title: 'Классический блин', weight: 100, price: 80 },
                { id: 502, type: 'base', title: 'Шоколадный блин', weight: 110, price: 100 },

                { id: 601, type: 'sweet', title: 'Клубника', weight: 30, price: 40 },
                { id: 602, type: 'sweet', title: 'Банан', weight: 40, price: 35 },
                { id: 603, type: 'sweet', title: 'Шоколад', weight: 20, price: 50 }
            ]
        }
    },

    computed: {
        baseFillings() {
            return this.items.filter(i => i.type === 'base')
        },
        sweetFillings() {
            return this.items.filter(i => i.type === 'sweet')
        },
        totalWeight() {
            return this.summaryCount * this.fillings.reduce((sum, id) => {
                const item = this.items.find(i => i.id === id)
                return sum + (item ? item.weight : 0)
            }, 0)
        },
        totalPrice() {
            return this.summaryCount * this.fillings.reduce((sum, id) => {
                const item = this.items.find(i => i.id === id)
                return sum + (item ? item.price : 0)
            }, 0)
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
            const index = this.fillings.indexOf(id)
            if (index !== -1) this.fillings.splice(index, 1)
        },
        clear() {
            this.fillings = []
            this.summaryCount = 0
        }
    }
}
</script>
