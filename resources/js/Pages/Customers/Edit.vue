<script setup lang="ts">
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { getTodofukenList } from '@/utils/todofuken';
import { Head, Link, useForm } from '@inertiajs/vue3';

// 都道府県のリストを取得
const todofukenList = getTodofukenList();

interface CustomerForm {
    name: string;
    furigana: string;
    tel: string;
    email: string;
    todofuken: string;
    co_name: string;
    co_tel: string;
    co_busho: string;
    co_post: string;
    is_delivery: string | boolean;
    optin_at: string;
    optin_method: string;
}

const props = defineProps<{ customer: Partial<CustomerForm> }>();
// console.log(props.seminar);

const form = useForm<CustomerForm>({
    name: props.customer.name ?? '',
    furigana: props.customer.furigana ?? '',
    tel: props.customer.tel ?? '',
    email: props.customer.email ?? '',
    todofuken: props.customer.todofuken ?? '',
    co_name: props.customer.co_name ?? '',
    co_tel: props.customer.co_tel ?? '',
    co_busho: props.customer.co_busho ?? '',
    co_post: props.customer.co_post ?? '',
    is_delivery: props.customer.is_delivery == '1',
    optin_at: props.customer.optin_at ?? '',
    optin_method: props.customer.optin_method ?? '',
});

const submit = () => {
    // チェックボックス値を0/1に変換
    form.is_delivery = form.is_delivery ? '1' : '0';

    form.patch(route('customers.update', props.customer), {
        onSuccess: () => {
            form.reset(
                'name',
                'furigana',
                'tel',
                'email',
                'todofuken',
                'co_name',
                'co_tel',
                'co_busho',
                'co_post',
                'is_delivery',
                'optin_at',
                'optin_method',
            );
        },
    });
};
</script>

<template>
    <Head title="顧客管理" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
            >
                顧客管理
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <form @submit.prevent="submit">
                    <div
                        class="mb-4 overflow-hidden bg-white p-6 shadow-sm sm:rounded-lg dark:bg-gray-800"
                    >
                        <div class="">
                            <label class="mt-1 inline-flex items-center">
                                <input
                                    id="is_delivery"
                                    type="checkbox"
                                    class="form-checkbox h-5 w-5 text-blue-600"
                                    v-model="form.is_delivery"
                                    :true-value="1"
                                    :false-value="0"
                                />
                                <span class="ml-2">配信対象にする</span>
                            </label>

                            <InputError
                                class="mt-2"
                                :message="form.errors.is_delivery"
                            />
                        </div>

                        <div class="mt-4">
                            <InputLabel for="optin_at" value="オプトイン同意" />

                            <TextInput
                                id="optin_at"
                                type="datetime-local"
                                class="mt-1 block w-full"
                                v-model="form.optin_at"
                            />

                            <InputError
                                class="mt-2"
                                :message="form.errors.optin_at"
                            />
                        </div>

                        <div class="mt-4">
                            <InputLabel
                                for="optin_method"
                                value="オプトインの方法"
                            />

                            <TextInput
                                id="optin_method"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.optin_method"
                            />

                            <InputError
                                class="mt-2"
                                :message="form.errors.optin_method"
                            />
                        </div>
                    </div>

                    <div
                        class="mb-4 overflow-hidden bg-white p-6 shadow-sm sm:rounded-lg dark:bg-gray-800"
                    >
                        <div class="mt-4 flex gap-4">
                            <div class="flex-1">
                                <InputLabel for="name" value="氏名" />

                                <TextInput
                                    id="name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.name"
                                    required
                                    autocomplete="name"
                                />

                                <InputError
                                    class="mt-2"
                                    :message="form.errors.name"
                                />
                            </div>

                            <div class="flex-1">
                                <InputLabel for="furigana" value="ふりがな" />

                                <TextInput
                                    id="furigana"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.furigana"
                                />

                                <InputError
                                    class="mt-2"
                                    :message="form.errors.furigana"
                                />
                            </div>
                        </div>

                        <div class="mt-4">
                            <InputLabel for="tel" value="電話番号" />

                            <TextInput
                                id="tel"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.tel"
                            />

                            <InputError
                                class="mt-2"
                                :message="form.errors.tel"
                            />
                        </div>

                        <div class="mt-4">
                            <InputLabel for="email" value="Email" />

                            <TextInput
                                id="email"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.email"
                                required
                            />

                            <InputError
                                class="mt-2"
                                :message="form.errors.email"
                            />
                        </div>

                        <div class="mt-4">
                            <InputLabel for="todofuken" value="都道府県" />

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

                            <InputError
                                class="mt-2"
                                :message="form.errors.todofuken"
                            />
                        </div>
                    </div>

                    <div
                        class="mb-4 overflow-hidden bg-white p-6 shadow-sm sm:rounded-lg dark:bg-gray-800"
                    >
                        <div class="mt-4 flex gap-4">
                            <div class="flex-1">
                                <InputLabel for="co_name" value="会社名" />

                                <TextInput
                                    id="co_name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.co_name"
                                />

                                <InputError
                                    class="mt-2"
                                    :message="form.errors.co_name"
                                />
                            </div>

                            <div class="flex-1">
                                <InputLabel for="co_tel" value="電話番号" />

                                <TextInput
                                    id="co_tel"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.co_tel"
                                />

                                <InputError
                                    class="mt-2"
                                    :message="form.errors.co_tel"
                                />
                            </div>
                        </div>

                        <div class="mt-4 flex gap-4">
                            <div class="flex-1">
                                <InputLabel for="co_busho" value="部署" />

                                <TextInput
                                    id="co_busho"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.co_busho"
                                />

                                <InputError
                                    class="mt-2"
                                    :message="form.errors.co_busho"
                                />
                            </div>

                            <div class="flex-1">
                                <InputLabel for="co_post" value="役職" />

                                <TextInput
                                    id="co_post"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.co_post"
                                />

                                <InputError
                                    class="mt-2"
                                    :message="form.errors.co_post"
                                />
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 flex items-center justify-end">
                        <PrimaryButton
                            class="ms-4"
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                        >
                            更新
                        </PrimaryButton>
                    </div>

                    <div class="mt-4 flex items-center justify-center">
                        <Link
                            :href="route('customers.index')"
                            :class="'rounded-md border bg-gray-500 px-4 py-2 text-xs font-semibold text-white'"
                        >
                            <i class="fa-solid fa-backward"></i> 戻る
                        </Link>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
