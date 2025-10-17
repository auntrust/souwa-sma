<script setup lang="ts">
import SeminarDetails from '@/Components/SeminarDetails.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { nl2br } from '@/utils/format';
import { Head, Link } from '@inertiajs/vue3';

interface Seminar {
    seminar_type: string;
    detail_url: string;
    onsite_name?: string;
    onsite_zip?: string;
    onsite_address?: string;
    onsite_building?: string;
    // ... add all other required properties here ...
    is_paid: boolean;
    paid_fee: string;
    [key: string]: any; // fallback for any extra properties
}

interface Customer {
    [key: string]: any;
}

interface SeminarCustomer {
    [key: string]: any;
}

const props = defineProps<{
    seminar: Seminar;
    customer: Customer;
    seminarCustomer: SeminarCustomer;
    successMessage?: string;
}>();

// アンケートの質問を定義
const surveyQuestions = {
    survey_q2: 'セミナーの内容は理解しやすかったですか？',
    survey_q3: 'セミナーの時間は適切でしたか？',
    survey_q4: 'また参加したいと思いますか？',
    survey_q5: '他の方にもお勧めしたいと思いますか？',
};

// 評価の表示用文字列
const getRatingText = (rating: number) => {
    const ratings = {
        5: '非常に良い',
        4: '良い',
        3: '普通',
        2: '悪い',
        1: '非常に悪い',
    };
    return ratings[rating as keyof typeof ratings] || '未回答';
};
</script>

<template>
    <Head title="セミナー管理 | アンケート" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2
                    class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
                >
                    セミナー管理 | アンケート
                </h2>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <!-- セミナー情報 -->
                <div
                    class="mb-4 overflow-hidden bg-white p-6 shadow-sm sm:rounded-lg dark:bg-gray-800"
                >
                    <h2
                        class="mb-4 text-xl font-bold"
                        style="
                            border-left: 6px solid black;
                            padding-left: 0.5rem;
                        "
                    >
                        {{ seminar?.name }}<br />
                    </h2>

                    <p>{{ seminar?.description }}</p>
                    <br />

                    <SeminarDetails :seminar="seminar" :customer="customer" />
                </div>

                <!-- 顧客情報 -->
                <div
                    class="mb-4 overflow-hidden bg-white p-6 shadow-sm sm:rounded-lg dark:bg-gray-800"
                >
                    <h3
                        class="mb-4 text-lg font-bold text-gray-800 dark:text-gray-200"
                        style="
                            border-left: 6px solid #3b82f6;
                            padding-left: 0.5rem;
                        "
                    >
                        参加者情報
                    </h3>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                お名前
                            </p>
                            <p class="font-medium">
                                {{ seminarCustomer?.name || customer?.name }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                フリガナ
                            </p>
                            <p class="font-medium">
                                {{
                                    seminarCustomer?.furigana ||
                                    customer?.furigana
                                }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                電話番号
                            </p>
                            <p class="font-medium">
                                {{ seminarCustomer?.tel || customer?.tel }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                メールアドレス
                            </p>
                            <p class="font-medium">
                                {{ seminarCustomer?.email || customer?.email }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                都道府県
                            </p>
                            <p class="font-medium">
                                {{
                                    seminarCustomer?.todofuken ||
                                    customer?.todofuken
                                }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                会社名
                            </p>
                            <p class="font-medium">
                                {{
                                    seminarCustomer?.co_name ||
                                    customer?.co_name
                                }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                会社電話番号
                            </p>
                            <p class="font-medium">
                                {{
                                    seminarCustomer?.co_tel || customer?.co_tel
                                }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                部署
                            </p>
                            <p class="font-medium">
                                {{
                                    seminarCustomer?.co_busho ||
                                    customer?.co_busho
                                }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                役職
                            </p>
                            <p class="font-medium">
                                {{
                                    seminarCustomer?.co_post ||
                                    customer?.co_post
                                }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                参加人数
                            </p>
                            <p class="font-medium">
                                {{ seminarCustomer?.applicant_count }}名
                            </p>
                        </div>
                    </div>

                    <!-- 申込時の要望 -->
                    <div v-if="seminarCustomer?.request" class="mt-4">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            お申込み時のご要望・ご質問
                        </p>
                        <div
                            class="mt-2 rounded bg-gray-50 p-3 dark:bg-gray-700"
                        >
                            <span
                                v-html="nl2br(seminarCustomer.request)"
                            ></span>
                        </div>
                    </div>
                </div>

                <!-- アンケート結果 -->
                <div
                    v-if="seminarCustomer?.survey_at"
                    class="mb-4 overflow-hidden bg-white p-6 shadow-sm sm:rounded-lg dark:bg-gray-800"
                >
                    <h3
                        class="mb-4 text-lg font-bold text-gray-800 dark:text-gray-200"
                        style="
                            border-left: 6px solid #10b981;
                            padding-left: 0.5rem;
                        "
                    >
                        アンケート結果
                    </h3>

                    <!-- アンケート回答日時 -->
                    <div class="mb-4">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            回答日時
                        </p>
                        <p class="font-medium">
                            {{
                                new Date(
                                    seminarCustomer.survey_at,
                                ).toLocaleString('ja-JP')
                            }}
                        </p>
                    </div>

                    <!-- 総合評価 -->
                    <div class="mb-6">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            総合評価{{ seminarCustomer.survey_q1 }}
                        </p>
                        <div class="mt-2 flex items-center">
                            <div class="flex">
                                <span
                                    v-for="i in 5"
                                    :key="i"
                                    class="text-2xl"
                                    :class="
                                        i <= (seminarCustomer.survey_q1 || 0)
                                            ? 'text-yellow-400'
                                            : 'text-gray-300'
                                    "
                                >
                                    ★
                                </span>
                            </div>
                            <span class="ml-2 font-medium">
                                {{ getRatingText(seminarCustomer.survey_q1) }}
                            </span>
                        </div>
                    </div>

                    <!-- 各質問の回答 -->
                    <div class="space-y-4">
                        <div
                            v-for="(question, key) in surveyQuestions"
                            :key="key"
                            v-show="seminarCustomer[key]"
                        >
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {{ question }}
                            </p>
                            <div
                                class="mt-2 rounded bg-gray-50 p-3 dark:bg-gray-700"
                            >
                                <span
                                    v-html="nl2br(seminarCustomer[key])"
                                ></span>
                            </div>
                        </div>
                    </div>

                    <!-- ご意見・ご感想 -->
                    <div v-if="seminarCustomer?.survey_opinion" class="mt-6">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            ご意見・ご感想
                        </p>
                        <div
                            class="mt-2 rounded bg-gray-50 p-3 dark:bg-gray-700"
                        >
                            <span
                                v-html="nl2br(seminarCustomer.survey_opinion)"
                            ></span>
                        </div>
                    </div>
                </div>

                <!-- アンケート未回答の場合 -->
                <div
                    v-else
                    class="mb-4 overflow-hidden border border-yellow-200 bg-yellow-50 p-6 shadow-sm sm:rounded-lg"
                >
                    <h3 class="text-lg font-medium text-yellow-800">
                        アンケート未回答
                    </h3>
                    <p class="mt-2 text-yellow-700">
                        この参加者はまだアンケートに回答していません。
                    </p>
                </div>
                <div class="mt-4 flex items-center justify-center">
                    <Link
                        :href="route('seminars.entry_list', seminar.id)"
                        :class="'rounded-md border bg-gray-500 px-4 py-2 text-xs font-semibold text-white'"
                    >
                        <i class="fa-solid fa-backward"></i> 戻る
                    </Link>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
