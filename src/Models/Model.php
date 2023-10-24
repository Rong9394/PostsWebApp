<?php

namespace src\Models;

/**
 * Usage: Data classes can inherit from Model in order to easily covert associative arrays to custom objects.
 *
 * A class inheriting from Model will be able to have the following functionality:
 *
 * $user = new User(['id' => 1, 'email' => 'chris@test.com']);       <--- construct object given associative array
 * $user->email // 'chris@test.com'                                  <--- access instance variable as oyu normally would
 * $user->email = 'chris123@test.com';                               <--- set value on one of the properties
 * $user->email // 'chris@test.com'                                  <--- get the correct value back
 *
 * This is useful as the data we get back from the database is in an associative array format.
 *
 * How can we achieve this behaviour? "magic" __set and __get methods.
 *
 * The PHP docs (linked below) say:
 *
 * "__set() is run when writing data to inaccessible (protected or private) or non-existing properties."
 * "__get() is utilized for reading data from inaccessible (protected or private) or non-existing properties."
 *
 * We can use these methods to read from and write to an array of properties,
 * giving subclasses the appearance of having normally defined instance variables.
 *
 * For more information on how this works, refer to: https://www.php.net/manual/en/language.oop5.overloading.php#language.oop5.overloading.members
 *
 * This is the same fundamental technique that Laravel uses. For example, see: https://github.com/illuminate/database/blob/master/Eloquent/Model.php
 * You can search for "__get" and "__set" and you'll see that attributes get dynamically set and retrieved off the model.
 *
 */
class Model {

	private array $attributes;

	/**
	 * @param array $properties
	 */
	public function __construct(array $properties = []) {
		$this->attributes = $properties;
	}

	/**
	 * @param string $attribute
	 * @param mixed $value
	 * @return mixed
	 */
	public function __set(string $attribute, mixed $value) {
		return $this->attributes[$attribute] = $value;
	}

	/**
	 * @param string $attribute
	 * @return mixed|null
	 */
	public function __get(string $attribute) {
		if (array_key_exists($attribute, $this->attributes)) {
			return $this->attributes[$attribute];
		}
		return null;
	}

	public function createdAtFmt(): ?string {
		if ($this->attributes['created_at']) {
			return date("l jS \of F Y, h:i A", strtotime($this->attributes['created_at']));
		} else {
			return null;
		}
	}

	public function updatedAtFmt(): ?string {
		if ($this->attributes['updated_at']) {
			return date("l jS \of F Y, h:i A", strtotime($this->attributes['updated_at']));
		} else {
			return null;
		}
	}

}
