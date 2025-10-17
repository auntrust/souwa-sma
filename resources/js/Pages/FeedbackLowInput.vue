<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import PublicLayout from '../Layouts/PublicLayout.vue';

const props = defineProps<{
    seminar: {
        id: number;
        unique_key: string;
        name: string;
        finish_url: string;
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
    survey_opinion: '',
});

const submit = () => {
    form.post(route('seminar_customers.feedback_low_input_store'), {
        onSuccess: () => {
            form.reset('survey_opinion');
        },
    });
};
</script>

<template>
    <Head title="ご評価ありがとうございます" />
    <PublicLayout>
        <div
            class="mx-auto mt-16 max-w-xl rounded-xl border border-gray-100 bg-white p-10 shadow-lg"
        >
            <form @submit.prevent="submit">
                <h2
                    class="mb-8 border-b border-blue-100 pb-4 text-center text-2xl font-extrabold text-blue-900"
                >
                    【ご評価ありがとうございます】
                </h2>
                <div
                    class="mb-8 text-center text-lg font-semibold text-gray-800"
                >
                    ご満足いただけなかった点があれば、<br />
                    ぜひお聞かせください
                </div>
                <div class="mb-8 text-center">
                    <label
                        class="mb-3 block text-base font-medium text-gray-700"
                        >今後の改善に活かすため、<br />
                        率直なご意見をお待ちしております。</label
                    >
                    <textarea
                        v-model="form.survey_opinion"
                        class="w-full rounded-lg border px-3 py-2 transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                        rows="5"
                    ></textarea>
                </div>
                <button
                    type="submit"
                    class="w-full rounded-lg bg-blue-600 py-3 text-lg font-bold text-white shadow transition hover:bg-blue-700 disabled:opacity-40"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    ご意見を送信する
                </button>
            </form>
        </div>
    </PublicLayout>
</template>
