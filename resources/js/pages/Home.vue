<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import {
    FileText,
    Zap,
    Shield,
    Code,
    Database,
    Globe,
} from 'lucide-vue-next';
import { dashboard, login, register } from '@/routes';

defineProps<{
    canRegister: boolean;
}>();

const features = [
    {
        icon: FileText,
        title: 'Visual Form Builder',
        description:
            'Create forms visually with drag-and-drop. No coding required.',
    },
    {
        icon: Zap,
        title: 'Real-time Validation',
        description: 'Instant feedback with validation schemas.',
    },
    {
        icon: Shield,
        title: 'Secure by Default',
        description:
            'Built-in CSRF protection, rate limiting, and input sanitization.',
    },
    {
        icon: Code,
        title: 'API-First Design',
        description: 'RESTful API for embedding forms anywhere on the web.',
    },
    {
        icon: Database,
        title: 'Self-Hosted',
        description: 'Your data stays on your servers. Full control and privacy.',
    },
    {
        icon: Globe,
        title: 'CORS Support',
        description: 'Embed forms on any domain with configurable CORS policies.',
    },
];
</script>

<template>
    <div
        class="flex min-h-screen flex-col bg-background text-foreground"
    >
        <Head title="GoFormX – Your Forms, Our Backend" />

        <header
            class="w-full border-b border-border/50 bg-background/80 backdrop-blur-sm"
        >
            <nav
                class="container flex items-center justify-end gap-4 px-4 py-4 sm:px-6"
            >
                <Link
                    v-if="$page.props.auth?.user"
                    :href="dashboard()"
                    class="text-sm font-medium text-muted-foreground transition-colors hover:text-foreground"
                >
                    Dashboard
                </Link>
                <template v-else>
                    <Link
                        :href="login()"
                        class="text-sm font-medium text-muted-foreground transition-colors hover:text-foreground"
                    >
                        Log in
                    </Link>
                    <Link
                        v-if="canRegister"
                        :href="register()"
                        class="rounded-md border border-border bg-background px-4 py-2 text-sm font-medium shadow-sm transition-colors hover:bg-muted/50"
                    >
                        Register
                    </Link>
                </template>
            </nav>
        </header>

        <main class="flex-1">
            <!-- Hero -->
            <section
                class="relative flex min-h-[calc(100vh-4rem)] items-center overflow-hidden py-20 md:py-32"
            >
                <div
                    class="absolute inset-0 bg-gradient-to-b from-background via-background to-background/80"
                />
                <div class="absolute inset-0 overflow-hidden">
                    <div
                        class="absolute top-[20%] left-[10%] h-[500px] w-[500px] rounded-full bg-indigo-500/15 blur-3xl"
                    />
                    <div
                        class="absolute bottom-[10%] right-[10%] h-[400px] w-[400px] rounded-full bg-purple-500/15 blur-3xl"
                    />
                    <div
                        class="absolute right-[30%] top-1/2 h-[300px] w-[300px] rounded-full bg-violet-500/10 blur-3xl"
                    />
                </div>
                <div class="container relative z-10">
                    <div class="flex flex-col items-center text-center">
                        <h1
                            class="text-4xl font-bold tracking-tight sm:text-5xl md:text-6xl lg:text-7xl"
                        >
                            <span
                                class="bg-gradient-to-r from-foreground via-foreground to-foreground/80 bg-clip-text text-transparent"
                            >
                                Your Forms,
                            </span>
                            <br />
                            <span
                                class="bg-gradient-to-r from-indigo-400 via-purple-400 to-pink-400 bg-clip-text text-transparent italic"
                            >
                                Our Backend
                            </span>
                        </h1>
                        <p
                            class="mt-6 max-w-[42rem] text-lg text-muted-foreground sm:text-xl"
                        >
                            Build and host forms with Laravel and Go. Dashboard
                            and form builder in Laravel; form API and public
                            submit in Go.
                        </p>
                        <p class="mt-2 text-sm text-muted-foreground/70">
                            No lock-in. Self-host or use the API.
                        </p>
                        <div
                            class="mt-8 flex flex-col gap-4 sm:flex-row"
                        >
                            <Button
                                v-if="canRegister"
                                size="lg"
                                class="border-0 bg-gradient-to-r from-indigo-500 to-purple-500 hover:from-indigo-600 hover:to-purple-600"
                                as-child
                            >
                                <Link :href="register()">Get started</Link>
                            </Button>
                            <Button
                                v-else
                                size="lg"
                                class="border-0 bg-gradient-to-r from-indigo-500 to-purple-500 hover:from-indigo-600 hover:to-purple-600"
                                as-child
                            >
                                <Link :href="login()">Log in</Link>
                            </Button>
                            <Button
                                size="lg"
                                variant="outline"
                                class="border-border/50 bg-background/50 backdrop-blur hover:bg-background/80"
                                as-child
                            >
                                <a
                                    href="https://github.com/goformx/goforms"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                >
                                    View on GitHub
                                </a>
                            </Button>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Features -->
            <section class="relative py-20">
                <div
                    class="absolute inset-0 bg-gradient-to-b from-transparent via-muted/30 to-transparent"
                />
                <div class="container relative z-10">
                    <div class="mb-12 text-center">
                        <h2 class="text-3xl font-bold tracking-tight sm:text-4xl">
                            Everything You Need
                        </h2>
                        <p class="mt-4 text-lg text-muted-foreground">
                            Powerful features to build and manage forms at scale.
                        </p>
                    </div>
                    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        <Card
                            v-for="feature in features"
                            :key="feature.title"
                            class="border-border/50 bg-card/50 transition-all duration-300 hover:border-border hover:bg-card/70 backdrop-blur-sm"
                        >
                            <CardHeader>
                                <div
                                    class="mb-2 flex h-12 w-12 items-center justify-center rounded-lg bg-gradient-to-br from-indigo-500/20 to-purple-500/20"
                                >
                                    <component
                                        :is="feature.icon"
                                        class="h-6 w-6 text-indigo-400"
                                    />
                                </div>
                                <CardTitle>{{ feature.title }}</CardTitle>
                                <CardDescription>
                                    {{ feature.description }}
                                </CardDescription>
                            </CardHeader>
                        </Card>
                    </div>
                </div>
            </section>

            <!-- CTA -->
            <section class="relative py-20">
                <div class="absolute inset-0 overflow-hidden">
                    <div
                        class="absolute left-1/2 top-1/2 h-[300px] w-[600px] -translate-x-1/2 -translate-y-1/2 rounded-full bg-indigo-500/10 blur-3xl"
                    />
                </div>
                <div class="container relative z-10">
                    <div class="flex flex-col items-center text-center">
                        <h2 class="text-3xl font-bold tracking-tight sm:text-4xl">
                            Ready to Get Started?
                        </h2>
                        <p
                            class="mt-4 max-w-[42rem] text-lg text-muted-foreground"
                        >
                            Create your first form in minutes. No credit card
                            required.
                        </p>
                        <div class="mt-8">
                            <Button
                                v-if="$page.props.auth?.user"
                                size="lg"
                                class="border-0 bg-gradient-to-r from-indigo-500 to-purple-500 hover:from-indigo-600 hover:to-purple-600"
                                as-child
                            >
                                <Link :href="dashboard()">Go to Dashboard</Link>
                            </Button>
                            <Button
                                v-else-if="canRegister"
                                size="lg"
                                class="border-0 bg-gradient-to-r from-indigo-500 to-purple-500 hover:from-indigo-600 hover:to-purple-600"
                                as-child
                            >
                                <Link :href="register()"
                                    >Create Your Account</Link
                                >
                            </Button>
                            <Button
                                v-else
                                size="lg"
                                class="border-0 bg-gradient-to-r from-indigo-500 to-purple-500 hover:from-indigo-600 hover:to-purple-600"
                                as-child
                            >
                                <Link :href="login()">Log in</Link>
                            </Button>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>
</template>
