<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Badge } from '@/components/ui/badge';
import { Card } from '@/components/ui/card';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Checkbox } from '@/components/ui/checkbox';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { type BreadcrumbItem } from '@/types';

interface QueryRule {
    id: string;
    field: string;
    operator: string;
    value: string;
    logic?: 'and' | 'or';
}

interface SavedQuery {
    id: number;
    name: string;
    description?: string;
    criteria: QueryRule[];
    type: string;
    is_public: boolean;
}

const props = defineProps<{
    savedQueries?: SavedQuery[];
    recentQueries?: Array<{
        query: string;
        result_count: number;
        executed_at: string;
    }>;
}>();

const queryName = ref('');
const queryDescription = ref('');
const queryRules = ref<QueryRule[]>([
    { id: '1', field: 'program', operator: 'equals', value: 'BSIT' }
]);
const queryResults = ref<any[]>([]);
const isRunning = ref(false);
const showResults = ref(false);

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Students',
        href: '/students',
    },
    {
        title: 'Advanced Query',
        href: '/students/query',
    },
];

const availableFields = [
    { value: 'program', label: 'Program', type: 'select' },
    { value: 'standing', label: 'Year Standing', type: 'select' },
    { value: 'current_gpa', label: 'Current GPA', type: 'number' },
    { value: 'total_credits', label: 'Total Credits', type: 'number' },
    { value: 'active_violations', label: 'Active Violations', type: 'number' },
    { value: 'skills.name', label: 'Skills', type: 'text' },
    { value: 'affiliations.name', label: 'Affiliations', type: 'text' },
    { value: 'violations.severity', label: 'Violation Severity', type: 'select' },
    { value: 'achievements.type', label: 'Achievement Type', type: 'select' },
];

const operators = [
    { value: 'equals', label: 'Equals' },
    { value: 'not_equals', label: 'Not Equals' },
    { value: 'greater_than', label: 'Greater Than' },
    { value: 'less_than', label: 'Less Than' },
    { value: 'greater_equal', label: 'Greater or Equal' },
    { value: 'less_equal', label: 'Less or Equal' },
    { value: 'contains', label: 'Contains' },
    { value: 'not_contains', label: 'Not Contains' },
    { value: 'is_null', label: 'Is Empty' },
    { value: 'is_not_null', label: 'Is Not Empty' },
];

const logicOperators = [
    { value: 'and', label: 'AND' },
    { value: 'or', label: 'OR' },
];

const queryTemplates = [
    {
        name: 'Basketball Try-outs Eligibility',
        description: 'Students eligible for basketball team',
        rules: [
            { id: '1', field: 'current_gpa', operator: 'greater_equal', value: '2.5', logic: 'and' },
            { id: '2', field: 'active_violations', operator: 'equals', value: '0', logic: 'and' },
            { id: '3', field: 'standing', operator: 'not_equals', value: '1st Year' },
        ]
    },
    {
        name: 'Programming Contest Ready',
        description: 'Students ready for programming competitions',
        rules: [
            { id: '1', field: 'skills.name', operator: 'contains', value: 'programming', logic: 'and' },
            { id: '2', field: 'skills.level', operator: 'greater_equal', value: 'intermediate', logic: 'and' },
            { id: '3', field: 'current_gpa', operator: 'greater_equal', value: '3.0' },
        ]
    },
    {
        name: 'Dean\'s List',
        description: 'Students with outstanding academic performance',
        rules: [
            { id: '1', field: 'current_gpa', operator: 'greater_equal', value: '3.5' },
            { id: '2', field: 'total_credits', operator: 'greater_equal', value: '12', logic: 'and' },
            { id: '3', field: 'active_violations', operator: 'equals', value: '0', logic: 'and' },
        ]
    },
    {
        name: 'At-Risk Students',
        description: 'Students who may need academic intervention',
        rules: [
            { id: '1', field: 'current_gpa', operator: 'less_than', value: '2.0' },
            { id: '2', field: 'active_violations', operator: 'greater_than', value: '2', logic: 'or' },
        ]
    },
];

const addRule = () => {
    const newId = (Math.max(...queryRules.value.map(r => parseInt(r.id))) + 1).toString();
    queryRules.value.push({
        id: newId,
        field: 'program',
        operator: 'equals',
        value: '',
        logic: 'and'
    });
};

