<template>
    <div class="mt-10 sm:mt-0">
        <Card>
            <CardHeader>
                <CardTitle>Delete Account</CardTitle>
                <CardDescription>
                    Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.
                </CardDescription>
            </CardHeader>
            <CardContent>
                <Button variant="destructive" @click="confirmUserDeletion">
                    Delete Account
                </Button>
            </CardContent>
        </Card>

        <Dialog :open="confirmingUserDeletion" @update:open="confirmingUserDeletion = false">
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>Are you sure you want to delete your account?</DialogTitle>
                    <DialogDescription>
                        Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.
                    </DialogDescription>
                </DialogHeader>
                <form @submit.prevent="deleteUser">
                    <div class="mt-4">
                        <Input
                            type="password"
                            placeholder="Password"
                            v-model="form.password"
                            required
                            autofocus
                        />
                        <InputError :message="form.errors.password" class="mt-2" />
                    </div>
                    <DialogFooter class="mt-6">
                        <Button variant="outline" @click="confirmingUserDeletion = false">
                            Cancel
                        </Button>
                        <Button variant="destructive" :disabled="form.processing">
                            Delete Account
                        </Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { DangerButton } from '@/components/ui/button';
import InputError from '@/components/InputError.vue';

const confirmingUserDeletion = ref(false);
const form = useForm({
    password: '',
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;
};

const deleteUser = () => {
    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => form.reset(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;
    form.reset();
};
</script>