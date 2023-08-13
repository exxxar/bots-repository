<script setup>
import CategoryList from "@/ClientTg/Components/Shop/Categories/CategoryList.vue";
</script>

<template>


    <div class="card card-style bg-25 mb-3" data-card-height="200" style="height: 200px;">
        <div class="card-center">
            <p class="text-center mb-2">
                <i class="fa-brands fa-shopify color-red2-dark fa-4x"></i>
            </p>
            <h2 class="color-white font-700 text-center mb-0">Категории товара</h2>
            <p class="color-white text-center opacity-60 mt-n1 mb-3">Список доступных в магазине категорий</p>
        </div>
        <div class="card-overlay bg-black opacity-80"></div>
    </div>
    <CategoryList size="100"/>

</template>
<script>
export default {

}
</script>
