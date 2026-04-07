<template>
    <Select v-bind="props">
        <SelectTrigger :class="triggerClass">
            <SelectValue :placeholder="placeholder" />
        </SelectTrigger>

        <SelectContent>
            <div v-for="item in items" :key="item.value">
                <SelectItem :value="item.value" @click="selectItem(item)">
                    {{ item.label }}
                </SelectItem>
            </div>
        </SelectContent>
    </Select>
</template>

<script setup lang="ts">
    import { computed } from 'vue';
    import * as Select from '@/components/ui/select';

    interface SelectItem {
        value: string | number;
        label: string;
    }

    interface Props {
        items?: SelectItem[];
        placeholder?: string;
        modelValue?: string | number;
        triggerClass?: string;
    }

    const props = withDefaults(defineProps<Props>(), {
        placeholder: 'Select an option',
        items: () => [],
        triggerClass: '',
    });

    const emit = defineEmits<{
        'update:modelValue': [value: string | number];
    }>();

    const selectItem = (item: SelectItem) => {
        emit('update:modelValue', item.value);
    };
</script>
