<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import PublicLayout from '../Layouts/PublicLayout.vue';

const props = defineProps<{
    cid: string | number;
    customer: {
        id: number;
        name: string;
        email: string;
        is_unsubscribe: number;
    };
}>();
</script>

<template>
    <Head title="再開手続きが完了しました" />
    <PublicLayout>
        <div class="mx-auto mt-8 max-w-xl rounded bg-white p-6 shadow">
            <h2 class="mb-4 text-center text-xl font-bold">
                【再開手続きが完了しました】
            </h2>

            <div class="mb-6 rounded-lg bg-gray-50 p-4">
                <p class="mb-2 text-center text-sm text-gray-600">
                    配信再開対象のメールアドレス
                </p>
                <p class="text-center font-medium text-gray-800">
                    {{ customer.email }}
                </p>
            </div>
            <p class="mb-2 text-center">
                メール配信の再開手続きが完了いたしました。
            </p>
            <p class="mb-6 text-center text-sm text-gray-600">
                今後、弊社からのセミナー情報等をお送りいたします。<br />
                引き続きよろしくお願いいたします。
            </p>

            <div class="border-t pt-4">
                <p class="mb-2 text-center text-sm text-gray-700">
                    配信を停止される場合は、下記URLよりお手続きください。
                </p>
                <div class="text-center">
                    <a
                        :href="`/unsubscribe/${cid}`"
                        class="inline-block rounded bg-red-600 px-4 py-2 text-white transition-colors hover:bg-red-700"
                    >
                        配信停止手続きはこちら
                    </a>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
