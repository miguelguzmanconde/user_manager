<script setup>

    import { Link } from '@inertiajs/vue3';

    const props = defineProps({
        href: {
            type: String,
            required: true,
        },
        active: {
            type: Boolean,
        },
    });

</script>
<template>
    <Link
        class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-500 focus:bg-gray-500 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
        :href="href"
    >
        <slot />
    </Link>
</template>