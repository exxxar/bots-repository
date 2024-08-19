<template>
    <form v-on:submit.prevent="addCategory">
        <div class="form-floating mb-2">
            <input type="text"
                   v-model="categoryForm.title"
                   class="form-control" id="floatingInput" placeholder="name@example.com" required>
            <label for="floatingInput">Название категории</label>
        </div>

        <div class="form-floating mb-3">
            <input type="number"
                   v-model="categoryForm.order_position"
                   class="form-control" id="floatingInput" placeholder="name@example.com">
            <label for="floatingInput">Позиция в выдаче</label>
        </div>

        <nav class="navbar navbar-expand-sm fixed-bottom p-3 bg-transparent border-0"
             style="border-radius:10px 10px 0px 0px;">
            <button
                class="btn btn-primary w-100 p-3"
                type="submit">
                <span v-if="categoryForm.id">Обновить категорию</span>
                <span v-else>Добавить категорию</span>
            </button>
        </nav>

    </form>

</template>
<script>
export default {
    props: ["item"],
    data() {
        return {
            categoryForm: {
                id: null,
                title: null,
                order_position: 0,
            },
        }
    },
    mounted() {
        if (this.item) {
            this.$nextTick(() => {
                this.categoryForm.id = this.item.id || null
                this.categoryForm.title = this.item.title || null
                this.categoryForm.order_position = this.item.order_position || 0
            })
        }
    },
    methods: {
        addCategory() {
            this.$store.dispatch("addProductCategory", {
                category: this.categoryForm
            }).then(() => {
                this.$notify({
                    title: "Категории товаров",
                    text: "Данные успешно сохранены!",
                    type: 'success'
                });

                this.$emit("callback")
                document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(item => item.click())

            }).catch(() => {
                this.$notify({
                    title: "Категории товаров",
                    text: "Ошибка сохранения категории!",
                    type: 'error'
                });

                document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(item => item.click())
            })
        },
    }
}
</script>