const removeRule = (id: string) => {
    queryRules.value = queryRules.value.filter(rule => rule.id !== id);
};

const loadTemplate = (template: any) => {
    queryRules.value = [...template.rules];
    queryName.value = template.name;
    queryDescription.value = template.description;
};

const runQuery = async () => {
    isRunning.value = true;
    try {
        const response = await router.post('/students/query/run', {
            name: queryName.value,
            description: queryDescription.value,
            rules: queryRules.value
        });
        
        queryResults.value = response.props.results;
        showResults.value = true;
    } catch (error) {
        console.error('Query failed:', error);
    } finally {
        isRunning.value = false;
    }
};

const saveQuery = async () => {
    try {
        await router.post('/students/query/save', {
            name: queryName.value,
            description: queryDescription.value,
            criteria: queryRules.value,
            type: 'custom'
        });
    } catch (error) {
        console.error('Save failed:', error);
    }
};

const exportResults = () => {
    const csv = [
        ['Student ID', 'Name', 'Program', 'GPA', 'Credits', 'Violations'],
        ...queryResults.value.map(student => [
            student.stud_id,
            `${student.fname} ${student.lname}`,
            student.program,
            student.current_gpa,
            student.total_credits,
            student.active_violations
        ])
    ].map(row => row.join(',')).join('\n');

    const blob = new Blob([csv], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `student_query_results_${new Date().toISOString().split('T')[0]}.csv`;
    a.click();
    window.URL.revokeObjectURL(url);
};

const getFieldType = (field: string) => {
    const fieldInfo = availableFields.find(f => f.value === field);
    return fieldInfo?.type || 'text';
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Advanced Student Query" />

        <div class="space-y-6">
            <!-- Query Builder -->
            <Card class="p-6">
                <div class="space-y-4">
                    <!-- Query Info -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <Label for="query-name">Query Name</Label>
                            <Input
                                id="query-name"
                                v-model="queryName"
                                placeholder="Enter query name..."
                            />
                        </div>
                        <div>
                            <Label for="query-description">Description</Label>
                            <Input
                                id="query-description"
                                v-model="queryDescription"
                                placeholder="Brief description of this query..."
                            />
                        </div>
                    </div>

                    <!-- Quick Templates -->
                    <div>
                        <Label class="text-base font-medium">Quick Templates</Label>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-2 mt-2">
                            <Button
                                v-for="template in queryTemplates"
                                :key="template.name"
                                variant="outline"
                                size="sm"
                                @click="loadTemplate(template)"
                                class="text-left h-auto p-3"
                            >
                                <div class="font-medium">{{ template.name }}</div>
                                <div class="text-xs text-gray-500">{{ template.description }}</div>
                            </Button>
                        </div>
                    </div>

                    <!-- Query Rules -->
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <Label class="text-base font-medium">Query Rules</Label>
                            <Button size="sm" @click="addRule">+ Add Rule</Button>
                        </div>

                        <div class="space-y-3">
                            <div
                                v-for="(rule, index) in queryRules"
                                :key="rule.id"
                                class="flex items-center space-x-3 p-4 border rounded-lg bg-gray-50"
                            >
                                <!-- Logic Operator (except first rule) -->
                                <div v-if="index > 0" class="w-20">
                                    <Select v-model="rule.logic">
                                        <SelectTrigger>
                                            <SelectValue />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem
                                                v-for="op in logicOperators"
                                                :key="op.value"
                                                :value="op.value"
                                            >
                                                {{ op.label }}
                                            </SelectItem>
                                        </SelectContent>
                                    </Select>
                                </div>

                                <!-- Field -->
                                <div class="flex-1">
                                    <Select v-model="rule.field">
                                        <SelectTrigger>
                                            <SelectValue />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem
                                                v-for="field in availableFields"
                                                :key="field.value"
                                                :value="field.value"
                                            >
                                                {{ field.label }}
                                            </SelectItem>
                                        </SelectContent>
                                    </Select>
                                </div>

                                <!-- Operator -->
                                <div class="flex-1">
                                    <Select v-model="rule.operator">
                                        <SelectTrigger>
                                            <SelectValue />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem
                                                v-for="op in operators"
                                                :key="op.value"
                                                :value="op.value"
                                            >
                                                {{ op.label }}
                                            </SelectItem>
                                        </SelectContent>
                                    </Select>
                                </div>

                                <!-- Value -->
                                <div class="flex-1">
                                    <Input
                                        v-if="getFieldType(rule.field) === 'text'"
                                        v-model="rule.value"
                                        placeholder="Enter value..."
                                    />
                                    <Input
                                        v-else-if="getFieldType(rule.field) === 'number'"
                                        v-model="rule.value"
                                        type="number"
                                        step="0.01"
                                        placeholder="Enter number..."
                                    />
                                    <Select v-else>
                                        <SelectTrigger>
                                            <SelectValue />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem value="BSIT">BSIT</SelectItem>
                                            <SelectItem value="BSCS">BSCS</SelectItem>
                                            <SelectItem value="BSM">BSM</SelectItem>
                                            <SelectItem value="BSA">BSA</SelectItem>
                                            <SelectItem value="1st Year">1st Year</SelectItem>
                                            <SelectItem value="2nd Year">2nd Year</SelectItem>
                                            <SelectItem value="3rd Year">3rd Year</SelectItem>
                                            <SelectItem value="4th Year">4th Year</SelectItem>
                                            <SelectItem value="5th Year">5th Year</SelectItem>
                                            <SelectItem value="minor">Minor</SelectItem>
                                            <SelectItem value="major">Major</SelectItem>
                                            <SelectItem value="critical">Critical</SelectItem>
                                            <SelectItem value="beginner">Beginner</SelectItem>
                                            <SelectItem value="intermediate">Intermediate</SelectItem>
                                            <SelectItem value="advanced">Advanced</SelectItem>
                                            <SelectItem value="expert">Expert</SelectItem>
                                        </SelectContent>
                                    </Select>
                                </div>

                                <!-- Remove Button -->
                                <Button
                                    variant="destructive"
                                    size="sm"
                                    @click="removeRule(rule.id)"
                                >
                                    Remove
                                </Button>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex space-x-3">
                        <Button @click="runQuery" :disabled="isRunning">
                            {{ isRunning ? 'Running...' : 'Run Query' }}
                        </Button>
                        <Button variant="outline" @click="saveQuery" :disabled="!queryName">
                            Save Query
                        </Button>
                    </div>
                </div>
            </Card>

            <!-- Results -->
            <Card v-if="showResults" class="p-6">
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold">Query Results</h3>
                        <div class="flex space-x-2">
                            <Badge class="bg-blue-100 text-blue-800">
                                {{ queryResults.length }} Students Found
                            </Badge>
                            <Button variant="outline" size="sm" @click="exportResults">
                                Export CSV
                            </Button>
                        </div>
                    </div>

                    <div v-if="queryResults.length > 0" class="overflow-x-auto">
                        <table class="w-full border-collapse">
                            <thead>
                                <tr class="border-b">
                                    <th class="text-left p-2 font-medium">Name</th>
                                    <th class="text-left p-2 font-medium">Student ID</th>
                                    <th class="text-left p-2 font-medium">Program</th>
                                    <th class="text-left p-2 font-medium">GPA</th>
                                    <th class="text-left p-2 font-medium">Credits</th>
                                    <th class="text-left p-2 font-medium">Violations</th>
                                    <th class="text-left p-2 font-medium">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="student in queryResults" :key="student.stud_id" class="border-b">
                                    <td class="p-2">
                                        <Link :href="`/students/${student.stud_id}`" class="text-blue-600 hover:underline">
                                            {{ student.fname }} {{ student.lname }}
                                        </Link>
                                    </td>
                                    <td class="p-2">{{ student.stud_num }}</td>
                                    <td class="p-2">{{ student.program }}</td>
                                    <td class="p-2">{{ student.current_gpa?.toFixed(2) || 'N/A' }}</td>
                                    <td class="p-2">{{ student.total_credits || 0 }}</td>
                                    <td class="p-2">
                                        <Badge :class="student.active_violations > 0 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'">
                                            {{ student.active_violations || 0 }}
                                        </Badge>
                                    </td>
                                    <td class="p-2">
                                        <Button variant="outline" size="sm">
                                            View Profile
                                        </Button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div v-else class="text-center py-8 text-gray-500">
                        <div class="text-4xl mb-4">🔍</div>
                        <h3 class="text-lg font-semibold mb-2">No results found</h3>
                        <p>Try adjusting your query criteria and run again.</p>
                    </div>
                </div>
            </Card>
        </div>
    </AppLayout>
</template>
