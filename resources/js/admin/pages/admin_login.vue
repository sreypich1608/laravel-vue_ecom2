<template>
    <Card
        style="
            margin-left: auto;
            margin-right: auto;
            margin-top: 200px;
            max-width: 450px;
        "
    >
        <template #title>
            <h3>Login</h3>
        </template>
        <div class="px-3 pb-3">
            <div class="space">
            <Label>Email :</Label>
            <Input type="email" v-model="data.email" placeholder="email" />
        </div>
        <div class="space">
            <Label>Password :</Label>
            <Input
                type="password"
                password
                v-model="data.password"
                placeholder="******"
            />
        </div>
        <Button
            style="width: 100%; margin-top: 20px"
            @click="login"
            :disabled="isLogging"
            :loading="isLogging"
            type="primary"
            >{{ isLogging ? 'Logging..':'Login' }}</Button
        >
        </div>
    </Card>
</template>
<script>
export default {
    data() {
        return {
            data: {
                password: "",
                email: "",
            },
            isLogging:false,
        };
    },
    methods: {
        async login(){
            if (this.data.password.trim() == "")
                return this.e("Password is required");
            if (this.data.email.trim() == "")
                return this.e("Email name is required");
            if (this.data.password.length <6)
                return this.e("password is required 6 or more digit");
            this.isLogging = true
            const res = await this.callApi('post','app/admin_login',this.data)
            if(res.status === 200){
                this.success("You are login to admin")
                window.location = '/dashboard'
                localStorage.setItem('admin_login', true); // Mark as logged in
                // this.$router.push(res.data.redirect); // Redirect to the dashboard
                this.$store.commit('setUpdateUser', res.data.user);
            }else if(res.status === 422){
                for(let i in res.data.errors){
                    this.e(res.data.errors[i][0])
                }
            }
            else{
                if(res.status ==401){
                    this.info(res.data.msg)
                }else{
                    this.swr()
                }
            }
            this.isLogging = false
        }
    },
};
</script>

