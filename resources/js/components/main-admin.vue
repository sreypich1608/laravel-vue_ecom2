<template>
    <div>
        <div v-if="$store.state.user">
            <!--========== ADMIN SIDE MENU one ========-->
            <div class="_1side_menu">
                <div class="_1side_menu_logo">
                    <h3 style="text-align: center">Logo Image</h3>
                    <!--<img src="/img/logo.jpg" style="width: 108px;margin-left: 68px;"/>-->
                </div>

                <!--~~~~~~~~ MENU CONTENT ~~~~~~~~-->
                <div class="_1side_menu_content">
                    <div class="_1side_menu_img_name">
                        <!-- <img class="_1side_menu_img" src="/pic.png" alt="" title=""> -->
                        <p class="_1side_menu_name">Admin</p>
                    </div>

                    <!--~~~ MENU LIST ~~~~~~-->
                    <div class="_1side_menu_list">
                        <ul class="_1side_menu_list_ul">
                            <li>
                                <router-link to="dashboard"><Icon type="ios-speedometer" />Dashboard</router-link>
                            </li>
                            <li>
                                <router-link to="adminuser"><Icon type="ios-speedometer" />Admin User</router-link>
                            </li>
                            <li>
                                <router-link to="category"><Icon type="ios-speedometer" />Category</router-link>
                            </li>
                            <li>
                                <router-link to="product"><Icon type="ios-speedometer" />Product</router-link>
                            </li>
                            <li>
                                <router-link to="admincustomer"><Icon type="ios-speedometer" />Customer</router-link>
                            </li>
                            <li>
                                <router-link to="adminorder"><Icon type="ios-speedometer" />Orders</router-link>
                            </li>
                            <!-- <li>
                                <router-link to="tags"><Icon type="ios-speedometer" />Tags</router-link>
                            </li>
                            <li>
                                <router-link to="adminuser"><Icon type="ios-speedometer" />Admin user</router-link>
                            </li>
                            <li>
                                <router-link to="roles"><Icon type="ios-speedometer" />Role Management</router-link>
                            </li>
                            <li>
                                <router-link to="assignrole"><Icon type="ios-speedometer" />Assign Role</router-link>
                            </li> -->
                            <li @click="logout">
                                <a><Icon type="ios-speedometer" />Log Out</a>
                            </li>
                            <!-- <li><router-link to="tags"><Icon type="ios-speedometer" /> Tags</router-link></li>
                            <li><router-link to="category"><Icon type="ios-speedometer" /> Category</router-link></li>
                            <li><router-link to="adminusers"><Icon type="ios-speedometer" /> Admin users</router-link></li>
                            <li><router-link to="role"><Icon type="ios-speedometer" /> Role Management</router-link></li>
                            <li><router-link to="assignRole"><Icon type="ios-speedometer" /> Assign role</router-link></li> -->

                            <!-- <li>
                                <a
                                    ><Icon type="ios-speedometer" /> Logout</a
                                >
                            </li> -->
                        </ul>
                    </div>
                </div>
            </div>
            <!--========== ADMIN SIDE MENU ========-->

            <!--========= HEADER ==========-->
            <div class="header">
                <div class="_2menu _box_shadow">
                    <div class="_2menu_logo">
                        <ul class="open_button">
                            <li>
                                <Icon type="ios-list" />
                            </li>
                            <!--<li><Icon type="ios-albums" /></li>-->
                        </ul>
                    </div>
                </div>
            </div>
            <!--========= HEADER ==========-->
        </div>
        <router-view></router-view>
    </div>
</template>

<script>
export default {
    props:['user'],
    data(){
        return {
            isLogin:false,
        }
    },
    methods:{
        async logout(){
            const res = await this.callApi('post','/app/admin_logout')
            if (res.status === 200) {
                localStorage.removeItem('admin_login'); // Clear login status
                this.$router.push('/admin_login'); // Redirect to login
                this.$emit('update:user', null); // Clear user data
                this.$store.commit('setUpdateUser', false);
                }
        }
    },
    created(){
        this.$store.commit('setUpdateUser',this.user)
    },
    watch: {
        user: {
            immediate: true, // Sync on component creation
            handler(newUser) {
                // Update Vuex store when the prop changes
                this.$store.commit('setUpdateUser', newUser);
            },
        },
    },
};
</script>
