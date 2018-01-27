<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Example extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'content'];

    /**
     * Avaliable rules of Model
     * 
     * @var array
     */
    protected $rules = [
        'title'   => 'required|min:10',
        'content' => 'required|min:100',
    ];

    /**
     * Scope a query to search.
     * 
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, Request $request)
    {
    	if ($search = $request->get('search')) {
	    	$query->where('id', 'like', "%{$search}%")
	    		->orWhere('title', 'like', "%{$search}%")
	    		->orWhere('content', 'like', "%{$search}%");
    	}

    	return $query;
    }

    /**
     * Get rules of Model
     * 
     * @return array
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * Set new rules of Model
     * 
     * @param array $rules
     * @return array
     */
    public function setRules(array $rules)
    {
        $this->rules = $rules;
        return $this->rules;
    }
}