<script setup>
import Renderer from '@/AdminPanel/Components/HTMLConstructor/Renderer.vue'
</script>
<template>
    <p
        :style="[
                     isSelected(block.id)?'border:2px red dashed':'',
                    ]"

        :class="[
      'alert',
      'alert-' + block.props.variant,
      block.props.dismissible ? 'alert-dismissible fade show' : '',
                block.props.marginTop,
                block.props.marginRight,
                block.props.marginBottom,
                block.props.marginLeft,
                 block.props.paddingTop,
                block.props.paddingRight,
                block.props.paddingBottom,
                block.props.paddingLeft,
    ]"
        role="alert"
        @click.stop="$emit('select', block.id)"
    >
        {{ block.props.text }}

        <Renderer
            :is-selected="isSelected"
            :blocks="block.children || []" @select="$emit('select', $event)" />

        <button
            v-if="block.props.dismissible"
            type="button"
            class="btn-close"
            data-bs-dismiss="alert"
            aria-label="Close"
        ></button>
    </p>
</template>

<script>
export default {
    props: ['block', 'isSelected']
}
</script>
