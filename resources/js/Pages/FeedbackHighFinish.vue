<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
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
</script>

<template>
    <Head title="ご評価ありがとうございます" />
    <PublicLayout>
        <div class="mx-auto mt-8 max-w-xl rounded bg-white p-6 shadow">
            <h2 class="mb-4 text-center text-xl font-bold">
                【ご評価ありがとうございます】
            </h2>
            <div class="mb-5 text-center">
                よろしければ、Googleでご感想を<br />
                シェアしていただけませんか？<br /><br />
                いただいた評価は、今後のセミナー運営や<br />
                新しい受講者の参考になります。<br /><br />
                星評価だけでも大変励みになります。
            </div>
            <div class="mb-5 text-center">
                <a
                    :href="seminar.google_review_url"
                    target="_blank"
                    class="inline-block rounded bg-blue-600 px-6 py-2 font-bold text-white transition hover:bg-blue-700"
                >
                    クチコミ投稿はこちら
                </a>
            </div>
        </div>
    </PublicLayout>
</template>
