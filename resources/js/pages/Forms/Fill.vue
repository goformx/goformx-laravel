<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

defineProps<{
    formId: string;
}>();
</script>

<template>
    <Head :title="`Form`" />
    <div class="min-h-screen bg-background p-6">
        <div id="form-fill-container" class="mx-auto max-w-2xl">
            <p class="text-muted-foreground">Loading form…</p>
        </div>
    </div>
</template>
