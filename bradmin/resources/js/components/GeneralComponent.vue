<template >
    <div class="content" :class="classes">
        <div class="loading" v-if="loading">Загрузка....</div>
        <div class="error" v-if="error">{{ error}}</div>
        <component :is="{template: `<div>${responseHtml}</div>`, props:[responseHtml]}"
                   track-by="${responseHtml}"
                   @showDeleteModal="show_modal"
                   @redirectTo="redirectTo"
                   @sorting="sorting"
                   @filter="filter"
                   @filterClear="filterClear"
                   @fireAction="fireAction"
                   :key="$route.fullPath"
        ></component>

        <nav v-if="pagination.pagesNumber.length > 1">
            <ul class="pagination" role="navigation">
                <li class="page-item" v-bind:class="[ pagination.current_page <= 1 ? 'disabled' : '']">
                    <router-link :to="{query: {page: 1, sort: currentSorting, filter: currentFilter}}" class="page-link" aria-label="« First">
                        <span aria-hidden="true">‹‹</span>
                    </router-link>
                </li>
                <li class="page-item" v-bind:class="[ pagination.current_page <= 1 ? 'disabled' : '']">
                    <router-link :to="{query: {page: pagination.current_page - 1, sort: currentSorting, filter: currentFilter}}" class="page-link" aria-label="« Previous">
                        <span aria-hidden="true">‹</span>
                    </router-link>
                </li>
                <li class="page-item" v-for="page in pagination.pagesNumber"
                    v-bind:class="[ page == currentPage ? 'active' : '']">
                    <router-link :to="{query: {page: page, sort: currentSorting, filter: currentFilter}}" class="page-link">
                        {{page}}
                    </router-link>
                </li>
                <li class="page-item" v-bind:class="[ pagination.current_page >= pagination.last_page ? 'disabled' : '']">
                    <router-link :to="{query: {page: pagination.current_page + 1, sort: currentSorting, filter: currentFilter}}" class="page-link" aria-label="Next »">
                        <span aria-hidden="true">›</span>
                    </router-link>
                </li>
                <li class="page-item" v-bind:class="[ pagination.current_page >= pagination.last_page ? 'disabled' : '']">
                    <router-link :to="{query: {page: pagination.last_page, sort: currentSorting, filter: currentFilter}}" class="page-link" aria-label="Last »">
                        <span aria-hidden="true">››</span>
                    </router-link>
                </li>
            </ul>
        </nav>

        <div v-if="showModal">
            <transition name="modal">
                <modal
                        @close="showModal = false"
                        @fireAction="fireAction"
                ></modal>
            </transition>
        </div>

    </div>
</template>


