<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CreatedUpdatedBy;

class Country extends Model
{
    use HasFactory;
    use SoftDeletes;
    use CreatedUpdatedBy;

    protected $table = 'country';

    protected $fillable = ['name', 'timezone', 'created_by', 'updated_by', 'acronym'];

    public static $timezones = [
        'GMT-12:00 International Date Line West',
        'GMT-11:00 Midway Island, Samoa',
        'GMT-10:00 Hawaii',
        'GMT-09:00 Alaska',
        'GMT-08:00 Pacific Time (US and Canada); Tijuana',
        'GMT-07:00 Mountain Time (US and Canada)',
        'GMT-07:00 Chihuahua, La Paz, Mazatlan',
        'GMT-07:00 Arizona',
        'GMT-06:00 Central Time (US and Canada',
        'GMT-06:00 Saskatchewan',
        'GMT-06:00 Guadalajara, Mexico City, Monterrey',
        'GMT-06:00 Central America',
        'GMT-05:00 Eastern Time (US and Canada)',
        'GMT-05:00 Indiana (East)',
        'GMT-05:00 Bogota, Lima, Quito',
        'GMT-04:00 Atlantic Time (Canada)',
        'GMT-04:00 Caracas, La Paz',
        'GMT-04:00 Santiago',
        'GMT-03:30 Newfoundland and Labrador',
        'GMT-03:00 Brasilia',
        'GMT-03:00 Buenos Aires, Georgetown',
        'GMT-03:00 Greenland',
        'GMT-02:00 Mid-Atlantic',
        'GMT-01:00 Azores',
        'GMT-01:00 Cape Verde Islands',
        'GMT Greenwich Mean Time: Dublin, Edinburgh, Lisbon, London',
        'GMT Casablanca, Monrovia',
        'GMT+01:00 Belgrade, Bratislava, Budapest, Ljubljana, Prague',
        'GMT+01:00 Sarajevo, Skopje, Warsaw, Zagreb',
        'GMT+01:00 Brussels, Copenhagen, Madrid, Paris',
        'GMT+01:00 Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna',
        'GMT+01:00 West Central Africa',
        'GMT+02:00 Bucharest',
        'GMT+02:00 Cairo',
        'GMT+02:00 Helsinki, Kiev, Riga, Sofia, Tallinn, Vilnius',
        'GMT+02:00 Athens, Istanbul, Minsk',
        'GMT+02:00 Jerusalem',
        'GMT+02:00 Harare, Pretoria',
        'GMT+03:00 Moscow, St. Petersburg, Volgograd',
        'GMT+03:00 Kuwait, Riyadh',
        'GMT+03:00 Nairobi',
        'GMT+03:00 Baghdad',
        'GMT+03:00 Tehran',
        'GMT+04:00 Abu Dhabi, Muscat',
        'GMT+04:00 Baku, Tbilisi, Yerevan',
        'GMT+04:30 Kabul',
        'GMT+05:00 Ekaterinburg',
        'GMT+05:00 Islamabad, Karachi, Tashkent',
        'GMT+05:30 Chennai, Kolkata, Mumbai, New Delhi',
        'GMT+05:45 Kathmandu',
        'GMT+06:00 Astana, Dhaka',
        'GMT+06:00 Sri Jayawardenepura',
        'GMT+06:00 Almaty, Novosibirsk',
        'GMT+06:30 Yangon Rangoon',
        'GMT+07:00 Bangkok, Hanoi, Jakarta',
        'GMT+07:00 Krasnoyarsk',
        'GMT+08:00 Beijing, Chongqing, Hong Kong SAR, Urumqi',
        'GMT+08:00 Kuala Lumpur, Singapore',
        'GMT+08:00 Taipei',
        'GMT+08:00 Perth',
        'GMT+08:00 Irkutsk, Ulaanbaatar',
        'GMT+09:00 Seoul',
        'GMT+09:00 Osaka, Sapporo, Tokyo',
        'GMT+09:00 Yakutsk',
        'GMT+09:30 Darwin',
        'GMT+09:30 Adelaide',
        'GMT+10:00 Canberra, Melbourne, Sydney',
        'GMT+10:00 Brisbane',
        'GMT+10:00 Hobart',
        'GMT+10:00 Vladivostok',
        'GMT+10:00 Guam, Port Moresby',
        'GMT+11:00 Magadan, Solomon Islands, New Caledonia',
        'GMT+12:00 Fiji Islands, Kamchatka, Marshall Islands',
        'GMT+12:00 Auckland, Wellington',
        'GMT+13:00 Nuku\'alofa',
    ];

    public function created_by_user(){
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updated_by_user(){
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function getCreatedByNameAttribute(){
        return $this->created_by_user ?  $this->created_by_user->fullName : null;
    }

    public function getUpdatedByNameAttribute(){
        return $this->updated_by_user ? $this->updated_by_user->fullName : null;
    }

    public function states(){
        return $this->hasMany(State::class, 'country_id');
    }
}
