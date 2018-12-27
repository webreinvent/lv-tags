<?php namespace WebReinvent\LvTags\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model {

    use SoftDeletes;

    //-------------------------------------------------
    protected $table = 'lv_tags';
    //-------------------------------------------------
    protected $dates = [
        'created_at', 'updated_at', 'deleted_at',
    ];
    //-------------------------------------------------
    protected $dateFormat = 'Y-m-d H:i:s';
    //-------------------------------------------------
    protected $fillable = [
        'category', 'name', 'slug', 'order_number', 'usage',
        'created_by', 'updated_by', 'deleted_by'
    ];
    //-------------------------------------------------
    public function setCategoryAttribute($value)
    {
        $value = ucwords(strtolower($value));

        return $this->attributes['category'] = $value;
    }
    //-------------------------------------------------
    public function scopeFindByName($query, $name)
    {
        return $query->where('name', $name);
    }
    //-------------------------------------------------
    public function scopeFindBySlug($query, $slug)
    {
        return $query->where('slug', $slug);
    }
    //-------------------------------------------------
    public function scopeFindByCategory($query, $category)
    {
        return $query->where('category', ucwords(strtolower($category)));
    }
    //-------------------------------------------------
    public function scopeFindByUsage($query, $operator, $count)
    {
        return $query->where('usage', $operator, $count);
    }
    //-------------------------------------------------
    public function scopeCreatedBy( $query, $user_id ) {
        return $query->where( 'created_by', $user_id );
    }

    //-------------------------------------------------
    public function scopeUpdatedBy( $query, $user_id ) {
        return $query->where( 'updated_by', $user_id );
    }

    //-------------------------------------------------
    public function scopeDeletedBy( $query, $user_id ) {
        return $query->where( 'deleted_by', $user_id );
    }
    //-------------------------------------------------
    public function scopePopular($query, $records = 10)
    {
        return $query->orderBy('usage', 'DESC')->take($records);
    }
    //-------------------------------------------------
    public function scopeCreatedBetween($query, $from, $to)
    {
        return $query->whereBetween('created_at', array($from, $to));
    }
    //-------------------------------------------------
    public function scopeUpdatedBetween($query, $from, $to)
    {
        return $query->whereBetween('updated_at', array($from, $to));
    }
    //-------------------------------------------------
    public function scopeDeletedBetween($query, $from, $to)
    {
        return $query->whereBetween('deleted_at', array($from, $to));
    }
    //-------------------------------------------------
    public function createdBy()
    {
        return $this->belongsTo(config('lvtags.defaultUserModel'),
            'created_by', 'id'
        );
    }
    //-------------------------------------------------
    public function updatedBy()
    {
        return $this->belongsTo(config('lvtags.defaultUserModel'),
            'updated_by', 'id'
        );
    }

    //-------------------------------------------------
    public function deletedBy()
    {
        return $this->belongsTo(config('lvtags.defaultUserModel'),
            'deleted_by', 'id'
        );
    }
    //-------------------------------------------------
    //-------------------------------------------------

}
