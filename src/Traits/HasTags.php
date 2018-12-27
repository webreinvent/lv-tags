<?php namespace WebReinvent\LvTags\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use WebReinvent\LvTags\Entities\Tag;

trait HasTags {

    protected $tags = [];
    protected $tag = [];

    //---------------------------------------------------------------------------

    public static function bootHasTags()
    {

        static::created(function (Model $model) {
            $model->attachTags($model->tags);

        });

        static::deleted(function (Model $model) {
            $tags = $model->tags()->get();
            $model->detachTags($tags);
        });

    }

    //---------------------------------------------------------------------------
    public function tags()
    {
        return $this
            ->morphToMany(Tag::class,
                'lv_tag_model',
                'lv_tag_models',
                'lv_tag_model_id',
                'lv_tag_id');
    }

    //---------------------------------------------------------------------------

    public function attachTags($tags)
    {
        if(!is_array($tags))
        {
            $this->attachTag($tags);
        }

        foreach ($tags as $tag)
        {
            $this->attachTag($tag);
        }
    }

    //---------------------------------------------------------------------------

    public function attachTag($name)
    {
        $this->tag['name'] = $name;
        $this->setSlug($name);

        $tag_inputs['name'] = $this->tag['name'];
        $tag_inputs['slug'] = $this->tag['slug'];
        if(isset($this->tag['category']))
        {
            $tag_inputs['category'] = $this->tag['category'];
        }

        $tag = Tag::firstOrCreate($tag_inputs);
        $tag->fill($this->tag);
        $tag->save();

        $this->tags()->syncWithoutDetaching([$tag->id]);

    }

    //---------------------------------------------------------------------------
    public function setTagCategory($category)
    {
        $this->tag['category'] = $category;
    }
    //---------------------------------------------------------------------------
    public function setCreatedBy($user_id)
    {
        $this->tag['created_by'] = $user_id;
    }
    //---------------------------------------------------------------------------
    public function setUpdatedBy($user_id)
    {
        $this->tag['updated_by'] = $user_id;
    }
    //---------------------------------------------------------------------------
    public function setDeletedBy($user_id)
    {
        $this->tag['deleted_by'] = $user_id;
    }
    //---------------------------------------------------------------------------
    public function setSlug($slug=null)
    {
        if(!$slug)
        {
            $slug = str_slug($this->tag['name']);
        }
        $this->tag['slug'] = $slug;
    }
    //---------------------------------------------------------------------------
    public function setCreatedAt($date_time)
    {
        $this->tag['created_at'] = $date_time;
    }
    //---------------------------------------------------------------------------
    public function setUpdatedAt($date_time)
    {
        $this->tag['updated_at'] = $date_time;
    }
    //---------------------------------------------------------------------------
    public function setDeletedAt($date_time)
    {
        $this->tag['deleted_at'] = $date_time;
    }
    //---------------------------------------------------------------------------
    public function setOrderNumber($order_number)
    {
        $this->tag['order_number'] = $order_number;
    }
    //---------------------------------------------------------------------------
    public function setUsage($usage)
    {
        $this->tag['usage'] = $usage;
    }
    //---------------------------------------------------------------------------
    protected static function convertToTags($values, $failIfNotExit=false)
    {
        $collection = collect($values)->map(function ($value) {
            return static::findTag($value);
        })->filter();

        return $collection;
    }
    //---------------------------------------------------------------------------
    protected static function findTag($name)
    {
        $slug = str_slug($name);
        $tag = Tag::where('slug', $slug)->first();
        return $tag;

    }
    //---------------------------------------------------------------------------
    public function scopeWithAnyTags(Builder $query, $tags_arr)
    {
        $tags = static::convertToTags($tags_arr);
        return $query->whereHas('tags', function (Builder $query) use ($tags) {
            $tagIds = collect($tags)->pluck('id');
            $query->whereIn('lv_tag_models.lv_tag_id', $tagIds);
        });
    }
    //---------------------------------------------------------------------------
    public function scopeWithAllTags(Builder $query, $tags_arr)
    {
        $tags = static::convertToTags($tags_arr, true);

        collect($tags)->each(function ($tag) use ($query) {
            $query->whereIn("{$this->getTable()}.{$this->getKeyName()}", function ($query) use ($tag) {
                $query->from('lv_tag_models')
                    ->select('lv_tag_models.lv_tag_model_id')
                    ->where('lv_tag_models.lv_tag_id', $tag ? $tag->id : 0);
            });
        });

        return $query;
    }
    //---------------------------------------------------------------------------
    //---------------------------------------------------------------------------

}
