<?php

namespace App\Http\Controllers\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Encore\Admin\Auth\Database\AdminPermission;
use Encore\Admin\Traits\AdminBuilder;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
/**
 * Class Administrator.
 *
 * @property Role[] $roles
 */
class Member extends Model implements AuthenticatableContract
{
    use Authenticatable, AdminBuilder, AdminPermission;

    protected $fillable = ['username', 'password', 'name', 'avatar','status'];

    public $timestamps  = false;

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable('member');

        parent::__construct($attributes);
    }

}
