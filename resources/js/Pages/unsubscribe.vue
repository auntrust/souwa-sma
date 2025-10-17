<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { computed } from 'vue';
import PublicLayout from '../Layouts/PublicLayout.vue';

const props = defineProps<{
    cid: string | number;
    customer: {
        id: number;
        name: string;
        email: string;
        is_unsubscribe: number;
        unsubscribe_at?: string | null; // 追加: 配信停止日時
    };
    // コントローラーから渡される「すでに目標状態か」フラグを受け取る（命名を揃える）
    wasAlreadyInTargetState?: boolean;
}>();

// 日時整形（アジア/東京での表示）
const formatDateTime = (iso?: string | null) => {
    if (!iso) return null;
    const d = new Date(iso);
    return new Intl.DateTimeFormat('ja-JP', {
        dateStyle: 'medium',
        timeStyle: 'short',
        timeZone: 'Asia/Tokyo',
    }).format(d);
};

const formattedUnsubscribedAt = computed(() =>
    formatDateTime(props.customer.unsubscribe_at),
);

const handleResubscribe = () => {
    if (
        confirm(
            '配信を再開しますか？\n再開すると、今後弊社からのメール配信を受信されます。',
        )
    ) {
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
        <div
            class="mx-auto mt-16 max-w-xl rounded-xl border border-gray-100 bg-white p-10 shadow-md"
        >
            <!-- すでに配信停止されている場合 -->
            <div v-if="wasAlreadyInTargetState">
                <h2
                    class="mb-8 border-b border-blue-100 pb-4 text-center text-2xl font-extrabold text-blue-900"
                >
                    【すでに配信停止されています】
                </h2>

                <div
                    class="mb-8 rounded-xl border border-blue-100 bg-blue-50 p-6"
                >
                    <p
                        class="mb-2 text-center text-base font-semibold text-blue-700"
                    >
                        対象のメールアドレス
                    </p>
                    <p
                        class="text-center text-lg font-bold tracking-wide text-blue-900"
                    >
                        {{ customer.email }}
                    </p>
                </div>

                <p class="mb-4 text-center text-lg font-semibold text-gray-800">
                    このメールアドレスは、<br />
                    すでに配信停止の手続きが完了しています。
                </p>
                <p class="mb-8 text-center text-base text-gray-600">
                    弊社からのメール配信は停止されています。
                </p>

                <!-- 配信停止日 -->
                <div v-if="formattedUnsubscribedAt" class="mt-4 text-center">
                    <span class="text-sm text-gray-600">配信停止日：</span>
                    <span class="text-base font-semibold text-gray-900">
                        {{ formattedUnsubscribedAt }}
                    </span>
                </div>
            </div>

            <!-- 新規に配信停止された場合 -->
            <div v-else>
                <h2
                    class="mb-8 border-b border-blue-100 pb-4 text-center text-2xl font-extrabold text-blue-900"
                >
                    【停止手続きが完了しました】
                </h2>

                <div
                    class="mb-8 rounded-xl border border-blue-100 bg-blue-50 p-6"
                >
                    <p
                        class="mb-2 text-center text-base font-semibold text-blue-700"
                    >
                        配信停止対象のメールアドレス
                    </p>
                    <p
                        class="text-center text-lg font-bold tracking-wide text-blue-900"
                    >
                        {{ customer.email }}
                    </p>
                </div>

                <p class="mb-4 text-center text-lg font-semibold text-gray-800">
                    メール配信の停止手続きが完了いたしました。
                </p>
                <p class="mb-4 text-center text-base text-gray-600">
                    今後、弊社からのメール配信は停止されます。
                </p>

                <!-- 配信停止日 -->
                <div v-if="formattedUnsubscribedAt" class="mt-2 text-center">
                    <span class="text-sm text-gray-600">配信停止日：</span>
                    <span class="text-base font-semibold text-gray-900">
                        {{ formattedUnsubscribedAt }}
                    </span>
                </div>
            </div>

            <div class="mt-8 border-t border-blue-100 pt-6">
                <p class="mb-3 text-center text-base text-gray-700">
                    配信を再開される場合は、下記URLよりお手続きください。
                </p>
                <div class="text-center">
                    <button
                        @click="handleResubscribe"
                        class="inline-block rounded-lg bg-blue-600 px-6 py-3 text-lg font-bold text-white shadow transition hover:bg-blue-700"
                    >
                        配信再開手続きはこちら
                    </button>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
