<template>
    <a href="javascript:void(0)"
       @click="select"
       style="height:80px;"
       v-bind:class="{'border-success':selected}"
       class="card card-style m-0 mb-2">
        <div class="card-center">
            <h5 class="pl-3">{{item.title || 'Не указано'}}</h5>
            <p class="pl-3 mt-n2 font-12 color-highlight mb-0"><span class="font-weight-bold">{{item.count || 0}}</span> ед. товаров в категории</p>
        </div>
        <div class="card-center opacity-30">
            <i class="fa fa-arrow-right opacity-50 float-right color-theme pr-3"></i>
        </div>
    </a>
</template>
<script>
export default {
    props:["item", "selected"],
    methods:{
        select(){
            return this.$emit("select", this.item)
        }
    }
}
</script>
