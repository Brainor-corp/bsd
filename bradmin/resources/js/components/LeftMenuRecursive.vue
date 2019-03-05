<template>
    <ul class="sub-menu collapse" :id="menuParentItemUrl.replace(/\//g, '')"
        :class="{ 'show' : menuParentItemUrl === activeUrlParams}"
    >
        <li v-for="menuItem in menuItemNodes">
            <router-link v-if="menuItem.nodes && !menuItem.noDirect"
                         :to="'#' + menuItem.url.replace(/\//g, '')"
                         :class="['menu-item', { 'router-link-exact-active' : menuItem.url === activeUrlParams}]"
                         :data-toggle="'collapse'"
                         :data-target="'#' + menuItem.url.replace(/\//g, '')"
                         :aria-expanded="true"
                         :aria-controls="menuItem.url.replace(/\//g, '')"
            >
                <i v-if="menuItem.iconText" class="icon">{{ menuItem.iconText }}</i>
                <template v-else>
                    <i v-if="menuItem.icon" class="icon" :class="menuItem.icon"></i>
                </template>
                <transition name="fade">
                    <span v-show="sidebarOpen">{{ menuItem.text }}</span>
                </transition>
            </router-link>
            <a href="#" v-else-if="menuItem.nodes && menuItem.noDirect"
                         :class="['menu-item', { 'router-link-exact-active' : menuItem.url === activeUrlParams}]"
                         :data-toggle="'collapse'"
                         :data-target="'#' + menuItem.url.replace(/\//g, '')"
                         :aria-expanded="true"
                         :aria-controls="menuItem.url.replace(/\//g, '')"
            >
                <i v-if="menuItem.iconText" class="icon">{{ menuItem.iconText }}</i>
                <template v-else>
                    <i v-if="menuItem.icon" class="icon" :class="menuItem.icon"></i>
                </template>
                <transition name="fade">
                    <span v-show="sidebarOpen">{{ menuItem.text }}</span>
                </transition>
            </a>
            <router-link v-else :to="menuItem.url" :class="['menu-item', { 'router-link-exact-active' : menuItem.url === activeUrlParams}]">
                <i v-if="menuItem.iconText" class="icon">{{ menuItem.iconText }}</i>
                <template v-else>
                    <i v-if="menuItem.icon" class="icon" :class="menuItem.icon"></i>
                </template>
                <transition name="fade">
                    <span v-show="sidebarOpen">{{ menuItem.text }}</span>
                </transition>
            </router-link>
            <left-menu-recursive v-if="menuItem.nodes" :menuParentItemUrl="menuItem.url.replace(/\//g, '')" :menuItemNodes="menuItem.nodes" :sidebarOpen="sidebarOpen"></left-menu-recursive>
        </li>
    </ul>
</template>
<script>
    import $ from 'jquery';

    function setParentActive(ul) {
        if(ul.length !== 0)
        {
            ul.collapse('show');
            ul.siblings('a').addClass('router-link-active collapsable');
            setParentActive($(ul).parent().closest('.sub-menu'));
        }
    };
    
    export default {
        props: [ 'menuItemNodes','menuParentItemUrl' ],
        name: 'left-menu-recursive',
        computed: {
            sidebarOpen() {
                return this.$store.state.sidebar.sidebarOpen;
            },
            activeUrlParams: {
                get: function() {
                    return this.$store.state.options.activeUrlParams;
                },
                set: function(newValue) {
                    return newValue;
                }
            }
        },
        mounted: function () {
            setParentActive($('.router-link-exact-active').closest('.sub-menu'));
        }
    }
</script>