<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import QRCode from 'qrcode';
import { onMounted, ref } from 'vue';
import PublicLayout from '../Layouts/PublicLayout.vue';

const props = defineProps<{
    seminar: {
        id: number;
        name: string;
        finish_url: string;
        google_review_url: string;
    };
    customer: {
        id: number;
        name: string;
        furigana: string;
        tel: string;
        email: string;
        todofuken: string;
        co_name: string;
        co_tel: string;
        co_busho: string;
        co_post: string;
    };
}>();

const qrCodeDataUrl = ref<string>('');

onMounted(async () => {
    try {
        const dataUrl = await QRCode.toDataURL(
            props.seminar.google_review_url,
            {
                width: 200,
                margin: 2,
                color: {
                    dark: '#000000',
                    light: '#FFFFFF',
                },
            },
        );
        qrCodeDataUrl.value = dataUrl;
    } catch (error) {
        console.error('QRコードの生成に失敗しました:', error);
    }
});
</script>

<template>
    <Head title="ご評価ありがとうございます" />
    <PublicLayout>
        <div
            class="mx-auto mt-16 max-w-xl rounded-xl border border-gray-100 bg-white p-10 shadow-lg"
        >
            <h2
                class="mb-8 border-b border-blue-100 pb-4 text-center text-2xl font-extrabold text-blue-900"
            >
                【ご評価ありがとうございます】
            </h2>
            <div
                class="mb-8 text-center text-lg font-semibold leading-relaxed text-gray-800"
            >
                よろしければ、Googleで<br />
                クチコミ投稿をいただけませんか？<br /><br />
                いただいた評価は、今後のセミナー運営や<br />
                新しい受講者の参考になります。<br /><br />
                星評価だけでも大変励みになります。
            </div>

            <hr class="mb-8 border-blue-100" />

            <div class="mb-8 text-center">
                <a
                    :href="seminar.google_review_url"
                    target="_blank"
                    class="inline-block rounded-lg bg-blue-600 px-8 py-3 text-lg font-bold text-white shadow transition hover:bg-blue-700"
                >
                    クチコミ投稿はこちら
                </a>
            </div>

            <!-- QRコード表示エリア -->
            <div class="mb-8 text-center">
                <div v-if="qrCodeDataUrl" class="mb-4">
                    <a :href="seminar.google_review_url" target="_blank">
                        <img
                            :src="qrCodeDataUrl"
                            alt="Google レビューページへのQRコード"
                            class="mx-auto rounded-lg border border-blue-100 bg-white p-3 shadow"
                        />
                    </a>
                    <div class="mt-2 text-sm text-gray-500">
                        スマートフォンでQRコードを読み取ると<br />クチコミ投稿ページが開きます
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
