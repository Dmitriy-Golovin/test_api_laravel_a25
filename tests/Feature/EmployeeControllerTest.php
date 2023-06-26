<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\User;
use Hash;
use Illuminate\Http\JsonResponse;
use Tests\TestCase;

class EmployeeControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testAddReturnValidResponseStatusAndData()
    {
        $payload = [
            'email' => 'test_email@mail.com',
            'password' => 'password',
        ];

        $this->json('post', 'api/add-employee', $payload)
            ->assertStatus(JsonResponse::HTTP_CREATED)
            ->assertJsonStructure(
                [
                    'data' => [
                        'id',
                        'user' => [
                            'id',
                            'name',
                            'email',
                            'email_verified_at',
                            'created_at',
                            'updated_at'
                        ],
                        'created_at',
                        'updated_at'
                    ],
                    'message'
                ]
            );
    }

    public function testAddForEmptyPayload()
    {
        $this->json('post', 'api/add-employee')
            ->assertStatus(JsonResponse::HTTP_BAD_REQUEST)
            ->assertJsonStructure(
                [
                    'message',
                    'success',
                    'data' => [
                        'email',
                        'password'
                    ]
                ]
            );
    }

    public function testAddForNotValidEmail()
    {
        $payload = [
            'password' => 'password',
        ];

        $this->json('post', 'api/add-employee', $payload)
            ->assertStatus(JsonResponse::HTTP_BAD_REQUEST)
            ->assertJsonStructure(
                [
                    'message',
                    'success',
                    'data' => [
                        'email',
                    ]
                ]
            );
    }

    public function testAddForNotValidPassword()
    {
        $payload = [
            'email' => '_test_email@mail.com',
        ];

        $this->json('post', 'api/add-employee', $payload)
            ->assertStatus(JsonResponse::HTTP_BAD_REQUEST)
            ->assertJsonStructure(
                [
                    'message',
                    'success',
                    'data' => [
                        'password',
                    ]
                ]
            );
    }
}
