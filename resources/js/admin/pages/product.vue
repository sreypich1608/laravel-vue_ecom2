<template>
    <div>
        <div class="content">
            <div class="container-fluid">
                <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
                <div
                    class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20"
                >
                    <p class="_title0">
                        Product Mangement
                        <Button @click="addmodal = true"
                            ><Icon type="md-add" /> Add Product</Button
                        >
                    </p>

                    <div class="_overflow _table_div">
                        <table class="_table" style="table-layout: fixed; width: 100%;">
                            <!-- TABLE TITLE -->
                            <tr>
                                <th style="width: 50px;">ID</th>
                                <th style="width: 70px;">Image</th>
                                <th style="width: 200px;">Name</th>
                                <th style="width: 100px;">Description</th>
                                <th style="width: 100px;">Cost</th>
                                <th style="width: 100px;">Price</th>
                                <th style="width: 100px;">Stock</th>
                                <th style="width: 150px;">Category</th>
                                <th style="width: 150px;">Created at</th>
                                <th style="width: 150px;">Action</th>
                            </tr>
                            <!-- TABLE TITLE -->

                            <!-- ITEMS -->
                            <tr v-for="(product, i) in productList" :key="i">
                                <td>{{ product.id }}</td>
                                <td><img
                                        style="
                                            width: 25px;
                                            height: 25px;
                                            object-fit: cover;
                                        "
                                        :src="`/uploads/${product.image_url}`"
                                        alt=""
                                    /></td>
                                <td class="_table_name">{{ product.name }}</td>
                                <td class="overflow-hidden-cell">{{ product.description }}</td>
                                <td class="overflow-hidden-cell">{{ product.cost }}</td>
                                <td class="overflow-hidden-cell">{{ product.price }}</td>
                                <td class="overflow-hidden-cell">{{ product.stock }}</td>
                                <td class="overflow-hidden-cell">{{ getCategoryName(product.category_id) }}</td>
                                <td class="overflow-hidden-cell">{{ product.created_at }}</td>
                                <td class="overflow-hidden-cell">
                                    <Button
                                        @click="showEdit(product, i)"
                                        type="info"
                                        size="small"
                                        class="mr-2"
                                        >Edit</Button
                                    >
                                    <Button
                                        @click="showDelete(product, i)"
                                        type="error"
                                        size="small"
                                        :loading="isDeleting"
                                        >Delete</Button
                                    >
                                </td>
                            </tr>
                            <tr v-if="!productList.length">
                                <td colspan="4">No categorys found</td>
                            </tr>
                            <!-- ITEMS -->
                        </table>
                    </div>
                </div>
                <Page :total="100" />

                <!--add product Modal start-->
                <Modal width="900px"
                    v-model="addmodal"
                    title="Add Product"
                    :mask-closable="false"
                    :closable="false"
                >
                    <div class="px-4 py-2">
                        <Space class="mb-4" direction="vertical" size="large">
                            <Space>
                                Product Name:
                                <Input v-model="data.name" placeholder="Enter cost" style="width: 690px" />
                            </Space>
                        </Space>
                        <Space direction="vertical" size="large">
                            <Space>
                                Cost:
                                <Input v-model="data.cost" placeholder="Enter cost" style="width: 350px" />
                                Price:
                                <Input v-model="data.price" placeholder="Enter price" style="width: 350px" />
                            </Space>
                            <Space>
                                Stock
                                <Input v-model="data.stock" placeholder="Enter stock" style="width: 330px" />
                                Category:
                                <Select
                                    v-model="data.category_id"
                                    style="width: 340px"
                                    placeholder="Selet category"
                                >
                                    <Option :value="category.id" v-for="(category, i) in categoryList" :key="i" style="width: 340px">{{ category.name }}</Option>
                                </Select>
                            </Space>
                        </Space>
                        <Space direction="vertical" size="large">
                            <!-- Upload image start-->
                            <div class="mt-4">
                                <Upload style="width: 800px;"
                                    ref="uploads"
                                    :headers="{
                                        'x-csrf-token': token,
                                        'x-requested-with': 'XMLHttpRequest',
                                    }"
                                    :on-success="handleSuccess"
                                    :on-error="handleError"
                                    :format="['jpg', 'jpeg', 'png']"
                                    :on-format-error="handleFormatError"
                                    :max-size="2048"
                                    :on-exceeded-size="handleMaxSize"
                                    type="drag"
                                    action="app/upload"
                                >
                                    <div style="padding: 20px 0">
                                        <Icon
                                            type="ios-cloud-upload"
                                            size="52"
                                            style="color: #3399ff"
                                        ></Icon>
                                        <p>Click or drag files here to upload</p>
                                    </div>
                                </Upload>
                                <!--preview image modal-->
                                <div style="margin-left: auto; margin-right: auto">
                                    <ImagePreview
                                        v-model="visible"
                                        :preview-list="[
                                            `/uploads/${data.image_url}`,
                                        ]"
                                        style="
                                            width: 300px;
                                            position: absolute;
                                            left: 40%;
                                        "
                                    />
                                </div>
                                <div class="image_thumb" v-if="data.image_url">
                                    <div class="demo-upload-list">
                                        <Image
                                            :src="`/uploads/${data.image_url}`"
                                            fit="cover"
                                            width="100%"
                                            height="100%"
                                        />
                                        <div class="demo-upload-list-cover">
                                            <Icon
                                                type="ios-eye-outline"
                                                @click="handleView(data.image_url)"
                                            ></Icon>
                                            <Icon
                                                type="ios-trash-outline"
                                                @click="deleteImage"
                                            ></Icon>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Upload image end-->
                        </Space>
                        <Space style="width: 800px;margin-top: 10px;" direction="vertical" size="large">
                            <Input v-model="data.description" type="textarea" :rows="4" placeholder="Enter something..." />    
                        </Space>
                    </div>
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
                                isAdding ? "Adding.." : "Add Product"
                            }}</Button
                        >
                    </template>
                </Modal>
                <!--edit category Modal start-->
                <Modal width="900px"
                    v-model="editmodal"
                    title="Edit Product"
                    :mask-closable="false"
                    :closable="false"
                >
                <div class="px-4 py-2">
                        <Space class="mb-4" direction="vertical" size="large">
                            <Space>
                                Product Name:
                                <Input v-model="editdata.name" placeholder="Enter cost" style="width: 690px" />
                            </Space>
                        </Space>
                        <Space direction="vertical" size="large">
                            <Space>
                                Cost:
                                <Input v-model="editdata.cost" placeholder="Enter cost" style="width: 350px" />
                                Price:
                                <Input v-model="editdata.price" placeholder="Enter price" style="width: 350px" />
                            </Space>
                            <Space>
                                Stock
                                <Input v-model="editdata.stock" placeholder="Enter stock" style="width: 330px" />
                                Category:
                                <Select
                                    v-model="editdata.category_id"
                                    style="width: 340px"
                                    placeholder="Selet category"
                                >
                                    <Option :value="category.id" v-for="(category, i) in categoryList" :key="i" style="width: 340px">{{ category.name }}</Option>
                                </Select>
                            </Space>
                        </Space>
                        <Space direction="vertical" size="large">
                            <!-- Upload image start-->
                            <div class="mt-2">
                                <Upload style="width: 800px;"
                                    v-show="isIconImageNew"
                                    ref="editDataUploads"
                                    :headers="{
                                        'x-csrf-token': token,
                                        'x-requested-with': 'XMLHttpRequest',
                                    }"
                                    :on-success="handleSuccess"
                                    :on-error="handleError"
                                    :format="['jpg', 'jpeg', 'png']"
                                    :on-format-error="handleFormatError"
                                    :max-size="2048"
                                    :on-exceeded-size="handleMaxSize"
                                    type="drag"
                                    action="app/upload"
                                >
                                    <div style="padding: 20px 0">
                                        <Icon
                                            type="ios-cloud-upload"
                                            size="52"
                                            style="color: #3399ff"
                                        ></Icon>
                                        <p>Click or drag files here to upload</p>
                                    </div>
                                </Upload>
                                <!--preview image modal-->
                                <div style="margin-left: auto; margin-right: auto">
                                    <ImagePreview
                                        v-model="visible"
                                        :preview-list="[
                                            `/uploads/${editdata.image_url}`,
                                        ]"
                                        style="
                                            width: 300px;
                                            position: absolute;
                                            left: 40%;
                                        "
                                    />
                                </div>
                            </div>
                            <div class="image_thumb" v-if="editdata.image_url">
                                <div class="demo-upload-list">
                                    <Image
                                        :src="`/uploads/${editdata.image_url}`"
                                        fit="cover"
                                        width="100%"
                                        height="100%"
                                    />
                                    <div class="demo-upload-list-cover">
                                        <Icon
                                            type="ios-eye-outline"
                                            @click="handleView(editdata.image_url)"
                                        ></Icon>
                                        <Icon
                                            type="ios-trash-outline"
                                            @click="deleteImage(false)"
                                        ></Icon>
                                    </div>
                                </div>
                            </div>
                            <!-- Upload image end-->
                        </Space>
                        <Space style="width: 800px;margin-top: 10px;" direction="vertical" size="large">
                            <Input v-model="editdata.description" type="textarea" :rows="4" placeholder="Enter something..." />    
                        </Space>
                    </div>
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
import { Space } from "view-ui-plus";
import deleteModal from "../component/deleteModal.vue";
import { mapGetters } from "vuex/dist/vuex.cjs.js";

