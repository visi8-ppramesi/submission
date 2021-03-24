<template>
    <jet-form-section @submitted="updateProfileInformation">
        <template #title>
            Profile Information
        </template>

        <template #description>
            Update your account's profile information and email address.
        </template>

        <template #form>
            <!-- Profile Photo -->
            <div class="col-span-6 sm:col-span-4" v-if="$page.props.jetstream.managesProfilePhotos">
                <!-- Profile Photo File Input -->
                <input type="file" class="hidden"
                            ref="photo"
                            @change="updatePhotoPreview">

                <jet-label for="photo" value="Photo" />

                <!-- Current Profile Photo -->
                <div class="mt-2" v-show="! photoPreview">
                    <img :src="user.profile_photo_url" :alt="user.name" class="rounded-full h-20 w-20 object-cover">
                </div>

                <!-- New Profile Photo Preview -->
                <div class="mt-2" v-show="photoPreview">
                    <span class="block rounded-full w-20 h-20"
                          :style="'background-size: cover; background-repeat: no-repeat; background-position: center center; background-image: url(\'' + photoPreview + '\');'">
                    </span>
                </div>

                <jet-secondary-button class="mt-2 mr-2" type="button" @click.native.prevent="selectNewPhoto">
                    Select A New Photo
                </jet-secondary-button>

                <jet-secondary-button type="button" class="mt-2" @click.native.prevent="deletePhoto" v-if="user.profile_photo_path">
                    Remove Photo
                </jet-secondary-button>

                <jet-input-error :message="form.errors.photo" class="mt-2" />
            </div>

            <!-- Name -->
            <div class="col-span-6 sm:col-span-4">
                <jet-label for="name" value="Name" />
                <jet-input id="name" type="text" class="mt-1 block w-full" v-model="form.name" autocomplete="name" />
                <jet-input-error :message="form.errors.name" class="mt-2" />
            </div>

            <!-- Email -->
            <div class="col-span-6 sm:col-span-4">
                <jet-label for="email" value="Email" />
                <jet-input id="email" type="email" class="mt-1 block w-full" v-model="form.email" />
                <jet-input-error :message="form.errors.email" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-4">
                <jet-label for="full_name" value="Full Name" />
                <jet-input id="full_name" type="text" class="mt-1 block w-full" v-model="form.full_name" autocomplete="full_name" />
                <jet-input-error :message="form.errors.full_name" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-4">
                <jet-label for="date_of_birth" value="Date Of Birth" />
                <jet-input id="date_of_birth" type="text" class="mt-1 block w-full" v-model="form.date_of_birth" autocomplete="date_of_birth" />
                <jet-input-error :message="form.errors.date_of_birth" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-4">
                <jet-label for="phone" value="Phone" />
                <jet-input id="phone" type="text" class="mt-1 block w-full" v-model="form.phone" autocomplete="phone" />
                <jet-input-error :message="form.errors.phone" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-4">
                <jet-label for="id_number" value="ID Number" />
                <jet-input id="id_number" type="text" class="mt-1 block w-full" v-model="form.id_number" autocomplete="id_number" />
                <jet-input-error :message="form.errors.id_number" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-4">
                <jet-label for="portfolio_url" value="Portfolio URL" />
                <jet-input id="portfolio_url" type="text" class="mt-1 block w-full" v-model="form.portfolio_url" autocomplete="portfolio_url" />
                <jet-input-error :message="form.errors.portfolio_url" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-4">
                <jet-label for="social_media" value="Social Media" />
                <div v-for="(acc, idx) in form.social_media" :key="idx" class="social-media-container">
                    <jet-select :options="socialMediaList" v-model="acc.type"/>
                    <jet-input :id="'social_media_'+idx" type="text" class="mt-1 block w-full" v-model="acc.url" autocomplete="social_media" />
                    <v-icon v-if="idx === 0" @click="addSocmed">mdi-plus-circle-outline</v-icon>
                    <v-icon v-else @click="delSocmed(idx)">mdi-close-circle-outline</v-icon>
                </div>
                <jet-input-error :message="form.errors.social_media" class="mt-2" />
            </div>
        </template>

        <template #actions>
            <jet-action-message :on="form.recentlySuccessful" class="mr-3">
                Saved.
            </jet-action-message>

            <jet-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Save
            </jet-button>
        </template>
    </jet-form-section>
</template>

<script>
    import JetButton from '@/Jetstream/Button'
    import JetFormSection from '@/Jetstream/FormSection'
    import JetInput from '@/Jetstream/Input'
    import JetInputError from '@/Jetstream/InputError'
    import JetLabel from '@/Jetstream/Label'
    import JetActionMessage from '@/Jetstream/ActionMessage'
    import JetSecondaryButton from '@/Jetstream/SecondaryButton'
    import JetSelect from '@/Jetstream/Select'

    export default {
        components: {
            JetActionMessage,
            JetButton,
            JetFormSection,
            JetInput,
            JetInputError,
            JetLabel,
            JetSecondaryButton,
            JetSelect,
        },

        props: ['user'],

        data() {
            return {
                form: this.$inertia.form({
                    _method: 'PUT',
                    name: this.user.name,
                    email: this.user.email,
                    full_name: this.user.full_name,
                    date_of_birth: this.user.date_of_birth,
                    phone: this.user.phone,
                    id_number: this.user.id_number,
                    portfolio_url: this.user.portfolio_url,
                    social_media: JSON.parse(this.user.social_media),
                    photo: null,
                }),

                photoPreview: null,
            }
        },

        methods: {
            addSocmed(){
                this.form.social_media.push({
                    type: '',
                    url: ''
                })
            },
            delSocmed(idx){
                this.form.social_media.splice(idx, 1)
            },
            updateProfileInformation() {
                if (this.$refs.photo) {
                    this.form.photo = this.$refs.photo.files[0]
                }

                this.form.social_media = JSON.stringify(this.form.social_media)

                this.form.post(route('user-profile-information.update'), {
                    errorBag: 'updateProfileInformation',
                    preserveScroll: true,
                    onSuccess: () => {
                        this.form.social_media = JSON.parse(this.form.social_media)
                    }
                });
            },

            selectNewPhoto() {
                this.$refs.photo.click();
            },

            updatePhotoPreview() {
                const reader = new FileReader();

                reader.onload = (e) => {
                    this.photoPreview = e.target.result;
                };

                reader.readAsDataURL(this.$refs.photo.files[0]);
            },

            deletePhoto() {
                this.$inertia.delete(route('current-user-photo.destroy'), {
                    preserveScroll: true,
                    onSuccess: () => (this.photoPreview = null),
                });
            },
        },
    }
</script>

<style lang="scss">
.social-media-container{
    display: flex;
    gap: 10px;
}
</style>
