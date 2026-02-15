<template>
    <div    @click.stop="$emit('select', block.id)">
        test
        <slot name="header"></slot>
        <slot name="default"></slot>
        <slot name="footer"></slot>
    </div>

</template>
<script>
export default {
    props: ['block', 'isSelected']
}
</script>
