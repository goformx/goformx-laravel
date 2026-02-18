<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { dashboard } from '@/routes';

interface Form {
    id?: string;
    ID?: string;
    title?: string;
    description?: string;
    schema?: unknown;
    [key: string]: unknown;
}

interface Props {
    form: Form;
}

const props = defineProps<Props>();

const formId = props.form.id ?? props.form.ID ?? '';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard().url },
    { title: 'Forms', href: '/forms' },
    { title: props.form.title ?? 'Edit Form', href: `/forms/${formId}` },
];
</script>

<template>
    <Head :title="(form.title as string) ?? 'Edit Form'" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <h1 class="text-xl font-semibold">{{ form.title ?? 'Untitled Form' }}</h1>
            <p v-if="form.description" class="text-muted-foreground">{{ form.description }}</p>
            <div class="rounded-xl border border-sidebar-border/70 p-8 text-center text-muted-foreground">
                Form builder placeholder – edit functionality to be implemented.
            </div>
        </div>
    </AppLayout>
</template>
