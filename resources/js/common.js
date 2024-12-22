import axios from "axios";

// we write it like to use it everywhere
// by just one line of code like
// const res = await this.callApi('post','/app/create_tag',{tagName:'testtag'});
// by just change parameter: method, url, and dataObj


export default {
    data() {
        return {

        }
    },
    methods: {
        // call api method
        async callApi(method,url,dataObj) {
            try {
                return await axios({
                    method: method,
                    url: url,
                    data: dataObj
                });
            } catch (e) {
                return e.response;
            }
        },

        info (desc,title="Hey") {
            this.$Notice.info({
                title: title,
                desc:desc
            });
        },
        success (desc,title="Hey") {
            this.$Notice.success({
                title: title,
                desc:desc
            });
        },
        warning (desc,title="Hey") {
            this.$Notice.warning({
                title: title,
                desc:desc
            });
        },
        e (desc,title="Oops!") {
            this.$Notice.error({
                title: title,
                desc:desc
            });
        },
        swr (desc="something went wrong! please try again.",title="Oops!") {
            this.$Notice.error({
                title: title,
                desc:desc
            });
        },
        
    },
    
}