<script>
    import axios from 'axios';
    import $ from 'jquery';
    import 'selectize';
    import deparam from 'deparam';
    import modal from './DeleteModal';

    export default {
        components: { modal },
        data(){
            return {
                uid : Math.floor(Math.random() * 101),
                loading: false,
                responseData: null,
                responseHtml: '',
                error: null,
                classes: '',
                actionResponseData: null,
                actionError: null,
                redirectUrl: this.$route.path,
                showModal: false,
                pagination: {
                    total: 0,
                    per_page: 7,
                    from: 1,
                    to: 0,
                    last_page: 1,
                    current_page: 1,
                    each_side: 3,
                    pagesNumber:[]
                },
                link:'',
            };
        },
        computed: {
            currentPage() {
                if (typeof this.$route.query.page === 'undefined') {
                    return 1;
                }else{
                    return this.$route.query.page;
                }
            },
            currentSorting(){
                let sortObject = null;
                if (typeof this.$route.query.sort !== 'undefined') {
                    sortObject = this.$route.query.sort;
                }
                return sortObject;
            },
            currentFilter(){
                let filterObject = null;
                if (typeof this.$route.query.filter !== 'undefined') {
                    filterObject = this.$route.query.filter;
                }
                return filterObject;
            }
        },
        created: function () {
            this.fetchData(this.currentPage);
            this.$store.commit('activeUrlParams', this.$route.path);
        },
        updated: function () {

            this.$nextTick(function () {
                $('.multiselect').selectize({
                    plugins: ['remove_button'],
                    delimiter: ',',
                    persist: false,
                    create: function(input) {
                        return {
                            value: input,
                            text: input
                        }
                    }
                });
                $(function(){
                    $('.wysiwyg_editor').each(function(e){
                        var instance = CKEDITOR.instances[this.id];
                        if(instance)
                        {
                            CKEDITOR.remove(instance);
                        }
                        CKEDITOR.replace( this.id );
                    });

                    jQuery(function($) {
                        $('.date input').each(function () {
                           $(this).datetimepicker({
                               format: $(this).data('datepicker-format'),
                               language: $(this).data('datepicker-language'),
                               todayHighlight: true,
                               minuteStep: $(this).data('datepicker-minutestep'),
                               todayBtn: ($(this).data('datepicker-todaybtn')===1) ? 'linked' : false,
                               clearBtn: ($(this).data('datepicker-clearbtn')===1)
                           });
                        });
                    });

                    function dropzoneExists(selector) {
                        var elements = $(selector).find('.dz-default');
                        return elements.length > 0;
                    }

                    var dropZones = [];
                    $('.dropzone').each(function(e){
                        var exists = dropzoneExists('div#' + this.id);
                        if(!exists)
                        {
                            dropZones[this.id] = new Dropzone("#"+this.id, { url: this.getAttribute('data-dropzone-url')});
                        }
                    });
                });
            });

        },
        methods: {
            show_modal: function(event){
                this.showModal = true;
                this.link = event.target.dataset.deleteLink;
            },
            fetchData(page) {

                let query = Object.assign({}, this.$route.query);
                this.$router.replace({ query });
                if(typeof this.$route.query.sort !== 'undefined') {
                    if(typeof this.$route.query.sort !== 'object') {
                        if (Object.keys(this.$route.query.sort).length <= 0) {
                            delete query.sort;
                            this.$router.replace({ query });
                        }
                    }else{
                        delete query.sort;
                        this.$router.replace({ query });
                    }
                }
                if(typeof this.$route.query.filter !== 'undefined') {
                    if(typeof this.$route.query.filter !== 'object') {
                        if (Object.keys(this.$route.query.filter).length <= 0) {
                            delete query.filter;
                            this.$router.replace({ query });
                        }
                    }else{
                        delete query.filter;
                        this.$router.replace({ query });
                    }
                }

                this.error = this.responseData = null;
                this.loading = true;
                this.classes = '';

                var paras = document.getElementsByClassName('b-r-custom-asset');

                while(paras[0]) {
                    paras[0].parentNode.removeChild(paras[0]);
                }

                let ajaxUrl = '';

                if(this.$route.path === '/'+this.$store.state.options.adminUrl){
                    ajaxUrl = '/' + this.$store.state.options.adminUrl +'/dashboard';
                }
                else{
                    ajaxUrl = this.$route.path;
                }
                axios
                    .post(ajaxUrl + document.location.search,
                        {}
                    )
                    .then(response => {
                        if (typeof response.data.data !== 'undefined') {
                            this.responseData = response.data.data;
                            if (typeof response.data.data.pagination !== 'undefined') {


                                this.pagination.total = response.data.data.pagination.total;
                                this.pagination.per_page = response.data.data.pagination.per_page;
                                this.pagination.from = response.data.data.pagination.from;
                                this.pagination.to = response.data.data.pagination.to;
                                this.pagination.last_page = response.data.data.pagination.last_page;
                                this.pagination.current_page = response.data.data.pagination.current_page;

                                var from = this.pagination.current_page - this.pagination.each_side;
                                if (from < 1) {
                                    from = 1;
                                }
                                var to = from + (this.pagination.each_side * 2);
                                if (to >= this.pagination.last_page) {
                                    to = this.pagination.last_page;
                                }
                                var pagesArray = [];
                                while (from <= to) {
                                    pagesArray.push(from);
                                    from++;
                                }
                                this.pagination.pagesNumber = pagesArray;

                                if( this.pagination.pagesNumber.length > 1){
                                    let query = Object.assign({}, this.$route.query);
                                    query.page = page;
                                    this.$router.replace({ query });
                                }
                            }
                        }

                        if (typeof response.data.html !== 'undefined') {
                            this.responseHtml = response.data.html;
                        }

                        if (typeof response.data.meta !== 'undefined') {
                            if (typeof response.data.meta.title !== 'undefined') {
                                this.$store.commit('titleUpdate', response.data.meta.title);
                            }

                            if (typeof response.data.meta.class !== 'undefined') {
                                this.classes = response.data.meta.class;
                            }

                            if (typeof response.data.meta.scripts !== 'undefined') {
                                $.each(response.data.meta.scripts.head, function(index, filename){
                                    if(filename) {
                                        var fileref = document.createElement('script');
                                        fileref.setAttribute("type","text/javascript");
                                        fileref.setAttribute("src", filename);
                                        fileref.setAttribute("class", "b-r-custom-asset");

                                        document.getElementsByTagName("head")[0].appendChild(fileref)
                                    }
                                });

                                $.each(response.data.meta.scripts.body, function(index, filename){
                                    if(filename) {
                                        var fileref = document.createElement('script');
                                        fileref.setAttribute("type","text/javascript");
                                        fileref.setAttribute("src", filename);
                                        fileref.setAttribute("class", "b-r-custom-asset");

                                        document.getElementsByTagName("body")[0].appendChild(fileref)
                                    }
                                });
                            }

                            if (typeof response.data.meta.styles !== 'undefined') {
                                $.each(response.data.meta.styles, function(index, filename){
                                    if(filename) {
                                        var fileref = document.createElement("link");
                                        fileref.setAttribute("rel", "stylesheet");
                                        fileref.setAttribute("type", "text/css");
                                        fileref.setAttribute("href", filename);
                                        fileref.setAttribute("class", "b-r-custom-asset");

                                        document.getElementsByTagName("head")[0].appendChild(fileref)
                                    }
                                });
                            }
                        }
                        this.loading = false;

                    })
                    .catch(error => {
                        this.loading = false;
                        this.error = error.response.data.message || error.message;
                    });

            },
            fireAction(event) {
                this.error = this.actionResponseData = this.actionError = null;
                this.loading = true;

                var instance;
                for ( instance in CKEDITOR.instances )
                {
                    CKEDITOR.instances[instance].updateElement();
                }

                let ajaxUrl = event.target.attributes.action.value + document.location.search,
                    method = event.target.attributes.method.value,
                    formId = event.target.attributes.id.value,
                    formData = $('#'+formId).serialize(),
                    vm = this;
                axios({
                    method:method,
                    url:ajaxUrl,
                    data:formData
                })
                .then(function (response) {
                    if (typeof response.data.data !== 'undefined') {
                        vm.actionResponseData = response.data.data;

                    }
                    if (typeof response.data.redirect !== 'undefined') {
                        vm.redirectUrl = response.data.redirect.url;
                    }
                    vm.loading = false;
                    vm.redirectTo(null,vm.redirectUrl);
                })
                .catch(function (error) {
                    vm.loading = false;
                    vm.error = error.response.data.message || error.message;
                });
                this.showModal = false;
            },
            changePage: function (page) {
                this.pagination.current_page = page;
                this.fetchData(page);
            },
            redirectTo: function (event, url = null) {
                let redirectUrl = document.createElement('a');

                if(null === url){
                    redirectUrl.href = event.target.attributes.href.value;
                }else{
                    redirectUrl.href = url;
                }

                if(redirectUrl.pathname === this.$route.path){
                    this.fetchData(this.currentPage);
                }
                else{
                    this.$router.push({ path: redirectUrl.pathname});
                }
            },
            sorting: function (event) {

                let sortObject = {},
                needToUpdate = true;

                if (typeof this.$route.query.sort !== 'undefined') {
                    sortObject = deparam(this.$route.query.sort);

                    if (typeof sortObject[event.target.dataset.sortBy] !== 'undefined') {
                        if (sortObject[event.target.dataset.sortBy]['type'] === event.target.dataset.sortType) {
                            delete sortObject[event.target.dataset.sortBy];
                            needToUpdate = false;
                        }
                    }
                }
                if(needToUpdate === true){
                    if (typeof sortObject[event.target.dataset.sortBy] === 'undefined') {
                        sortObject[event.target.dataset.sortBy] = {};
                    }

                    sortObject[event.target.dataset.sortBy]['type']= event.target.dataset.sortType;
                    sortObject[event.target.dataset.sortBy]['by']= event.target.dataset.sortBy;
                }
                if(Object.keys(sortObject).length > 0) {
                    let query = Object.assign({}, this.$route.query);
                    query.sort = jQuery.param(sortObject);
                    this.$router.replace({ query });
                }else{
                    let query = Object.assign({}, this.$route.query);
                    delete query.sort;
                    this.$router.replace({ query });
                }

            },
            filter: function () {

                let filterObject = {};

                $(".filter-input").each(function() {
                    if(($(this).val() !== '') && ($(this).val() !== null)){
                        filterObject[$(this).data('filterName')] = {};
                        filterObject[$(this).data('filterName')]['field'] = $(this).data('filterName');
                        filterObject[$(this).data('filterName')]['value'] = $(this).val();
                    }
                });

                if(Object.keys(filterObject).length > 0) {
                    let query = Object.assign({}, this.$route.query);
                    query.filter = jQuery.param(filterObject);
                    this.$router.replace({ query });
                }else{
                    let query = Object.assign({}, this.$route.query);
                    delete query.filter;
                    this.$router.replace({ query });
                }
            },
            filterClear: function () {
                let query = Object.assign({}, this.$route.query);
                delete query.filter;
                this.$router.replace({ query });

            }
        }
    }
</script>