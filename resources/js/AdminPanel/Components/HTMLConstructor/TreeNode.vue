<template>
    <li>
        <div
            class="tree-node"
            :class="{ selected: node.id === selectedId }"
            @click.stop="$emit('select', node.id)"
        >
      <span v-if="hasChildren"

            @click.stop="toggle" class="toggle">
        {{ expanded ? '▼' : '▶' }}
      </span>

            <span class="node-label"     @dblclick.stop="dbClick">{{ node.type }}</span>
        </div>

        <ul v-if="expanded && hasChildren" class="children">
            <TreeNode
                v-for="child in node.children"
                :key="child.id"
                :node="child"
                @dblClick="$emit('dblClick')"
                :selected-id="selectedId"
                @select="$emit('select', $event)"
            />
        </ul>
    </li>
</template>

<script>
export default {
    name: 'TreeNode',
    props: {
        node: Object,
        selectedId: String
    },
    data() {
        return {
            expanded: true
        }
    },
    computed: {
        hasChildren() {
            return Array.isArray(this.node.children) && this.node.children.length > 0
        }
    },
    methods: {
        toggle() {
            this.expanded = !this.expanded
        },
        dbClick(){
            this.$emit("dblClick")
        }
    }
}
</script>

<style scoped>
.tree-node {
    padding: 4px 6px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 4px;
}

.tree-node.selected {
    background: #e7f1ff;
    border-radius: 4px;
}

.toggle {
    cursor: pointer;
    font-size: 10px;
    width: 12px;
    display: inline-block;
}

.children {
    list-style: none;
    padding-left: 16px;
}
</style>
