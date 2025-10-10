<script setup lang="ts">
import {
    formatDateWithWeekday,
    formatTime,
    getDurationMinutes,
} from '@/utils/format';

import { Head, useForm } from '@inertiajs/vue3';
import PublicLayout from '../Layouts/PublicLayout.vue';

const props = defineProps<{
    seminar: {
        id: number;
        unique_key: string;
        name: string;
        seminar_date: string;
        seminar_type: string;
        is_paid: string;
        paid_fee: number;
        start_time: string;
        end_time: string;
        venue_name: string;
        venue_zip: string;
        venue_address: string;
        venue_building: string;
        venue_tel: string;
        venue_map_url: string;
        online_url: string;
        online_id: string;
        online_pwd: string;
        webinar_url: string;
        webinar_start_at: string;
        webinar_end_at: string;
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
        <div class="mx-auto mt-8 max-w-xl rounded bg-white p-6 shadow">
            <h2 class="mb-4 text-center font-bold">
                セミナーの評価をお聞かせください
            </h2>

            <h1 class="text-l mb-4 text-center text-xl font-bold">
                【{{ seminar.name }}】<br />
                {{ formatDateWithWeekday(seminar.seminar_date) }}
            </h1>

            <div class="mb-4">
                講義時間：{{ formatTime(seminar.start_time) }}〜{{
                    formatTime(seminar.end_time)
                }}（{{
                    getDurationMinutes(seminar.start_time, seminar.end_time)
                }}分）<br />
                受講料：{{
                    seminar.is_paid == '1'
                        ? seminar.paid_fee.toLocaleString() + '円'
                        : '無料'
                }}<br />
                <!-- 開催形式ごとの表示切り替え -->
                <template v-if="seminar.seminar_type === 'onsite'">
                    開催形式：現地開催<br />
                    &nbsp;→ 会場名：{{ seminar.venue_name }}<br />
                    &nbsp;→ 郵便番号：{{ seminar.venue_zip }}<br />
                    &nbsp;→ 住所：{{ seminar.venue_address }}
                    {{ seminar.venue_building }}<br />
                    &nbsp;→ 電話番号：{{ seminar.venue_tel }}<br />
                    &nbsp;→ 地図URL：<a
                        :href="seminar.venue_map_url"
                        target="_blank"
                        >{{ seminar.venue_map_url }}</a
                    ><br />
                </template>
                <template v-else-if="seminar.seminar_type === 'online'">
                    開催形式：オンラインセミナー<br />
                </template>
                <template v-else-if="seminar.seminar_type === 'webinar'">
                    開催形式：ウェビナー<br />
                </template>
            </div>

            <form @submit.prevent="submit">
                <div class="mb-5">
                    <h2 class="text-l mb-4 font-bold">
                        【Q1】本日のセミナーはいかがでしたか？
                    </h2>
                    <div class="mb-4 px-4">
                        <label class="mb-2 block">
                            <input
                                type="radio"
                                value="5"
                                v-model="form.survey_q1"
                                class="mr-2"
                                required
                            />
                            非常に満足（★★★★★）
                        </label>
                        <label class="mb-2 block">
                            <input
                                type="radio"
                                value="4"
                                v-model="form.survey_q1"
                                class="mr-2"
                            />
                            満足（★★★★）
                        </label>
                        <label class="mb-2 block">
                            <input
                                type="radio"
                                value="3"
                                v-model="form.survey_q1"
                                class="mr-2"
                            />
                            普通（★★★）
                        </label>
                        <label class="mb-2 block">
                            <input
                                type="radio"
                                value="2"
                                v-model="form.survey_q1"
                                class="mr-2"
                            />
                            やや不満（★★）
                        </label>
                        <label class="mb-2 block">
                            <input
                                type="radio"
                                value="1"
                                v-model="form.survey_q1"
                                class="mr-2"
                            />
                            非常に不満（★）
                        </label>
                    </div>
                </div>

                <div class="mb-5">
                    <h2 class="text-l mb-4 font-bold">
                        【Q2】セミナー内容について
                    </h2>

                    <div class="mb-4 px-4">
                        <div class="mb-1 block font-medium">
                            あなたの学びになったことや印象に残った内容を教えてください。
                        </div>
                        <textarea
                            v-model="form.survey_q2"
                            class="w-full rounded border px-3 py-2"
                            rows="4"
                        ></textarea>
                    </div>
                </div>

                <div class="mb-5">
                    <h2 class="text-l mb-4 font-bold">
                        【Q3】講師についての印象
                    </h2>

                    <div class="mb-4 px-4">
                        <div class="mb-1 block font-medium">
                            話し方、スライドのわかりやすさなど
                        </div>
                        <textarea
                            v-model="form.survey_q3"
                            class="w-full rounded border px-3 py-2"
                            rows="4"
                        ></textarea>
                    </div>
                </div>

                <div class="mb-5">
                    <h2 class="text-l mb-4 font-bold">
                        【Q4】今後扱ってほしいテーマはありますか？
                    </h2>

                    <div class="mb-4 px-4">
                        <textarea
                            v-model="form.survey_q4"
                            class="w-full rounded border px-3 py-2"
                            rows="4"
                        ></textarea>
                    </div>
                </div>

                <div class="mb-5">
                    <h2 class="text-l mb-4 font-bold">
                        【Q5】その他ご意見・ご感想があればご記入ください
                    </h2>

                    <div class="mb-4 px-4">
                        <textarea
                            v-model="form.survey_q5"
                            class="w-full rounded border px-3 py-2"
                            rows="4"
                        ></textarea>
                    </div>
                </div>

                <button
                    type="submit"
                    class="w-full rounded bg-blue-600 py-2 text-white"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    アンケートに回答する
                </button>
            </form>
        </div>
    </PublicLayout>
</template>
