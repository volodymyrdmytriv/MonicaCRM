<style scoped>
</style>

<template>
<div class="ml3">
    <div class="tc">
        <div class="dib">
            <span @click="updateActiveTab('jobs')" class="f6 fl bb bt br ph3 pv2 dib  bl mb2 b--gray-monica br2 br--left" :class="[activeTab == 'jobs' ? ' bl  b ' : 'bg-gray-monica  pointer ']">Jobs</span>
            <span @click="updateActiveTab('notes')" class="f6 fl bb bt br ph3 pv2 dib  bl mb2 b--gray-monica br2 br--left" :class="[activeTab == 'notes' ? ' bl  b ' : 'bg-gray-monica  pointer ']">Notes</span>
        </div>
    </div>
    <div v-if="activeTab == 'jobs'">
        <company-jobs :company-id="companyId"></company-jobs>
    </div>
    <div v-if="activeTab == 'notes'">
        <div class="row section">
            Notes 
        </div>
    </div>
</div>
</template>

<script>

import Jobs from './company-views/Jobs';

export default {
    components: {
        'company-jobs': Jobs,
    },
    props: {
        companyId: {
            type: String,
            default: '',
        },
        defaultActiveTab: {
            type: String,
            default: 'jobs'
        }
    },
    data() {
        return {
            activeTab: '',
        };
    },
    mounted() {
        this.activeTab = this.defaultActiveTab;
    },
    methods: {
        updateActiveTab(tab) {
            this.activeTab = tab;
            axios.post('/workcash/companies/' + this.companyId + '/set_active_tab', { 'tab': tab })
                .then(response => {
                    
                });
        },
    }
}
</script>