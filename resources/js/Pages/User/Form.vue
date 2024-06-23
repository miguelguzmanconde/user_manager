<script setup>

    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import InputError from '@/Components/InputError.vue';
    import InputLabel from '@/Components/InputLabel.vue';
    import PrimaryButton from '@/Components/PrimaryButton.vue';
    import TextInput from '@/Components/TextInput.vue';
    import PasswordForm from './PasswordForm.vue';
    import { Head, useForm } from '@inertiajs/vue3';

    const props = defineProps({
        user: {
            type: Object,
        },
        canResetPassword: {
            type: Boolean,
        },
        status: {
            type: String,
        },
    });

    const types = [
        {
            id: 'guest',
            name: 'Guest'
        },{
            id: 'admin',
            name: 'Administrator'
        }
    ];

    const statuses = [
        {
            id: 'active',
            name: 'Active'
        },{
            id: 'inactive',
            name: 'Inactive'
        }
    ];

    const form = useForm(props.user ?
    {
        id: props.user.id,
        email: props.user.email,
        name: props.user.name,
        type: props.user.type,
        status: props.user.status,
    } :
    {
        id: -1,
        email: '',
        name: '',
        type: 'guest',
        password: '123456789',
        status: 'active',
    });

    const submit = () => {
        if (props.user)
            form.put(route('user.update'), {
                onFinish: () => form.reset(),
            });
        else
            form.post(route('user.store'), {
                onFinish: () => form.reset(),
            });
    };

</script>

<template>
    <AuthenticatedLayout>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-300">

                        <div v-if="user">
                            <Head :title="'Editing user: '.concat(user.name)" />
                        </div>

                        <div v-else>
                            <Head title="Create a new user" />
                        </div>

                        <form @submit.prevent="submit">

                            <div>

                                <InputLabel for="type" value="Type" />
                                <v-select
                                    id="type"
                                    v-model="form.type"
                                    :items="types"
                                    item-title="name"
                                    item-value="id"
                                    :persistent-hint="false"
                                    :single-line="false"
                                    return-object
                                    class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                    ></v-select>

                                <InputError class="mt-2" :message="form.errors.type" />

                            </div>

                            <div>

                                <InputLabel for="email" value="Email" />

                                <TextInput
                                    id="email"
                                    type="email"
                                    class="mt-1 block w-full"
                                    v-model="form.email"
                                    required
                                    autofocus
                                    autocomplete="username"
                                />

                                <InputError class="mt-2" :message="form.errors.email" />

                            </div>

                            <div>

                                <InputLabel for="name" value="Name" />

                                <TextInput
                                    id="name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.name"
                                    required
                                    autofocus
                                    autocomplete="name"
                                />

                                <InputError class="mt-2" :message="form.errors.name" />

                            </div>

                            <div>

                                <InputLabel for="status" value="Status" />

                                <v-select
                                    id="status"
                                    v-model="form.status"
                                    :items="statuses"
                                    item-title="name"
                                    item-value="id"
                                    :persistent-hint="false"
                                    :single-line="false"
                                    return-object
                                    class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                    >
                                </v-select>

                                <InputError class="mt-2" :message="form.errors.status" />

                            </div>

                            <div v-if="user === undefined || user === null">

                                <InputLabel value="Password" />

                                <h2 class="text-lg font-medium text-gray-900">Every new User created through this module, will have <strong class="font-large">'123456789'</strong> as a Password</h2>

                            </div>

                            <div class="flex items-center justify-end mt-4">

                                <div v-if="user">

                                    <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                        Update
                                    </PrimaryButton>

                                </div>

                                <div v-else>

                                    <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                        Save
                                    </PrimaryButton>

                                </div>

                                <Transition
                                    enter-active-class="transition ease-in-out"
                                    enter-from-class="opacity-0"
                                    leave-active-class="transition ease-in-out"
                                    leave-to-class="opacity-0"
                                >
                                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600 dark:text-gray-400">Saved.</p>
                                </Transition>

                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>

        <div class="py-2" v-if="user">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-300">

                        <PasswordForm
                            class="max-w-xl"
                            :id="form.id"
                        />

                    </div>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>

<style scoped>
    >>> .v-input .v-input__details {
        display: none !important;
    }
</style>
