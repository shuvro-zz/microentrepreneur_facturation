<?php

namespace App\Models;

use App\Benefit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bill extends Model
{

    protected $fillable = ['client_id'];

    protected $appends = ['total_price'];
    use SoftDeletes;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function benefits()
    {
        return $this->belongsToMany(Benefit::class)->withPivot('unit_price', 'currency', 'quantity');
    }

    public function getTotalPriceAttribute()
    {
        $benefits = collect($this->benefits)->map(function($b) {
            return ['currency' => $b->pivot->currency, 'price' => $b->pivot->unit_price * $b->pivot->quantity];
        })->groupBy(function($b) {
            return $b['currency'];
        })->map(function($bs) {
            return $bs->sum(function($b) {
                return $b['price'];
            });
        });

        return $benefits->all();
    }
}
