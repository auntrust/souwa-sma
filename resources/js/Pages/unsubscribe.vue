<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import PublicLayout from '../Layouts/PublicLayout.vue';

defineProps<{
    cid: string | number;
}>();
</script>

<template>
    <Head title="配信停止" />
    <PublicLayout>
        <div class="mx-auto mt-8 max-w-xl rounded bg-white p-6 shadow">
            <h2 class="mb-4 text-center text-xl font-bold">
                【配信停止が完了しました】
            </h2>
            <p class="mb-4 text-center">
                メール配信の停止手続きが完了いたしました。
            </p>
            <p class="mb-6 text-center text-sm text-gray-600">
                今後、弊社からのメール配信は停止されます。<br />
                ご利用いただき、ありがとうございました。
            </p>

            <div class="border-t pt-4">
                <p class="mb-2 text-center text-sm text-gray-700">
                    配信を再開される場合は、下記URLよりお手続きください。
                </p>
                <div class="text-center">
                    <a
                        :href="`/resubscribe/${cid}`"
                        class="inline-block rounded bg-blue-600 px-4 py-2 text-white transition-colors hover:bg-blue-700"
                    >
                        配信再開手続きはこちら
                    </a>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
