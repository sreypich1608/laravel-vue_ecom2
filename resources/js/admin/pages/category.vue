<template>
    <div>
        <div class="content">
            <div class="container-fluid">
                <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
                <div
                    class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20"
                >
                    <p class="_title0">
                        Category Mangement
                        <Button @click="addmodal = true"
                            ><Icon type="md-add" /> Add category</Button
                        >
                    </p>

                    <div class="_overflow _table_div">
                        <table class="_table">
                            <!-- TABLE TITLE -->
                            <tr>
                                <th>ID</th>
                                <th>category Name</th>
                                <th>Created at</th>
                                <th>Action</th>
                            </tr>
                            <!-- TABLE TITLE -->

                            <!-- ITEMS -->
                            <tr v-for="(category, i) in categoryList" :key="i">
                                <td>{{ category.id }}</td>
                                <td class="_table_name">{{ category.name }}</td>
                                <td>{{ category.created_at }}</td>
                                <td>
                                    <Button
                                        @click="showEdit(category, i)"
                                        type="info"
                                        size="small"
                                        class="mr-2"
                                        >Edit</Button
                                    >
                                    <Button
                                        @click="showDelete(category, i)"
                                        type="error"
                                        size="small"
                                        :loading="isDeleting"
                                        >Delete</Button
                                    >
                                </td>
                            </tr>
                            <tr v-if="!categoryList.length">
                                <td colspan="4">No categorys found</td>
                            </tr>
                            <!-- ITEMS -->
                        </table>
                    </div>
                </div>
                <Page :total="100" />

                <!--add category Modal start-->
                <Modal
                    v-model="addmodal"
                    title="Add Category"
                    :mask-closable="false"
                    :closable="false"
                >
                    <Input
                        v-model="data.name"
                        placeholder="Add category name"
                    />
                    <template #footer>
                        <Button @click="addmodal = false" type="defualt"
                            >Close</Button
                        >
                        <Button
                            @click="add"
                            :disabled="isAdding"
                            :loading="isAdding"
                            type="primary"
                            >{{
                                isAdding ? "Adding.." : "Add Category"
                            }}</Button
                        >
                    </template>
                </Modal>
                <!--edit category Modal start-->
                <Modal
                    v-model="editmodal"
                    title="Edit category"
                    :mask-closable="false"
                    :closable="false"
                >
                    <Input
                        v-model="editdata.name"
                        placeholder="Edit category name"
                    />

                    <template #footer>
                        <Button type="defualt" @click="editmodal = false"
                            >Close</Button
                        >
                        <Button
                            @click="edit"
                            :disabled="isAdding"
                            :loading="isAdding"
                            type="primary"
                            >{{
                                isAdding ? "Editing.." : "Edit Category"
                            }}</Button
                        >
                    </template>
                </Modal>
                <deleteModal></deleteModal>
                <!-- category Modal close-->
            </div>
        </div>
    </div>
</template>
<script>
import deleteModal from "../component/deleteModal.vue";
import { mapGetters } from "vuex/dist/vuex.cjs.js";

export default {
    data() {
        return {
            data: {
                name: "",
            },
            addmodal: false,
            editmodal: false,
            isAdding: false,
            isDeleting: false,
            categoryList: [],
            editdata: {
                name: "",
            },
            index: -1,
        };
    },
    methods: {
        async add() {
            if (this.data.name.trim() == ""){
                return this.e("Category Name is require!!!");
            }
            const res = await this.callApi(
                "post",
                "app/add_category",
                this.data
            );
            if (res.status === 201) {
                this.categoryList.unshift(res.data);
                // this.categoryList = [res.data, ...this.categoryList]; // Reassign array to trigger reactivity
                this.success("Category add successfully.");
                this.data.name = "";
            } else {
                if (res.status == 422) {
                    if (res.data.errors.name) {
                        this.e(res.data.errors.name[0]);
                    }
                } else {
                    this.swr();
                }
            }
            console.log(this.data.categoryName);
            this.addmodal = false;
        },
        showEdit(category, i) {
            const obj ={
                id:category.id,
                name:category.name
            }
            this.editdata = obj;
            this.index = i;
            this.editmodal = true;
        },
        async edit() {
            if (this.editdata.name.trim() == "") {
                return this.e("Category Name is require!!!");
            }
            const res = await this.callApi(
                "post",
                "app/edit_category",
                this.editdata
            );
            if (res.status === 200) {
                this.categoryList[this.index].name = this.editdata.name;
                this.success("category has been edit successfully!");
                this.editmodal = false;
                this.editdata.name = ''
                this.index = -1
            } else {
                if (res.status == 422) {
                    if (res.data.errors.name) {
                        this.e(res.data.errors.name[0]);
                    }
                } else {
                    this.swr();
                }
            }
        },
        showDelete(category, i){
            const deleteModalObj = {
                showDeleteModal: true,
                deleteUrl: "app/delete_category",
                data: category,
                deleteIndex: i,
                isDeleted: false,
            };
            this.$store.commit("setDeleteModalObj", deleteModalObj);
        },
        async fetchCategory(){
            const res = await this.callApi("get", "app/get_category");
            if (res.status == 200) {
                this.categoryList = res.data;
            } else {
                this.swr();
            }
        },
    },
    created() {
        this.token = window.Laravel.csrfToken;
        this.fetchCategory()
    },
    components: {
        deleteModal,
    },
    watch: {
        getDeleteModalObj(obj) {
            if (obj.isDeleted) {
                console.log("Delete modal object:", obj.isDeleted);
                this.categoryList.slice(obj.deleteIndex, 1)
                this.fetchCategory()
            }
        },
    },
    computed: {
        ...mapGetters(['getDeleteModalObj'])
    },
};
</script>
