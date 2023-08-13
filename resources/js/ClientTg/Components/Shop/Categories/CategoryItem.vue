<template>
    <a :href="'#/products-in-category/'+item.id"
       style="height:80px;"

       class="card card-style mb-3">
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
    props:["item"]
}
</script>
