<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';

defineProps<{
    mustVerifyEmail?: boolean;
    status?: string;
}>();
</script>

<template>
    <Head title="Profile" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-2xl font-bold leading-tight tracking-wide text-gray-800 dark:text-gray-200"
            >
                プロフィール
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-3xl space-y-8 sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden border border-gray-200 bg-white p-8 shadow-xl sm:rounded-2xl dark:bg-gray-800"
                >
                    <UpdateProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                        :status="status"
                        class="max-w-xl"
                    />
                </div>

                <div
                    class="overflow-hidden border border-gray-200 bg-white p-8 shadow-xl sm:rounded-2xl dark:bg-gray-800"
                >
                    <UpdatePasswordForm class="max-w-xl" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
