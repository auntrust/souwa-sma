<script setup lang="ts">
import {
    formatDateWithWeekday,
    formatTime,
    getDurationMinutes,
} from '@/utils/format';
import { getTodofukenList } from '@/utils/todofuken';
import { Head, useForm } from '@inertiajs/vue3';
import PublicLayout from '../Layouts/PublicLayout.vue';

// 都道府県のリストを取得
const todofukenList = getTodofukenList();

const props = defineProps<{
    seminar: {
        id: number;
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
    seminar_id: '',
    customer_id: props.customer?.id ? String(props.customer.id) : '',
    name: props.customer?.name ?? '',
    furigana: props.customer?.furigana ?? '',
    tel: props.customer?.tel ?? '',
    email: props.customer?.email ?? '',
    todofuken: props.customer?.todofuken ?? '',
    co_name: props.customer?.co_name ?? '',
    co_tel: props.customer?.co_tel ?? '',
    co_busho: props.customer?.co_busho ?? '',
    co_post: props.customer?.co_post ?? '',
    applicant_count: '1',
    request: '',
    is_overwrite_customer_info: '',
});

const submit = () => {
    form.is_overwrite_customer_info = form.is_overwrite_customer_info
        ? '1'
        : '0';

    form.seminar_id = String(props.seminar.id);
    // customer.idがnullでない場合のみセット
    if (props.customer != null) {
        form.customer_id = String(props.customer.id);
    }

    form.post(route('seminar_customers.entry_store'), {
        onSuccess: () => {
            form.reset(
                'seminar_id',
                'customer_id',
                'name',
                'furigana',
                'tel',
                'email',
                'todofuken',
                'co_name',
                'co_tel',
                'co_busho',
                'co_post',
                'applicant_count',
                'request',
                'is_overwrite_customer_info',
            );
        },
    });
};
</script>

<template>
    <Head title="セミナー申し込み" />
    <PublicLayout>
        <div class="mx-auto mt-8 max-w-md rounded bg-white p-6 shadow">
            <h2 class="mb-4 text-center text-xl font-bold">
                【{{ seminar.name }}】<br />
                {{ formatDateWithWeekday(seminar.onsite_date) }}
            </h2>

            <div class="mb-4">
                <!-- 開催形式ごとの表示切り替え -->
                <template v-if="seminar.seminar_type === 'onsite'">
                    ▼講義時間<br />{{
                        formatTime(seminar.onsite_start_time)
                    }}〜{{ formatTime(seminar.onsite_end_time) }}（{{
                        getDurationMinutes(
                            seminar.onsite_start_time,
                            seminar.onsite_end_time,
                        )
                    }}分）<br /><br />
                    ▼受講料<br />{{
                        seminar.is_paid == '1'
                            ? seminar.paid_fee.toLocaleString() + '円'
                            : '無料'
                    }}<br /><br />

                    開催形式：現地開催<br />
                    会場名：{{ seminar.onsite_name }}<br />
                    郵便番号：{{ seminar.onsite_zip }}<br />
                    住所：{{ seminar.onsite_address }}<br />
                    {{ seminar.onsite_building }}<br />
                    地図URL：<a
                        :href="seminar.onsite_map_url"
                        target="_blank"
                        >{{ seminar.onsite_map_url }}</a
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
                    <h2 class="mb-4 text-center text-xl font-bold">
                        【お客様情報】
                    </h2>
                    <div class="mb-4">
                        <label class="mb-1 block font-medium"
                            >氏名<span class="ml-1 text-xs text-red-600"
                                >※必須</span
                            ></label
                        >
                        <input
                            v-model="form.name"
                            type="text"
                            class="w-full rounded border px-3 py-2"
                            required
                        />
                    </div>
                    <div class="mb-4">
                        <label class="mb-1 block font-medium"
                            >ふりがな<span class="ml-1 text-xs text-red-600"
                                >※必須</span
                            ></label
                        >
                        <input
                            v-model="form.furigana"
                            type="text"
                            class="w-full rounded border px-3 py-2"
                            required
                        />
                    </div>
                    <div class="mb-4">
                        <label class="mb-1 block font-medium">電話番号</label>
                        <input
                            v-model="form.tel"
                            type="text"
                            class="w-full rounded border px-3 py-2"
                        />
                    </div>
                    <div class="mb-4">
                        <label class="mb-1 block font-medium"
                            >メールアドレス<span
                                class="ml-1 text-xs text-red-600"
                                >※必須</span
                            ></label
                        >
                        <input
                            v-model="form.email"
                            type="email"
                            class="w-full rounded border px-3 py-2"
                            required
                        />
                    </div>
                    <div class="mb-4">
                        <label class="mb-1 block font-medium">都道府県</label>
                        <select
                            id="todofuken"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            v-model="form.todofuken"
                        >
                            <option value="">選択してください</option>
                            <option
                                v-for="todofuken in todofukenList"
                                :key="todofuken"
                                :value="todofuken"
                            >
                                {{ todofuken }}
                            </option>
                        </select>
                    </div>
                </div>

                <div class="mb-5">
                    <h2 class="mb-4 text-center text-xl font-bold">
                        【会社情報】
                    </h2>

                    <div class="mb-4">
                        <label class="mb-1 block font-medium">会社名</label>
                        <input
                            v-model="form.co_name"
                            type="text"
                            class="w-full rounded border px-3 py-2"
                        />
                    </div>
                    <div class="mb-4">
                        <label class="mb-1 block font-medium">電話番号</label>
                        <input
                            v-model="form.co_tel"
                            type="text"
                            class="w-full rounded border px-3 py-2"
                        />
                    </div>
                    <div class="mb-4">
                        <label class="mb-1 block font-medium">部署</label>
                        <input
                            v-model="form.co_busho"
                            type="text"
                            class="w-full rounded border px-3 py-2"
                        />
                    </div>
                    <div class="mb-4">
                        <label class="mb-1 block font-medium">役職</label>
                        <input
                            v-model="form.co_post"
                            type="text"
                            class="w-full rounded border px-3 py-2"
                        />
                    </div>
                </div>

                <div class="mb-5">
                    <h2 class="mb-4 text-center text-xl font-bold">
                        【その他】
                    </h2>

                    <div class="mb-4">
                        <label class="mb-1 block font-medium">参加人数</label>
                        <input
                            v-model="form.applicant_count"
                            type="number"
                            class="w-full rounded border px-3 py-2"
                            required
                        />
                    </div>
                    <div class="mb-4">
                        <label class="mb-1 block font-medium"
                            >ご質問・ご要望など</label
                        >
                        <textarea
                            v-model="form.request"
                            class="w-full rounded border px-3 py-2"
                            rows="4"
                        ></textarea>
                    </div>
                </div>

                <p class="mb-4 text-xs text-red-600">
                    ※ご登録いただいたメールアドレスには、今後関連するセミナー情報をお送りする場合がございます。
                </p>

                <div class="mb-4 text-center" v-if="props.customer">
                    <label class="inline-flex items-center">
                        <input
                            type="checkbox"
                            v-model="form.is_overwrite_customer_info"
                            class="form-checkbox"
                            :true-value="'1'"
                            :false-value="''"
                        />
                        <span class="ml-2"
                            >入力した内容を登録して次回も利用する</span
                        >
                    </label>
                </div>

                <button
                    type="submit"
                    class="w-full rounded bg-blue-600 py-2 text-white"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    同意して申し込む
                </button>
            </form>
        </div>
    </PublicLayout>
</template>
