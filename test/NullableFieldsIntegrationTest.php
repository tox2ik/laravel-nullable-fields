<?php

use Iatstuti\Database\Support\NullableFields;
use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Events\Dispatcher;

class NullableFieldsIntegrationTest extends PHPUnit_Framework_TestCase
{

    public static function setUpBeforeClass()
    {
        $manager = new Manager();
        $manager->addConnection([
            'driver'   => 'sqlite',
            'database' => ':memory:',
        ]);

        //$manager->setEventDispatcher(new Dispatcher(new Container()));

        $manager->setAsGlobal();
        $manager->bootEloquent();

        $manager->schema()->create('user_profiles', function ($table) {
            $table->increments('id');
            $table->string('facebook_profile')->nullable()->default(null);
            $table->string('twitter_profile')->nullable()->default(null);
            $table->string('linkedin_profile')->nullable()->default(null);
            $table->text('array_casted')->nullable()->default(null);
            $table->text('array_not_casted')->nullable()->default(null);
        });
    }


    public function nullableFieldsSavedAsNull()
    {
        $user                   = new UserProfile;
        $user->facebook_profile = ' ';
        $user->twitter_profile  = 'michaeldyrynda';
        $user->linkedin_profile = '';
        $user->array_casted     = [ ];
		
		
        $user->save();

		
        $this->assertSame(' ', $user->facebook_profile);
        $this->assertSame('michaeldyrynda', $user->twitter_profile);
        $this->assertNull($user->linkedin_profile);
        $this->assertNull($user->array_casted);
		
        $this->assertNull(null);
    }


    public function testEmptyNullableFieldsAreFilledAsNull()
    {
        $user = UserProfile::create([
            'facebook_profile' => '',
            'twitter_profile'  => 'michaeldyrynda',
            'linkedin_profile' => ' ',
            'array_casted'     => [ ],
            'array_not_casted' => [ ],
        ]);

		
        $this->assertNull($user->facebook_profile);
        $this->assertSame('michaeldyrynda', $user->twitter_profile);
        $this->assertEquals(' ', $user->linkedin_profile);
        $this->assertNull($user->array_casted);
        $this->assertNull($user->array_not_casted);
    }

}

class UserProfile extends Model
{
    use \Illuminate\Support\Traits\NullableFieldsTrait;
    public $timestamps = false;
    protected $fillable = [
        'facebook_profile',
        'twitter_profile',
        'linkedin_profile',
        'array_casted',
        'array_not_casted',
    ];

	protected $fillableNullable = [
        'facebook_profile',
        'twitter_profile',
        'linkedin_profile',
        'array_casted',
        'array_not_casted',
    ];

    //protected $casts = [ 'array_casted' => 'array', ];
}


