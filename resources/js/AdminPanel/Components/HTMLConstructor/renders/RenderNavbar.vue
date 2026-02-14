<script setup>
import Renderer from '@/AdminPanel/Components/HTMLConstructor/Renderer.vue'
</script>
<template>
    <nav
        class="navbar mb-2"

        :style="[
                     isSelected(block.id)?'border:2px red dashed':'',
                    ]"

        :class="[
      'navbar-' + block.props.variant,
      'bg-' + block.props.bg
    ]"
        @click.stop="select"
    >
        <div class="container-fluid">
            <span class="navbar-brand">{{ block.props.brand }}</span>
        </div>

        <Renderer :is-selected="isSelected" :blocks="block.children" @select="$emit('select', $event)" />

    </nav>
</template>

<script>
export default {
    name: 'RenderNavbar',
    props: ['block','isSelected'],
    methods: {
        select() {
            this.$emit('select', this.block.id)
        }
    }
}
</script>
