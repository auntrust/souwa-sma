<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import PublicLayout from '../Layouts/PublicLayout.vue';
</script>

<template>
    <Head title="セミナー申し込み完了" />
    <PublicLayout>
        <div class="mx-auto mt-8 max-w-xl rounded bg-white p-6 shadow">
            <h2 class="mb-4 text-center text-xl font-bold">
                【お申し込みありがとうございます】
            </h2>
            <p class="mb-2 text-center">
                セミナーのお申し込みを受け付けました。
            </p>
            <p class="text-center text-sm text-gray-600">
                ご登録いただいたメールアドレスに受付完了のご案内をお送りしました。<br />
                当日お会いできることを楽しみにしております。
            </p>
        </div>
    </PublicLayout>
</template>
