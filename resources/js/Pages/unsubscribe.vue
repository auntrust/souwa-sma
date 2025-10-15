<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import PublicLayout from '../Layouts/PublicLayout.vue';

const props = defineProps<{
    cid: string | number;
    customer: {
        id: number;
        name: string;
        email: string;
        is_delivery: number;
    };
    // コントローラーから渡される、すでに停止済みかどうかのフラグ
    wasAlreadyStopped?: boolean;
}>();

const handleResubscribe = () => {
    if (
        confirm(
            '配信を再開しますか？\n再開すると、今後弊社からのメール配信を受信されます。',
        )
    ) {
        // 確認OKの場合、配信再開処理を実行
        router.post(
            `/resubscribe/${props.cid}`,
            {},
            {
                onSuccess: () => {
                    alert('配信再開が完了しました。\nありがとうございました。');
                },
                onError: () => {
                    alert('エラーが発生しました。\nもう一度お試しください。');
                },
            },
        );
    }
};
</script>

<template>
    <Head title="停止手続きが完了しました" />
    <PublicLayout>
        <div class="mx-auto mt-8 max-w-xl rounded bg-white p-6 shadow">
            <!-- すでに配信停止されている場合 -->
            <div v-if="wasAlreadyStopped">
                <h2 class="mb-4 text-center text-xl font-bold">
                    【すでに配信停止されています】
                </h2>

                <div class="mb-6 rounded-lg bg-gray-50 p-4">
                    <p class="mb-2 text-center text-sm text-gray-600">
                        対象のメールアドレス
                    </p>
                    <p class="text-center font-medium text-gray-800">
                        {{ customer.email }}
                    </p>
                </div>

                <p class="mb-4 text-center">
                    このメールアドレスは、すでに配信停止の手続きが完了しています。
                </p>
                <p class="mb-6 text-center text-sm text-gray-600">
                    弊社からのメール配信は停止されています。
                </p>
            </div>

            <!-- 新規に配信停止された場合 -->
            <div v-else>
                <h2 class="mb-4 text-center text-xl font-bold">
                    【停止手続きが完了しました】
                </h2>

                <div class="mb-6 rounded-lg bg-gray-50 p-4">
                    <p class="mb-2 text-center text-sm text-gray-600">
                        配信停止対象のメールアドレス
                    </p>
                    <p class="text-center font-medium text-gray-800">
                        {{ customer.email }}
                    </p>
                </div>

                <p class="mb-4 text-center">
                    メール配信の停止手続きが完了いたしました。
                </p>
                <p class="mb-6 text-center text-sm text-gray-600">
                    今後、弊社からのメール配信は停止されます。<br />
                    ご利用いただき、ありがとうございました。
                </p>
            </div>

            <div class="border-t pt-4">
                <p class="mb-2 text-center text-sm text-gray-700">
                    配信を再開される場合は、下記URLよりお手続きください。
                </p>
                <div class="text-center">
                    <button
                        @click="handleResubscribe"
                        class="inline-block rounded bg-blue-600 px-4 py-2 text-white transition-colors hover:bg-blue-700"
                    >
                        配信再開手続きはこちら
                    </button>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
