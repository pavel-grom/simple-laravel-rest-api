<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ItemTest extends TestCase
{
    protected $itemJsonStructure = [
        'data' => ['id', 'name', 'key', 'created_at', 'updated_at'],
        'result'
    ];

    protected $itemCollectionJsonStructure = [
        'data' => [['id', 'name', 'key', 'created_at', 'updated_at']],
        'result'
    ];

    protected $itemHistoryJsonStructure = [
        'data' => [['id', 'item_id', 'name', 'key', 'created_at', 'updated_at']],
        'result'
    ];


    /**
     * test items list
     *
     * @return void
     */
    public function testItemsList()
    {
        $this->actingAs($this->user, 'api')
            ->json('GET', '/api/items')
            ->assertStatus(200)
            ->assertJsonStructure($this->itemCollectionJsonStructure);
    }

    /**
     * test item
     *
     * @return void
     */
    public function testItem()
    {
        $this->actingAs($this->user, 'api')
            ->json('GET', '/api/items/1')
            ->assertStatus(200)
            ->assertJsonStructure($this->itemJsonStructure);
    }

    /**
     * test create item
     *
     * @return void
     */
    public function testCreateItem()
    {
        $name = str_random(mt_rand(3, 255));
        $key = str_random(mt_rand(3, 25));

        $this->actingAs($this->user, 'api')
            ->json('POST', '/api/items', [
                'name' => $name,
                'key' => $key,
            ])->assertStatus(201)
            ->assertJsonStructure($this->itemJsonStructure);

        $this->assertDatabaseHas('items', [
            'name' => $name,
            'key' => $key,
        ]);
    }

    /**
     * test create invalid item
     *
     * @return void
     */
    public function testCreateInvalidItem()
    {
        $name = str_random(mt_rand(255, 1000));
        $key = str_random(mt_rand(1, 2));

        $this->actingAs($this->user, 'api')
            ->json('POST', '/api/items', [
                'name' => $name,
                'key' => $key,
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'name',
                'key'
            ]);

        $this->assertDatabaseMissing('items', [
            'name' => $name,
            'key' => $key,
        ]);
    }

    /**
     * test update item
     *
     * @return void
     */
    public function testUpdateItem()
    {
        $name = str_random(mt_rand(3, 255));
        $key = str_random(mt_rand(3, 25));

        $this->actingAs($this->user, 'api')
            ->json('PUT', '/api/items/1', [
                'name' => $name,
                'key' => $key,
            ])->assertStatus(200)
            ->assertJsonStructure($this->itemJsonStructure);

        $this->assertDatabaseHas('items', [
            'name' => $name,
            'key' => $key,
        ]);

        $this->assertDatabaseHas('items_history', [
            'item_id' => 1,
            'name' => $name,
            'key' => $key,
        ]);

        $this->json('GET', '/api/items/1/history')
            ->assertStatus(200)
            ->assertJsonStructure($this->itemHistoryJsonStructure);
    }

    /**
     * test update invalid item
     *
     * @return void
     */
    public function testUpdateInvalidItem()
    {
        $name = str_random(mt_rand(255, 1000));
        $key = str_random(mt_rand(1, 2));

        $this->actingAs($this->user, 'api')
            ->json('PUT', '/api/items/1', [
                'name' => $name,
                'key' => $key,
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'name',
                'key'
            ]);

        $this->assertDatabaseMissing('items', [
            'name' => $name,
            'key' => $key,
        ]);
    }

    /**
     * test delete item
     *
     * @return void
     */
    public function testDeleteItem()
    {
        $this->actingAs($this->user, 'api')
            ->json('DELETE', '/api/items/1')
            ->assertStatus(200)
            ->assertJsonStructure($this->itemJsonStructure);

        $this->json('GET', '/api/items/1')
            ->assertStatus(404);

        $this->assertDatabaseMissing('items', [
            'id' => 1,
        ]);
    }

    /**
     * test item history list
     */
    public function testItemHistoryList()
    {
        for ($i = 0; $i <= 50; $i++) {
            $name = str_random(mt_rand(3, 255));
            $key = str_random(mt_rand(3, 25));

            $this->json('PUT', '/api/items/1', [
                'name' => $name,
                'key' => $key,
            ]);
        }

        $this->actingAs($this->user, 'api')
            ->json('GET', '/api/items/1/history')
            ->assertStatus(200)
            ->assertJsonStructure($this->itemHistoryJsonStructure);
    }
}
