<style scoped>
</style>

<template>
<div class="coworkers ba b--near-white br2 bg-gray-monica pa3 mb3 f6">
    <div class="w-100 dt mb1">
        <div class="dtc">
            <h3 class="f6 ttu normal">Coworkers</h3>
        </div>
    </div>

    <div class="mb1">
        <div v-for="coworker in coworkers" :key="coworker.id" class="flex mb3">
            <div class="flex-none">
                <a :href="'/people/' + coworker.contact_id">
                    <img v-if="coworker.has_avatar" v-tooltip.bottom="coworker.complete_name" :src="coworker.avatar_url" class="br4 h3 w3 dib tc" />
                    <div v-else v-tooltip.bottom="coworker.complete_name" :style="{ 'background-color': coworker.default_avatar_color }" class="br4 h3 w3 dib pt3 white tc f4">
                    {{ coworker.initials }}
                    </div>
                </a>
            </div>
            <div class="flex-auto ml2">
                <div class="b">{{ coworker.role }}</div>
                <div>
                    <a v-if="!coworker.contact_is_partial" :href="'/people/' + coworker.contact_id">{{ coworker.complete_name }}</a>
                    <span v-else>{{ coworker.complete_name }}</span>
                </div>
                <div>
                    <div class="fr">
                        <a v-if="coworker.contact_is_partial" class="action-link pointer" @click="editCoworker(coworker)" >Edit</a>
                        <a class="action-link pointer" @click="deleteCoworkerModal(coworker)" >Delete</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- EMPTY BOX - DISPLAY ADD BUTTON -->
    <p class="mb0 tc">
      <a class="pointer" @click="modalAddCoworker">
        Add Coworker
      </a>
    </p>

    <!-- Create/Edit coworker modal -->
    <sweet-modal ref="coworkerModal" overlay-theme="dark" :title="!coworkerModalEditMode? $t('workcash.coworker_modal_add_title') : $t('workcash.coworker_modal_edit_title') ">
      <form ref="coworkerModalForm" @submit.prevent="modalStoreCoworker()" class="relative">
        <!-- Form Errors -->
        <div :is="errorTemplate" :errors="coworkerForm.errors" />
        
        <!-- New contact / link existing -->
        <div v-if="!coworkerModalEditMode" class="pa4-ns ph3 pv2 mb3 mb0-ns bb b--gray-monica">
            <p class="mb2 b">{{ $t('workcash.coworker_form_add_choice') }}</p>
            <div class="dt dt--fixed">
            <div class="dtc pr2">
                <input type="radio" id="new" name="new_contact" value="new" v-model="coworkerForm.contact_type" >
                <label for="new" class="pointer">{{ $t('workcash.coworker_form_create_contact') }}</label>
            </div>
            <div class="dtc">
                <input type="radio" id="existing" name="new_contact" value="existing" v-model="coworkerForm.contact_type" >
                <label for="existing" class="pointer">{{ $t('workcash.coworker_form_associate_contact') }}</label>
            </div>
            </div>
        </div>

        <div v-if="coworkerForm.contact_type == 'new'">
            <div class="pa4-ns ph3 pv2 bb b--gray-monica">
            <div class="mb3 mb0-ns">
                <div class="dt dt--fixed">
                <div class="dtc pr2">
                    <form-input
                    v-model="coworkerForm.first_name"
                    :input-type="'text'"
                    :required="true"
                    :title="$t('workcash.coworker_form_firstname_title')">
                    </form-input>
                </div>
                <div class="dtc">
                    <form-input
                    v-model="coworkerForm.last_name"
                    :input-type="'text'"
                    :required="false"
                    :title="$t('workcash.coworker_form_lastname_title')">
                    </form-input>
                </div>
                </div>
            </div>
            </div>

            <div class="pa4-ns ph3 pv2 bb b--gray-monica">
                <div class="mb3 mb0-ns">
                    <label for="avatar">{{ $t('people.information_edit_avatar') }}</label>
                    <input ref="coworkerAvatarInput" type="file" class="form-control-file" id="avatar">
                </div>
            </div>

            <!-- Gender -->
            <div class="pa4-ns ph3 pv2 mb3 mb0-ns bb b--gray-monica">
            <form-select
                :options="restData.genders"
                :required="true"
                :title="$t('workcash.coworker_form_gender')"
                v-model="coworkerForm.gender_id" >
            </form-select>
            </div>

            <div class="pa4-ns ph3 pv2 bb b--gray-monica">
            <div class="mb3 mb0-ns">
                <label class="pa0 ma0 lh-copy pointer" for="realContact">
                <input type="checkbox" id="realContact" v-model="coworkerForm.real_contact"> {{ $t('people.relationship_form_also_create_contact') }}
                </label>
            </div>
            </div>
        </div>

        <div v-if="coworkerForm.contact_type == 'existing'">
            <div class="pa4-ns ph3 pv2 mb3 mb0-ns bb b--gray-monica">
                <div v-if="restData.existingContacts.length == 0">
                    <div class="mb1 mt2 tc">
                    <img src="/img/people/no_record_found.svg">
                    <p>{{$t('workcash.coworked_form_no_contacts')}}</p>
                    </div>
                </div>
                <div v-if="restData.existingContacts.length > 0">
                    <contact-select
                    :required="true"
                    v-model="coworkerForm.existing_contact_id"
                    :title="$t('workcash.coworked_form_associate_dropdown')"
                    :placeholder="$t('workcash.coworked_form_associate_dropdown_placeholder')"
                    :default-options="restData.existingContacts" >
                    </contact-select>
                </div>
            </div>
        </div>

        <div class="pa4-ns ph3 pv2 bb b--gray-monica">
            <form-input
            v-model="coworkerForm.role"
            :input-type="'text'"
            :required="true"
            :title="$t('workcash.coworker_form_role_title')">
            </form-input>
        </div>

      </form>
      <div class="ph4-ns ph3 pv3 bb b--gray-monica flex-ns justify-end">
          <a class="btn mr3" @click="modalCancelCoworker()">
            {{ $t('app.cancel') }}
          </a>
          <a class="btn btn-primary" @click="modalStoreCoworker()">
            {{ $t('app.save') }}
          </a>
      </div>
    </sweet-modal>

    <!-- Delete Coworker -->
    <sweet-modal ref="deleteModal" overlay-theme="dark" :title="$t('workcash.coworker_modal_delete_title')">
        <div class="ph4-ns ph3 pv3 mb4">
            <p class="mb2">
            {{ $t('workcash.coworker_modal_delete_desc', {name: deleteForm.name}) }}
            </p>
        </div>
        <div class="ph4-ns ph3 pv3 flex-ns justify-end" >
            <span >
                <a class="btn mr3" @click="closeDeleteModal()">
                    {{ $t('app.cancel') }}
                </a>
                <a class="btn btn-primary" @click="deleteCoworker()">
                    {{ $t('app.delete') }}
                </a>
            </span>
        </div>
    </sweet-modal>

