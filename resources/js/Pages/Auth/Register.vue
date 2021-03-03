<template>
    <jet-authentication-card>
        <template #logo>
            <jet-authentication-card-logo />
        </template>

        <jet-validation-errors class="mb-4" />

        <v-stepper
            v-model="registerStepper"
            vertical
        >
            <v-stepper-step
            :complete="registerStepper > 1"
            step="1"
            >
                Login Info
            </v-stepper-step>
            <v-stepper-content step="1">
                <v-form
                    ref="loginForm"
                    v-model="loginForm"
                    lazy-validation
                >
                    <v-text-field
                        v-model="loginData.name"
                        :rules="nameRules"
                        label="Username"
                        required
                    />
                    <v-text-field
                        v-model="loginData.email"
                        :rules="emailRules"
                        label="Email"
                        required
                    />
                    <v-text-field
                        v-model="loginData.password"
                        label="Password"
                        type="password"
                        :rules="[v => !!v || 'Password is required']"
                        required
                    />
                    <v-text-field
                        v-model="repeatPass"
                        label="Repeat password"
                        type="password"
                        :rules="repeatPassRule"
                        required
                    />
                </v-form>
                <v-btn
                    @click="loginContinue"
                >
                    Continue
                </v-btn>
            </v-stepper-content>

            <v-stepper-step
            :complete="registerStepper > 2"
            step="2"
            >
                Personal Info
            </v-stepper-step>

            <v-stepper-content step="2">
                <v-form
                    ref="personalForm"
                    v-model="personalForm"
                    lazy-validation
                >
                    <v-text-field
                        v-model="personalData.fullName"
                        :rules="[v => !!v || 'Full name is required']"
                        label="Full name"
                        required
                    />
                    <v-text-field
                        v-model="personalData.phone"
                        :rules="phoneNumberRules"
                        label="Phone number"
                        required
                    />
                    <v-text-field
                        v-model="personalData.id_number"
                        :rules="[v => !!v || 'Id number is required']"
                        label="KTP or SIM number"
                        required
                    />
                    <v-text-field
                        v-model="personalData.portfolio_url"
                        :rules="urlRules"
                        label="Portfolio URL"
                        required
                    />
                    <div v-for="(acc, idx) in personalData.socialMedia" :key="idx" class="d-flex flex-row" :class="(idx === 0) ? 'pt-2':''">
                        <v-select
                            :items="socItems"
                            v-model="personalData.socialMedia[idx].type"
                            label="Social media name"
                            :rules="[v => !!v || 'Social media type required']"
                            style="width:25%"
                            class="mr-2"
                        ></v-select>
                        <v-text-field
                            v-model="personalData.socialMedia[idx].url"
                            :rules="urlRules"
                            label="Social media URL"
                            required
                            style="width:75%"
                            class="mr-2"
                        />
                        <v-icon v-if="idx === 0" @click="addSocmed" class="mt-3">mdi-plus-circle-outline</v-icon>
                        <v-icon v-else @click="delSocmed(idx)" class="mt-3">mdi-close-circle-outline</v-icon>
                        <!-- <v-btn v-if="idx === 0" @click="addSocmed" class="mt-3">+</v-btn> -->
                        <!-- <v-btn v-else @click="delSocmed" class="mt-3">-</v-btn> -->
                    </div>
                </v-form>
                <v-btn
                    @click="personalContinue"
                >
                    Continue
                </v-btn>
                <v-btn text @click="registerStepper = 1">
                    Back
                </v-btn>
            </v-stepper-content>

            <v-stepper-step
            :complete="registerStepper > 3"
            step="3"
            >
                Submissions
            </v-stepper-step>

            <v-stepper-content step="3">
                <v-icon @click="addSubmission" class="mt-3">mdi-plus-circle-outline</v-icon>
                <v-form
                    ref="submissionForm"
                    v-model="submissionForm"
                    lazy-validation
                >
                    <v-expansion-panels accordion class="px-0">
                        title: '',
                        description: '',
                        story_concept: null,
                        summary_file: null,
                        character_design: null,
                        world_design: null,
                        team_profile: null,
                        pilot_video: null
                        <v-expansion-panel v-for="(sub, idx) in submissionData" :key="idx" class="px-0">
                            <v-expansion-panel-header class="px-0">
                                Submission {{idx + 1}}
                            </v-expansion-panel-header>
                            <v-expansion-panel-content class="px-0">
                                <v-text-field
                                    v-model="submissionData[idx].title"
                                    :rules="[v => !!v || 'Title required']"
                                    label="Submission title"
                                    required
                                />
                                <v-textarea
                                    v-model="submissionData[idx].description"
                                    label="Description"
                                ></v-textarea>
                                <v-file-input
                                    v-model="submissionData[idx].story_concept"
                                    accept="application/pdf,.zip,.rar"
                                    label="Story concept (PDF or Zip file)"
                                ></v-file-input>
                                <v-file-input
                                    v-model="submissionData[idx].summary_file"
                                    accept="application/pdf,.zip,.rar"
                                    label="Logline, synopsis, outline or script (PDF or Zip file)"
                                ></v-file-input>
                                <v-file-input
                                    v-model="submissionData[idx].character_design"
                                    accept="application/pdf,.zip,.rar"
                                    label="Character design (PDF or Zip file)"
                                ></v-file-input>
                                <v-file-input
                                    v-model="submissionData[idx].world_design"
                                    accept="application/pdf,.zip,.rar"
                                    label="World design (PDF or Zip file)"
                                ></v-file-input>
                                <v-textarea
                                    v-model="submissionData[idx].team_profile"
                                    label="Team profile"
                                ></v-textarea>
                                <v-file-input
                                    v-model="submissionData[idx].pilot_video"
                                    accept="video/mp4,video/x-m4v,video/*"
                                    label="Pilot video (mp4 file)"
                                ></v-file-input>
                             </v-expansion-panel-content>
                        </v-expansion-panel>
                    </v-expansion-panels>
                </v-form>
                <v-btn
                    @click="submit"
                >
                    Submit
                </v-btn>
                <v-btn text @click="registerStepper = 2">
                    Back
                </v-btn>
            </v-stepper-content>
        </v-stepper>
    </jet-authentication-card>
