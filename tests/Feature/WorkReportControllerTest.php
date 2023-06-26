<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\User;
use App\Models\WorkReport;
use Hash;
use Tests\TestCase;
use Illuminate\Http\JsonResponse;

class WorkReportControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testPaySalaryReturnValidResponseStatus()
    {
        $user = User::create(
            [
                'name' => 'user_test_name',
                'password' => Hash::make('test_password'),
                'email' => 'test@mail.com'
            ]
        );

        $employee = Employee::create(
            [
                'user_id' => $user->id,
            ]
        );

        $workReport = WorkReport::create(
            [
                'employee_id' => $employee->id,
                'hours' => 12,
                'status' => WorkReport::STATUS_UNPAID,
            ]
        );

        $workReport->update(['status' => WorkReport::STATUS_PAID]);

        $this->json('put', 'api/pay-salary')
            ->assertStatus(JsonResponse::HTTP_OK);
    }

    public function testAddReturnValidResponseStatusAndData()
    {
        $user = User::create(
            [
                'name' => 'user_test',
                'password' => Hash::make('password_'),
                'email' => '_test_@mail.com'
            ]
        );

        $employee = Employee::create(
            [
                'user_id' => $user->id,
            ]
        );

        $payload = [
            'employee_id' => $employee->id,
            'hours' => 10,
        ];

        $this->json('post', 'api/add-report', $payload)
            ->assertStatus(JsonResponse::HTTP_CREATED)
            ->assertJsonStructure(
                [
                    'data' => [
                        'id',
                        'hours',
                        'status',
                        'employee' => [
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
                        'created_at',
                        'updated_at'
                    ],
                    'message'
                ]
            );
    }

    public function testGetSalaryListReturnValidResponseStatusAndData()
    {
        $data = collect(WorkReport::groupBy('employee_id')
            ->select('employee_id')
            ->selectRaw('sum(hours * ' . WorkReport::RATE_HOUR . ') as salary')
            ->where('status', WorkReport::STATUS_UNPAID)
            ->get()
            ->toArray())->mapWithKeys(function (array $item) {
                return [$item['employee_id'] => $item['salary']];
            });

        $this->json('get', 'api/salary-list')
            ->assertStatus(JsonResponse::HTTP_OK)
            ->assertExactJson(
                [
                    'data' => $data,
                    'message' => 'Success'
                ]
                );
    }

    public function testAddForEmptyPayload()
    {
        $this->json('post', 'api/add-report')
            ->assertStatus(JsonResponse::HTTP_BAD_REQUEST)
            ->assertJsonStructure(
                [
                    'message',
                    'success',
                    'data' => [
                        'employee_id',
                        'hours'
                    ]
                ]
            );
    }

    public function testAddForNotValidEmployeeId()
    {
        $payload = [
            'hours' => 8,
        ];

        $this->json('post', 'api/add-report')
            ->assertStatus(JsonResponse::HTTP_BAD_REQUEST)
            ->assertJsonStructure(
                [
                    'message',
                    'success',
                    'data' => [
                        'employee_id',
                    ]
                ]
            );
    }

    public function testAddForNotValidHours()
    {
        $payload = [
            'employee_id' => 1,
        ];

        $this->json('post', 'api/add-report')
            ->assertStatus(JsonResponse::HTTP_BAD_REQUEST)
            ->assertJsonStructure(
                [
                    'message',
                    'success',
                    'data' => [
                        'hours',
                    ]
                ]
            );
    }
}
