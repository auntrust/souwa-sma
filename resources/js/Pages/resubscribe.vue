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
        <div
            class="mx-auto mt-16 max-w-xl rounded-xl border border-gray-100 bg-white p-10 shadow-lg"
        >
            <h2
                class="mb-8 border-b border-blue-100 pb-4 text-center text-2xl font-extrabold text-blue-900"
            >
                【再開手続きが完了しました】
            </h2>

            <div class="mb-8 rounded-xl border border-blue-100 bg-blue-50 p-6">
                <p
                    class="mb-2 text-center text-base font-semibold text-blue-700"
                >
                    配信再開対象のメールアドレス
                </p>
                <p
                    class="text-center text-lg font-bold tracking-wide text-blue-900"
                >
                    {{ customer.email }}
                </p>
            </div>
            <p class="mb-4 text-center text-lg font-semibold text-gray-800">
                メール配信の再開手続きが完了いたしました。
            </p>
            <p class="mb-8 text-center text-base text-gray-600">
                今後、弊社からのセミナー情報等をお送りいたします。<br />
                引き続きよろしくお願いいたします。
            </p>

            <div class="border-t border-blue-100 pt-6">
                <p class="mb-3 text-center text-base text-gray-700">
                    配信を停止される場合は、下記URLよりお手続きください。
                </p>
                <div class="text-center">
                    <a
                        :href="`/unsubscribe/${cid}`"
                        class="inline-block rounded-lg bg-red-600 px-6 py-3 text-lg font-bold text-white shadow transition hover:bg-red-700"
                    >
                        配信停止手続きはこちら
                    </a>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
