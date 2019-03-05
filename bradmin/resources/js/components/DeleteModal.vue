<style>
    @import '../../css/modal.css';
</style>
<template>
    <div class="modal-mask">
        <div class="modal-wrapper">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Подтвердите удаление</h4>
                        <button type="button" class="close" @click="$emit('close')">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="$emit('fireAction',$event)" id="delete-row-form" :action="getLink" method="post" class="mt-3">
                        <div class="modal-body">
                            Вы действительно хотите удалить данную запись?
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="_token" :value="getCSRF">
                            <input type="hidden" name="pluginData[deleteUrl]" :value="getDeleteUrl">
                            <input type="hidden" name="pluginData[sectionPath]" :value="getSectionPath">
                            <button type="button" class="float-right btn btn-secondary mr-3" @click="$emit('close')">Отмена</button>
                            <button class="float-right btn btn-danger" type="submit">Удалить</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import $ from 'jquery';

    export default {
        computed: {
            getLink() {
                return this.$parent.$data.link;
            },
            getCSRF() {
                return $('meta[name="csrf-token"]').attr('content');
            },
            getDeleteUrl() {
                return $('.br-display').data('delete-redirect');
            },
            getSectionPath() {
                return $('.br-display').data('section-path');
            }
        },
    }
</script>