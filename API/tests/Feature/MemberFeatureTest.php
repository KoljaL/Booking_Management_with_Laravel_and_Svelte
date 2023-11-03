<?php
// /tests/Feature/MemberFeatureTest.php
namespace Tests\Feature;

use App\Models\Member;
use App\Models\Staff;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MemberFeatureTest extends TestCase {
    use RefreshDatabase;
    protected $member;
    protected $staff;
    protected function setUp(): void {
        parent::setUp();
        $this->member = Member::factory()->create();
        $this->staff = Staff::factory()->create();
    }
    public function test_get_all_members() {
        $members = Member::factory()->count(5)->create();
        $response = $this->get('/api/member');
        $response->assertStatus(200);
        $response->assertJson($members->toArray());
    }
    public function test_get_one_member() {
        $response = $this->get('/api/member/' . $this->member->id);
        $response->assertStatus(200);
        $response->assertJson($this->member->toArray());
    }
    public function test_create_member() {
        $member = Member::factory()->make();
        $response = $this->post('/api/member', $member->toArray());
        $response->assertStatus(201);
        $response->assertJson($member->toArray());
    }
    public function test_update_member() {
        $this->member->phone = '0123456789';
        $response = $this->put('/api/member/' . $this->member->id, $this->member->toArray());
        $response->assertStatus(200);
        $response->assertJson($this->member->toArray());
    }
    public function test_delete_member() {
        $response = $this->delete('/api/member/' . $this->member->id);
        $response->assertStatus(204);
    }
}
// namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
// use Illuminate\Foundation\Testing\WithFaker;
// use Tests\TestCase;

// class MemberFeatureTest extends TestCase
// {
//     /**
//      * A basic feature test example.
//      */
//     public function test_example(): void
//     {
//         $response = $this->get('/');

//         $response->assertStatus(200);
//     }
// }
