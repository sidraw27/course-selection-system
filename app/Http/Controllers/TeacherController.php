<?php

namespace App\Http\Controllers;

use App\Exceptions\CreateRecordFailedException;
use App\Repositories\TeacherRepository;
use App\Services\TeacherService;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

class TeacherController extends Controller
{
    protected TeacherService $service;

    public function __construct(TeacherService $teacherService)
    {
        $this->service = $teacherService;
    }

    public function store(Request $request): JsonResponse
    {
        $validator = $this->validateFillableInfo($request->all());

        if ($validator->fails()) {
            return $this->getResponse($validator->errors()->first(), 400);
        }

        try {
            $student = $this->service->createTeacher($request->get('name'));
        } catch (CreateRecordFailedException $e) {
            return $this->getResponse('建立老師失敗，請通知管理者', 400);
        }

        return $this->getResponse('ok', 201, $student);
    }

    public function update(string $shortId, Request $request): JsonResponse
    {
        $validator = $this->validateFillableInfo(Arr::add($request->all(), 'short_id', $shortId), [
            'short_id' => 'required|size:' . TeacherRepository::SHORT_ID_LENGTH
        ], [
            'short_id.required' => '標記 ID 是必填',
            'short_id.size' => '更新對象不存在',
        ]);

        if ($validator->fails()) {
            return $this->getResponse($validator->errors()->first(), 400);
        }

        if ( ! $this->service->updateTeacher($shortId, $request->get('name'))) {
            return $this->getResponse('update failed', 400);
        }

        return $this->getResponse('ok');
    }

    public function destroy(string $shortId): JsonResponse
    {
        if ( ! $this->service->deleteTeacher($shortId)) {
            return $this->getResponse('ok');
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
