<template>
    <p class="mb-0">
        <i class="fa fa-star"
           v-bind:class="{'text-primary':rating>=1,'text-white':rating<1}"
        ></i>
        <i class="fa fa-star"
           v-bind:class="{'text-primary':rating>=2,'text-white':rating<2}"
        ></i>
        <i class="fa fa-star"
           v-bind:class="{'text-primary':rating>=3,'text-white':rating<3}"
        ></i>
        <i class="fa fa-star"
           v-bind:class="{'text-primary':rating>=4,'text-white':rating<4}"
        ></i>
        <i class="fa fa-star"
           v-bind:class="{'text-primary':rating>=5,'text-white':rating<5}"
        ></i>
    </p>
</template>
<script>
export default {
    props: {
        rating: {
            type: Number,
            default: 0
        }
    }
}
</script>