</div>
</template>

<script>

import { SweetModal } from 'sweet-modal-vue';
import Error from '../partials/Error.vue';

export default {

    components: {
        SweetModal
    },

    props: {
        companyId: {
            type: String,
            default: '',
        },
    },

    data() {
        return {
            coworkers: [],
            restData: {
                genders: [],
                existingContacts: []
            },
            coworkerModalEditMode: false,
            coworkerForm: {
                errors: [],
                id: 0,
                contact_type: "new",
                first_name: '',
                last_name: '',
                gender_id: "0",
                real_contact: false,
                existing_contact_id: 0,
                role: '',
            },
            errorTemplate: Error,
            deleteForm: {
                id: 0,
                name: ''
            }
        };
    },
    
    mounted() {
        this.getCoworkers();
    },

    methods: {
        loadRestData(callback) {
            if(this.restData.genders.length > 0) {
                callback();
                return;
            }
            axios.get('/workcash/companies/' + this.companyId + '/coworker-modal-data')
                .then(response => {
                    this.restData.genders = response.data.genders;
                    this.restData.existingContacts = response.data.existingContacts;
                    callback();
                });
        },
        getCoworkers() {
            axios.get('/workcash/companies/' + this.companyId + '/coworkers')
                .then(response => {
                    this.coworkers = response.data;
                });
        },
        modalAddCoworker() {
            this.coworkerModalEditMode = false;
            this.coworkerForm.errors = [];
            this.coworkerForm.contact_type = 'new';
            this.coworkerForm.first_name = '';
            this.coworkerForm.last_name = '';
            this.coworkerForm.gender_id = '0';
            this.coworkerForm.real_contact = false;
            this.coworkerForm.role = '';
            this.coworkerForm.existing_contact_id = 0;
            if(this.$refs.coworkerAvatarInput) {
                this.$refs.coworkerAvatarInput.value = '';
            }
            var vm = this;
            this.loadRestData(function() {
                vm.$refs.coworkerModal.open();
            })
        },
        modalCancelCoworker() {
            this.$refs.coworkerModal.close();
        },
        errorHandler(error) {
            if (typeof error.response.data === 'object') {
                this.coworkerForm.errors = _.flatten(_.toArray(error.response.data));
            } else {
                this.coworkerForm.errors = [this.$t('app.error_try_again')];
            }
        },
        modalStoreCoworker() {
            if(!this.$refs.coworkerModalForm.reportValidity()) {
                return;
            }

            var formData = new FormData();
            formData.append('contact_type', this.coworkerForm.contact_type);
            formData.append('first_name', this.coworkerForm.first_name);
            formData.append('last_name', this.coworkerForm.last_name);
            formData.append('gender_id', this.coworkerForm.gender_id);
            formData.append('real_contact', this.coworkerForm.real_contact);
            formData.append('role', this.coworkerForm.role);
            formData.append('existing_contact_id', this.coworkerForm.existing_contact_id);

            if(this.$refs.coworkerAvatarInput && this.$refs.coworkerAvatarInput.files.length > 0)
            {
                formData.append('avatar', this.$refs.coworkerAvatarInput.files[0], 'avatar');
            }
            var config = {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            };
            config={};

            //form validation success
            // send form data
            if(!this.coworkerModalEditMode) {
                axios.post('/workcash/companies/' + this.companyId + '/coworkers', formData, config)
                    .then(response => {
                        this.$refs.coworkerModal.close();
                        this.getCoworkers();
                    })
                    .catch(this.errorHandler);
            }
            else {
                //edit mode
                formData.append('_method', 'PUT');
                axios.post('/workcash/companies/' + this.companyId + '/coworkers/' + this.coworkerForm.id, formData, config)
                    .then(response => {
                        this.$refs.coworkerModal.close();
                        this.getCoworkers();
                    })
                    .catch(this.errorHandler);
            }
        },
        editCoworker(coworker) {
            this.coworkerModalEditMode = true;
            this.coworkerForm.errors = [];
            this.coworkerForm.id = coworker.id;
            this.coworkerForm.contact_type = 'new';
            this.coworkerForm.first_name = coworker.first_name;
            this.coworkerForm.last_name = coworker.last_name;
            this.coworkerForm.gender_id = String( coworker.gender_id );
            this.coworkerForm.real_contact = false;
            this.coworkerForm.role = coworker.role;
            this.coworkerForm.existing_contact_id = 0;
            this.$refs.coworkerAvatarInput.value = '';
            var vm = this;
            this.loadRestData(function() {
                vm.$refs.coworkerModal.open();
            })
        },
        deleteCoworkerModal(coworker) {
            this.deleteForm.name = coworker.complete_name;
            this.deleteForm.id = coworker.id;
            this.$refs.deleteModal.open();
        },
        closeDeleteModal() {
            this.$refs.deleteModal.close();
        },
        deleteCoworker() {
            axios.delete('/workcash/companies/' + this.companyId + '/coworkers/' + this.deleteForm.id)
                .then(response => {
                    this.$refs.deleteModal.close();
                    this.getCoworkers();
            })
        }
    }

};
</script>