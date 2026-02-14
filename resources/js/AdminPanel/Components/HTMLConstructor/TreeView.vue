<script setup>
import TreeNode from "@/AdminPanel/Components/HTMLConstructor/TreeNode.vue";
</script>
<template>
    <ul class="tree-list">

        <TreeNode
            v-for="node in blocks"
            :key="node.id"
            :node="node"
            @dblClick="$emit('dblClick')"
            :selected-id="selectedId"
            @select="$emit('select', $event)"
        />
    </ul>
</template>

<script>


export default {
    name: 'TreeView',
    props: {
        blocks: Array,
        selectedId: String
    },

}
</script>

<style scoped>
.tree-list {
    list-style: none;
    padding-left: 0;
    margin: 0;
}
</style>
