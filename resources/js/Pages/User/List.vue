<script setup>

    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import DangerButton from '@/Components/DangerButton.vue';
    import InputError from '@/Components/InputError.vue';
    import InputLabel from '@/Components/InputLabel.vue';
    import Modal from '@/Components/Modal.vue';
    import SecondaryButton from '@/Components/SecondaryButton.vue';
    import TextInput from '@/Components/TextInput.vue';
    import { nextTick, ref } from 'vue'
    import { Head } from '@inertiajs/vue3';
    import { Link, useForm, usePage } from '@inertiajs/vue3';

    const props = defineProps({
        page: {
            type: Number,
            default: 1,
        },
        itemsPerPage: {
            type: Number,
            default: 10,
        },
        search: {
            type: String,
            default: '',
        },
        sortBy: {
            type: Array,
            default: undefined,
        },
    });

    const itemsPerPage = ref(10);
    const search = ref('');
    const serverItems = ref([]);
    const loading = ref(true);
    const totalItems = ref(0);
    const headers = [
        { title: 'Id', key: 'id', align: 'start', },
        { title: 'Name', key: 'name', align: 'start', },
        { title: 'Type', key: 'type', align: 'start' },
        { title: 'status', key: 'status', align: 'end' },
        { title: 'Actions', key: 'actions', align: 'end', sortable: false },
    ];

    const APILaravel = {
        async fetch ({ page, itemsPerPage, sortBy, search }) {
            const calling = axios.post(route('user.list.table'), {
                sortBy: sortBy ?? null,
                page: page,
                itemsPerPage: itemsPerPage,
                search: search
            })
            .then(response => {
                return response.data
            });
            console.log(calling);
            return calling;
        },
    };

    const loadItems = async ({ page, itemsPerPage: pageItemsPerPage, sortBy }) => {
        loading.value = true;
        try {
            const response = await APILaravel.fetch({
                page,
                itemsPerPage: pageItemsPerPage || props.itemsPerPage,
                sortBy: (sortBy !== undefined && sortBy !== null && sortBy.length) ? sortBy : undefined,
                search: { name: search.value, type: '', status: '' },
            });
            console.log(response);
            serverItems.value = response.items;
            totalItems.value = response.total;
        } catch (error) {
            console.error('Error fetching data:', error);
        } finally {
            loading.value = false;
        }
    };

    const confirmingUserDeletion = ref(false);
    const passwordInput = ref(null);

    const deleteForm = useForm({
        id: -1,
        password: '',
    });

    const confirmUserDeletion = (id) => {
        deleteForm.id = id;
        confirmingUserDeletion.value = true;
        nextTick(() => passwordInput.value.focus());
    };

    const deleteUser = () => {
        deleteForm.delete(route('user.destroy'), {
            preserveScroll: true,
            onSuccess: () => closeModal(),
            onError: () => passwordInput.value.focus(),
            onFinish: () => deleteForm.reset(),
        });
    };

    const closeModal = () => {
        confirmingUserDeletion.value = false;
        deleteForm.reset();
    };

</script>

<template>

    <Head title="User's list" />

    <AuthenticatedLayout>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <v-data-table-server
                            v-model:items-per-page="itemsPerPage"
                            :headers="headers"
                            :items="serverItems"
                            :items-length="totalItems"
                            :loading="loading"
                            :search="search"
                            :sort-by="sortBy"
                            item-key="id"
                            item-value="name"
                            @update:options="loadItems"
                        >
                            <template v-slot:top>
                            <v-toolbar flat>
                                <v-toolbar-title>User's list</v-toolbar-title>
                                <v-divider
                                    class="mx-4"
                                    inset
                                    vertical
                                ></v-divider>
                                <v-spacer></v-spacer>
                                <Link :href="route('user.create')">
                                    <v-icon
                                        class="me-2"
                                        size="x-large"
                                    >mdi-plus</v-icon>
                                </Link>
                            </v-toolbar>
                            </template>
                            <template v-slot:item.status="{ item }">
                                <v-chip
                                    :color="item.status == 'active' ? 'green' : 'red'"
                                    :text="item.status == 'active' ? 'Active' : 'Inactive'"
                                    class="text-uppercase"
                                    size="small"
                                    label
                                ></v-chip>
                            </template>
                            <template v-slot:item.actions="{ item }">
                                <Link :href="route('user.edit', {id: item.id})">
                                    <v-icon
                                        class="me-2"
                                        size="large"
                                    >mdi-pencil</v-icon>
                                </Link>
                                <v-icon
                                    size="large"
                                    @click="confirmUserDeletion(item.id)"
                                >mdi-delete</v-icon>
                            </template>
                        </v-data-table-server>

                        <Modal :show="confirmingUserDeletion" @close="closeModal">
                            <div class="p-6">
                                <h2 class="text-lg font-medium text-gray-900">
                                    Are you sure you want to delete your account?
                                </h2>

                                <p class="mt-1 text-sm text-gray-600">
                                    Once your account is deleted, all of its resources and data will be permanently deleted. Please
                                    enter your password to confirm you would like to permanently delete your account.
                                </p>

                                <div class="mt-6">
                                    <InputLabel for="password" value="Password" class="sr-only" />

                                    <TextInput
                                        id="password"
                                        ref="passwordInput"
                                        v-model="deleteForm.password"
                                        type="password"
                                        class="mt-1 block w-3/4"
                                        placeholder="Password"
                                        @keyup.enter="deleteUser"
                                    />

                                    <InputError :message="deleteForm.errors.password" class="mt-2" />
                                </div>

                                <div class="mt-6 flex justify-end">
                                    <SecondaryButton @click="closeModal"> Cancel </SecondaryButton>

                                    <DangerButton
                                        class="ms-3"
                                        :class="{ 'opacity-25': deleteForm.processing }"
                                        :disabled="deleteForm.processing"
                                        @click="deleteUser"
                                    >
                                        Delete Account
                                    </DangerButton>
                                </div>
                            </div>
                        </Modal>
                    </div>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>

</template>