</template>

<script>
    import JetAuthenticationCard from '@/Jetstream/AuthenticationCard'
    import JetAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo'
    import JetButton from '@/Jetstream/Button'
    import JetInput from '@/Jetstream/Input'
    import JetCheckbox from "@/Jetstream/Checkbox";
    import JetLabel from '@/Jetstream/Label'
    import JetValidationErrors from '@/Jetstream/ValidationErrors'

    export default {
        components: {
            JetAuthenticationCard,
            JetAuthenticationCardLogo,
            JetButton,
            JetInput,
            JetCheckbox,
            JetLabel,
            JetValidationErrors
        },

        data() {
            return {
                // form: this.$inertia.form({
                //     name: '',
                //     email: '',
                //     password: '',
                //     password_confirmation: '',
                //     terms: false,
                // })
                repeatPass: '',
                registerStepper: 1,
                loginForm: null,
                personalForm: null,
                submissionForm: null,
                loginName: '',
                socItems: [
                    'Facebook',
                    'Twitter',
                    'Instagram',
                    'Some other stuff'
                ],
                personalData:{
                    fullName: '',
                    phone: '',
                    id_number: '',
                    portfolio_url: '',
                    socialMedia: [
                        {
                            type: '',
                            url: ''
                        }
                    ]
                },
                loginData: {
                    name: '',
                    email: '',
                    password: '',
                    terms: false,
                },
                submissionData:[{
                    title: '',
                    description: '',
                    story_concept: null,
                    summary_file: null,
                    character_design: null,
                    world_design: null,
                    team_profile: null,
                    pilot_video: null
                }],
                submissions: [],
                nameRules: [
                    v => !!v || 'Name is required',
                    v => (v && v.length <= 10) || 'Name must be less than 10 characters',
                ],
                emailRules: [
                    v => !!v || 'E-mail is required',
                    v => /.+@.+\..+/.test(v) || 'E-mail must be valid',
                ],
                repeatPassRule: [
                    v => (this.repeatPass === this.loginData.password) || 'Password must match'
                ],
                phoneNumberRules: [
                    v => /^[\+62]?[0-9]{10,13}$/.test(v) || 'Phone number is invalid'
                ],
                urlRules: [
                    v => /^(?:(?:(?:https?|ftp):)?\/\/)(?:\S+(?::\S*)?@)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z0-9\u00a1-\uffff][a-z0-9\u00a1-\uffff_-]{0,62})?[a-z0-9\u00a1-\uffff]\.)+(?:[a-z\u00a1-\uffff]{2,}\.?))(?::\d{2,5})?(?:[/?#]\S*)?$/i.test(v) || 'URL invalid'
                ]
            }
        },

        methods: {
            addSubmission(){
                this.submissionData.push({
                    title: '',
                    description: '',
                    story_concept: null,
                    summary_file: null,
                    character_design: null,
                    world_design: null,
                    team_profile: null,
                    pilot_video: null
                })
            },
            addSocmed(){
                this.personalData.socialMedia.push({
                    type: '',
                    url: ''
                })
            },
            delSocmed(idx){
                this.personalData.socialMedia.splice(idx, 1)
            },
            submit() {
                this.form.post(this.route('register'), {
                    onFinish: () => this.form.reset('password', 'password_confirmation'),
                })
            },
            loginContinue() {
                // if(this.$refs.loginForm.validate()){
                if(true){
                    this.registerStepper = 2
                }
            },
            personalContinue() {
                // if(this.$refs.personalForm.validate()){
                if(true){
                    this.registerStepper = 3
                }
            },
        }
    }
</script>
