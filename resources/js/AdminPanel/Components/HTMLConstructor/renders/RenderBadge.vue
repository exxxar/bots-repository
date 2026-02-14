<template>
  <span
      :style="[
                     isSelected(block.id)?'border:2px red dashed':'',
                    ]"

      :class="[
      'badge',
      'bg-' + block.props.variant,
      block.props.pill ? 'rounded-pill' : '',
        block.props.marginTop,
                block.props.marginRight,
                block.props.marginBottom,
                block.props.marginLeft,
                 block.props.paddingTop,
                block.props.paddingRight,
                block.props.paddingBottom,
                block.props.paddingLeft,
    ]"
      @click.stop="$emit('select', block.id)"
  >
    {{ block.props.text }}
  </span>
</template>

<script>
export default {
    props: ['block', 'isSelected']
}
</script>
