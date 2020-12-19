<?php

namespace App\Http\Controllers;

use App\Exceptions\CourseNotFoundException;
use App\Exceptions\StudentNotFoundException;
use App\Repositories\CourseRepository;
use App\Repositories\StudentRepository;
use App\Services\CourseAssistantService;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class CourseAssistantController extends Controller
{
    protected CourseAssistantService $service;

    public function __construct(CourseAssistantService $courseAssistantService)
    {
        $this->service = $courseAssistantService;
    }

    public function update(string $courseShortId, string $studentShortId): JsonResponse
    {
        $validator = $this->validateInfo($courseShortId, $studentShortId);

        if ($validator->fails()) {
            return $this->getResponse($validator->errors()->first(), 400);
        }

        try {
            $this->service->putAssistant($courseShortId, $studentShortId);
        } catch (CourseNotFoundException | StudentNotFoundException$e) {
            return $this->getResponse('target not found', 404);
        }

        return $this->getResponse('ok');
    }

    public function destroy(string $courseShortId, string $studentShortId): JsonResponse
    {
        $validator = $this->validateInfo($courseShortId, $studentShortId);

        if ($validator->fails()) {
            return $this->getResponse($validator->errors()->first(), 400);
        }

        try {
            $this->service->removeAssistant($courseShortId, $studentShortId);
        } catch (CourseNotFoundException | StudentNotFoundException$e) {
            return $this->getResponse('target not found', 404);
        }

        return $this->getResponse('ok');
    }

    private function validateInfo(string $courseShortId, string $studentShortId): ValidatorContract
    {
        return Validator::make([
            'course_short_id' => $courseShortId,
            'student_short_id' => $studentShortId,
        ], [
            'course_short_id' => 'required|size:' . CourseRepository::SHORT_ID_LENGTH,
            'student_short_id' => 'required|size:' . StudentRepository::SHORT_ID_LENGTH,
        ]);
    }
}
