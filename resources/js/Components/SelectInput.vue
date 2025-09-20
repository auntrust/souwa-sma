<script setup lang="ts">
import { onMounted, ref } from 'vue';

const model = defineModel<string | number>({ required: true });

const SelectInput = ref<HTMLInputElement | null>(null);
defineProps({
    options: null,
});

onMounted(() => {
    if (SelectInput.value) {
        SelectInput.value?.focus();
    }
});

defineExpose({ focus: () => SelectInput.value?.focus() });
</script>

<template>
    <select
        class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
        v-model="model"
        ref="selectInput"
    >
        <option value="">選択してください</option>
        <option v-for="op in options" :key="op.id" :value="op.id">
            {{ op.name }}
        </option>
    </select>
</template>