export default {
    data() {
        return {
            data: {
                name: "",
                description: "",
                cost: "",
                price: "",
                stock: "",
                category_id: "",
                image_url: "",
            },
            addmodal: false,
            editmodal: false,
            isAdding: false,
            isDeleting: false,
            productList: [],
            categoryList: [],
            editdata: {
                name: "",
                description: "",
                cost: "",
                price: "",
                stock: "",
                category_id: "",
                image_url: "",
            },
            index: -1,
            token: "",
            visible: false,
            isIconImageNew: false,
            isEditingItem: false,
        };
    },
    methods: {
        getCategoryName(categoryId){
            const category = this.categoryList.find(cat => cat.id === categoryId);
        return category ? category.name : 'Unknown';
        },
        async add() {
            if (this.data.name.trim() == ""){
                return this.e("Product Name is require!!!");
            }
            if (this.data.cost == ""){
                return this.e("Product cost is require!!!");
            }
            if (this.data.price == ""){
                return this.e("Product price is require!!!");
            }
            if (this.data.stock == ""){
                return this.e("Product stock is require!!!");
            }
            if (this.data.category_id == ""){
                return this.e("Product category is require!!!");
            }
            const res = await this.callApi(
                "post",
                "app/add_product",
                this.data
            );
            if (res.status === 201) {
                this.productList.unshift(res.data);
                // this.categoryList = [res.data, ...this.categoryList]; // Reassign array to trigger reactivity
                this.success("Product add successfully.");
                this.data.name = "";
                this.data.description = "",
                this.data.cost = "",
                this.data.price = "",
                this.data.stock = "",
                this.data.category_id = "",
                this.data.image_url = "",
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
        showEdit(product, i) {
            // const obj ={
            //     id:product.id,
            //     name:product.name
            // }
            this.isEditingItem = true
            this.editdata = product;
            this.index = i;
            this.editmodal = true;
            if(this.editdata.image_url == null){
                this.isIconImageNew = true;
            }
            console.log(this.editdata)
        },
        async edit() {
            if (this.editdata.name.trim() == ""){
                return this.e("Product Name is require!!!");
            }
            if (this.editdata.cost == ""){
                return this.e("Product cost is require!!!");
            }
            if (this.editdata.price == ""){
                return this.e("Product price is require!!!");
            }
            if (this.editdata.stock == ""){
                return this.e("Product stock is require!!!");
            }
            if (this.editdata.category_id == ""){
                return this.e("Product category is require!!!");
            }
            const res = await this.callApi(
                "post",
                "app/edit_product",
                this.editdata
            );
            if (res.status === 200) {
                this.productList[this.index].name = this.editdata.name;
                this.productList[this.index].description = this.editdata.description;
                this.productList[this.index].cost = this.editdata.cost;
                this.productList[this.index].price = this.editdata.price;
                this.productList[this.index].stock = this.editdata.stock;
                this.productList[this.index].category_id = this.editdata.category_id;
                this.productList[this.index].image_url = this.editdata.image_url;
                this.success("Product has been edit successfully!");
                this.colseEditngModal()
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
        showDelete(product, i){
            const deleteModalObj = {
                showDeleteModal: true,
                deleteUrl: "app/delete_product",
                data: product,
                deleteIndex: i,
                isDeleted: false,
            };
            this.$store.commit("setDeleteModalObj", deleteModalObj);
        },
        handleSuccess(res, file) {
            if (this.isEditingItem) {
                this.editdata.image_url = res;
                this.isIconImageNew = false;
            } else {
                this.data.image_url = res;
            }
        },
        handleError(res, file) {
            this.$Notice.warning({
                title: "The file format is incorrect",
                desc: `${
                    file.errors.file.length
                        ? file.errors.file[0]
                        : "somwthing went wrong!"
                }`,
            });
        },
        handleFormatError(file) {
            this.$Notice.warning({
                title: "The file format is incorrect",
                desc:
                    "File format of " +
                    file.name +
                    " is incorrect, please select jpg or png.",
            });
        },
        handleFormatError(file) {
            this.$Notice.warning({
                title: "The file format is incorrect",
                desc:
                    "File format of " +
                    file.name +
                    " is incorrect, please select jpg or png.",
            });
        },
        handleMaxSize(file) {
            this.$Notice.warning({
                title: "Exceeding file size limit",
                desc: "File  " + file.name + " is too large, no more than 2M.",
            });
        },
        handleView(name) {
            this.data.image_url = name;
            this.visible = true;
        },
        async deleteImage(isAdd = true) {
            let image;
            if (!isAdd) {
                // for editing
                this.isIconImageNew = true;
                image = this.editdata.image_url;
                this.editdata.image_url = ""; // delete old image from showing
                if (this.$refs.editDataUploads) {
                    this.$refs.editDataUploads.clearFiles();
                }
            } else {
                image = this.data.image_url;
                this.data.image_url = "";
                if (this.$refs.uploads) {
                    this.$refs.uploads.clearFiles();
                }
            }
            const res = await this.callApi("post", "app/delete_image", {
                imageName: image,
            });
            if (res.status != 200) {
                if (!isAdd) {
                    this.editdata.image_url = image;
                } else {
                    this.data.image_url = image;
                }
                //this.data.image_url = image;
                this.swr();
            }
        },
        colseEditngModal() {
            this.isEditingItem = false; // Reset editing flag
            this.isIconImageNew = false; // Reset new image flag
            this.editmodal = false; // Close the modal

            // Optionally reset the editdata object
            this.editdata = {
                name: "",
                description: "",
                cost: "",
                price: "",
                stock: "",
                category_id: "",
                image_url: "",
            };
        },
        async fetchProducts(){
            const res = await this.callApi("get", "app/get_product");
            if (res.status == 200) {
                this.productList = res.data.products;
                this.categoryList = res.data.category;
            } else {
                this.swr();
            }
        },
    },
    created() {
        this.token = window.Laravel.csrfToken;
        this.fetchProducts()
    },
    components: {
        deleteModal,
    },
    watch: {
        getDeleteModalObj(obj) {
            if (obj.isDeleted) {
                console.log("Delete modal object:", obj.isDeleted);
                this.productList.splice(obj.deleteIndex, 1)
                this.fetchProducts()
            }
        },
    },
    computed: {
        ...mapGetters(['getDeleteModalObj'])
    },
};
</script>
<style>
.overflow-hidden-cell {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
</style>