<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { dashboard } from '@/routes';

interface Form {
    id?: string;
    ID?: string;
    title?: string;
    description?: string;
    [key: string]: unknown;
}

interface Props {
    forms: Form[];
}

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard().url },
    { title: 'Forms', href: '/forms' },
];
</script>

<template>
    <Head title="Forms" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="flex items-center justify-between">
                <h1 class="text-xl font-semibold">Forms</h1>
            </div>
            <div v-if="forms.length === 0" class="rounded-xl border border-sidebar-border/70 p-8 text-center text-muted-foreground">
                No forms yet.
            </div>
            <div v-else class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                <Link
                    v-for="form in forms"
                    :key="form.id ?? form.ID"
                    :href="`/forms/${form.id ?? form.ID}`"
                    class="block rounded-xl border border-sidebar-border/70 p-4 transition-colors hover:bg-sidebar-accent"
                >
                    <h3 class="font-medium">{{ form.title ?? 'Untitled' }}</h3>
                    <p v-if="form.description" class="mt-1 text-sm text-muted-foreground">
                        {{ form.description }}
                    </p>
                </Link>
            </div>
        </div>
    </AppLayout>
</template>
