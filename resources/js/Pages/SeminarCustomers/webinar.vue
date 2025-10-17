<script setup lang="ts">
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { formatDateTimeAtWithWeekday } from '@/utils/format';
import { Head, usePage } from '@inertiajs/vue3';

const props = defineProps<{
    seminar: {
        id: number;
        name: string;
        description: string;
        speaker_info: string;
        benefits: string;
        detail_url: string;
        seminar_type: string;
        webinar_url: string;
        webinar_start_at: string;
        webinar_end_at: string;
        organizer_name: string;
        organizer_tel: string;
        organizer_email: string;
        is_paid: string | boolean;
        paid_fee: string;
        unique_key: string;
    };
    customer: {
        id: number;
        unique_key: string;
        name: string;
        email: string;
    };
    seminarCustomer: {
        id: number;
        webinar_view_at: string | null;
    };
}>();

// 現在時刻と視聴期間の比較
const now = new Date();
const startDate = new Date(props.seminar.webinar_start_at);
const endDate = new Date(props.seminar.webinar_end_at);
const isViewable = now >= startDate && now <= endDate;
const isExpired = now > endDate;
const isNotStarted = now < startDate;

// 視聴済みかどうかをチェック
const isAlreadyViewed = props.seminarCustomer.webinar_view_at !== null;

// ウェビナーURLへのリンク生成
const webinarViewUrl = `/to_webinar/${props.seminar.unique_key}/${props.customer.unique_key}`;

// エラーメッセージの取得
// 型定義を追加して型エラーを防ぐ
interface FlashProps {
    error?: string;
}
interface User {
    // 必要に応じて User 型のプロパティを定義してください
    id: number;
    name: string;
    email: string;
    // 他のプロパティ
}

interface PageProps {
    flash?: FlashProps;
    auth: {
        user: User;
    };
    [key: string]: any;
}
const page = usePage<PageProps>();
const errorMessage = page.props.flash?.error as string | undefined;
</script>

