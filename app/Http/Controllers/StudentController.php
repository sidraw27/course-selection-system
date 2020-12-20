<?php

namespace App\Http\Controllers;

use App\Exceptions\CreateRecordFailedException;
use App\Repositories\StudentRepository;
use App\Services\StudentService;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    protected StudentService $service;

    public function __construct(StudentService $studentService)
    {
        $this->service = $studentService;
    }

    public function store(Request $request): JsonResponse
    {
        $validator = $this->validateFillableInfo($request->all());

        if ($validator->fails()) {
            return $this->getResponse($validator->errors()->first(), 400);
        }

        try {
            $student = $this->service->createStudent($request->get('name'));
        } catch (CreateRecordFailedException $e) {
            // 若可能是系統錯誤可採取不透漏過多錯誤訊息方式
            return $this->getResponse('建立學生失敗，請通知管理者', 400);
        }

        return $this->getResponse('ok', 201, $student);
    }

    public function update(string $shortId, Request $request): JsonResponse
    {
        $validator = $this->validateFillableInfo(Arr::add($request->all(), 'short_id', $shortId), [
            'short_id' => 'required|size:' . StudentRepository::SHORT_ID_LENGTH
        ], [
            'short_id.required' => '標記 ID 是必填',
            'short_id.size' => '更新對象不存在',
        ]);

        if ($validator->fails()) {
            return $this->getResponse($validator->errors()->first(), 400);
        }

        if ( ! $this->service->updateStudent($shortId, $request->get('name'))) {
            return $this->getResponse('update failed', 400);
        }

        return $this->getResponse('ok');
    }

    public function destroy(string $shortId): JsonResponse
    {
        if ( ! $this->service->deleteStudent($shortId)) {
            return $this->getResponse('destroy failed', 400);
        }

        return $this->getResponse('ok');
    }

    private function validateFillableInfo(array $data, array $rule = [], array $message = []): ValidatorContract
    {
        $rule = array_merge([
            'name' => 'required|max:10'
        ], $rule);

        $message = array_merge([
            'name.required' => '名稱是必填欄位',
            'name.max' => '名稱超過長度限制',
        ], $message);

        return Validator::make($data, $rule, $message);
    }
}
