<template>

    <button
        type="button"
        @click="openCalcSizeModal"
        class="btn btn-light w-100 p-3 my-2">
        <span v-if="emptyCalc"><i class="fa-solid fa-dolly text-primary"></i> Размер посылки</span>
        <span v-else>
            {{ formSize.width || 0 }}x{{ formSize.height || 0 }}x{{
                formSize.length || 0
            }} см, до {{ formSize.weight || 0 }}кг
        </span>
    </button>

    <!-- Modal -->
    <div class="modal fade" id="calc-size-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <form class="modal-content" v-on:submit.prevent="submitSizeForm">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Расчёт размеров</h1>
                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">


                    <div class="divider my-3">Габариты</div>
                    <div class="form-floating mb-3">
                        <input type="number"
                               min="0"
                               v-model="formSize.width"
                               required
                               class="form-control" id="floatingInput" placeholder="name@example.com">
                        <label for="floatingInput">Ширина, см</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="number"
                               min="0"
                               v-model="formSize.height"
                               required
                               class="form-control" id="floatingInput" placeholder="name@example.com">
                        <label for="floatingInput">Высота, см</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="number"
                               min="0"
                               v-model="formSize.length"
                               required
                               class="form-control" id="floatingInput" placeholder="name@example.com">
                        <label for="floatingInput">Длина, см</label>
                    </div>
                    <div class="form-floating mb-3"
                         v-if="!shopMode">
                        <input type="number"
                               min="0"

                               v-model="formSize.weight"
                               required
                               class="form-control" id="floatingInput" placeholder="name@example.com">
                        <label for="floatingInput">Вес, кг</label>
                    </div>

                    <div class="alert alert-light" v-else>
                        {{ summaryWeight }}, кг
                    </div>

                    <template v-if="shopMode">
                        <div class="divider my-3">Товары в упаковке</div>

                        <div class="row row-cols-4 mb-3">
                            <template :key="index"
                                      v-for="(n, index) in formSize.items.length">
                                <div class="col mb-2">
                                    <button
                                        type="button"
                                        v-bind:class="{'btn-primary': selectedIndex===index,
                                        'btn-light': selectedIndex!==index}"
                                        @click="selectProduct(index)"
                                        class="btn mr-2 w-100">{{ index + 1 }}
                                    </button>
                                </div>
                            </template>

                            <div class="col mb-2">
                                <button
                                    type="button"
                                    @click="addProduct"
                                    class="btn btn-primary mr-2 w-100">+
                                </button>
                            </div>
                        </div>


                        <template v-if="loaded">
                            <div class="form-floating mb-3">
                                <input type="text"
                                       v-model="formSize.items[selectedIndex].name"
                                       required
                                       class="form-control" id="floatingInput" placeholder="name@example.com">
                                <label for="floatingInput">Название</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="text"
                                       maxlength="255"
                                       v-model="formSize.items[selectedIndex].ware_key"
                                       required
                                       class="form-control" id="floatingInput" placeholder="name@example.com">
                                <label for="floatingInput">Артикул товара</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="number"
                                       min="0"
                                       v-model="formSize.items[selectedIndex].weight"
                                       required
                                       class="form-control" id="floatingInput" placeholder="name@example.com">
                                <label for="floatingInput">Вес, кг</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="number"
                                       min="0"
                                       v-model="formSize.items[selectedIndex].price"
                                       required
                                       class="form-control" id="floatingInput" placeholder="name@example.com">
                                <label for="floatingInput">Объявленная стоимость товара, руб</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="number"
                                       min="0"
                                       v-model="formSize.items[selectedIndex].amount"
                                       required
                                       class="form-control" id="floatingInput" placeholder="name@example.com">
                                <label for="floatingInput">Кол-во товара, ед.</label>
                            </div>
                            <a href="javascript:void(0)"
                               v-if="selectedIndex>0"
                               @click="removeProduct(selectedIndex)"
                               class="btn btn-link text-danger w-100 text-center">Удалить товар</a>
                        </template>


                    </template>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary w-100">
                        <span v-if="emptyCalc"><i class="fa-solid fa-dolly"></i> Добавить</span>
                        <span v-else>
                            {{ formSize.width || 0 }}x{{ formSize.height || 0 }}x{{
                                formSize.length || 0
                            }} см, до {{ formSize.weight || summaryWeight || 0 }}кг
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>

</template>

<script>
export default {
    props:["shopMode"],
    data() {
        return {
            selectedIndex: 0,
            loaded: true,
            calcSizeModal: null,
            formSize: {
                width: null,
                height: null,
                weight: null,
                length: null,
                items: [
                    {
                        name: '',
                        ware_key: '',
                        price: 0,
                        amount: 1,
                        weight: 0,
                    }
                ],

            }
        }
    },
    computed: {
        summaryWeight() {
            let sum = 0
            this.formSize.items.forEach(item => sum += item.weight)
            return sum
        },
        emptyCalc() {
            return this.formSize.width == null &&
                this.formSize.height == null &&
                this.formSize.length == null &&
                this.formSize.weight == null
        }
    },
    mounted() {
        this.calcSizeModal = new bootstrap.Modal(document.getElementById('calc-size-modal'), {})
    },
    methods: {
        selectProduct(index) {
            this.selectedIndex = index
            this.loaded = false
            this.$nextTick(() => {
                this.loaded = true
            })
        },
        openCalcSizeModal() {
            this.calcSizeModal.show();
        },
        removeProduct(index) {
            this.formSize.items.splice(index, 1)
            this.selectedIndex = 0
        },
        addProduct() {
            this.formSize.items.push({
                name: '',
                ware_key: '',
                price: 0,
                amount: 1,
                weight: 0,
            })

            this.selectProduct(this.formSize.items.length - 1)
        },
        submitSizeForm() {
            if (this.shopMode)
                this.formSize.weight = this.summaryWeight
            this.$emit("callback", this.formSize)
            this.calcSizeModal.toggle();

            this.$nextTick(() => {
                this.formSize.weight = null
                this.formSize.height = null
                this.formSize.width = null
                this.formSize.length = null
            })
        }
    }
}
</script>
