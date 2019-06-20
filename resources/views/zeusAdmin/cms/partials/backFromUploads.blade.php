<a @click.prevent="$emit('redirectTo',$event)" href="{{ url('/'.config('zeusAdmin.admin_url').'/cms/ZeusAdminFiles') }}" class="btn btn-secondary">
    Вернуться к списку
</a>
<hr>