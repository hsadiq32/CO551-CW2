<?php
declare(strict_types=1);
require_once 'vendor/autoload.php';

use Phinx\Migration\AbstractMigration;

final class MockData extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up()
    {
        $table = $this->table('student');
        $faker = Faker\Factory::create();
        $x = 1;
        while($x <= 20) {
            $mockData[] = array(
                'studentid'  => $faker->unique()->ean8, // 8 digit ID
                'password'  => $faker->password(), // random password password_hash() implmentation?
                'dob'  => $faker->dateTimeBetween('1950-01-01', '2013-12-31')
                ->format('Y-m-d'), // formatted date
                'firstname'  => $faker->firstName(),
                'lastname'  => $faker->lastName(),
                'house'  => $faker->address(),
                'town'  => $faker->city(),
                'county'  => $faker->state(),
                'country'  => $faker->country(),
                'postcode'  => $faker->postcode()
            );   
        $x++;
        } 
        $table->insert($mockData)->saveData();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->execute('DELETE FROM student');
    }
}