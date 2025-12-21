<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $table;

    protected $primaryKey = 'id_customer';

    public function __construct(array $attributes = [])
    {
        $this->table = env('DB_PREFIX', 'soft_') . 'customer'; // CAMBIO IMPORTANTE
        parent::__construct($attributes);
    }

    public $timestamps = false;

    protected $fillable = [
        'id_shop_group',
        'id_shop',
        'id_gender',
        'id_default_group',
        'id_lang',
        'id_risk',
        'company',
        'siret',
        'ape',
        'firstname',
        'lastname',
        'email',
        'passwd',
        'last_passwd_gen',
        'birthday',
        'newsletter',
        'ip_registration_newsletter',
        'newsletter_date_add',
        'optin',
        'website',
        'outstanding_allow_amount',
        'show_public_prices',
        'max_payment_days',
        'secure_key',
        'active',
        'is_guest',
        'deleted',
        'date_add',
        'date_upd',
    ];

    public function defaultGroup(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'id_default_group', 'id_group');
    }

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(
            Group::class,
            'soft_customer_group',
            'id_customer',
            'id_group',
            'id_customer',
            'id_group',
        );
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'id_customer', 'id_customer');
    }
}
