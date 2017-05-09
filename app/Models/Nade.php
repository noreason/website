<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
use Carbon\Carbon;
use Auth;

class Nade extends BaseModel
{
    protected $dates = ['approved_at'];

    protected $fillable = [
        'type', 'pop_spot', 'title', 'imgur_album', 'youtube', 'is_working',
        'is_approved', 'tags',
    ];

    protected $nadeTypes = [
        'flash' => [
            'label' => 'Flashbang',
            'class' => 'fa fa-eye-slash',
        ],
        'frag'  => [
            'label' => 'High Explosive Grenade',
            'class' => 'fa fa-bomb',
         ],
        'fire'  => [
            'label' => 'Incendiary / Molotov',
            'class' => 'glyphicon glyphicon-fire'
         ],
        'smoke' => [
            'label' => 'Smoke Grenade',
            'class' => 'fa fa-soundcloud',
        ],
    ];

    protected static $popSpots = [
        'a-site' => 'A Site',
        'b-site' => 'B Site',
        'mid'    => 'Middle',
        'other'  => 'Other',
    ];

    protected $messages = [
        'pop_spots' => 'You must select a valid option from the list',
        'messages'  => 'You must select a valid option from the list',
    ];

    public function __construct()
    {
        parent::__construct();
    }

    public function approve(User $user)
    {
        if (!$this->isApproved()) {
            $this->approved_by = Auth::user()->id;
            ;
            $this->approved_at = $this->freshTimestamp();
        }

        return $this;
    }

    public function getNadeTypes()
    {
        return $this->nadeTypes;
    }

    public function getNadeTypeKeys()
    {
        return array_keys($this->nadeTypes);
    }

    public static function getNadeTypeLabel($nadeType)
    {
        switch ($nadeType) {
            case "flash":
                $type = "Flashbang";
                break;
            case "frag":
                $type = "High Explosive Grenade";
                break;
            case "fire":
                $type = "Incendiary / Molotov";
                break;
            case "smoke":
                $type = "Smoke Grenade";
                break;
        }
        return $type;
    }

    public static function getPopSpots()
    {
        return self::$popSpots;
    }

    public static function getPopSpotKeys()
    {
        return array_keys(self::$popSpots);
    }

    public function isApproved()
    {
        $emptyDate = new Carbon('0000-00-00 00:00:00');

        if (!$this->approved_at || $emptyDate->gte($this->approved_at)) {
            return false;
        }

        return true;
    }

    public function approved_by()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function map()
    {
        return $this->belongsTo(Map::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function unapprove()
    {
        $this->approved_by = null;
        $this->approved_at = null;
        $this->save();
        return $this;
    }

    public function setNadeValidation()
    {
        $this->setRule('title', 'required')
             ->setRule('pop_spot', 'required|in:' . implode(',', $this->getPopSpotKeys()))
             ->setRule('imgur_album', 'url|required_without:youtube')
             ->setRule('youtube', 'url')
             ->setRule('is_working', 'boolean')
             ->setRule('is_approved', 'boolean')
             ->setRule('maps', 'exists:maps')
             ->setRule('type', 'required|in:' . implode(',', $this->getNadeTypeKeys()));
    }

    public function getIcon()
    {
        return $this->nadeTypes[$this->type]['class'];
    }
    public function getLabel()
    {
        return $this->nadeTypes[$this->type]['label'];
    }
}
