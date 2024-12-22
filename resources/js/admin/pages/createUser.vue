<template>
    <div>
        <div class="content">
            <div class="container-fluid">
                <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
                <div
                    class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20"
                >
                    <p class="_title0">
                        Admin Mangement
                        <Button @click="addmodal = true"
                            ><Icon type="md-add" /> Add user</Button
                        >
                    </p>

                    <div class="_overflow _table_div">
                        <table class="_table">
                            <!-- TABLE TITLE -->
                            <tr>
                                <th>ID</th>
                                <th>User Name</th>
                                <th>Email</th>
                                <th>Created at</th>
                                <th>Action</th>
                            </tr>
                            <!-- TABLE TITLE -->

                            <!-- ITEMS -->
                            <tr v-for="(user, i) in userList" :key="i">
                                <td>{{ user.id }}</td>
                                <td class="_table_name">{{ user.name }}</td>
                                <td>{{ user.email }}</td>
                                <td>{{ user.created_at }}</td>
                                <td>
                                    <Button
                                        @click="showEdit(user, i)"
                                        type="info"
                                        size="small"
                                        class="mr-2"
                                        >Edit</Button
                                    >
                                    <Button
                                        @click="showDelete(user, i)"
                                        type="error"
                                        size="small"
                                        :loading="isDeleting"
                                        >Delete</Button
                                    >
                                </td>
                            </tr>
                            <tr v-if="!userList.length">
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
                    title="Add User"
                    :mask-closable="false"
                    :closable="false"
                >
                    <Space direction="vertical" size="large">
                        <Space>
                        <Input style="width: 490px"
                            v-model="data.name"
                            placeholder="Add user name"
                            />
                        </Space>
                        <Space>
                            <Input style="width: 490px"
                            v-model="data.email"
                            placeholder="Add user email"
                        />
                        </Space>
                        <Space>
                            <Input v-model="data.password" type="password" password placeholder="Enter Password" style="width: 490px" />
                        </Space>
                    </Space>
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
                                isAdding ? "Adding.." : "Add User"
                            }}</Button
                        >
                    </template>
                </Modal>
                <!--edit category Modal start-->
                <Modal
                    v-model="editmodal"
                    title="Edit User"
                    :mask-closable="false"
                    :closable="false"
                >
                <Space direction="vertical" size="large">
                        <Space>
                        <Input style="width: 490px"
                            v-model="editdata.name"
                            placeholder="Add user name"
                            />
                        </Space>
                        <Space>
                            <Input style="width: 490px"
                            v-model="editdata.email"
                            placeholder="Add user email"
                        />
                        </Space>
                        <Space>
                            <Input v-model="editdata.password" type="password" password placeholder="Enter Password" style="width: 490px" />
                        </Space>
                    </Space>

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
                                isAdding ? "Editing.." : "Edit user"
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
import { Space } from "view-ui-plus";
import deleteModal from "../component/deleteModal.vue";
import { mapGetters } from "vuex/dist/vuex.cjs.js";

export default {
    data() {
        return {
            data: {
                name: "",
                email: "",
                password: "",
            },
            addmodal: false,
            editmodal: false,
            isAdding: false,
            isDeleting: false,
            userList: [],
            editdata: {
                name: "",
                email: "",
                password: "",
            },
            index: -1,
        };
    },
    methods: {
        async add() {
            if (this.data.name.trim() == ""){
                return this.e("Category Name is require!!!");
            }
            if (this.data.email.trim() == ""){
                return this.e("user email is require!!!");
            }
            if (this.data.password.trim() == ""){
                return this.e("user password is require!!!");
            }
            const res = await this.callApi(
                "post",
                "app/add_user",
                this.data
            );
            if (res.status === 201) {
                this.userList.unshift(res.data);
                this.success("User add successfully.");
                this.data = {
                    name: "",
                    email: "",
                    password: "",
                }
                this.addmodal = false;
            } else {
                if (res.status == 422) {
                    for (let i in res.data.errors) {
                        this.e(res.data.errors[i][0]);
                    }
                } else {
                    this.swr();
                }
            }
        },
        showEdit(user, i) {
            this.editdata = user;
            this.index = i;
            this.editmodal = true;
        },
        async edit() {
            if (this.editdata.name.trim() == "") {
                return this.e("user Name is require!!!");
            }
            if (this.editdata.email.trim() == "") {
                return this.e("email Name is require!!!");
            }
            const res = await this.callApi(
                "post",
                "app/edit_category",
                this.editdata
            );
            if (res.status === 200) {
                this.userList[this.index].name = this.editdata.name;
                this.userList[this.index].email = this.editdata.email;
                this.success("user has been edit successfully!");
                this.editmodal = false;
                this.editdata = {
                    name: "",
                    email: "",
                    password: "",
                }
                this.index = -1
            } else {
                if (res.status == 422) {
                    for (let i in res.data.errors) {
                        this.e(res.data.errors[i][0]);
                    }
                } else {
                    this.swr();
                }
            }
        },
        showDelete(category, i){
            const deleteModalObj = {
                showDeleteModal: true,
                deleteUrl: "app/delete_user",
                data: category,
                deleteIndex: i,
                isDeleted: false,
            };
            this.$store.commit("setDeleteModalObj", deleteModalObj);
        },
        async fetchAdminUser(){
            const res = await this.callApi("get", "app/get_user");
            if (res.status == 200) {
                this.userList = res.data;
            } else {
                this.swr();
            }
        },
    },
    created() {
        this.token = window.Laravel.csrfToken;
        this.fetchAdminUser()
    },
    components: {
        deleteModal,
    },
    watch: {
        getDeleteModalObj(obj) {
            if (obj.isDeleted) {
                console.log("Delete modal object:", obj.isDeleted);
                this.userList.splice(obj.deleteIndex, 1)
                this.fetchAdminUser()
            }
        },
    },
    computed: {
        ...mapGetters(['getDeleteModalObj'])
    },
};
</script>
