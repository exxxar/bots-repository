<script setup>
import Renderer from '../Renderer.vue'
</script>
<template>
    <div
        :class="[
                block.props.block ? 'w-100' : '',
                block.props.marginTop,
                block.props.marginRight,
                block.props.marginBottom,
                block.props.marginLeft,
                 block.props.paddingTop,
                block.props.paddingRight,
                block.props.paddingBottom,
                block.props.paddingLeft,
]"

        :style="[
                     isSelected(block.id)?'border:2px red dashed':'',
                    ]"

        @click.stop="$emit('select', block.id)"
    >

        <!-- Заголовок -->
        <button
            class="btn btn-outline-primary w-100 text-start"
            type="button"
            :data-bs-toggle="'collapse'"
            :data-bs-target="'#collapse-' + block.id"
        >
            {{ block.props.title }}
        </button>

        <!-- Контент -->
        <div
            :id="'collapse-' + block.id"
            class="collapse"
            :class="{ show: block.props.show }"
        >
            <div class="card card-body mt-2">

                <!-- Вложенные компоненты -->
                <Renderer
                    :is-selected="isSelected"
                    :blocks="block.children"
                    @select="$emit('select', $event)"
                    @delete="$emit('delete', $event)"
                    @context="$emit('context', $event)"
                />

            </div>
        </div>

    </div>
</template>

<script>


export default {
    props: ['block','isSelected'],

}
</script>
