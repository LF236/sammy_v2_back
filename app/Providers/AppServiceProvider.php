<?php

namespace App\Providers;

use App\Application\Email\Services\Contracts\MailSenderInterface;
use App\Application\Email\Services\MailSenderService;
use App\Application\MagicToken\Services\Contracts\MagicLinkSeenderInterface;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Infraestructure\Persistence\User\EloquentUserRepository;
use App\Application\MagicToken\Services\MagicLinkService;
use App\Domain\Auth\AuthUserProviderInterface;
use App\Domain\MagicToken\Repositories\MagicTokenRepositoryInterface;
use App\Domain\Permission\Repositories\PermissionRepository;
use App\Domain\Person\Repesitories\PersonRepository;
use App\Domain\Rols\Repositories\RolsRepository;
use App\Domain\RolsPermission\Repositories\RolsPermissionRepository;
use App\Domain\UserRole\Repositories\UserRoleRepository;
use App\Infraestructure\Laravel\Auth\LaravelAuthUserProvider;
use App\Infraestructure\Persistence\MagicToken\EloquentMaginTokenRepository;
use App\Infraestructure\Persistence\Permission\EloquentPermissionRepository;
use App\Infraestructure\Persistence\Person\EloquentPersonRepository;
use App\Infraestructure\Persistence\Role\EloquentRoleRepository;
use App\Infraestructure\Persistence\RolsPermission\EloquentRolPermissionRepository;
use App\Infraestructure\Persistence\UserRole\EloquentUserRoleRepository;
use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
		//
		$this->app->bind(UserRepositoryInterface::class, EloquentUserRepository::class);
		$this->app->bind(MagicLinkSeenderInterface::class, MagicLinkService::class);
		$this->app->bind(MagicTokenRepositoryInterface::class, EloquentMaginTokenRepository::class);

		// Email
		$this->app->bind(MailSenderInterface::class, MailSenderService::class);

		// Permission
		$this->app->bind(PermissionRepository::class, EloquentPermissionRepository::class);

		// Rols
		$this->app->bind(RolsRepository::class, EloquentRoleRepository::class);

		// Rols-Permissions
		$this->app->bind(RolsPermissionRepository::class, EloquentRolPermissionRepository::class);

		// UserRole
		$this->app->bind(UserRoleRepository::class, EloquentUserRoleRepository::class);

		// Person
		$this->app->bind(PersonRepository::class, EloquentPersonRepository::class);

		// AuthUserProvider
		$this->app->bind(AuthUserProviderInterface::class, LaravelAuthUserProvider::class);
    }
}
