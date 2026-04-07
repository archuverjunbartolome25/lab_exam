<template>
    <Tabs :default-value="modelValue" @update:model-value="emit('update:modelValue', $event)">
        <TabsList>
            <TabsTrigger v-for="tab in tabs" :key="tab.value" :value="tab.value" :class="tab.value === modelValue ? 'border-b-2 border-foreground' : 'border-transparent text-foreground hover:text-foreground'">
                {{ tab.label }}
            </TabsTrigger>
        </TabsList>

        <TabsContent v-for="tab in tabs" :key="tab.value" :value="tab.value">
            <slot :name="tab.value" />
        </TabsContent>
    </Tabs>
</template>

<script setup lang="ts">
    import * as Tabs from '@/components/ui/tabs';

    interface Tab {
        value: string;
        label: string;
    }

    interface Props {
        tabs?: Tab[];
        modelValue?: string;
    }

    const props = withDefaults(defineProps<Props>(), {
        tabs: () => [],
    });

    const emit = defineEmits<{
        'update:modelValue': [value: string];
    }>();
</script>
