<script setup lang="ts">
interface Props {
    organizerName: string;
    organizerTel: string;
    organizerEmail: string;
}

defineProps<Props>();
</script>

<template>
    <div
        v-if="organizerName || organizerTel || organizerEmail"
        class="mx-auto mt-8 max-w-xl rounded bg-white p-6 shadow"
    >
        ▼お問い合わせはこちら<br />
        <span v-if="organizerName">{{ organizerName }}</span>
        <span v-if="organizerTel"><br />{{ organizerTel }}</span>
        <span v-if="organizerEmail"><br />{{ organizerEmail }}</span>
    </div>
</template>