<template>
    <Head title="ウェビナー詳細" />

    <PublicLayout>
        <div
            class="mx-auto mt-12 max-w-3xl rounded-xl border border-gray-100 bg-white p-10 shadow-lg"
        >
            <!-- エラーメッセージ -->
            <div
                v-if="errorMessage"
                class="mb-8 rounded-xl border border-red-200 bg-red-50 p-4"
            >
                <div class="flex items-center">
                    <svg
                        class="mr-2 h-5 w-5 text-red-400"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd"
                        ></path>
                    </svg>
                    <span class="font-medium text-red-800">{{
                        errorMessage
                    }}</span>
                </div>
            </div>

            <!-- ヘッダー -->
            <div class="mb-10 border-b border-blue-100 pb-6">
                <h1 class="mb-4 text-3xl font-extrabold text-blue-900">
                    {{ seminar.name }}
                </h1>
                <div class="text-base font-semibold text-gray-600">
                    参加者様：{{ customer.name }}
                </div>
            </div>

            <!-- 視聴期間の表示 -->
            <div class="mb-10">
                <h2 class="mb-4 text-xl font-bold text-blue-900">視聴期間</h2>
                <div class="rounded-xl border border-blue-100 bg-blue-50 p-6">
                    <div class="text-lg font-semibold text-blue-800">
                        {{
                            formatDateTimeAtWithWeekday(
                                seminar.webinar_start_at,
                            )
                        }}
                        〜
                        {{
                            formatDateTimeAtWithWeekday(seminar.webinar_end_at)
                        }}
                    </div>
                    <div class="mt-2 text-sm text-red-600">
                        視聴期間を過ぎると視聴できなくなりますので、期間中にご視聴ください。
                    </div>
                    <div class="mt-2 text-sm font-bold text-red-600">
                        ※ このウェビナーは1回限りの視聴となります。
                    </div>
                </div>
            </div>

            <!-- 視聴状況の表示 -->
            <div class="mb-10">
                <h2 class="mb-4 text-xl font-bold text-blue-900">視聴状況</h2>
                <div
                    v-if="isAlreadyViewed"
                    class="rounded-xl border border-orange-200 bg-orange-50 p-6"
                >
                    <div class="text-lg font-bold text-orange-800">
                        ✅ 視聴済み（視聴完了）
                    </div>
                    <div class="mt-2 text-base text-gray-700">
                        視聴日時：{{
                            formatDateTimeAtWithWeekday(
                                seminarCustomer.webinar_view_at!,
                            )
                        }}
                    </div>
                    <div class="mt-2 text-sm font-bold text-red-600">
                        このウェビナーは1回限りの視聴のため、再視聴はできません。
                    </div>
                </div>
                <div
                    v-else-if="isNotStarted"
                    class="rounded-xl border border-yellow-100 bg-yellow-50 p-6"
                >
                    <div class="text-lg font-bold text-yellow-800">
                        ⏰ 視聴開始前です。開始時刻までお待ちください。
                    </div>
                </div>
                <div
                    v-else-if="isExpired"
                    class="rounded-xl border border-red-100 bg-red-50 p-6"
                >
                    <div class="text-lg font-bold text-red-800">
                        ❌ 視聴期間が終了しました。
                    </div>
                </div>
                <div
                    v-else
                    class="rounded-xl border border-green-100 bg-green-50 p-6"
                >
                    <div class="text-lg font-bold text-green-800">
                        ✅ 現在視聴可能です。
                    </div>
                    <div class="mt-2 text-sm font-bold text-blue-600">
                        ⚠️
                        一度視聴を開始すると再視聴はできませんのでご注意ください。
                    </div>
                </div>
            </div>

            <!-- セミナー詳細情報 -->
            <div class="mb-10">
                <h2 class="mb-4 text-xl font-bold text-blue-900">
                    セミナー詳細
                </h2>

                <!-- セミナー説明 -->
                <div v-if="seminar.description" class="mb-6">
                    <h3 class="mb-2 text-lg font-semibold text-gray-700">
                        概要
                    </h3>
                    <div class="whitespace-pre-line text-gray-600">
                        {{ seminar.description }}
                    </div>
                </div>

                <!-- 講師情報 -->
                <div v-if="seminar.speaker_info" class="mb-6">
                    <h3 class="mb-2 text-lg font-semibold text-gray-700">
                        講師情報
                    </h3>
                    <div class="whitespace-pre-line text-gray-600">
                        {{ seminar.speaker_info }}
                    </div>
                </div>

                <!-- 参加特典 -->
                <div v-if="seminar.benefits" class="mb-6">
                    <h3 class="mb-2 text-lg font-semibold text-gray-700">
                        参加特典
                    </h3>
                    <div class="text-gray-600">
                        {{ seminar.benefits }}
                    </div>
                </div>

                <!-- 受講料 -->
                <div class="mb-6">
                    <h3 class="mb-2 text-lg font-semibold text-gray-700">
                        受講料
                    </h3>
                    <div class="text-lg font-bold text-gray-800">
                        {{
                            seminar.is_paid === '1' || seminar.is_paid === true
                                ? `${Number(seminar.paid_fee).toLocaleString()}円`
                                : '無料'
                        }}
                    </div>
                </div>

                <!-- 詳細ページリンク -->
                <div v-if="seminar.detail_url" class="mb-6">
                    <a
                        :href="seminar.detail_url"
                        target="_blank"
                        class="inline-flex items-center rounded-lg bg-blue-600 px-5 py-2 text-base font-bold text-white shadow transition hover:bg-blue-700"
                    >
                        📄 詳細ページを見る
                        <svg
                            class="ml-2 h-4 w-4"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"
                            ></path>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- ウェビナー視聴ボタン -->
            <div class="mb-10 text-center">
                <!-- 視聴済みの場合 -->
                <div v-if="isAlreadyViewed" class="space-y-4">
                    <div class="text-lg font-bold text-gray-600">
                        ウェビナー視聴
                    </div>
                    <button
                        disabled
                        class="inline-flex cursor-not-allowed items-center rounded-lg bg-gray-400 px-8 py-4 text-xl font-bold text-white"
                    >
                        ✅ 視聴済み
                    </button>
                    <div class="text-sm text-red-500">
                        このウェビナーは既に視聴済みです。再視聴はできません。
                    </div>
                </div>
                <!-- 視聴可能な場合 -->
                <div v-else-if="isViewable" class="space-y-4">
                    <div class="text-lg font-bold text-gray-800">
                        ウェビナーを視聴する
                    </div>
                    <!-- 注意喚起 -->
                    <div
                        class="mb-4 rounded-xl border border-yellow-200 bg-yellow-50 p-4"
                    >
                        <div class="text-base font-bold text-yellow-800">
                            ⚠️ 重要なお知らせ
                        </div>
                        <div class="mt-1 text-sm text-yellow-700">
                            このウェビナーは1回限りの視聴となります。<br />
                            一度視聴を開始すると、再視聴はできませんのでご注意ください。
                        </div>
                    </div>
                    <a
                        :href="webinarViewUrl"
                        class="inline-flex items-center rounded-xl bg-red-600 px-8 py-4 text-xl font-bold text-white shadow transition hover:bg-red-700 focus:outline-none focus:ring-4 focus:ring-red-300"
                        onclick="return confirm('このウェビナーは1回限りの視聴となります。一度視聴を開始すると再視聴はできません。視聴を開始してもよろしいですか？');"
                    >
                        🎥 ウェビナーを視聴する
                        <svg
                            class="ml-3 h-6 w-6"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"
                            ></path>
                        </svg>
                    </a>
                    <div class="text-sm text-gray-500">
                        ※ クリックするとウェビナー配信サイトに移動します
                    </div>
                </div>
                <!-- 視聴開始前の場合 -->
                <div v-else-if="isNotStarted" class="space-y-4">
                    <div class="text-lg font-bold text-gray-600">
                        ウェビナー視聴
                    </div>
                    <button
                        disabled
                        class="inline-flex cursor-not-allowed items-center rounded-lg bg-gray-400 px-8 py-4 text-xl font-bold text-white"
                    >
                        🎥 視聴開始前です
                    </button>
                    <div class="text-sm text-gray-500">
                        開始時刻になりましたら、このボタンが有効になります
                    </div>
                </div>
                <!-- 視聴期間終了の場合 -->
                <div v-else-if="isExpired" class="space-y-4">
                    <div class="text-lg font-bold text-gray-600">
                        ウェビナー視聴
                    </div>
                    <button
                        disabled
                        class="inline-flex cursor-not-allowed items-center rounded-lg bg-gray-400 px-8 py-4 text-xl font-bold text-white"
                    >
                        🎥 視聴期間終了
                    </button>
                    <div class="text-sm text-red-500">
                        視聴期間が終了しました
                    </div>
                </div>
            </div>

            <!-- 主催者情報 -->
            <div class="border-t border-blue-100 pt-8">
                <h2 class="mb-4 text-xl font-bold text-blue-900">
                    お問い合わせ
                </h2>
                <div class="rounded-xl border border-blue-100 bg-blue-50 p-6">
                    <div class="mb-2">
                        <span class="font-semibold">主催：</span
                        >{{ seminar.organizer_name }}
                    </div>
                    <div v-if="seminar.organizer_tel" class="mb-2">
                        <span class="font-semibold">電話：</span>
                        <a
                            :href="`tel:${seminar.organizer_tel}`"
                            class="font-semibold text-blue-600 hover:underline"
                        >
                            {{ seminar.organizer_tel }}
                        </a>
                    </div>
                    <div v-if="seminar.organizer_email" class="mb-2">
                        <span class="font-semibold">メール：</span>
                        <a
                            :href="`mailto:${seminar.organizer_email}`"
                            class="font-semibold text-blue-600 hover:underline"
                        >
                            {{ seminar.organizer_email }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
