<?php

namespace App\Providers;

use App\Admin\Sections\Cities;
use App\Admin\Sections\CitiesClosestTerminalUpdater;
use App\Admin\Sections\Comments;
use App\Admin\Sections\Companies;
use App\Admin\Sections\ContactEmails;
use App\Admin\Sections\Contacts;
use App\Admin\Sections\Counterparties;
use App\Admin\Sections\ForwardThresholds;
use App\Admin\Sections\InsideForwarding;
use App\Admin\Sections\MaxPackageDimensions;
use App\Admin\Sections\News;
use App\Admin\Sections\Notes;
use App\Admin\Sections\Orders;
use App\Admin\Sections\OutsideForwarding;
use App\Admin\Sections\OversizeMarkups;
use App\Admin\Sections\Oversizes;
use App\Admin\Sections\PerKmTariffs;
use App\Admin\Sections\Permissions;
use App\Admin\Sections\Points;
use App\Admin\Sections\Polygons;
use App\Admin\Sections\Promotions;
use App\Admin\Sections\Regions;
use App\Admin\Sections\Requisites;
use App\Admin\Sections\Reviews;
use App\Admin\Sections\Roles;
use App\Admin\Sections\Routes;
use App\Admin\Sections\RouteTariffs;
use App\Admin\Sections\Services;
use App\Admin\Sections\Supports;
use App\Admin\Sections\Terminals;
use App\Admin\Sections\Thresholds;
use App\Admin\Sections\Types;
use App\Admin\Sections\Users;
use App\Policies\DefaultSectionPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Zeus\Admin\Cms\Sections\ZeusAdminComments;
use Zeus\Admin\Cms\Sections\ZeusAdminFiles;
use Zeus\Admin\Cms\Sections\ZeusAdminMenus;
use Zeus\Admin\Cms\Sections\ZeusAdminPages;
use Zeus\Admin\Cms\Sections\ZeusAdminPosts;
use Zeus\Admin\Cms\Sections\ZeusAdminTags;
use Zeus\Admin\Cms\Sections\ZeusAdminTerms;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // Политики для административной панели
        Cities::class => DefaultSectionPolicy::class,
        CitiesClosestTerminalUpdater::class => DefaultSectionPolicy::class,
        Comments::class => DefaultSectionPolicy::class,
        ContactEmails::class => DefaultSectionPolicy::class,
        Contacts::class => DefaultSectionPolicy::class,
        Companies::class => DefaultSectionPolicy::class,
        Counterparties::class => DefaultSectionPolicy::class,
        ForwardThresholds::class => DefaultSectionPolicy::class,
        InsideForwarding::class => DefaultSectionPolicy::class,
        News::class => DefaultSectionPolicy::class,
        Notes::class => DefaultSectionPolicy::class,
        Orders::class => DefaultSectionPolicy::class,
        OutsideForwarding::class => DefaultSectionPolicy::class,
        OversizeMarkups::class => DefaultSectionPolicy::class,
        Oversizes::class => DefaultSectionPolicy::class,
        PerKmTariffs::class => DefaultSectionPolicy::class,
        Permissions::class => DefaultSectionPolicy::class,
        Points::class => DefaultSectionPolicy::class,
        Polygons::class => DefaultSectionPolicy::class,
        Promotions::class => DefaultSectionPolicy::class,
        Regions::class => DefaultSectionPolicy::class,
        Requisites::class => DefaultSectionPolicy::class,
        Reviews::class => DefaultSectionPolicy::class,
        Roles::class => DefaultSectionPolicy::class,
        Routes::class => DefaultSectionPolicy::class,
        RouteTariffs::class => DefaultSectionPolicy::class,
        Services::class => DefaultSectionPolicy::class,
        Supports::class => DefaultSectionPolicy::class,
        Terminals::class => DefaultSectionPolicy::class,
        Thresholds::class => DefaultSectionPolicy::class,
        Types::class => DefaultSectionPolicy::class,
        Users::class => DefaultSectionPolicy::class,

        // CMS
        ZeusAdminComments::class =>DefaultSectionPolicy::class,
        ZeusAdminFiles::class =>DefaultSectionPolicy::class,
        ZeusAdminMenus::class =>DefaultSectionPolicy::class,
        ZeusAdminPages::class =>DefaultSectionPolicy::class,
        ZeusAdminPosts::class =>DefaultSectionPolicy::class,
        ZeusAdminTags::class =>DefaultSectionPolicy::class,
        ZeusAdminTerms::class =>DefaultSectionPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
