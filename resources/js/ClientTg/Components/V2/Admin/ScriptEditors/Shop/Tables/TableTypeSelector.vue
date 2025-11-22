<template>
    <div class="table-type-selector">
        <div class="scroll-container">
            <button
                v-for="type in tableTypes"
                :key="type.id"
                type="button"
                class="btn"
                v-bind:class="{'btn-primary': selectedTypes.indexOf(type.id) !== -1  , 'btn-light' : selectedTypes.indexOf(type.id) === -1}"
                @click="selectType(type.id)"
            >
                {{ type.label }}
            </button>
        </div>
    </div>
</template>

<script>
export default {
    name: "TableTypeSelector",
    data() {
        return {
            selectedTypes: [],
            tableTypes: [
                {id: "all", label: "Все"},
                {id: 2, label: "2 места"},
                {id: 3, label: "3 места"},
                {id: 4, label: "4 места"},
                {id: 5, label: "5 мест"},
                {id: 6, label: "6 мест"},
                {id: 8, label: "8 мест"},
                {id: 10, label: "10 мест"},
            ],
        };
    },
    methods: {
        selectType(id) {

            if (id === 'all') {
                this.selectedTypes = []
                this.$emit("selection", this.selectedTypes)
                return
            }
            let index = this.selectedTypes.findIndex(i => i === id)

            if (index !== -1)
                this.selectedTypes.splice(index, 1)
            else
                this.selectedTypes.push(id)

            this.$emit("selection", this.selectedTypes)
        },
    },
};
</script>

<style scoped>
.table-type-selector {
    padding: 0px;
}

.scroll-container {
    display: flex;
    overflow-x: auto;
    gap: 12px;
    padding-bottom: 8px;
}

.table-button {
    flex: 0 0 auto;
    padding: 10px 16px;
    border: 2px solid #ccc;
    background: white;
    border-radius: 8px;
    cursor: pointer;
    white-space: nowrap;
    transition: all 0.2s ease;
}

.table-button.selected {
    border-color: #007bff;
    background: #e6f0ff;
    font-weight: bold;
}
</style>
