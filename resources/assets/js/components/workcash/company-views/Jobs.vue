<style scoped>
.jobs .list-actions {
    width: 50px;
    text-align: center;
}

.jobs .job-form .coworker-list {
    max-height: 15rem;
    overflow: auto;
}

.jobs .job-coworker + .job-coworker:before {
    content: ", "
}

.jobs .job-completed {
    opacity: 0.75;
}

.jobs .files-list {
    max-height: 25rem;
    overflow: auto;
}

</style>

<template>
<div class="section jobs">
    <div class="section-title">
        <div>
        <img src="/img/people/tasks.svg" class="icon-section icon-jobs" />
        <h3>
            {{ $t('workcash.company_section_jobs') }}

            <span class="f6 pt2 fr relative" style="top: -7px">
            <a class="btn" @click="addNewJob()">
                {{ $t('workcash.company_add_new_job') }}
            </a>
            </span>
        </h3>
        <ul>
            <li v-for="job in sortedJobs(jobs)" :key="job.id" class="mb3" :class="{'job-completed': job.completed}" >
                <div v-if="job.id == 'separator'">
                    <h3 class="bg-near-white">
                        {{ $t('workcash.company_section_completed_jobs') }}
                    </h3>
                </div>
                <div v-else>
                    <input type="checkbox" v-model="job.completed" @click="toggleComplete(job)" >&nbsp;<strong>{{ job.title }}</strong>
                    <div class="fr">
                        <a class="pointer" @click="editFiles(job)">Files</a>
                        <a class="pointer" @click="editJob(job)">Edit</a>
                        <a class="pointer" @click="deleteJobModal(job)">Delete</a>
                    </div>
                    <div>{{ job.description }}</div>
                    <div v-if="job.coworkers.length > 0">
                        <span class="b">Coworkers: </span>
                        <span class="job-coworker" v-for="coworker in job.coworkers" :key="coworker.id">
                            <a v-if="!coworker.contact_is_partial" :href="'/people/' + coworker.contact_id">{{ coworker.complete_name }}</a>
                            <span v-else>{{ coworker.complete_name }}</span>
                        </span>
                    </div>
                    <div>
                        Files: 
                    </div>
                    <div><span class="b">Start date: </span>{{ job.start_date }}</div>
                    <div v-if="job.completed"><span class="b">End date: </span>{{ job.completed_at }}</div>
                </div>
            </li>
        </ul>
        </div>
    </div>

    <!-- Create/Edit job modal -->
    <sweet-modal ref="jobModal" overlay-theme="dark" :title="jobForm.id ? $t('workcash.job_modal_edit_title') : $t('workcash.job_modal_add_title') ">
    <form ref="jobModalForm" @submit.prevent="modalStoreCoworker()" class="job-form relative">
        <!-- Form Errors -->
        <error :errors="jobForm.errors" class="ph4 pv2" /> 

        <div class="ph4 pv2 bb b--gray-monica">
            <form-input
            v-model="jobForm.title"
            :input-type="'text'"
            :required="true"
            :title="$t('workcash.job_form_name_title')">
            </form-input>
        </div>
        <div class="ph4 pv2 bb b--gray-monica">
            <p class="mb2 b">
            {{ $t('workcash.job_form_description_title') }}
            </p>
            <textarea v-model="jobForm.description" rows="3" class="br2 f5 w-100 ba b--black-40 pa2 outline-0" required ></textarea>
        </div>
        <div class="ph4 pv2 bb b--gray-monica">
            <div v-if="restData.existingCoworkers.length == 0" class="mb2">
                <div class="mb1 mt2 tc">
                <img src="/img/people/no_record_found.svg">
                <p>{{$t('workcash.job_form_no_contacts')}}</p>
                </div>
            </div>
            <div v-if="restData.existingCoworkers.length > 0" class="mb2">
                <form-select 
                    :title="$t('workcash.job_form_coworkers_dropdown')"
                    :placeholder="$t('workcash.job_form_coworkers_dropdown')" 
                    :label="'complete_name'" 
                    :options="restData.existingCoworkers"
                    :required="false"
                    @input="modalAddCoworker" />
            </div>
            <div class="coworker-list">
                <ul v-if="jobForm.coworkers.length > 0" class="table">
                    <li v-for="(coworker, index) in jobForm.coworkers" :key="coworker.id" class="table-row">
                        <div class="table-cell">
                            {{ coworker.name + ', ' + coworker.role }}
                        </div>
                        <div class="table-cell list-actions">
                            <span class="pointer" @click="modalRemoveCoworker(index)">
                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                            </span>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <div class="ph4 pv2 ">
            <p class="mb2 b">
            {{ $t('workcash.job_form_start_date') }}
            </p>
            <input v-model="jobForm.start_date" type="date" class="br2 f5 w-100 ba b--black-40 pa2 outline-0"  required>
        </div>
        <div class="ph4 pv2 bb b--gray-monica">
            <div class="mb3 mb0-ns">
                <label class="pa0 ma0 lh-copy pointer" for="jobCompleted">
                <input type="checkbox" id="jobCompleted" v-model="jobForm.completed"> {{ $t('workcash.job_form_completed_title') }}
                </label>
            </div>
        </div>
        <div class="ph4-ns ph3 pv3 bb b--gray-monica flex-ns justify-end">
          <a class="btn mr3" @click="cancelJob()">
            {{ $t('app.cancel') }}
          </a>
          <a class="btn btn-primary" @click="storeJob()">
            {{ $t('app.save') }}
          </a>
        </div>
    </form>
    </sweet-modal>

    <!-- Delete Job data -->
    <sweet-modal ref="deleteModal" overlay-theme="dark" :title="$t('workcash.job_modal_delete_title')">
        <div class="ph4-ns ph3 pv3 mb4">
            <p class="mb2">
            {{ $t('workcash.job_modal_delete_desc', {name: deleteForm.name}) }}
            </p>
        </div>
        <div class="ph4-ns ph3 pv3 flex-ns justify-end" >
            <span >
                <a class="btn mr3" @click="closeDeleteModal()">
                    {{ $t('app.cancel') }}
                </a>
                <a class="btn btn-primary" @click="deleteJob()">
                    {{ $t('app.delete') }}
                </a>
            </span>
        </div>
    </sweet-modal>
    
    <!-- File Management popup -->
    <sweet-modal ref="filesModal" overlay-theme="dark" title="File management">
    <div>
        <div class="ph4 pv2 bb b--gray-monica">
            <p class="mb2 b">
            Files related to {{filesForm.job.title}}
            </p>
            <div class="files-list">
                <ul class="table">
                    <li v-for="(file, index) in filesForm.files" :key="file.name" class="table-row">
                        <div class="table-cell">
                            {{ file.name }}
                        </div>
                        <div class="table-cell list-actions">
                            <span class="pointer" @click="removeFile(index)">
                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                            </span>
                        </div>
                    </li>
                </ul>
                <div class="progress">
                    <div class="progress-bar progress-bar-striped active" role="progressbar"
                        aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:40%">
                        Uploading...
                    </div>
                </div>
                <div class="tc" >
                    <label for="job-file-upload"><a class="pointer">Upload</a></label>
                    <input id="job-file-upload" type="file" style="display: none" @change="onFilesSelected" multiple >
                </div>
            </div>
        </div>
        <div class="ph4-ns ph3 pv3 bb b--gray-monica flex-ns justify-end">
          <a class="btn mr3" @click="cancelFiles()">
            {{ $t('app.cancel') }}
          </a>
          <a class="btn btn-primary" @click="updateFiles()">
            {{ $t('app.save') }}
          </a>
        </div>
    </div>
    </sweet-modal>


