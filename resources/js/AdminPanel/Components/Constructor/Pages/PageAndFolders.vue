<script setup>
import PagesList from "@/AdminPanel/Components/Constructor/Pages/PagesList.vue";
</script>

<template>
    <ul class="nav nav-tabs justify-content-center">
        <li class="nav-item">
            <a class="nav-link"
               @click="tab=0"
               v-bind:class="{'active':tab===0}"
               aria-current="page" href="javascript:void(0)">По папкам</a>
        </li>
        <li class="nav-item">
            <a class="nav-link"
               @click="tab=1"
               v-bind:class="{'active':tab===1}"
               href="javascript:void(0)">Все</a>
        </li>

    </ul>

    <template v-if="tab===1">
        <PagesList
            :editor="true"
            :current="currentPage||null"
            v-on:callback="pageListCallback"/>
    </template>
</template>
<script>
export default {
    props:["currentPage"],
    data(){
        return {
            tab:0
        }
    }
}
</script>
