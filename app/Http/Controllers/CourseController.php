<?php

namespace App\Http\Controllers;

use App\Exceptions\CourseNotFoundException;
use App\Exceptions\CreateRecordFailedException;
use App\Exceptions\TeacherNotFoundException;
use App\Repositories\CourseRepository;
use App\Repositories\TeacherRepository;
use App\Services\CourseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    protected CourseService $service;

    public function __construct(CourseService $courseService)
    {
        $this->service = $courseService;
    }

    public function show(string $shortId): JsonResponse
    {
        try {
            $course = $this->service->getCourseInfo($shortId);
        } catch (CourseNotFoundException $e) {
            return $this->getResponse('not found', 404);
        }

        return $this->getResponse('ok', 200, $course);
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'teacher_short_id' => 'nullable|size:' . TeacherRepository::SHORT_ID_LENGTH,
            'name' => 'required|max:10',
        ], [
            'teacher_id.size' => '設定師資錯誤',
            'name.required' => '名稱是必填欄位',
            'name.max' => '名稱超過長度限制',
        ]);

        if ($validator->fails()) {
            return $this->getResponse($validator->errors()->first(), 400);
        }

        try {
            $student = $this->service->createCourse($request->get('name'), $request->get('teacher_id'));
        } catch (CreateRecordFailedException $e) {
            // 若可能是系統錯誤可採取不透漏過多錯誤訊息方式
            return $this->getResponse('建立課程失敗，請通知管理者', 400);
        } catch (TeacherNotFoundException $e) {
            return $this->getResponse($e->getMessage(), 404);
        }

        return $this->getResponse('ok', 201, $student);
    }

    public function update(string $shortId, Request $request): JsonResponse
    {
        $validator = Validator::make([
            'short_id' => $shortId,
            'name' => $courseName = $request->get('name'),
            'teacher_short_id' => $teacherShortId = $request->get('teacher_short_id'),
        ], [
            'teacher_short_id' => 'nullable|size:' . TeacherRepository::SHORT_ID_LENGTH,
            'name' => 'nullable|max:10',
            'short_id' => 'required|size:' . CourseRepository::SHORT_ID_LENGTH
        ], [
            'teacher_id.size' => '設定師資錯誤',
            'name.max' => '名稱超過長度限制',
            'short_id.required' => '標記 ID 是必填',
            'short_id.size' => '更新對象不存在',
        ]);

        if ($validator->fails()) {
            return $this->getResponse($validator->errors()->first(), 400);
        }

        try {
            if ($this->service->updateCourse($shortId, $courseName, $teacherShortId)) {
                return $this->getResponse('ok');
            } else {
                return $this->getResponse('update failed', 400);
            }
        } catch (TeacherNotFoundException $e) {
            return $this->getResponse($e->getMessage(), 404);
        }
    }

    public function destroy(string $shortId): JsonResponse
    {
        if ($this->service->deleteCourse($shortId)) {
            return $this->getResponse('ok');
        } else {
            return $this->getResponse('destroy failed', 400);
        }
    }

    public function students(string $shortId): JsonResponse
    {
        try {
            $students = $this->service->getSelectedStudent($shortId);
        } catch (CourseNotFoundException $e) {
            return $this->getResponse('not found', 404);
        }

        return $this->getResponse('ok', 200, compact('students'));
    }
}
