<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import PublicLayout from '../Layouts/PublicLayout.vue';
</script>

<template>
    <Head title="セミナー申し込み完了" />
    <PublicLayout>
        <div
            class="mx-auto mt-16 max-w-xl rounded-xl border border-gray-100 bg-white p-10 shadow-lg"
        >
            <h2
                class="mb-8 border-b border-blue-100 pb-4 text-center text-2xl font-extrabold text-blue-900"
            >
                【お申し込みありがとうございます】
            </h2>
            <p class="mb-4 text-center text-lg font-semibold text-gray-800">
                セミナーのお申し込みを受け付けました。
            </p>
            <p
                class="rounded border border-blue-100 bg-blue-50 px-4 py-3 text-center text-base leading-relaxed text-gray-600"
            >
                ご登録いただいたメールアドレスに<br />
                受付完了のご案内をお送りしました。<br /><br />
                当日お会いできることを楽しみにしております。
            </p>
        </div>
    </PublicLayout>
</template>
