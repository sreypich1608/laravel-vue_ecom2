<template>
    <div>
        <!-- delete category modal-->
        <template style="z-index: 999 !important;">
            <Modal v-model="getDeleteModalObj.showDeleteModal"
                :mask-closable="false"
                :closable="false"
                width="360">
                <template #header>
                    <p style="color: #f60; text-align: center;z-index: 999 !important;">
                        <Icon type="ios-information-circle"></Icon>
                        <span>Delete confirmation</span>
                    </p>
                </template>
                <div style="text-align: center">
                    <p>Are you sure you want to delete this?</p>
                </div>
                <template #footer>
                    <Button
                        type="defualt"
                        size="large"
                        @click="closeModal"
                        >Cancel</Button
                    >
                    <Button
                        type="error"
                        size="large"
                        :loading="isDeleting"
                        :disabled="isDeleting"
                        @click="deleteTag"
                        >Delete</Button
                    >
                </template>
            </Modal>
        </template>
    </div>
</template>

<script>
import { Modal } from "view-ui-plus";
import { mapGetters } from "vuex";

export default {
    data() {
        return {
            isDeleting: false,
        };
    },
    methods: {
        async deleteTag() {
            this.isDeleting = true;
            const res = await this.callApi(
                "post",
                this.getDeleteModalObj.deleteUrl,
                this.getDeleteModalObj.data
            );
            if (res.status === 200) {
                this.success("This has been delete successfully!");
                this.$store.commit("setDeleteModal",true);
            } else {
                this.swr();
                this.$store.commit("setDeleteModal",false);
            }
            this.isDeleting = false;
        },
        closeModal(){
            this.$store.commit("setDeleteModal",false);
        }
    },
    computed: {
        ...mapGetters(["getDeleteModalObj"]),
    },
    
};
</script>
