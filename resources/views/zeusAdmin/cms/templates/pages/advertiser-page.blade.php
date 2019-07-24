<?php
/**
 * class: ZeusAdminPageTemplate
 * title: Шаблон страницы "Рекламодателям"
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
    {!! html_entity_decode($page->content) !!}


@endsection