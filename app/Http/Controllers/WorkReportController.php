<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddWorkReportRequest;
use App\Models\WorkReport;
use Exception;
use Illuminate\Http\JsonResponse;

class WorkReportController extends Controller
{
    public function add(AddWorkReportRequest $request): JsonResponse
    {
        try {
            $data = $request->all();
            $workReport = new WorkReport();
            $workReport->employee_id = $data['employee_id'];
            $workReport->hours = $data['hours'];
            $workReport->status = WorkReport::STATUS_UNPAID;
            $workReport->save();
        } catch (Exception $e) {
            return response()->json([
                'data' => [],
                'message'=>$e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'data' => $workReport->serialize(),
            'message' => 'Success'
        ], JsonResponse::HTTP_CREATED);
    }

    public function getSalaryList(): JsonResponse
    {
        try {
            $data = collect(WorkReport::groupBy('employee_id')
                ->select('employee_id')
                ->selectRaw('sum(hours * ' . WorkReport::RATE_HOUR . ') as salary')
                ->where('status', WorkReport::STATUS_UNPAID)
                ->get()
                ->toArray());
        } catch (Exception $e) {
            return response()->json([
                'data' => [],
                'message'=>$e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'data' => $data->mapWithKeys(function (array $item) {
                return [$item['employee_id'] => $item['salary']];
            }),
            'message' => 'Success'
        ], JsonResponse::HTTP_OK);
    }

    public function paySalary(): JsonResponse
    {
        try {
            WorkReport::where('status', WorkReport::STATUS_UNPAID)
                ->update(['status' => WorkReport::STATUS_PAID]);
        } catch (Exception $e) {
            return response()->json([
                'data' => [],
                'message'=>$e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'data' => '',
            'message' => 'Success'
        ], JsonResponse::HTTP_OK);
    }
}
