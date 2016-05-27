<?php

namespace Illuminate\Support\Traits;


/**
 * Nullable fields trait.
 *
 * force null value on attributes with an empty value for fields configured in $this->fillableNullable
 *
 * @author Jaroslav Rakhmatoullin <jazzoslav@gmail.com>
 * @property $attributes;
 * @property $fillableNullable nullbale properties must not be in this->fillable,
 *           the fillable prefix is for consistency.
 * @superclass \Illuminate\Database\Eloquent\Model;
 */
trait NullableFieldsTrait
{
	
	public function save(array $options = [])
	{
		foreach ($this->attributes as $i => $e ) {
			
			if (in_array($i, $this->fillableNullable)) {
				$this->attributes[$i] = $this->convertToNull($e, $i);
				
			}
		}

		return parent::save($options);
	}


	/**
	 * Convert a value that is not obviously empty to empty.
	 * Will return the original value if none of the internal rules apply.
	 *
	 * @param  mixed $value
	 * @return mixed
	 */
	public function convertToNull($value, $key)
	{
		if ($value == "NULL" or $value == 'null' or empty($value) ) {
			return null;
		}
		
		if (is_array($value)) {
			return count($value) === 0 ? null : $value;
		}

		return $value;
	}
}
