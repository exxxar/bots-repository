<template>
    <div class="selected-tables-list mt-2" v-if="sortedSelectedTables.length>0">
        <h6 class="fw-bold">Выбранные столики - {{sortedSelectedTables.length}} шт.</h6>
        <div class="scroll-container">
            <div class="badge"
                v-for="(table, index) in sortedSelectedTables"
                :key="index"
            >

            {{ table.description }} ({{ table.seats }} места)
                <span @click="removeItem(table.id)"> <i class="fa-solid fa-xmark"></i></span>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "SelectedTablesList",
    props: {
        sortedSelectedTables: {
            type: Array,
            required: true,
        },
    },
    methods:{
        removeItem(id){
            this.$emit("remove", id)
        }
    }
};
</script>

<style scoped>
.selected-tables-list {
    padding: 0px;
}

.scroll-container {
    display: flex;
    overflow-x: auto;
    gap: 12px;
    padding: 10px 0;
}

.table-card {
    flex: 0 0 auto;
    text-align: center;
    border: 1px solid #eee;
    border-radius: 8px;
    padding: 8px;
    background: #fff;
    min-width: 120px;
}

.table-img {
    width: 80px;
    height: auto;
    margin-bottom: 6px;
}

.badge {
    display: inline-block;
    background-color: #007bff;
    color: #fff;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.85rem;
    font-weight: 600;
}
</style>