</div>
</template>

<script>

import { SweetModal } from 'sweet-modal-vue';
import Error from '../../partials/Error.vue';
import vSelect from 'vue-select';

export default {
    
    components: {
        SweetModal,
        Error,
        vSelect
    },

    props: {
        jobModalEditMode: false,
        companyId: {
            type: String,
            default: '',
        }
    },
    
    data() {
        return {
            jobs: [],
            restData: {
                existingCoworkers: [],
            },
            jobForm: {
                errors: [],
                id: '',
                title: '',
                description: '',
                coworkers: [],
                start_date: '',
                completed: false,
            },
            deleteForm: {
                id: 0,
                name: ''
            },
            filesForm: {
                job: {},
                files: []
            }
        }
    },
    mounted() {
        this.getJobs();
    },
    methods: {
        getJobs() {
            axios.get('/workcash/companies/' + this.companyId + '/jobs')
                .then(response => {
                    this.jobs = response.data.data;
                    
                });
        },
        sortedJobs(jobs) {
            
            var notCompleted = jobs.filter(function (job) {
                return job.completed === false;
            });

            var completed = jobs.filter(function (job) {
                return job.completed === true;
            });

            var separator = [];
            
            if(completed.length > 0) {
                separator.push({id: 'separator'});
            };

            return notCompleted.concat(separator, completed);
        },
        errorHandler(error) {
            this.jobForm.errors = [this.$t('app.error_try_again')];
        },
        loadRestData(callback) {
            axios.get('/workcash/companies/' + this.companyId + '/coworkers')
                .then(response => {
                    this.restData.existingCoworkers = response.data;
                    if(callback) {
                        callback();
                    }
                });
        },
        addNewJob() {
            this.jobForm.error = [];
            this.jobForm.id = '';
            this.jobForm.title = '';
            this.jobForm.description = '';
            this.jobForm.coworkers = [];
            this.jobForm.start_date = '';
            this.jobForm.completed = false;

            var vm=this;
            this.loadRestData(function() {
                vm.$refs.jobModal.open();
            });
        },
        modalAddCoworker(coworker_id) {
            if(coworker_id) {
                var coworker = this.restData.existingCoworkers.find(function(item) {
                    return item.id == coworker_id;
                });
                if(coworker) {
                    this.jobForm.coworkers.push(coworker);
                }
            }
        },
        modalRemoveCoworker(index) {
            this.jobForm.coworkers.splice(index, 1);
        },
        cancelJob() {
            this.$refs.jobModal.close();
        },
        storeJob() {
            if(!this.$refs.jobModalForm.reportValidity()) {
                return;
            }

            var formData = new FormData();
            if(this.jobForm.id) {
                formData.append('id', this.jobForm.id);
            }
            formData.append('company_id', this.companyId);
            formData.append('title', this.jobForm.title);
            formData.append('description', this.jobForm.description);
            for(let i=0; i<this.jobForm.coworkers.length; i++)
            {
                formData.append('coworkers['+i+']', this.jobForm.coworkers[i].id);
            }
            formData.append('start_date', this.jobForm.start_date);
            if(this.jobForm.completed)
            {
                formData.append('completed', '1');
            }

            if(!this.jobForm.id) {
                axios.post('/workcash/companies/' + this.companyId + '/jobs', formData)
                    .then(response => {
                        this.$refs.jobModal.close();
                        this.getJobs();
                    })
                    .catch(this.errorHandler);
            }
            else {
                formData.append('_method', 'PUT');
                axios.post('/workcash/companies/' + this.companyId + '/jobs/' + this.jobForm.id, formData)
                    .then(response => {
                        this.$refs.jobModal.close();
                        this.getJobs();
                    })
                    .catch(this.errorHandler);
            }
        },
        editJob(job) {
            this.jobForm.error = [];
            this.jobForm.id = job.id;
            this.jobForm.title = job.title;
            this.jobForm.description = job.description;
            this.jobForm.coworkers = [];
            this.jobForm.start_date = job.start_date_str;
            this.jobForm.completed = job.completed;
            var vm=this;
            this.loadRestData(function() {
                for(var index in job.coworkers)
                {
                    vm.modalAddCoworker(job.coworkers[index].id);
                }
                vm.$refs.jobModal.open();
            });
        },
        toggleComplete(job) {
            var data = {};
            if(job.completed) {
                data['completed'] = '0';
            }
            else {
                data['completed'] = '1';
            }

            axios.put('/workcash/companies/' + this.companyId + '/jobs/' + job.id + '/completed', data)
                .then(response => {
                    var job_updated = response.data.data;
                    job.completed = job_updated.completed;
                    job.completed_at = job_updated.completed_at;
                    this.$notify({
                        group: 'main',
                        title: this.$t('app.default_save_success'),
                        text: '',
                        type: 'success'
                    });
                })
                .catch(this.errorHandler);
        },
        deleteJobModal(job) {
            this.deleteForm.name = job.title;
            this.deleteForm.id = job.id;
            this.$refs.deleteModal.open();
        },
        closeDeleteModal() {
            this.$refs.deleteModal.close();
        },
        deleteJob() {
            axios.delete('/workcash/companies/' + this.companyId + '/jobs/' + this.deleteForm.id)
                .then(response => {
                    this.$refs.deleteModal.close();
                    this.getJobs();
            })
        },
        editFiles(job) {
            this.filesForm.job = job;

            this.$refs.filesModal.open();
        },
        onFilesSelected(event) {
            var files = event.target.files;
            for(var i=0; i<files.length; i++)
            {
                this.filesForm.files.push(files[i]);
            }
        },
        removeFile(index) {
            this.filesForm.files.splice(index, 1);
        }
    }

}

</script>