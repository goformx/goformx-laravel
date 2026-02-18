<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';
import { Formio } from '@formio/js';
import goforms from '@goformx/formio';
import { Alert, AlertDescription } from '@/components/ui/alert';
import { AlertCircle, CheckCircle2 } from 'lucide-vue-next';

Formio.use(goforms);

const props = defineProps<{
    formId: string;
}>();

const page = usePage();
const goFormsPublicUrl = computed(() => (page.props.goFormsPublicUrl as string) ?? '');

const schemaUrl = computed(
    () => (goFormsPublicUrl ? `${goFormsPublicUrl}/forms/${props.formId}/schema` : ''),
);
const submitUrl = computed(
    () => (goFormsPublicUrl ? `${goFormsPublicUrl}/forms/${props.formId}/submit` : ''),
);

const status = ref<'loading' | 'form' | 'success' | 'error'>('loading');
const errorMessage = ref<string | null>(null);
const formInstance = ref<unknown>(null);

onMounted(async () => {
    const container = document.getElementById('form-fill-container');
    if (!container || !schemaUrl.value || !submitUrl.value) {
        status.value = 'error';
        errorMessage.value = 'Form temporarily unavailable.';
        return;
    }

    try {
        const response = await fetch(schemaUrl.value);
        if (response.status === 404) {
            status.value = 'error';
            errorMessage.value = 'Form not found.';
            return;
        }
        if (!response.ok) {
            status.value = 'error';
            errorMessage.value = 'Form temporarily unavailable.';
            return;
        }

        const data = await response.json();
        const schema = data?.data ?? data;
        if (!schema?.components?.length) {
            status.value = 'error';
            errorMessage.value = 'Form not found.';
            return;
        }

        const form = await Formio.createForm(container, schema, {
            readOnly: false,
            noAlerts: true,
            noSubmit: true,
        });
        formInstance.value = form;

        form.on('submit', async (submission: { data?: Record<string, unknown> }) => {
            errorMessage.value = null;
            try {
                const res = await fetch(submitUrl.value, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(submission?.data ?? submission),
                });

                if (res.ok) {
                    status.value = 'success';
                    return;
                }
                if (res.status === 422) {
                    const errData = await res.json().catch(() => ({}));
                    const errors = errData?.errors ?? errData?.data?.errors ?? [];
                    if (Array.isArray(errors)) {
                        form.emit('submitError', { errors });
                    } else if (typeof errors === 'object') {
                        form.emit('submitError', { errors });
                    }
                    return;
                }
                if (res.status === 429) {
                    errorMessage.value = 'Too many submissions. Please try again later.';
                    return;
                }
                if (res.status === 404) {
                    errorMessage.value = 'Form no longer available.';
                    return;
                }
                errorMessage.value = 'Submission failed. Please try again.';
            } catch {
                errorMessage.value = 'Submission failed. Please try again.';
            }
        });

        status.value = 'form';
    } catch {
        status.value = 'error';
        errorMessage.value = 'Form temporarily unavailable.';
    }
});
</script>

<template>
    <Head :title="`Form`" />
    <div class="min-h-screen bg-background p-6">
        <div class="mx-auto max-w-2xl space-y-4">
            <Alert v-if="errorMessage" variant="destructive">
                <AlertCircle class="h-4 w-4" />
                <AlertDescription>{{ errorMessage }}</AlertDescription>
            </Alert>

            <div
                v-if="status === 'success'"
                class="rounded-lg border border-border bg-card p-6 text-center"
            >
                <CheckCircle2 class="mx-auto mb-2 h-12 w-12 text-green-600" />
                <h2 class="text-lg font-semibold">Thank you</h2>
                <p class="text-muted-foreground">Your response has been submitted.</p>
            </div>

            <div
                v-show="status === 'loading' || status === 'form'"
                class="rounded-lg border border-border bg-card p-6"
            >
                <div
                    v-if="status === 'loading'"
                    class="py-12 text-center text-muted-foreground"
                >
                    Loading form…
                </div>
                <div id="form-fill-container" class="min-h-[200px]" />
            </div>
        </div>
    </div>
</template>
