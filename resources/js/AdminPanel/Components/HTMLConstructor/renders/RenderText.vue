<script setup>
import Renderer from '@/AdminPanel/Components/HTMLConstructor/Renderer.vue'
</script>
<template>
    <p
        style="word-break: break-all;"


        :class="[
      block.props.fontSize,
      block.props.fontWeight,
      block.props.fontStyle,
      block.props.textAlign,
      block.props.textTransform,
      block.props.lineHeight,

      block.props.marginTop,
      block.props.marginRight,
      block.props.marginBottom,
      block.props.marginLeft
    ]"
        :style="{
        border: isSelected(block.id)?'2px red dashed':null,
        color: block.props.textColor?.startsWith('#') ? block.props.textColor : null,
        letterSpacing: block.props.letterSpacing ? block.props.letterSpacing + 'px' : null
    }"
        @click.stop="$emit('select', block.id)"
    >
        {{ block.props.text }}

        <Renderer
            :is-selected="isSelected"
            :blocks="block.children || []" @select="$emit('select', $event)" />
    </p>
</template>

<script>
export default {
    props: ['block', 'isSelected']
}
</script>
