<template>
    <form v-on:submit.prevent="addCategory">
        <div class="form-floating mb-2">
            <input type="text"
                   v-model="categoryForm.label"
                   class="form-control" id="floatingInput" placeholder="name@example.com" required>
            <label for="floatingInput">Название категории</label>
        </div>

        <div class="form-floating mb-3">
            <input type="number"
                   v-model="categoryForm.order_position"
                   class="form-control" id="floatingInput" placeholder="name@example.com">
            <label for="floatingInput">Позиция в выдаче</label>
        </div>

        <button
            class="btn btn-primary w-100"
            type="submit">Добавить категорию
        </button>
    </form>

</template>
<script>
export default {
    data(){
        return {
            categoryForm: {
                label: null,
                order_position:0,
            },
        }
    },
    methods:{
        addCategory() {
            this.$store.dispatch("addProductCategory", {
                category: this.categoryForm
            }).then(() => {

            })
        },
    }
}
</script>
