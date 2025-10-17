<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import PublicLayout from '../Layouts/PublicLayout.vue';
</script>

<template>
    <Head title="ご意見ありがとうございます" />
    <PublicLayout>
        <div
            class="mx-auto mt-16 max-w-xl rounded-xl border border-gray-100 bg-white p-10 shadow-lg"
        >
            <h2
                class="mb-8 border-b border-blue-100 pb-4 text-center text-2xl font-extrabold text-blue-900"
            >
                【ご意見ありがとうございます】
            </h2>
            <p
                class="text-center text-lg font-semibold leading-relaxed text-gray-800"
            >
                セミナーに関する貴重なご意見をいただき、<br />
                ありがとうございました。<br /><br />
                今後の改善に活かしてまいります。
            </p>
        </div>
    </PublicLayout>
</template>
