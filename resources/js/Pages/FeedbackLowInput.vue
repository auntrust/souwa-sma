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
        <div class="mx-auto mt-8 max-w-xl rounded bg-white p-6 shadow">
            <form @submit.prevent="submit">
                <h2 class="mb-4 text-center text-xl font-bold">
                    【ご評価ありがとうございます】
                </h2>
                <div class="mb-5 text-center">
                    ご満足いただけなかった点があれば、<br />
                    ぜひお聞かせください
                </div>
                <div class="mb-5 text-center">
                    <label class="mb-1 block font-medium"
                        >今後の改善に活かすため、<br />
                        率直なご意見をお待ちしております。</label
                    >
                    <textarea
                        v-model="form.survey_opinion"
                        class="w-full rounded border px-3 py-2"
                        rows="4"
                    ></textarea>
                    <button
                        type="submit"
                        class="w-full rounded bg-blue-600 py-2 text-white"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                    >
                        ご意見を送信する
                    </button>
                </div>
            </form>
        </div>
    </PublicLayout>
</template>
