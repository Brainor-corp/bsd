<style src="../../css/sidebarHeader.css"></style>

<template>
    <div class="align-items-center d-flex justify-content-between sidebar-header px-3 my-2">
        <router-link to="/bradmin"><img class=" logo-img" :src="getLogoUrl" alt=""></router-link>
        <button class="sidebar-toggle-btn" @click="changeSidebarOpenCookie(sidebarOpenButtonAction)"><i :class="ico"></i></button>
    </div>
</template>

<script>
    function menuIconChange(currentState, button)
    {
        if(currentState === 'false' || currentState === false || currentState === null || typeof currentState === 'undefined'){
            button.$data.ico = "fa fa-unlock"
        }else{
            button.$data.ico = "fa fa-lock"
        }
    }

    export default {
        data(){
            return {
                sidebarOpenButtonAction:this.$cookie.get('sidebarOpen'),
                ico: ""
            };
        },
        created: function () {
            let val = this.$cookie.get('sidebarOpen');
            menuIconChange(val, this);
        },
        methods: {
            changeSidebarOpenCookie(actionValue) {
                let value;
                var vm = this;
                if(actionValue === 'false' || actionValue === false || actionValue === null || typeof actionValue === 'undefined'){
                    value = true;
                    this.$store.commit('sidebarFixedState', value);
                    menuIconChange(value, vm);

                }else{
                    value = false;
                    this.$store.commit('sidebarFixedState', value);
                    menuIconChange(value, vm);
                }
                this.sidebarOpenButtonAction = value;


                this.$cookie.set('sidebarOpen', this.sidebarOpenButtonAction, { expires: '1Y' });
            }
        },
        computed: {
            getLogoUrl: function () {
                return this.$store.state.sidebar.logoUrl;
            }
        }
    };
</script>