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
        class="mx-auto mt-8 max-w-xl rounded-xl border border-green-200 bg-green-50 p-6 shadow-md"
    >
        <div class="mb-2 flex items-center text-lg font-bold text-green-700">
            <i class="fa-solid fa-envelope-circle-check mr-2"></i>
            お問い合わせはこちら
        </div>
        <div class="text-base font-semibold text-gray-800" v-if="organizerName">
            {{ organizerName }}
        </div>
        <div class="text-sm text-gray-700" v-if="organizerTel">
            <i class="fa-solid fa-phone mr-1"></i>{{ organizerTel }}
        </div>
        <div class="text-sm text-gray-700" v-if="organizerEmail">
            <i class="fa-solid fa-envelope mr-1"></i>{{ organizerEmail }}
        </div>
    </div>
</template>
