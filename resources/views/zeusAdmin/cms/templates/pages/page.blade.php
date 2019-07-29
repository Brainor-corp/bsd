<?php
/**
 * class: ZeusAdminPageTemplate
 * title: Шаблон Базовый
 */
?>

@extends('v1.layouts.innerPageLayout')

@section('content')
    @php
        foreach ($page->ancestors as $ancestor)
        {
            $ancestors[] = $ancestor;
        }
        $ancestors[] = $page;
    @endphp

    @include('zeusAdmin.cms.partials.breadcrumbs', ['ancestor' => $ancestors])
    <section class="wrapper">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <header class="wrapper__header">
                        <h1>{{ $page->title }}</h1>
                    </header>
                    <div class="row">
                        <div class="col-8">
                            {!! html_entity_decode($page->content) !!}
                        </div>
                        <div class="col-3 offset-md-1">
                            @include('zeusAdmin.cms.partials.sidebarKlientam')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


@endsection