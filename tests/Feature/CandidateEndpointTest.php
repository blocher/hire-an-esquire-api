<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\Candidate;
use App\User;

class CandidateEndpointTest extends TestCase
{
    
    use DatabaseTransactions;


    public function testGetCandidates()
    {
        $response = $this->json('GET', '/api/v1/candidates', ['api_token'=>$this->getAuthToken()]);

        $response->assertStatus(200);
        $response->assertJson([
        	'status' => [
					'error' => false
				]
        	]);
        $response->assertJsonCount(2, 'data');
    }

    public function testGetReviewedCandidates()
    {
        $response = $this->json('GET', '/api/v1/candidates', ['api_token'=>$this->getAuthToken(), 'reviewed' => 1]);

        $response->assertJsonMissing(['reviewed'=>0]);
    }

    public function testGetUnreviewedCandidates()
    {
        $response = $this->json('GET', '/api/v1/candidates', ['api_token'=>$this->getAuthToken(), 'reviewed' => 0]);

        $response->assertJsonMissing(['reviewed'=>1]);
    }

    public function testCreateCandidate() {

    	$response = $this->json('POST', '/api/v1/candidates', ['api_token'=>$this->getAuthToken(), 'name'=>'Test Name', 'status'=>'pending', 'years_exp'=>10, 'date_applied'=>'2010-01-01 00:00:00', 'description' => 'test_description']);
    	$response->assertStatus(201);
		$this->assertEquals($response->getData()->data->name, 'Test Name');

    }

    public function testUpdateCandidate() {

    	$candidate = Candidate::where('status','pending')->where('reviewed',0)->first();

    	$response = $this->json('PUT', '/api/v1/candidates/' . $candidate->id, ['api_token'=>$this->getAuthToken(), 'name'=>'Test Name', 'status'=>'accepted', 'years_exp'=>10, 'date_applied'=>'2010-01-01 00:00:00', 'description' => 'test_description']);
    	$response->assertStatus(200);

    	$candidate = Candidate::find($candidate->id);

		$this->assertEquals($candidate->reviewed, 1);

		$response = $this->json('PUT', '/api/v1/candidates/' . $candidate->id, ['api_token'=>$this->getAuthToken(), 'name'=>'Test Name', 'status'=>'pending', 'years_exp'=>10, 'date_applied'=>'2010-01-01 00:00:00', 'description' => 'test_description']);
    	$response->assertStatus(200);

    	$candidate = Candidate::find($candidate->id);

		$this->assertEquals($candidate->reviewed, 1);
		$this->assertEquals($candidate->name, "Test Name");
		$this->assertEquals($candidate->status, "pending");
		$this->assertEquals($candidate->years_exp, 10);
		$this->assertContains('2010-01-01 00:00:00', $candidate->date_applied);

    }

    public function testCandidateValidation() {

    	$response = $this->json('POST', '/api/v1/candidates', ['api_token'=>$this->getAuthToken(), 'status'=>'pending', 'years_exp'=>10, 'date_applied'=>'2010-01-01 00:00:00', 'description' => 'test_description']);
    	$response->assertStatus(400);

		$response = $this->json('POST', '/api/v1/candidates', ['api_token'=>$this->getAuthToken(), 'name'=>'Test Name', 'status'=>'pending', 'years_exp'=>51, 'date_applied'=>'2010-01-01 00:00:00', 'description' => 'test_description']);
    	$response->assertStatus(400);

    	$response = $this->json('POST', '/api/v1/candidates', ['api_token'=>$this->getAuthToken(), 'name'=>'Test Name', 'status'=>'pending', 'years_exp'=>49, 'date_applied'=>'2010-01-b01', 'description' => 'test_description']);
    	$response->assertStatus(400);

    	$response = $this->json('POST', '/api/v1/candidates', ['api_token'=>$this->getAuthToken(), 'name'=>'Test Name', 'status'=>'pending', 'years_exp'=>49, 'description' => 'test_description']);
    	$response->assertStatus(400);

    	$response = $this->json('POST', '/api/v1/candidates', ['api_token'=>$this->getAuthToken(), 'name'=>'Test Name', 'status'=>'pending', 'years_exp'=>10, 'date_applied'=>'2010-01-01 00:00:00', 'description' => 'test_description']);
    	$response->assertStatus(201);
		$this->assertEquals($response->getData()->data->name, 'Test Name');

    }

    public function testCandidateStatusValidationRule() {

    	$candidate = Candidate::where('status','pending')->first();

    	$response = $this->json('PUT', '/api/v1/candidates/' . $candidate->id, ['api_token'=>$this->getAuthToken(), 'status'=>'accepted']);
    	$response->assertStatus(200);

    	$response = $this->json('PUT', '/api/v1/candidates/' . $candidate->id, ['api_token'=>$this->getAuthToken(), 'status'=>'rejected']);
    	$response->assertStatus(400);

    	$response = $this->json('PUT', '/api/v1/candidates/' . $candidate->id, ['api_token'=>$this->getAuthToken(), 'status'=>'pending']);
    	$response->assertStatus(200);

    	$response = $this->json('PUT', '/api/v1/candidates/' . $candidate->id, ['api_token'=>$this->getAuthToken(), 'status'=>'rejected']);
    	$response->assertStatus(200);

    	$response = $this->json('PUT', '/api/v1/candidates/' . $candidate->id, ['api_token'=>$this->getAuthToken(), 'status'=>'accepted']);
    	$response->assertStatus(400);

    }

    public function testDeleteCandidate() {

    	$candidate = Candidate::first();

    	$response = $this->json('DELETE', '/api/v1/candidates/' . $candidate->id, ['api_token'=>$this->getAuthToken()]);
    	$response->assertStatus(200);

    	$candidate = Candidate::find($candidate->id);

    	$this->assertEmpty($candidate);

    }

    public function testNotFound() {

    	$response = $this->json('DELETE', '/api/v2/candidates/', ['api_token'=>$this->getAuthToken()]);
    	$response->assertStatus(404);

    }

    public function testRequiredAuthentication()
    {
        $response = $this->json('GET', '/api/v1/candidates', ['api_token'=>'1ee567a5-83ae-4309-8f2b-3ad94bcc94ddZZZ', 'reviewed' => 1]);

        $response->assertStatus(403);

        $response = $this->json('GET', '/api/v1/candidates', ['reviewed' => 1]);

        $response->assertStatus(403);
    }

    private function getAuthToken() {

    	$user = User::first();
    	return $user->api_token;
    }

}
