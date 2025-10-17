<script setup lang="ts">
import SeminarDetails from '@/Components/SeminarDetails.vue';
import { Head, useForm } from '@inertiajs/vue3';
import PublicLayout from '../Layouts/PublicLayout.vue';

const props = defineProps<{
    seminar: {
        id: number;
        unique_key: string;
        name: string;
        description: string;
        speaker_info: string;
        benefits: string;
        detail_url: string;
        seminar_type: string;

        onsite_name: string;
        onsite_zip: string;
        onsite_address: string;
        onsite_building: string;
        onsite_map_url: string;
        onsite_date: string;
        onsite_start_time: string;
        onsite_end_time: string;
        onsite_capacity: number | string;

        online_url: string;
        online_id: string;
        online_pwd: string;
        online_date: string;
        online_start_time: string;
        online_end_time: string;
        online_capacity: number | string;

        webinar_url: string;
        webinar_start_at: string;
        webinar_end_at: string;

        organizer_name: string;
        organizer_tel: string;
        organizer_email: string;

        is_paid: string | boolean;
        paid_fee: string;

        is_consult: string | boolean;
        timerex_url: string;

        is_review: string | boolean;
        google_review_url: string;
    };
    customer: {
        id: number;
        unique_key: string;
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

const form = useForm({
    sid: props.seminar?.unique_key ? String(props.seminar.unique_key) : '',
    cid: props.customer?.unique_key ? String(props.customer.unique_key) : '',
    survey_q1: 3,
    survey_q2: '',
    survey_q3: '',
    survey_q4: '',
    survey_q5: '',
});

const submit = () => {
    form.post(route('seminar_customers.feedback_store'), {
        onSuccess: () => {
            form.reset(
                'survey_q1',
                'survey_q2',
                'survey_q3',
                'survey_q4',
                'survey_q5',
            );
        },
    });
};
</script>

<template>
    <Head title="セミナーの評価をお聞かせください" />
    <PublicLayout>
        <div
            class="mx-auto mt-12 max-w-xl rounded-xl border border-gray-100 bg-white p-10 shadow-lg"
        >
            <h1
                class="mb-6 border-b border-blue-100 pb-3 text-center text-2xl font-extrabold text-blue-900"
            >
                【{{ seminar.name }}】
            </h1>

            <SeminarDetails :seminar="seminar" />

            <h2
                class="my-8 rounded-xl border border-blue-100 bg-blue-50 py-5 text-center text-xl font-bold text-blue-800"
            >
                セミナーの評価をお聞かせください
            </h2>

            <form @submit.prevent="submit">
                <div class="mb-8">
                    <h2 class="mb-4 text-lg font-bold text-gray-800">
                        【Q1】本日のセミナーはいかがでしたか？
                    </h2>
                    <div class="mb-4 grid gap-2 px-4">
                        <label
                            class="flex items-center gap-2 font-medium text-gray-700"
                        >
                            <input
                                type="radio"
                                value="5"
                                v-model="form.survey_q1"
                                class="accent-blue-600"
                                required
                            />
                            非常に満足（★★★★★）
                        </label>
                        <label
                            class="flex items-center gap-2 font-medium text-gray-700"
                        >
                            <input
                                type="radio"
                                value="4"
                                v-model="form.survey_q1"
                                class="accent-blue-600"
                            />
                            満足（★★★★）
                        </label>
                        <label
                            class="flex items-center gap-2 font-medium text-gray-700"
                        >
                            <input
                                type="radio"
                                value="3"
                                v-model="form.survey_q1"
                                class="accent-blue-600"
                            />
                            普通（★★★）
                        </label>
                        <label
                            class="flex items-center gap-2 font-medium text-gray-700"
                        >
                            <input
                                type="radio"
                                value="2"
                                v-model="form.survey_q1"
                                class="accent-blue-600"
                            />
                            やや不満（★★）
                        </label>
                        <label
                            class="flex items-center gap-2 font-medium text-gray-700"
                        >
                            <input
                                type="radio"
                                value="1"
                                v-model="form.survey_q1"
                                class="accent-blue-600"
                            />
                            非常に不満（★）
                        </label>
                    </div>
                </div>

                <div class="mb-8">
                    <h2 class="mb-4 text-lg font-bold text-gray-800">
                        【Q2】セミナー内容について
                    </h2>
                    <div class="mb-4 px-4">
                        <div class="mb-2 font-medium text-gray-700">
                            あなたの学びになったことや印象に残った内容を教えてください。
                        </div>
                        <textarea
                            v-model="form.survey_q2"
                            class="w-full rounded-lg border px-3 py-2 transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                            rows="4"
                        ></textarea>
                    </div>
                </div>

                <div class="mb-8">
                    <h2 class="mb-4 text-lg font-bold text-gray-800">
                        【Q3】講師についての印象
                    </h2>
                    <div class="mb-4 px-4">
                        <div class="mb-2 font-medium text-gray-700">
                            話し方、スライドのわかりやすさなど
                        </div>
                        <textarea
                            v-model="form.survey_q3"
                            class="w-full rounded-lg border px-3 py-2 transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                            rows="4"
                        ></textarea>
                    </div>
                </div>

                <div class="mb-8">
                    <h2 class="mb-4 text-lg font-bold text-gray-800">
                        【Q4】今後扱ってほしいテーマはありますか？
                    </h2>
                    <div class="mb-4 px-4">
                        <textarea
                            v-model="form.survey_q4"
                            class="w-full rounded-lg border px-3 py-2 transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                            rows="4"
                        ></textarea>
                    </div>
                </div>

                <div class="mb-8">
                    <h2 class="mb-4 text-lg font-bold text-gray-800">
                        【Q5】その他ご意見・ご感想があればご記入ください
                    </h2>
                    <div class="mb-4 px-4">
                        <textarea
                            v-model="form.survey_q5"
                            class="w-full rounded-lg border px-3 py-2 transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                            rows="4"
                        ></textarea>
                    </div>
                </div>

                <button
                    type="submit"
                    class="w-full rounded-lg bg-blue-600 py-3 text-lg font-bold text-white shadow transition hover:bg-blue-700 disabled:opacity-40"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    アンケートに回答する
                </button>
            </form>
        </div>
    </PublicLayout>
</template>
