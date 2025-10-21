<script setup>
import PartnerList from "@/ClientTg/Components/V2/Shop/Partners/PartnerList.vue";
import PartnerTagList from "@/ClientTg/Components/V2/Shop/Partners/PartnerTagList.vue";
</script>
<template>
    <div class="container">
        <PartnerList
            v-on:select="selectPartner"></PartnerList>
        <PartnerTagList></PartnerTagList>
    </div>

</template>
<script>
export default {
        methods:{
            selectPartner(partner){
                this.$emit("select", partner)
            }
        }
}
</script>
